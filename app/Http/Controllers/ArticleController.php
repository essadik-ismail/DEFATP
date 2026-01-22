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
use App\Models\PvInstallation;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the articles.
     */
    public function index(Request $request): View
    {
        $query = Article::with([
            'dranef',
            'dpanef',
            'zdtf',
            'dfp',
            'contractVentes',
            'provinces',
            'communes',
            'essences',
            'products'
        ]);

        // Year filter
        if ($request->filled('year')) {
            $query->where('annee', $request->year);
        }

        // Adjudication date filter
        if ($request->filled('adjudication_date')) {
            $query->whereHas('contractVentes', function ($q) use ($request) {
                $q->whereDate('date_adjudication', $request->adjudication_date);
            });
        }

        // Type filter (from contract_ventes)
        if ($request->filled('type')) {
            $query->whereHas('contractVentes', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        // Global search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('lot', 'like', "%{$search}%")
                  ->orWhere('parcelle', 'like', "%{$search}%")
                  ->orWhere('annee', 'like', "%{$search}%")
                  ->orWhereHas('forets', function ($q) use ($search) {
                      $q->where('foret', 'like', "%{$search}%");
                  })
                  ->orWhereHas('contractVentes', function ($q) use ($search) {
                      $q->where('type', 'like', "%{$search}%")
                        ->orWhere('numeraAO', 'like', "%{$search}%");
                  });
            });
        }

        // Get available years for filter dropdown
        $availableYears = Article::select('annee')
            ->distinct()
            ->whereNotNull('annee')
            ->orderBy('annee', 'desc')
            ->pluck('annee')
            ->toArray();

        // Get available types for filter dropdown
        $availableTypes = ContractVente::select('type')
            ->distinct()
            ->whereNotNull('type')
            ->pluck('type')
            ->toArray();

        $perPage = $request->get('per_page', 15);
        $articles = $query->latest()->paginate($perPage)->appends($request->query());

        return view('articles.index', compact('articles', 'availableYears', 'availableTypes'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        // Optimize: Use select() to load only necessary fields
        $communes = Commune::select('id', 'nom')->orderBy('nom')->get();
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
            'depots'
        ));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Create the article
            $article = Article::create($request->validated());
            
            // Save code-based foreign keys (only if not empty and exists)
            if ($request->filled('dranef_code') && !empty(trim($request->dranef_code))) {
                $code = trim($request->dranef_code);
                if (Dranef::where('code', $code)->exists()) {
                    $article->dranef_code = $code;
                } else {
                    throw new \Exception("Le code DRANEF '{$code}' n'existe pas.");
                }
            } else {
                $article->dranef_code = null;
            }
            
            if ($request->filled('dpanef_code') && !empty(trim($request->dpanef_code))) {
                $code = trim($request->dpanef_code);
                if (Dpanef::where('code', $code)->exists()) {
                    $article->dpanef_code = $code;
                } else {
                    throw new \Exception("Le code DPANEF '{$code}' n'existe pas.");
                }
            } else {
                $article->dpanef_code = null;
            }
            
            if ($request->filled('zdtf_code') && !empty(trim($request->zdtf_code))) {
                $code = trim($request->zdtf_code);
                if (Zdtf::where('code', $code)->exists()) {
                    $article->zdtf_code = $code;
                } else {
                    throw new \Exception("Le code ZDTF '{$code}' n'existe pas.");
                }
            } else {
                $article->zdtf_code = null;
            }
            
            if ($request->filled('dfp_code') && !empty(trim($request->dfp_code))) {
                $code = trim($request->dfp_code);
                if (Dfp::where('code', $code)->exists()) {
                    $article->dfp_code = $code;
                } else {
                    throw new \Exception("Le code DFP '{$code}' n'existe pas.");
                }
            } else {
                $article->dfp_code = null;
            }
            $article->save();

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

            if ($request->has('parcelle_ids')) {
                $article->parcelles()->attach($request->parcelle_ids);
            }

            if ($request->has('nature_de_coupe_ids')) {
                $article->natureDeCoupes()->attach($request->nature_de_coupe_ids);
            }

            if ($request->has('mode_exploitation_ids')) {
                $article->modeExploitations()->attach($request->mode_exploitation_ids);
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

            ActivityLogger::log('create', 'Article créé', Article::class, $article->id);

            return redirect()->route('articles.index')
                ->with('success', 'Article créé avec succès.');
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
            'contractVentes',
            'dranef:id,code,dranef', // Fixed: removed 'designation' (doesn't exist)
            'dpanef:id,code,dpanef', // Fixed: removed 'designation' (doesn't exist)
            'zdtf:id,code,zdtf' // Fixed: removed 'designation' (doesn't exist)
        ]);

        // Optimize: Only load necessary exploitant fields
        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale')->orderBy('nom_complet')->get();
        $contractVente = $article->contractVentes->first();

        // Optimize: Eager load charges and their payments to prevent N+1 queries in view
        if ($contractVente) {
            $contractVente->load([
                'chargeApayer' => function($query) {
                    $query->with('payments');
                },
                'permisExploiter' // Load permis exploiter if exists
            ]);
        }

        return view('articles.show', compact('article', 'exploitants', 'contractVente'));
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

            return redirect()->route('articles.index')
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
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        try {
            $articleId = $article->id;
            $article->delete();

            ActivityLogger::log('delete', 'Article supprimé', Article::class, $articleId);

            return redirect()->route('articles.index')
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
            'selected_tranches' => 'required|string',
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

            $trancheIds = json_decode($validated['selected_tranches'], true);
            
            if (empty($trancheIds)) {
                throw new \Exception("Aucune tranche sélectionnée.");
            }

            // Process each selected tranche
            foreach ($trancheIds as $trancheId) {
                $tranche = ChargeApayer::find($trancheId);
                
                if (!$tranche) {
                    \Log::warning("Tranche with ID {$trancheId} not found. Skipping.");
                    continue;
                }

                // Create or update payment for this tranche
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
            }

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
     * Generate and download Lettre Adjudicataire PDF
     */
    public function lettreAdjudicataire(Article $article)
    {
        // Check if contract vente exists
        $contractVente = $article->contractVentes()->first();
        
        if (!$contractVente) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'Un contrat de vente doit être créé avant de générer la lettre adjudicataire.');
        }

        // Generate PDF logic here (to be implemented with PDF library)
        // For now, return a view
        return view('articles.lettre-adjudicataire', compact('article', 'contractVente'));
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
            ->with(['permis'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Count total paid tranches
        $totalPaidTranches = $paidTranches->count();
        
        // Check if can create more permis
        $canCreateMore = $permisEnlevers->count() < $totalPaidTranches;

        return view('articles.permis-enlever', compact('article', 'tranchesByDate', 'products', 'permisEnlevers', 'totalPaidTranches', 'canCreateMore'));
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
            'volume' => 'nullable|numeric|min:0',
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
                'percepteur' => $validated['percepteur_enlever'],
                'volume' => $validated['volume'],
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

        // Permis d'Enlever list (must select one for colportage)
        $permisEnlevers = PermiEnlever::where('contract_vente_id', $contractVente->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('articles.permis-colportage', compact('article', 'contractVente', 'permisEnlevers'));
    }

    /**
     * Store and generate Permis de Colportage
     */
    public function storePermisColportage(Request $request, Article $article)
    {
        $validated = $request->validate([
            'id_permis_enlever' => 'required|exists:permi_enlevers,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'vehicule_immatriculation' => 'required|string|max:255',
            'chauffeur_nom' => 'required|string|max:255',
            'chauffeur_cin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

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

            // Create colportage enlever record
            $colportage = ColportageEnlever::create([
                'article_id' => $article->id,
                'id_permis_enlever' => $permiEnlever->id,
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'vehicule_immatriculation' => $validated['vehicule_immatriculation'],
                'chauffeur_nom' => $validated['chauffeur_nom'],
                'chauffeur_cin' => $validated['chauffeur_cin'],
                'destination' => $validated['destination'],
                'numero_permis' => 'PC-' . $article->numero . '-' . date('Y'),
            ]);

            // Do not update current_step to a value outside the workflow steps

            ActivityLogger::log('create', 'Permis de colportage généré', ColportageEnlever::class, $colportage->id);

            DB::commit();

            return redirect()->route('articles.show', $article)
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
            'mo' => 'nullable|string|max:255',
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

