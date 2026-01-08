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
        $communes = Commune::orderBy('nom')->get();
        $provinces = Province::orderBy('nom')->get();
        $dranefs = Dranef::orderBy('code')->get();
        $dpanefs = Dpanef::with('dranef')->orderBy('code')->get();
        $zdtfs = Zdtf::with('dpanef')->orderBy('code')->get();
        $dfps = Dfp::with(['zdtf', 'dpanef'])->orderBy('code')->get();
        $forets = Foret::orderBy('foret')->get();
        $cantons = Canton::with('foret')->orderBy('canton')->get();
        $parcelles = Parcelle::with('canton')->orderBy('parcelle')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::orderBy('mode_exploiattion')->get();
        $essences = Essence::orderBy('essence')->get();
        $products = Product::orderBy('name')->get();
        $depots = Depot::orderBy('nom')->get();

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

            // Handle products (essence, product, quantity)
            if ($request->has('products') && is_array($request->products)) {
                foreach ($request->products as $productData) {
                    if (isset($productData['essence_id']) && isset($productData['product_id']) && isset($productData['quantity'])) {
                        $article->essences()->attach($productData['essence_id'], [
                            'product_id' => $productData['product_id'],
                            'quantity' => $productData['quantity']
                        ]);
                    }
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
        $article->load([
            'forets',
            'provinces',
            'parcelles',
            'natureDeCoupes',
            'modeExploitations',
            'essences',
            'products',
            'depots',
            'contractVentes',
            'dranef',
            'dpanef',
            'zdtf'
        ]);

        $exploitants = \App\Models\Exploitant::orderBy('nom_complet')->get();
        $contractVente = $article->contractVentes->first();
        
        // Load charges and their payments if contract exists
        if ($contractVente) {
            $contractVente->load(['chargeApayer.payments']);
        }

        return view('articles.show', compact('article', 'exploitants', 'contractVente'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        $communes = Commune::with('provinces')->orderBy('nom')->get();
        $provinces = Province::with('commune')->orderBy('nom')->get();
        $forets = Foret::orderBy('foret')->get();
        $cantons = Canton::with('foret')->orderBy('canton')->get();
        $parcelles = Parcelle::with('canton')->orderBy('parcelle')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $modeExploitations = ModeExploitation::orderBy('mode_exploiattion')->get();
        $essences = Essence::orderBy('essence')->get();
        $products = Product::orderBy('name')->get();
        $depots = Depot::orderBy('nom')->get();

        $article->load([
            'provinces',
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
                        $article->essences()->attach($productData['essence_id'], [
                            'product_id' => $productData['product_id'],
                            'quantity' => $productData['quantity']
                        ]);
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
            $article->update(['current_step' => $validated['step']]);

            ActivityLogger::log('update', 'Statut de l\'article mis à jour: ' . $validated['step'], Article::class, $article->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Statut de l\'article mis à jour avec succès.');
        } catch (\Exception $e) {
            ActivityLogger::log('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }
}

