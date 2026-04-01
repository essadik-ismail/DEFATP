<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Province;
use App\Models\Commune;
use App\Models\Dranef;
use App\Models\Dpanef;
use App\Models\Zdtf;
use App\Models\Dfp;
use App\Models\Foret;
use App\Models\Canton;
use App\Models\Parcelle;
use App\Models\NatureDeCoupe;
use App\Models\ModeExploitation;
use App\Models\Essence;
use App\Models\Product;
use App\Models\Depot;
use App\Models\ContractVente;
use App\Models\Payment;
use App\Models\ChargeApayer;
use App\Models\Exploitant;
use App\Models\Permis;
use App\Models\PermiEnlever;
use App\Models\PermisExploiter;
use App\Models\ColportageEnlever;
use App\Models\Carnet;
use App\Models\PvInstallation;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Imports\LocationsImport;
use App\Services\ActivityLogger;
use App\Services\LettreAdjudicataireService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArticleController extends Controller
{
    /**
     * Articles list removed: redirect to cessions (articles are accessed via cessions).
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('cessions.index');
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        // Optimize: Use select() to load only necessary fields
        $communes = Commune::select('id', 'nom', 'province_id')
            ->with('province:id,nom')
            ->orderBy('nom')
            ->get();
        $provinces = Province::select('id', 'nom')->orderBy('nom')->get();
        $dranefs = Dranef::select('id', 'code', 'dranef')->orderBy('code')->get(); // Fixed: use 'dranef' not 'designation'
        $dpanefs = Dpanef::select('id', 'code', 'dpanef', 'dranef_code') // Fixed: use 'dpanef' not 'designation'
            ->with('dranef:id,code,dranef')
            ->orderBy('code')
            ->get();
        $zdtfs = Zdtf::select('id', 'code', 'zdtf', 'dpanef_code') // Fixed: use 'zdtf' not 'designation'
            ->with('dpanef:id,code,dpanef')
            ->orderBy('code')
            ->get();
        $dfps = Dfp::select('id', 'code', 'dfp', 'zdtf_code', 'dpanef_code') // Fixed: use 'dfp' not 'designation'
            ->with(['zdtf:id,code,zdtf', 'dpanef:id,code,dpanef'])
            ->orderBy('code')
            ->get();
        $forets = Foret::select('id', 'foret')->orderBy('foret')->get();
        $cantons = Canton::select('id', 'canton', 'foret_id')
            ->with('foret:id,foret')
            ->orderBy('canton')
            ->get();
        $parcelles = Parcelle::select('id', 'parcelle', 'canton_id') // Fixed: removed 'nom' column (doesn't exist)
            ->with('canton:id,canton')
            ->orderBy('parcelle')
            ->get();
        $natureDeCoupes = NatureDeCoupe::select('id', 'nature_de_coupe')->orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::select('id', 'mode_exploiattion')->orderBy('mode_exploiattion')->get();
        $essences = Essence::select('id', 'essence')->orderBy('essence')->get();
        $products = Product::select('id', 'name')->orderBy('name')->get();
        $depots = Depot::select('id', 'nom')->orderBy('nom')->get();

        $currentUser = Auth::user()?->load(['dranef', 'dpanef', 'zdtf', 'dfp', 'province']);

        return view('articles.create', compact(
            'communes',
            'provinces',
            'dranefs',
            'dpanefs',
            'zdtfs',
            'dfps',
            'forets',
            'cantons',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots',
            'currentUser'
        ));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['groupe_cession_id'] = $data['cession_id'] ?? null;
            unset($data['cession_id']);

            // Create the article
            $article = Article::create($data);

            // When cession gets its first article, set status to "En cours"
            if ($article->groupe_cession_id) {
                $cession = \App\Models\Cession::find($article->groupe_cession_id);
                if ($cession && $cession->articles()->count() === 1) {
                    $cession->update(['Statut' => 'en_cours']);
                }
            }

            // Attach relationships
            if ($request->has('province_ids')) {
                $article->provinces()->attach($request->province_ids);
            }

            if ($request->has('commune_ids')) {
                $article->communes()->attach($request->commune_ids);
            }

            if ($request->has('foret_ids')) {
                $article->forets()->attach($request->foret_ids);
            }

            // Parcelle/Canton are stored as text on the article; parcelle_ids no longer used on create form
            if ($request->has('parcelle_ids') && is_array($request->parcelle_ids)) {
                $article->parcelles()->attach($request->parcelle_ids);
            }

            if ($request->has('nature_de_coupe_ids')) {
                $article->natureDeCoupes()->attach($request->nature_de_coupe_ids);
            }

            if ($request->has('mode_exploitation_ids')) {
                $article->modeExploitations()->attach($request->mode_exploitation_ids);
            }

            // Handle depots - only sync if checkbox is checked and depot_ids are provided
            if ($request->has('is_on_depot') && $request->has('depot_ids')) {
                $article->depots()->sync($request->depot_ids);
            } else {
                // If checkbox is unchecked, detach all depots
                $article->depots()->detach();
            }

            // Optimize: Handle products (essence, product, quantity) with bulk operations
            if ($request->has('products') && is_array($request->products)) {
                // Get existing combinations once
                $existingCombinations = DB::table('article_essence')
                    ->where('article_id', $article->id)
                    ->get(['essence_id', 'product_id'])
                    ->map(fn($row) => $row->essence_id . '-' . $row->product_id)
                    ->toArray();

                // Prepare data for bulk insert
                $essencesToAttach = [];
                foreach ($request->products as $productData) {
                    if (isset($productData['essence_id']) && isset($productData['product_id']) && isset($productData['quantity'])) {
                        $key = $productData['essence_id'] . '-' . $productData['product_id'];
                        
                        // Only add if not exists
                        if (!in_array($key, $existingCombinations)) {
                            $essencesToAttach[$productData['essence_id']] = [
                                'product_id' => $productData['product_id'],
                                'quantity' => $productData['quantity']
                            ];
                        }
                    }
                }

                // Bulk attach if any
                if (!empty($essencesToAttach)) {
                    $article->essences()->attach($essencesToAttach);
                }
            }

            // Handle depot if checkbox is checked
            if ($request->has('is_on_depot') && $request->is_on_depot) {
                // You might want to attach to a specific depot or handle this differently
                // For now, we'll just mark it in the article
            }

            DB::commit();

            $locationsImported = false;
            if ($request->hasFile('locations_file')) {
                try {
                    Excel::import(new LocationsImport($article->id), $request->file('locations_file'));
                    $locationsImported = true;
                    ActivityLogger::log('update', 'Locations (plan de situation) importées à la création', Article::class, $article->id);
                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
                    $messages = collect($failures)->map(fn ($f) => 'Ligne ' . $f->row() . ': ' . implode(', ', $f->errors()))->implode(' ; ');
                    \Log::warning('Locations import on create failed: ' . $messages);
                } catch (\Throwable $e) {
                    \Log::error('Locations import on create: ' . $e->getMessage());
                }
            }

            ActivityLogger::log('create', 'Article créé', Article::class, $article->id);

            $successMsg = 'Article créé avec succès.';
            if ($locationsImported) {
                $successMsg .= ' Plan de situation importé.';
            } elseif ($request->hasFile('locations_file')) {
                return redirect()->route('articles.edit', $article)
                    ->with('success', 'Article créé avec succès. L\'import du plan de situation a échoué ; vous pouvez réessayer l\'import Excel ci-dessous.');
            }

            return redirect()->route('cessions.index')
                ->with('success', $successMsg);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Erreur de validation. Veuillez vérifier les données saisies.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log full error for debugging
            \Log::error('Article creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Log error with shorter message
            $errorMessage = $e->getMessage();
            // Extract just the main error message, not the full SQL
            if (strpos($errorMessage, 'SQLSTATE') !== false) {
                // Extract the main error part
                preg_match('/SQLSTATE\[[^\]]+\]:\s*([^\(]+)/', $errorMessage, $matches);
                $errorMessage = $matches[1] ?? substr($errorMessage, 0, 100);
            }
            
            ActivityLogger::log('error', 'Erreur lors de la création de l\'article: ' . $errorMessage);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'article: ' . $errorMessage);
        }
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): View
    {
        // Optimize: Load only necessary fields for each relationship
        $article->load([
            'forets:id,foret',
            'provinces:id,nom',
            'communes:id,nom',
            'parcelles:id,parcelle', // Fixed: removed 'nom' column (doesn't exist)
            'natureDeCoupes:id,nature_de_coupe',
            'modeExploitations:id,mode_exploiattion',
            'essences:id,essence',
            'products:id,name',
            'depots:id,nom',
            'contractVentes' => function($query) {
                $query->with([
                    'chargeApayer' => function($q) {
                        $q->with('payments');
                    },
                    'permisExploiter',
                    'exploitant' => function($q) {
                        $q->select('id', 'nom_complet', 'numero', 'n_cin', 'adresse', 'categorie')
                          ->with('dranef:id,code,dranef');
                    }
                ]);
            },
        ]);

        // Optimize: Load exploitant fields needed for contract form (incl. CIN, numero, adresse for auto-fill)
        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale', 'numero', 'n_cin', 'adresse')
            ->orderBy('nom_complet')
            ->get();
        $contractVente = $article->contractVentes->first();

        // Load permis d'enlever for this contract if it exists
        $permisEnlevers = collect();
        if ($contractVente) {
            $permisEnlevers = \App\Models\PermiEnlever::where(function($query) use ($contractVente) {
                    $query->where('contract_vente_id', $contractVente->id)
                    ->orWhereHas('permis', function($q) use ($contractVente) {
                        $q->where('contract_vente_id', $contractVente->id);
                    });
                })
                ->with(['permis'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('articles.show', compact('article', 'exploitants', 'contractVente', 'permisEnlevers'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        // Optimize: Use select() to load only necessary fields
        $communes = Commune::select('id', 'nom', 'province_id')
            ->with('province:id,nom')
            ->orderBy('nom')
            ->get();
        $provinces = Province::select('id', 'nom')
            ->orderBy('nom')
            ->get();
        $dranefs = Dranef::select('id', 'code', 'dranef')->orderBy('code')->get(); // Fixed: use 'dranef' not 'designation'
        $dpanefs = Dpanef::select('id', 'code', 'dpanef', 'dranef_code') // Fixed: use 'dpanef' not 'designation'
            ->with('dranef:id,code,dranef')
            ->orderBy('code')
            ->get();
        $zdtfs = Zdtf::select('id', 'code', 'zdtf', 'dpanef_code') // Fixed: use 'zdtf' not 'designation'
            ->with('dpanef:id,code,dpanef')
            ->orderBy('code')
            ->get();
        $dfps = Dfp::select('id', 'code', 'dfp', 'zdtf_code', 'dpanef_code') // Fixed: use 'dfp' not 'designation'
            ->with(['zdtf:id,code,zdtf', 'dpanef:id,code,dpanef'])
            ->orderBy('code')
            ->get();
        $forets = Foret::select('id', 'foret')->orderBy('foret')->get();
        $cantons = Canton::select('id', 'canton', 'foret_id')
            ->with('foret:id,foret')
            ->orderBy('canton')
            ->get();
        $parcelles = Parcelle::select('id', 'parcelle', 'canton_id') // Fixed: removed 'nom' column (doesn't exist)
            ->with('canton:id,canton')
            ->orderBy('parcelle')
            ->get();
        $natureDeCoupes = NatureDeCoupe::select('id', 'nature_de_coupe')->orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::select('id', 'mode_exploiattion')->orderBy('mode_exploiattion')->get();
        $essences = Essence::select('id', 'essence')->orderBy('essence')->get();
        $products = Product::select('id', 'name')->orderBy('name')->get();
        $depots = Depot::select('id', 'nom')->orderBy('nom')->get();
        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale')->orderBy('nom_complet')->get();

        $article->load([
            'provinces',
            'communes',
            'forets',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots'
        ]);

        return view('articles.edit', compact(
            'article',
            'communes',
            'provinces',
            'dranefs',
            'dpanefs',
            'zdtfs',
            'dfps',
            'forets',
            'cantons',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots',
            'exploitants'
        ));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $article->update($request->validated());

            // Sync relationships
            if ($request->has('province_ids')) {
                $article->provinces()->sync($request->province_ids);
            }

            if ($request->has('commune_ids')) {
                $article->communes()->sync($request->commune_ids);
            }

            if ($request->has('foret_ids')) {
                $article->forets()->sync($request->foret_ids);
            }

            if ($request->has('parcelle_ids')) {
                $article->parcelles()->sync($request->parcelle_ids);
            }

            if ($request->has('nature_de_coupe_ids')) {
                $article->natureDeCoupes()->sync($request->nature_de_coupe_ids);
            }

            if ($request->has('mode_exploitation_ids')) {
                $article->modeExploitations()->sync($request->mode_exploitation_ids);
            }

            if ($request->has('depot_ids')) {
                $article->depots()->sync($request->depot_ids);
            } else {
                // If checkbox is unchecked, detach all depots
                $article->depots()->detach();
            }

            // Handle products
            if ($request->has('products') && is_array($request->products)) {
                // Detach all existing
                $article->essences()->detach();

                // Attach new ones
                foreach ($request->products as $productData) {
                    if (isset($productData['essence_id']) && isset($productData['product_id']) && isset($productData['quantity'])) {
                        // Check if this combination already exists (safety check even after detach)
                        $existingEntry = DB::table('article_essence')
                            ->where('article_id', $article->id)
                            ->where('essence_id', $productData['essence_id'])
                            ->where('product_id', $productData['product_id'])
                            ->first();
                        
                        if (!$existingEntry) {
                            $article->essences()->attach($productData['essence_id'], [
                                'product_id' => $productData['product_id'],
                                'quantity' => $productData['quantity']
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            ActivityLogger::log('update', 'Article modifié', Article::class, $article->id);

            return redirect()->route('cessions.index')
                ->with('success', 'Article modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error with shorter message
            $errorMessage = $e->getMessage();
            // Extract just the main error message, not the full SQL
            if (strpos($errorMessage, 'SQLSTATE') !== false) {
                // Extract the main error part
                preg_match('/SQLSTATE\[[^\]]+\]:\s*([^\(]+)/', $errorMessage, $matches);
                $errorMessage = $matches[1] ?? substr($errorMessage, 0, 100);
            }
            
            ActivityLogger::log('error', 'Erreur lors de la modification de l\'article: ' . $errorMessage);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification de l\'article. Veuillez vérifier les données saisies.');
        }
    }

    /**
     * Import locations (plan de situation) from Excel file with columns: mat, x, y.
     */
    public function importLocations(Request $request, Article $article): RedirectResponse
    {
        $request->validate([
            'locations_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'locations_file.required' => 'Veuillez sélectionner un fichier Excel.',
            'locations_file.mimes' => 'Le fichier doit être au format Excel (.xlsx ou .xls).',
            'locations_file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        try {
            Excel::import(new LocationsImport($article->id), $request->file('locations_file'));

            ActivityLogger::log('update', 'Locations (plan de situation) importées', Article::class, $article->id);

            return redirect()->route('articles.edit', $article)
                ->with('success', 'Fichier Excel importé avec succès. Les coordonnées (mat, x, y) ont été enregistrées dans le plan de situation.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = collect($failures)->map(fn ($f) => 'Ligne ' . $f->row() . ': ' . implode(', ', $f->errors()))->implode(' ; ');
            return redirect()->back()->with('error', 'Erreurs de validation: ' . $messages);
        } catch (\Throwable $e) {
            \Log::error('Locations import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        try {
            $articleId = $article->id;
            $article->delete();

            ActivityLogger::log('delete', 'Article supprimé', Article::class, $articleId);

            return redirect()->route('cessions.index')
                ->with('success', 'Article supprimé avec succès.');
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la suppression de l\'article: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'article: ' . $e->getMessage());
        }
    }

    /**
     * Toggle invendu status
     */
    public function toggleInvendu(Article $article): RedirectResponse
    {
        try {
            $article->invendu = !$article->invendu;
            $article->save();

            $status = $article->invendu ? 'invendu' : 'vendu';
            
            ActivityLogger::log('update', "Article marqué comme {$status}", Article::class, $article->id);

            return redirect()->back()->with('success', "L'article a été marqué comme {$status}.");
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la mise à jour du statut invendu: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }

    /**
     * Update charge payments for an article.
     */
    public function updateChargePayments(Request $request, Article $article): RedirectResponse
    {
        $contractVente = $article->contractVentes->first();
        
        if (!$contractVente) {
            return redirect()->back()
                ->with('error', 'Aucun contrat de vente trouvé pour cet article.');
        }

        $validated = $request->validate([
            'payments' => 'required|array',
            'payments.*.statut' => 'required|in:0,1',
            'payments.*.reference' => 'nullable|string|max:255',
            'payments.*.date_payment' => 'nullable|date',
            'payments.*.fichier_joint' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'payments.*.charge_id' => 'nullable|exists:charge_apayer,id',
            'payments.*.charge_nom' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['payments'] as $key => $paymentData) {
                $chargeId = $paymentData['charge_id'] ?? null;
                
                // Find or create charge
                if ($chargeId) {
                    $charge = ChargeApayer::find($chargeId);
                } else {
                    // Create new charge if it doesn't exist
                    $charge = ChargeApayer::create([
                        'nom' => $paymentData['charge_nom'],
                        'montant' => 0,
                        'date_echeance' => null,
                        'contrat_vente_id' => $contractVente->id,
                    ]);
                }

                if (!$charge) {
                    continue;
                }

                // Handle file upload
                $fichierJoint = null;
                if ($request->hasFile("payments.{$key}.fichier_joint")) {
                    $file = $request->file("payments.{$key}.fichier_joint");
                    $fichierJoint = $file->store('charge-payments', 'public');
                }

                // Find or create payment
                $payment = Payment::where('chargeapayer_id', $charge->id)->first();
                
                if ($payment) {
                    // Update existing payment
                    $payment->update([
                        'is_paye' => $paymentData['statut'] == '1',
                        'num_quittace' => $paymentData['reference'] ?? null,
                        'date_payment' => $paymentData['date_payment'] ?? null,
                        'fichier_joint' => $fichierJoint ?? $payment->fichier_joint,
                    ]);
                } else {
                    // Create new payment
                    Payment::create([
                        'nom' => $charge->nom,
                        'is_paye' => $paymentData['statut'] == '1',
                        'num_quittace' => $paymentData['reference'] ?? null,
                        'date_payment' => $paymentData['date_payment'] ?? null,
                        'fichier_joint' => $fichierJoint,
                        'chargeapayer_id' => $charge->id,
                        'contract_vente_id' => $contractVente->id,
                    ]);
                }
            }

            DB::commit();

            ActivityLogger::log('update', 'Paiements des charges mis à jour', Article::class, $article->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Paiements des charges enregistrés avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            ActivityLogger::log('error', 'Erreur lors de la mise à jour des paiements: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement des paiements: ' . $e->getMessage());
        }
    }

    /**
     * Pay selected tranches.
     */
    public function payTranches(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'selected_tranche' => 'required',
            'num_quittance' => 'required|string|max:255',
            'date_payment' => 'required|date',
            'fichier_joint' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $contractVente = $article->contractVentes->first();

            if (!$contractVente) {
                throw new \Exception("Aucun contrat de vente trouvé pour cet article.");
            }

            $trancheId = $validated['selected_tranche'];

            $tranche = ChargeApayer::find($trancheId);

            if (!$tranche) {
                throw new \Exception("Tranche sélectionnée introuvable.");
            }

            // Create or update payment for this single tranche
            $payment = Payment::firstOrNew([
                'chargeapayer_id' => $tranche->id,
                'contract_vente_id' => $contractVente->id,
            ]);

            $payment->is_paye = true;
            $payment->num_quittace = $validated['num_quittance'];
            $payment->date_payment = $validated['date_payment'];
            $payment->nom = $tranche->nom;

            // Handle file upload
            if ($request->hasFile('fichier_joint')) {
                $file = $request->file('fichier_joint');
                $path = $file->store('public/tranche_justificatifs');
                $payment->fichier_joint = str_replace('public/', '', $path);
            }

            $payment->save();

            // Check if all tranches are paid to update article status
            $allTranches = $contractVente->chargeApayer->filter(function($charge) {
                return str_starts_with($charge->nom, 'Tranche');
            });

            $allPaid = true;
            foreach ($allTranches as $tranche) {
                $payment = $tranche->payments->first();
                if (!$payment || !$payment->is_paye) {
                    $allPaid = false;
                    break;
                }
            }

            if ($allPaid) {
                $article->update(['current_step' => 'recollement']);
            } else {
                $article->update(['current_step' => 'paiement_tranches']);
            }

            DB::commit();
            ActivityLogger::log('update', 'Paiement des tranches effectué', Article::class, $article->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Paiement des tranches enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error paying tranches: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors du paiement des tranches: ' . $e->getMessage());
        }
    }

    /**
     * Update article step.
     */
    public function updateStep(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'step' => 'required|in:cahier_affiche,contrat_vente,paiement_charges,paiement_tranches,recollement,main_levee',
        ]);

        try {
            // Define steps in order
            $steps = ['cahier_affiche', 'contrat_vente', 'paiement_charges', 'paiement_tranches', 'recollement', 'main_levee'];
            
            // Find the index of the clicked step
            $clickedStepIndex = array_search($validated['step'], $steps);
            
            // Set current_step to the step BEFORE the clicked one, so the clicked step becomes active
            // For the first step (cahier_affiche), set it to itself
            $stepToSet = $clickedStepIndex > 0 ? $steps[$clickedStepIndex - 1] : 'cahier_affiche';
            
            $article->update(['current_step' => $stepToSet]);

            ActivityLogger::log('update', 'Navigation vers l\'étape: ' . $validated['step'], Article::class, $article->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Navigation vers l\'étape: ' . $validated['step']);
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }

    /**
     * Show the Lettre Adjudicataire page and resolved template values.
     */
    public function lettreAdjudicataire(Article $article, LettreAdjudicataireService $lettreAdjudicataireService): View|RedirectResponse
    {
        $article->load([
            'cession.dranef',
            'provinces',
            'contractVentes.exploitant',
            'contractVentes.chargeApayer',
            'contractVentes.permisExploiter',
        ]);

        $contractVente = $article->contractVentes->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer la lettre adjudicataire.');
        }

        $resolvedPlaceholders = $lettreAdjudicataireService->getResolvedData($article);
        $templateAvailable = $lettreAdjudicataireService->hasTemplate();

        return view('articles.lettre-adjudicataire', compact(
            'article',
            'contractVente',
            'resolvedPlaceholders',
            'templateAvailable'
        ));
    }

    /**
     * Generate and download the Lettre Adjudicataire DOCX.
     */
    public function downloadLettreAdjudicataire(Article $article, LettreAdjudicataireService $lettreAdjudicataireService): BinaryFileResponse|RedirectResponse
    {
        $article->load([
            'contractVentes.exploitant',
            'contractVentes.chargeApayer',
            'contractVentes.permisExploiter',
        ]);

        if (!$article->contractVentes->first()) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit etre cree avant de generer la lettre adjudicataire.');
        }

        try {
            $document = $lettreAdjudicataireService->generate($article);

            ActivityLogger::log(
                'export',
                'Lettre adjudicataire telechargee',
                Article::class,
                $article->id,
                ['format' => 'docx']
            );

            return response()->download(
                $document['path'],
                $document['download_name'],
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ]
            )->deleteFileAfterSend();
        } catch (\Throwable $e) {
            \Log::error('Erreur lors de la generation de la lettre adjudicataire: ' . $e->getMessage(), [
                'article_id' => $article->id,
                'exception' => $e,
            ]);

            return redirect()->route('articles.lettre-adjudicataire', $article)
                ->with('error', 'Erreur lors de la generation de la lettre adjudicataire: ' . $e->getMessage());
        }
    }

    /**
     * Show Permis d'Enlever form
     */
    public function permisEnlever(Article $article)
    {
        // Load article essences
        $article->load('essences');
        
        // Load all products referenced in essences to avoid N+1 queries
        $productIds = $article->essences->pluck('pivot.product_id')->filter()->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un permis d\'enlever.');
        }

        // Get paid tranches grouped by payment date
        $paidTranches = collect();
        
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye && $payment->date_payment) {
                    $paidTranches->push([
                        'charge' => $charge,
                        'payment' => $payment,
                        'date_paiement' => $payment->date_payment,
                        'montant' => $charge->montant,
                        'tranche_number' => $charge->nom,
                    ]);
                }
            }
        }

        if ($paidTranches->isEmpty()) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Aucune tranche payée n\'est disponible pour générer un permis d\'enlever.');
        }

        $tranchesByDate = $paidTranches->groupBy('date_paiement');

        // Get existing Permis d'Enlever for this article
        // Query records by contract_vente_id (direct relationship) or through permis relationship
        $permisEnlevers = PermiEnlever::where(function($query) use ($contractVente) {
                // Records with direct contract_vente_id
                $query->where('contract_vente_id', $contractVente->id)
                // OR records with permis relationship (for backward compatibility)
                ->orWhereHas('permis', function($q) use ($contractVente) {
                    $q->where('contract_vente_id', $contractVente->id);
                });
            })
            ->with(['permis', 'contractVente.payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter out dates that already have a Permis d'Enlever
        $datesWithPermis = $permisEnlevers->pluck('date_paiement')
            ->filter()
            ->map(function($date) {
                if ($date instanceof \Carbon\Carbon) {
                    return $date->format('Y-m-d');
                }
                // If it's already a string, try to parse it
                if (is_string($date)) {
                    try {
                        return \Carbon\Carbon::parse($date)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $date;
                    }
                }
                return $date;
            })
            ->toArray();

        // Remove dates that already have a permis
        $tranchesByDate = $tranchesByDate->filter(function($tranches, $date) use ($datesWithPermis) {
            // Normalize the date key to Y-m-d format for comparison
            $dateFormatted = $date;
            if ($date instanceof \Carbon\Carbon) {
                $dateFormatted = $date->format('Y-m-d');
            } elseif (is_string($date)) {
                try {
                    $dateFormatted = \Carbon\Carbon::parse($date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $dateFormatted = $date;
                }
            }
            return !in_array($dateFormatted, $datesWithPermis);
        });

        // Count total paid tranches
        $totalPaidTranches = $paidTranches->count();
        
        // Get total number of tranches from contract
        $nombreTranche = $contractVente->nombre_tranche ?? 1;
        
        // Check if can create more permis
        $canCreateMore = $permisEnlevers->count() < $totalPaidTranches;

        return view('articles.permis-enlever', compact('article', 'tranchesByDate', 'products', 'permisEnlevers', 'totalPaidTranches', 'canCreateMore', 'nombreTranche', 'contractVente'));
    }

    /**
     * Store and generate Permis d'Enlever
     */
    public function storePermisEnlever(Request $request, Article $article)
    {
        $validated = $request->validate([
            'date_paiement' => 'required|date',
            'num_quittance_enlever' => 'required|string|max:255',
            'date' => 'required|date',
            'percepteur_enlever' => 'required|string|max:255',
            // Essences come from the article table; if the article has none,
            // we'll return a clear error message below.
            'essences' => 'nullable|array',
            'essences.*.essence_id' => 'nullable|exists:essences,id',
            'essences.*.product_id' => 'nullable|exists:products,id',
            // quantity is calculated server-side based on paid tranches count
        ]);

        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();
        
        if (!$contractVente) {
            return redirect()->back()
                ->with('error', 'Un contrat de vente doit être créé.');
        }

        // Get tranches for the selected payment date
        $tranches = collect();
        
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye && $payment->date_payment == $validated['date_paiement']) {
                    $tranches->push([
                        'charge' => $charge,
                        'payment' => $payment,
                        'montant' => $charge->montant,
                        'tranche_number' => $charge->nom,
                    ]);
                }
            }
        }

        if ($tranches->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Aucune tranche trouvée pour cette date de paiement.');
        }

        // Load article essences for validation
        $article->load('essences');

        if ($article->essences->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Aucune essence n\'est associée à cet article. Ajoutez des essences à l\'article avant de générer un permis d\'enlever.');
        }
        
        // Calculate quantities: base quantity / number of tranches
        $tranchesCount = $tranches->count();
        
        // Validate and calculate quantities for each essence
        $essencesData = [];
        foreach (($validated['essences'] ?? []) as $essenceData) {
            if (empty($essenceData['essence_id']) || empty($essenceData['product_id'])) {
                continue;
            }
            $articleEssence = $article->essences->where('id', $essenceData['essence_id'])->first();
            if ($articleEssence) {
                // Calculate quantity: base quantity / number of tranches
                $baseQuantity = $articleEssence->pivot->quantity;
                $calculatedQuantity = $tranchesCount > 0 ? ($baseQuantity / $tranchesCount) : 0;
                
                $essencesData[] = [
                    'essence_id' => $essenceData['essence_id'],
                    'product_id' => $essenceData['product_id'],
                    'quantity' => round($calculatedQuantity, 2), // Round to 2 decimal places
                ];
            }
        }

        if (empty($essencesData)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Aucune essence valide trouvée.');
        }

        try {
            DB::beginTransaction();

            // Create PermiEnlever record (without Permis parent)
            $permiEnlever = PermiEnlever::create([
                'permis_id' => null,
                'contract_vente_id' => $contractVente->id,
                'num_quittance' => $validated['num_quittance_enlever'],
                'date' => $validated['date'],
                'num_tranche_paye' => $tranchesCount,
                'date_paiement' => $validated['date_paiement'],
                'percepteur' => $validated['percepteur_enlever'],
            ]);

            // Insert calculated quantities into permisenlever_product
            foreach ($essencesData as $essenceData) {
                // Insert all essences, even if quantity is 0 (as it's calculated)
                DB::table('permisenlever_product')->insert([
                    'permis_id' => $permiEnlever->id,
                    'product_id' => $essenceData['product_id'],
                    'id_essence' => $essenceData['essence_id'],
                    'quantity' => $essenceData['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            ActivityLogger::log('create', 'Permis d\'enlever généré', PermiEnlever::class, $permiEnlever->id);

            return redirect()->route('articles.permis-enlever', $article)
                ->with('success', 'Permis d\'enlever créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating Permis d\'enlever: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du permis d\'enlever: ' . $e->getMessage());
        }
    }

    /**
     * Show Permis d'Exploiter form
     */
    public function permisExploiter(Article $article)
    {
        $article->load(['essences', 'forets', 'communes']);
        
        $contractVente = $article->contractVentes()->with(['chargeApayer.payments', 'permisExploiter'])->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un permis d\'exploiter.');
        }

        // Get existing permis exploiter if exists
        $permisExploiter = $contractVente->permisExploiter;

        // Check if at least one tranche is paid
        $hasPaidTranches = false;
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye) {
                    $hasPaidTranches = true;
                    break;
                }
            }
        }

        if (!$hasPaidTranches && !$permisExploiter) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Au moins une tranche doit être payée avant de générer un permis d\'exploiter.');
        }

        return view('articles.permis-exploiter', compact('article', 'contractVente', 'permisExploiter'));
    }

    /**
     * Store and generate Permis d'Exploiter
     */
    public function storePermisExploiter(Request $request, Article $article)
    {
        $validated = $request->validate([
            'num_assurance' => 'required|string|max:255',
            'num_quittance' => 'required|string|max:255',
            'percepteur' => 'required|string|max:255',
            'date_expiration_assurance' => 'nullable|date',
            'article_ccs' => 'nullable|string|max:255',
            'dfp' => 'nullable|string|max:255',
            'clature' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $contractVente = $article->contractVentes()->first();
            
            if (!$contractVente) {
                return redirect()->back()
                    ->with('error', 'Un contrat de vente doit être créé avant de générer un permis d\'exploiter.');
            }

            // Check if at least one tranche is paid
            $hasPaidTranches = false;
            foreach ($contractVente->chargeApayer as $charge) {
                if (str_starts_with($charge->nom, 'Tranche')) {
                    $payment = $charge->payments->first();
                    if ($payment && $payment->is_paye) {
                        $hasPaidTranches = true;
                        break;
                    }
                }
            }

            if (!$hasPaidTranches) {
                return redirect()->back()
                    ->with('error', 'Au moins une tranche doit être payée avant de générer un permis d\'exploiter.');
            }

            // Create permis exploiter record
            $permisExploiter = PermisExploiter::create([
                'contrat_vente_id' => $contractVente->id,
                'num_assurance' => $validated['num_assurance'],
                'num_quittance' => $validated['num_quittance'],
                'percepteur' => $validated['percepteur'],
                'date_expiration_assurance' => $validated['date_expiration_assurance'] ?? null,
                'article_ccs' => $validated['article_ccs'] ?? null,
                'dfp' => $validated['dfp'] ?? null,
                'clature' => $validated['clature'] ?? false,
            ]);

            // Update article current step if needed
            // $article->update(['current_step' => 'permis_exploiter']);

            ActivityLogger::log('create', 'Permis d\'exploiter généré', PermisExploiter::class, $permisExploiter->id);

            DB::commit();

            return redirect()->route('articles.show', $article)
                ->with('success', 'Permis d\'exploiter créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating Permis d\'exploiter: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du permis d\'exploiter: ' . $e->getMessage());
        }
    }

    /**
     * Show Permis de Colportage form
     */
    public function permisColportage(Article $article)
    {
        $article->load(['essences', 'products', 'depots']);
        
        // Load all products referenced in essences to avoid N+1 queries
        $productIds = $article->essences->pluck('pivot.product_id')->filter()->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un permis de colportage.');
        }

        // Check if at least one tranche is paid
        $hasPaidTranches = false;
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye) {
                    $hasPaidTranches = true;
                    break;
                }
            }
        }

        if (!$hasPaidTranches) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Au moins une tranche doit être payée avant de générer un permis de colportage.');
        }

        // Permis d'Enlever list (must select one for colportage) - load with essences and products
        $permisEnlevers = PermiEnlever::where('contract_vente_id', $contractVente->id)
            ->with(['essences', 'products'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Load quantities from permisenlever_product table for each permis
        // This table stores: permis_id, product_id, id_essence, quantity
        $permisEnleversWithQuantities = $permisEnlevers->map(function($permis) {
            $quantities = DB::table('permisenlever_product')
                ->where('permis_id', $permis->id)
                ->select('id_essence', 'product_id', 'quantity')
                ->get()
                ->map(function($item) {
                    return [
                        'essence_id' => (int)$item->id_essence,
                        'product_id' => (int)$item->product_id,
                        'quantity' => (float)$item->quantity
                    ];
                })
                ->values(); // Reset array keys
            
            $permis->quantities = $quantities;
            return $permis;
        });

        // Get pre-selected permis d'enlever ID from query parameter
        $selectedPermisEnleverId = request('permis_enlever_id');

        // Carnets disponibles (pour liste déroulante optionnelle)
        $carnetsDisponibles = Carnet::disponible()->listable()->get();

        // Section 1 & 2: quantities and list for selected Permis d'Enlever
        $quantityInPermisEnlever = 0;
        $quantityUsedColportage = 0;
        $listPermisColportage = collect();

        if ($selectedPermisEnleverId) {
            $quantityInPermisEnlever = (float) DB::table('permisenlever_product')
                ->where('permis_id', $selectedPermisEnleverId)
                ->sum('quantity');

            $quantityUsedColportage = (float) ColportageEnlever::where('id_permis_enlever', $selectedPermisEnleverId)
                ->sum('quantity');

            $listPermisColportage = ColportageEnlever::where('id_permis_enlever', $selectedPermisEnleverId)
                ->whereNotNull('numero_permis')
                ->orderBy('date_debut', 'desc')
                ->get()
                ->groupBy('numero_permis')
                ->map(function ($rows) {
                    $first = $rows->first();
                    // Total volume for this permis de colportage (all essences/produits)
                    $first->total_quantity = (float) $rows->sum('quantity');
                    return $first;
                });
        }

        return view('articles.permis-colportage', compact(
            'article', 'contractVente', 'permisEnlevers', 'permisEnleversWithQuantities',
            'selectedPermisEnleverId', 'products', 'carnetsDisponibles',
            'quantityInPermisEnlever', 'quantityUsedColportage', 'listPermisColportage'
        ));
    }

    /**
     * Show the create Permis de Colportage form on a dedicated page.
     */
    public function permisColportageCreate(Article $article)
    {
        $article->load(['essences', 'products', 'depots']);

        $productIds = $article->essences->pluck('pivot.product_id')->filter()->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();

        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un permis de colportage.');
        }

        $hasPaidTranches = false;
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye) {
                    $hasPaidTranches = true;
                    break;
                }
            }
        }

        if (!$hasPaidTranches) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Au moins une tranche doit être payée avant de générer un permis de colportage.');
        }

        $permisEnlevers = PermiEnlever::where('contract_vente_id', $contractVente->id)
            ->with(['essences', 'products'])
            ->orderBy('created_at', 'desc')
            ->get();

        $permisEnleversWithQuantities = $permisEnlevers->map(function ($permis) {
            $quantities = DB::table('permisenlever_product')
                ->where('permis_id', $permis->id)
                ->select('id_essence', 'product_id', 'quantity')
                ->get()
                ->map(function ($item) {
                    return [
                        'essence_id' => (int) $item->id_essence,
                        'product_id' => (int) $item->product_id,
                        'quantity' => (float) $item->quantity,
                    ];
                })
                ->values();
            $permis->quantities = $quantities;
            return $permis;
        });

        $selectedPermisEnleverId = request('permis_enlever_id');
        $carnetsDisponibles = Carnet::disponible()->listable()->get();

        return view('articles.permis-colportage-create', compact(
            'article', 'contractVente', 'permisEnlevers', 'permisEnleversWithQuantities',
            'selectedPermisEnleverId', 'products', 'carnetsDisponibles'
        ));
    }

    /**
     * Store and generate Permis de Colportage
     */
    public function storePermisColportage(Request $request, Article $article)
    {
        $validated = $request->validate([
            'id_permis_enlever' => 'required|exists:permi_enlevers,id',
            'carnet_id' => 'required|exists:carnets,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'vehicule_immatriculation' => 'required|string|max:255',
            'chauffeur_nom' => 'required|string|max:255',
            'chauffeur_cin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'essences' => 'nullable|array',
            'essences.*.essence_id' => 'nullable|exists:essences,id',
            'essences.*.product_id' => 'nullable|exists:products,id',
            'essences.*.quantity' => 'nullable|numeric|min:0',
            'fichier_joint' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'id_permis_enlever.required' => 'Le permis d\'enlever est obligatoire.',
            'id_permis_enlever.exists' => 'Le permis d\'enlever selectionne est invalide.',
            'carnet_id.required' => 'Le numero de permis de colportage est obligatoire.',
            'carnet_id.exists' => 'Le numero de permis de colportage selectionne est invalide.',
        ]);

        try {
            DB::beginTransaction();

            // Handle optional attached file (justificatif du permis de colportage)
            $fichierJoint = null;
            if ($request->hasFile('fichier_joint')) {
                $file = $request->file('fichier_joint');
                $path = $file->store('public/colportage_justificatifs');
                $fichierJoint = str_replace('public/', '', $path);
            }

            // Ensure selected Permis d'Enlever belongs to this article (via contract vente)
            $contractVente = $article->contractVentes()->first();
            $permiEnlever = PermiEnlever::where('id', $validated['id_permis_enlever'])
                ->when($contractVente, fn($q) => $q->where('contract_vente_id', $contractVente->id))
                ->first();

            if (!$permiEnlever) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Le Permis d\'Enlever sélectionné est invalide pour cet article.');
            }

            // Generate numero_permis
            $numeroPermis = 'PC-' . $article->numero . '-' . date('Y');
            
            // Create one row per essence/product combination in colportage_enlever table
            // and update Permis d'Enlever quantities in permisenlever_product
            $essencesInserted = false;
            if (!empty($validated['essences'])) {
                foreach ($validated['essences'] as $essenceData) {
                    if (empty($essenceData['essence_id']) || empty($essenceData['product_id'])) {
                        continue;
                    }
                    
                    $essenceId = (int)$essenceData['essence_id'];
                    $productId = (int)$essenceData['product_id'];
                    $colportageQuantity = isset($essenceData['quantity']) ? (float)$essenceData['quantity'] : 0;
                    
                    // Only process if quantity > 0
                    if ($colportageQuantity > 0) {
                        // Create colportage record in colportage_enlever table
                        // Table structure: id_permis_enlever, product_id, id_essence, quantity
                        ColportageEnlever::create([
                            'article_id' => $article->id,
                            'id_permis_enlever' => $permiEnlever->id,
                            'product_id' => $productId,
                            'id_essence' => $essenceId,
                            'quantity' => $colportageQuantity,
                            'date_debut' => $validated['date_debut'],
                            'date_fin' => $validated['date_fin'],
                            'vehicule_immatriculation' => $validated['vehicule_immatriculation'],
                            'chauffeur_nom' => $validated['chauffeur_nom'],
                            'chauffeur_cin' => $validated['chauffeur_cin'],
                            'destination' => $validated['destination'],
                            'numero_permis' => $numeroPermis,
                            'carnet_id' => $validated['carnet_id'],
                            'fichier_joint' => $fichierJoint,
                        ]);
                        $essencesInserted = true;
                    }
                }
            }
            
            // If no essences were inserted, create at least one record with the main data
            if (!$essencesInserted) {
                ColportageEnlever::create([
                    'article_id' => $article->id,
                    'id_permis_enlever' => $permiEnlever->id,
                    'date_debut' => $validated['date_debut'],
                    'date_fin' => $validated['date_fin'],
                    'vehicule_immatriculation' => $validated['vehicule_immatriculation'],
                    'chauffeur_nom' => $validated['chauffeur_nom'],
                    'chauffeur_cin' => $validated['chauffeur_cin'],
                    'destination' => $validated['destination'],
                    'numero_permis' => $numeroPermis,
                    'carnet_id' => $validated['carnet_id'],
                    'fichier_joint' => $fichierJoint,
                ]);
            }

            // Si un carnet a été choisi, le passer en "epuise" (un numéro = un seul permis)
            if (!empty($validated['carnet_id'])) {
                Carnet::where('id', $validated['carnet_id'])
                    ->where('status', Carnet::STATUS_DISPONIBLE)
                    ->update(['status' => Carnet::STATUS_EPUISE]);
            }
            
            // Get the first colportage record for logging
            $colportage = ColportageEnlever::where('numero_permis', $numeroPermis)->first();

            // Do not update current_step to a value outside the workflow steps

            ActivityLogger::log('create', 'Permis de colportage généré', ColportageEnlever::class, $colportage->id);

            DB::commit();

            return redirect()->route('articles.permis-colportage', ['article' => $article, 'permis_enlever_id' => $permiEnlever->id])
                ->with('success', 'Permis de colportage généré avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating Permis de colportage: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du permis de colportage: ' . $e->getMessage());
        }
    }

    /**
     * Show PV d'Installation form
     */
    public function pvInstallation(Article $article)
    {
        $article->load(['essences', 'forets', 'communes']);
        
        $contractVente = $article->contractVentes()->with('chargeApayer.payments')->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer un PV d\'Installation.');
        }

        // Check if at least one tranche is paid
        $hasPaidTranches = false;
        foreach ($contractVente->chargeApayer as $charge) {
            if (str_starts_with($charge->nom, 'Tranche')) {
                $payment = $charge->payments->first();
                if ($payment && $payment->is_paye) {
                    $hasPaidTranches = true;
                    break;
                }
            }
        }

        if (!$hasPaidTranches) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Au moins une tranche doit être payée avant de générer un PV d\'Installation.');
        }

        // Get existing PV Installation if exists
        $pvInstallation = \App\Models\PvInstallation::where('contract_vente_id', $contractVente->id)->first();

        return view('articles.pv-installation', compact('article', 'contractVente', 'pvInstallation'));
    }

    /**
     * Store and generate PV d'Installation
     */
    public function storePvInstallation(Request $request, Article $article)
    {
        $validated = $request->validate([
            'pvn' => 'nullable|string|max:255',
            'date' => 'required|date',
            'participants' => 'nullable|string',
            'exploitant' => 'nullable|string|max:255',
            'reserve' => 'nullable|string',
            'emo' => 'nullable|string|max:255',
            'charbonniére' => 'nullable|string|max:255',
            'mise_en_charge' => 'nullable|string|max:255',
            'ravalement_souches' => 'nullable|string|max:255',
            'remarient' => 'nullable|string|max:255',
            'mise_en_defens' => 'nullable|string|max:255',
            'invitation_caporal' => 'nullable|string|max:255',
        ]);

        $contractVente = $article->contractVentes()->first();
        
        if (!$contractVente) {
            return redirect()->back()
                ->with('error', 'Un contrat de vente doit être créé.');
        }

        try {
            DB::beginTransaction();

            // Check if PV Installation already exists
            $pvInstallation = \App\Models\PvInstallation::where('contract_vente_id', $contractVente->id)->first();

            if ($pvInstallation) {
                // Update existing
                $pvInstallation->update($validated);
                ActivityLogger::log('update', 'PV d\'Installation modifié', \App\Models\PvInstallation::class, $pvInstallation->id);
                $message = 'PV d\'Installation modifié avec succès.';
            } else {
                // Create new
                $validated['contract_vente_id'] = $contractVente->id;
                $pvInstallation = \App\Models\PvInstallation::create($validated);
                ActivityLogger::log('create', 'PV d\'Installation créé', \App\Models\PvInstallation::class, $pvInstallation->id);
                $message = 'PV d\'Installation créé avec succès.';
            }

            DB::commit();

            return redirect()->route('articles.pv-installation', $article)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating/updating PV d\'Installation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du PV d\'Installation: ' . $e->getMessage());
        }
    }
}

