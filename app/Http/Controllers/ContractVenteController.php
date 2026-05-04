<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
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
    public function create(Article $article): View|RedirectResponse
    {
        $this->authorizeZdtf();

        if (($article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE) === ArticleWorkflowService::DRAFT_ARTICLE) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'L\'article doit être validé avant de créer un contrat de vente. Veuillez d\'abord valider l\'article à l\'étape 1.');
        }

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
        $this->authorizeZdtf();

        if (($article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE) === ArticleWorkflowService::DRAFT_ARTICLE) {
            return redirect()->route('articles.show', $article)
                ->with('error', 'L\'article doit être validé avant de créer un contrat de vente. Veuillez d\'abord valider l\'article à l\'étape 1.');
        }

        $validated = $this->validateContractRequest($request);

        try {
            DB::beginTransaction();

            $contractVente = ContractVente::updateOrCreate(
                ['article_id' => $article->id],
                $this->buildContractPayload($validated, $article) + ['Current_state' => 'contrat_créé']
            );

            $contractVente->chargeApayer()->delete();

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

            ChargeApayer::insert($charges->merge($tranches)->toArray());

            $article->update(['current_step' => 'contrat_vente']);

            $workflow = app(ArticleWorkflowService::class);
            $currentState = $article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE;
            if ($currentState === ArticleWorkflowService::ARTICLE_READY) {
                try { $workflow->transition($article, ArticleWorkflowService::CONTRACT_CREATED, Auth::id()); } catch (\RuntimeException) {}
            }

            DB::commit();

            ActivityLogger::log('create', 'Contrat de vente créé', ContractVente::class, $contractVente->id);

            return redirect()->route('contract-ventes.show', [$article, $contractVente])
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
     * Display a read-only view of the contract vente.
     */
    public function show(Article $article, ContractVente $contractVente): View
    {
        $contractVente->load(['chargeApayer', 'exploitant']);

        $charges = $contractVente->chargeApayer->filter(fn($c) => !str_starts_with($c->nom, 'Tranche'))->values();
        $tranches = $contractVente->chargeApayer->filter(fn($c) => str_starts_with($c->nom, 'Tranche'))
            ->sortBy(fn($c) => (int) preg_replace('/\D/', '', $c->nom))
            ->values();

        return view('contract-ventes.show', compact('article', 'contractVente', 'charges', 'tranches'));
    }

    /**
     * Show the form for editing the specified contract vente.
     */
    public function edit(Article $article, ContractVente $contractVente): View|RedirectResponse
    {
        if ($contractVente->is_validated) {
            return redirect()->route('contract-ventes.show', [$article, $contractVente])
                ->with('info', 'Ce contrat est validé et ne peut plus être modifié.');
        }

        $exploitants = Exploitant::select('id', 'nom_complet', 'raison_sociale', 'numero', 'n_cin', 'adresse', 'categorie', 'activite', 'qualification_rc', 'date_obtention', 'duree_validite', 'etat_validite', 'situation_administrative')
            ->orderBy('nom_complet')
            ->get();
        $contractVente->load('chargeApayer');

        $charges = $contractVente->chargeApayer->filter(fn($c) => !str_starts_with($c->nom, 'Tranche'))->values();
        $tranches = $contractVente->chargeApayer->filter(fn($c) => str_starts_with($c->nom, 'Tranche'))
            ->sortBy(function ($charge) {
                preg_match('/Tranche (\d+)/', $charge->nom, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })->values();

        return view('contract-ventes.edit', compact('article', 'contractVente', 'exploitants', 'charges', 'tranches'));
    }

    /**
     * Update the specified contract vente in storage.
     */
    public function update(Request $request, Article $article, ContractVente $contractVente): RedirectResponse
    {
        if ($contractVente->is_validated) {
            return redirect()->route('contract-ventes.show', [$article, $contractVente])
                ->with('error', 'Ce contrat est validé et ne peut plus être modifié.');
        }

        $validated = $this->validateContractRequest($request);

        try {
            DB::beginTransaction();

            $contractVente->update($this->buildContractPayload($validated, $article, $contractVente));

            $contractVente->chargeApayer()->delete();

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

            ChargeApayer::insert($charges->merge($tranches)->toArray());

            $steps = ['cahier_affiche', 'contrat_vente', 'paiement_charges', 'paiement_tranches', 'recollement', 'main_levee'];
            $currentStepIndex = array_search($article->current_step, $steps);
            $contratVenteIndex = array_search('contrat_vente', $steps);

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

    /**
     * Validate (lock) a contract vente — irreversible.
     */
    public function validateContract(Request $request, Article $article, ContractVente $contractVente): RedirectResponse
    {
        $this->authorizeZdtf();

        if ($contractVente->is_validated) {
            return redirect()->route('articles.show', $article)
                ->with('info', 'Ce contrat est déjà validé.');
        }

        $contractVente->update([
            'is_validated' => true,
            'validated_at' => now(),
            'Current_state' => 'contrat_validé',
        ]);

        ActivityLogger::log('validate', 'Contrat de vente validé (verrouillé)', ContractVente::class, $contractVente->id);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Contrat validé. Il est maintenant verrouillé.');
    }

    private function validateContractRequest(Request $request): array
    {
        $requestedTranches = (int) $request->input('nombre_tranche', 0);
        $tranchesRule = ['required', 'array'];

        if (in_array($requestedTranches, [1, 2, 4], true)) {
            $tranchesRule[] = 'size:' . $requestedTranches;
        }

        return $request->validate([
            'exploitant_id'              => 'required|exists:exploitants,id',
            'prix_vente'                 => 'required|numeric|min:0',
            'nombre_tranche'             => 'required|integer|in:1,2,4',
            'duree_decheache'            => 'required|integer|min:1',
            'bois_chauffage_volume_st'   => 'nullable|numeric|min:0',
            'percepteur'                 => 'nullable|string|max:255',
            'charges'                    => 'required|array',
            'charges.*.nom'              => 'required|string',
            'charges.*.montant'          => 'required|numeric|min:0',
            'charges.*.date_echeance'    => 'required|date',
            'tranches'                   => $tranchesRule,
            'tranches.*.montant'         => 'required|numeric|min:0',
            'tranches.*.date_echeance'   => 'required|date',
        ], [
            'duree_decheache.required'         => 'La durée de contrat est obligatoire.',
            'duree_decheache.integer'          => 'La durée doit être un nombre entier de mois.',
            'charges.*.date_echeance.required' => 'La date d\'échéance de chaque taxe est obligatoire.',
            'tranches.*.date_echeance.required'=> 'La date d\'échéance de chaque tranche est obligatoire.',
        ]);
    }

    private function buildContractPayload(array $validated, Article $article, ?ContractVente $existingContract = null): array
    {
        $charges = collect($validated['charges'] ?? []);
        $tranches = collect($validated['tranches'] ?? []);
        $selectedType = $article->cession?->mode_cession ?? $existingContract?->type;

        $cautionCharge = $charges->first(fn($c) => str_contains(strtolower((string) ($c['nom'] ?? '')), 'caution'));

        // Compute date_expiration = date_adjudication + duree (months)
        $dateAdj = $article->cession?->DateAdj ?? $existingContract?->date_adjudication;
        $duree = (int) ($validated['duree_decheache'] ?? 0);
        $dateExpiration = $dateAdj && $duree > 0
            ? \Illuminate\Support\Carbon::parse($dateAdj)->addMonths($duree)->toDateString()
            : null;

        return [
            'type' => $selectedType,
            'date_adjudication' => $dateAdj,
            'numeraAO' => $selectedType === 'appel_offre'
                ? ($article->cession?->numAO ?? $existingContract?->numeraAO)
                : null,
            'exploitant_id'         => $validated['exploitant_id'],
            'prix_vente'            => $validated['prix_vente'],
            'nombre_tranche'        => $validated['nombre_tranche'],
            'date_de_decheance'     => $cautionCharge['date_echeance'] ?? null,
            'duree_decheache'       => $validated['duree_decheache'],
            'date_expiration'       => $dateExpiration,
            'bois_chauffage_volume_st' => $validated['bois_chauffage_volume_st'] ?? null,
            'percepteur'              => $validated['percepteur'] ?? null,
        ];
    }

    /**
     * Get exploitant details via AJAX
     */
    public function getExploitant(Exploitant $exploitant)
    {
        return response()->json([
            'id'                      => $exploitant->id,
            'nom_complet'             => $exploitant->nom_complet,
            'raison_sociale'          => $exploitant->raison_sociale,
            'n_cin'                   => $exploitant->n_cin,
            'numero'                  => $exploitant->numero,
            'adresse'                 => $exploitant->adresse,
            'categorie'               => $exploitant->categorie,
            'activite'                => $exploitant->activite,
            'qualification_rc'        => $exploitant->qualification_rc,
            'date_obtention'          => $exploitant->date_obtention ? $exploitant->date_obtention->format('d/m/Y') : null,
            'duree_validite'          => $exploitant->duree_validite,
            'etat_validite'           => $exploitant->etat_validite,
            'situation_administrative'=> $exploitant->situation_administrative,
        ]);
    }

    private function authorizeZdtf(): void
    {
        $user = Auth::user();
        $allowedRoles = [UserRole::Admin->value, UserRole::Zdtf->value, UserRole::ZdtfDpanef->value];

        if (!$user || !in_array($user->role?->value ?? $user->role, $allowedRoles, true)) {
            abort(403, 'Accès refusé. Seuls les agents ZDTF peuvent gérer les contrats de vente.');
        }
    }
}
