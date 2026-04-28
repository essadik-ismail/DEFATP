<?php

namespace App\Http\Controllers;

use App\Models\ContractVente;
use App\Models\Article;
use App\Models\Exploitant;
use App\Models\ChargeApayer;
use App\Services\ActivityLogger;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ContractVenteController extends Controller
{
    /**
     * Show the form for creating a new contract vente.
     */
    public function create(Article $article): View
    {
        // Load exploitant fields needed for display
        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale', 'numero', 'n_cin', 'adresse', 'categorie', 'activite', 'qualification_rc', 'date_obtention', 'duree_validite', 'etat_validite', 'situation_administrative')
            ->orderBy('nom_complet')
            ->get();
        $contractVente = $article->contractVentes->first();
        
        return view('contract-ventes.create', compact('article', 'exploitants', 'contractVente'));
    }

    /**
     * Store a newly created contract vente in storage.
     */
    public function store(Request $request, Article $article): RedirectResponse
    {
        $validated = $this->validateContractRequest($request);

        try {
            DB::beginTransaction();

            // Create or update contract vente
            $contractVente = ContractVente::updateOrCreate(
                ['article_id' => $article->id],
                $this->buildContractPayload($validated, $article) + ['Current_state' => 'contrat_vente']
            );

            // Delete existing charges
            $contractVente->chargeApayer()->delete();

            // Optimize: Bulk insert charges and tranches
            $charges = collect($validated['charges'])->map(function ($chargeData) use ($contractVente) {
                return [
                    'nom' => $chargeData['nom'],
                    'montant' => $chargeData['montant'],
                    'date_echeance' => $chargeData['date_echeance'],
                    'contrat_vente_id' => $contractVente->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            $nombreTranches = (int) $validated['nombre_tranche'];
            $montantParTranche = $nombreTranches > 0
                ? round($validated['prix_vente'] / $nombreTranches, 2)
                : 0;

            $tranches = collect($validated['tranches'])->map(function ($trancheData, $index) use ($contractVente, $montantParTranche) {
                return [
                    'nom' => 'Tranche ' . ($index + 1),
                    'montant' => $montantParTranche,
                    'date_echeance' => $trancheData['date_echeance'],
                    'contrat_vente_id' => $contractVente->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            // Single insert for all charges and tranches
            ChargeApayer::insert($charges->merge($tranches)->toArray());

            $article->update(['current_step' => 'contrat_vente']);

            // Advance workflow to CONTRACT_CREATED if article is validated (ARTICLE_READY)
            // or still in DRAFT_ARTICLE (backwards compat: auto-validate + create contract)
            $workflow = app(ArticleWorkflowService::class);
            $currentState = $article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE;
            if ($currentState === ArticleWorkflowService::DRAFT_ARTICLE) {
                try { $workflow->transition($article, ArticleWorkflowService::ARTICLE_READY, Auth::id()); } catch (\RuntimeException) {}
                $currentState = $article->fresh()->workflow_state;
            }
            if ($currentState === ArticleWorkflowService::ARTICLE_READY) {
                try { $workflow->transition($article, ArticleWorkflowService::CONTRACT_CREATED, Auth::id()); } catch (\RuntimeException) {}
            }

            DB::commit();

            ActivityLogger::log('create', 'Contrat de vente créé', ContractVente::class, $contractVente->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Contrat de vente créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            ActivityLogger::log('error', 'Erreur lors de la création du contrat de vente: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du contrat de vente: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified contract vente.
     */
    public function edit(Article $article, ContractVente $contractVente): View
    {
        // Load exploitant fields needed for display
        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale', 'numero', 'n_cin', 'adresse', 'categorie', 'activite', 'qualification_rc', 'date_obtention', 'duree_validite', 'etat_validite', 'situation_administrative')
            ->orderBy('nom_complet')
            ->get();
        $contractVente->load('chargeApayer');
        
        // Separate charges and tranches
        $charges = $contractVente->chargeApayer->filter(function($charge) {
            return !str_starts_with($charge->nom, 'Tranche');
        })->values();
        
        $tranches = $contractVente->chargeApayer->filter(function($charge) {
            return str_starts_with($charge->nom, 'Tranche');
        })->sortBy(function($charge) {
            // Extract number from "Tranche 1", "Tranche 2", etc.
            preg_match('/Tranche (\d+)/', $charge->nom, $matches);
            return isset($matches[1]) ? (int)$matches[1] : 0;
        })->values();
        
        return view('contract-ventes.edit', compact('article', 'contractVente', 'exploitants', 'charges', 'tranches'));
    }

    /**
     * Update the specified contract vente in storage.
     */
    public function update(Request $request, Article $article, ContractVente $contractVente): RedirectResponse
    {
        $validated = $this->validateContractRequest($request);

        try {
            DB::beginTransaction();

            // Update contract vente
            $contractVente->update($this->buildContractPayload($validated, $article, $contractVente));

            // Delete existing charges
            $contractVente->chargeApayer()->delete();

            // Optimize: Bulk insert charges and tranches
            $charges = collect($validated['charges'])->map(function ($chargeData) use ($contractVente) {
                return [
                    'nom' => $chargeData['nom'],
                    'montant' => $chargeData['montant'],
                    'date_echeance' => $chargeData['date_echeance'],
                    'contrat_vente_id' => $contractVente->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            $nombreTranches = (int) $validated['nombre_tranche'];
            $montantParTranche = $nombreTranches > 0
                ? round($validated['prix_vente'] / $nombreTranches, 2)
                : 0;

            $tranches = collect($validated['tranches'])->map(function ($trancheData, $index) use ($contractVente, $montantParTranche) {
                return [
                    'nom' => 'Tranche ' . ($index + 1),
                    'montant' => $montantParTranche,
                    'date_echeance' => $trancheData['date_echeance'],
                    'contrat_vente_id' => $contractVente->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            // Single insert for all charges and tranches
            ChargeApayer::insert($charges->merge($tranches)->toArray());

            // Update article status to "contrat_vente" if not already at a later step
            $steps = ['cahier_affiche', 'contrat_vente', 'paiement_charges', 'paiement_tranches', 'recollement', 'main_levee'];
            $currentStepIndex = array_search($article->current_step, $steps);
            $contratVenteIndex = array_search('contrat_vente', $steps);
            
            // Only update if current step is before or equal to contrat_vente
            if ($currentStepIndex === false || $currentStepIndex <= $contratVenteIndex) {
                $article->update(['current_step' => 'contrat_vente']);
            }

            DB::commit();

            ActivityLogger::log('update', 'Contrat de vente mis à jour', ContractVente::class, $contractVente->id);

            return redirect()->route('articles.show', $article)
                ->with('success', 'Contrat de vente mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            ActivityLogger::log('error', 'Erreur lors de la mise à jour du contrat de vente: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du contrat de vente: ' . $e->getMessage());
        }
    }

    private function validateContractRequest(Request $request): array
    {
        $requestedTranches = (int) $request->input('nombre_tranche', 0);
        $tranchesRule = ['required', 'array'];

        if (in_array($requestedTranches, [1, 2, 4], true)) {
            $tranchesRule[] = 'size:' . $requestedTranches;
        }

        return $request->validate([
            'exploitant_id' => 'required|exists:exploitants,id',
            'prix_vente' => 'required|numeric|min:0',
            'nombre_tranche' => 'required|integer|in:1,2,4',
            'duree_decheache' => 'nullable|string|max:255',
            'date_limite_tranche' => 'nullable|date',
            'date_limite_taxes' => 'nullable|date',
            'bois_chauffage_volume_st' => 'nullable|numeric|min:0',
            'charges' => 'required|array',
            'charges.*.nom' => 'required|string',
            'charges.*.montant' => 'required|numeric|min:0',
            'charges.*.date_echeance' => 'required|date',
            'tranches' => $tranchesRule,
            'tranches.*.montant' => 'required|numeric|min:0',
            'tranches.*.date_echeance' => 'required|date',
        ]);
    }

    private function buildContractPayload(array $validated, Article $article, ?ContractVente $existingContract = null): array
    {
        $charges = collect($validated['charges'] ?? []);
        $tranches = collect($validated['tranches'] ?? []);
        $selectedType = $article->cession?->mode_cession ?? $existingContract?->type;

        $cautionCharge = $charges->first(function ($charge) {
            $name = strtolower((string) ($charge['nom'] ?? ''));

            return str_contains($name, 'caution');
        });

        $taxDates = $charges->filter(function ($charge) {
            $name = strtolower((string) ($charge['nom'] ?? ''));

            return !str_contains($name, 'caution') && !blank($charge['date_echeance'] ?? null);
        })->pluck('date_echeance');

        $trancheDates = $tranches->pluck('date_echeance')->filter();

        return [
            'type' => $selectedType,
            'date_adjudication' => $article->cession?->DateAdj ?? $existingContract?->date_adjudication,
            'numeraAO' => $selectedType === 'appel_doffre'
                ? ($article->cession?->numAO ?? $existingContract?->numeraAO)
                : null,
            'exploitant_id' => $validated['exploitant_id'],
            'prix_vente' => $validated['prix_vente'],
            'nombre_tranche' => $validated['nombre_tranche'],
            'date_limite_tranche' => $validated['date_limite_tranche'] ?? ($trancheDates->max() ?: null),
            'date_limite_taxes' => $validated['date_limite_taxes'] ?? ($taxDates->min() ?: null),
            'date_de_decheance' => $cautionCharge['date_echeance'] ?? null,
            'duree_decheache' => $validated['duree_decheache'] ?? null,
            'bois_chauffage_volume_st' => $validated['bois_chauffage_volume_st'] ?? null,
        ];
    }

    /**
     * Get exploitant details via AJAX
     */
    public function getExploitant(Exploitant $exploitant)
    {
        return response()->json([
            'id' => $exploitant->id,
            'nom_complet' => $exploitant->nom_complet,
            'raison_sociale' => $exploitant->raison_sociale,
            'n_cin' => $exploitant->n_cin,
            'numero' => $exploitant->numero,
            'adresse' => $exploitant->adresse,
            'categorie' => $exploitant->categorie,
            'activite' => $exploitant->activite,
            'qualification_rc' => $exploitant->qualification_rc,
            'date_obtention' => $exploitant->date_obtention ? $exploitant->date_obtention->format('d/m/Y') : null,
            'duree_validite' => $exploitant->duree_validite,
            'etat_validite' => $exploitant->etat_validite,
            'situation_administrative' => $exploitant->situation_administrative,
        ]);
    }
}
