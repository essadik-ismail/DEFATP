<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Article;
use App\Models\ContractVente;
use App\Models\Payment;
use App\Models\PermisExploiter;
use App\Models\PvInstallation;
use App\Models\Prorogation;
use App\Models\Recolement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Central workflow state machine for Article dossiers.
 *
 * Each state describes where the dossier currently stands. Transitions are
 * guarded Ã¢â‚¬â€ attempting an illegal transition throws a WorkflowException.
 *
 * Allowed progression:
 *   DRAFT_ARTICLE
 *     Ã¢â€ â€™ ARTICLE_READY           (article fully filled in)
 *     Ã¢â€ â€™ CONTRACT_CREATED        (contract vente attached)
 *     Ã¢â€ â€™ LETTER_GENERATED        (adjudication letter generated)
 *     Ã¢â€ â€™ LETTER_SIGNED_UPLOADED  (signed letter uploaded)
 *     Ã¢â€ â€™ CAUTION_PAID            (caution payment marked paid)
 *     Ã¢â€ â€™ TAXES_PAID              (taxes payment marked paid)
 *     Ã¢â€ â€™ PERMIT_READY            (all financial obligations met)
 *     Ã¢â€ â€™ PERMIT_ISSUED           (permis exploiter issued)
 *     Ã¢â€ â€™ PV_INSTALLATION_DONE    (PV d'installation completed)
 *     Ã¢â€ â€™ VEHICLES_DECLARED       (at least one vehicle declared)
 *     Ã¢â€ â€™ TRANCHES_IN_PROGRESS    (tranche payments started)
 *     Ã¢â€ â€™ COLPORTAGE_ACTIVE       (colportage permits issued)
 *     Ã¢â€ â€™ RECOLEMENT_PENDING      (contract expired, rÃƒÂ©colement initiated)
 *     Ã¢â€ â€™ MAINLEVEE_DONE          (mainlevÃƒÂ©e issued)
 *     Ã¢â€ â€™ CLOSED                  (dossier definitively closed)
 *
 * Prorogation is a side-effect, not a primary state change.
 */
class ArticleWorkflowService
{
    // -------------------------------------------------------------------------
    // State constants
    // -------------------------------------------------------------------------
    const DRAFT_ARTICLE          = 'DRAFT_ARTICLE';
    const ARTICLE_READY          = 'ARTICLE_READY';
    const CONTRACT_CREATED       = 'CONTRACT_CREATED';
    const LETTER_GENERATED       = 'LETTER_GENERATED';
    const LETTER_SIGNED_UPLOADED = 'LETTER_SIGNED_UPLOADED';
    const CAUTION_PAID           = 'CAUTION_PAID';
    const TAXES_PAID             = 'TAXES_PAID';
    const PERMIT_READY           = 'PERMIT_READY';
    const PERMIT_ISSUED          = 'PERMIT_ISSUED';
    const PV_INSTALLATION_DONE   = 'PV_INSTALLATION_DONE';
    const VEHICLES_DECLARED      = 'VEHICLES_DECLARED';
    const TRANCHES_IN_PROGRESS   = 'TRANCHES_IN_PROGRESS';
    const COLPORTAGE_ACTIVE      = 'COLPORTAGE_ACTIVE';
    const PROROGATION_PENDING    = 'PROROGATION_PENDING';
    const PROROGATION_APPROVED   = 'PROROGATION_APPROVED';
    const RECOLEMENT_PENDING     = 'RECOLEMENT_PENDING';
    const MAINLEVEE_DONE         = 'MAINLEVEE_DONE';
    const CLOSED                 = 'CLOSED';

    // Side-states not in the primary workflow; map each to its effective parent for display
    const SIDE_STATE_PARENTS = [
        self::PROROGATION_PENDING  => self::TRANCHES_IN_PROGRESS,
        self::PROROGATION_APPROVED => self::TRANCHES_IN_PROGRESS,
    ];

    // Human-readable labels for UI (only primary workflow steps Ã¢â‚¬â€ not side-states like prorogation)
    const LABELS = [
        self::DRAFT_ARTICLE          => 'Brouillon',
        self::CONTRACT_CREATED       => 'Contrat crÃƒÂ©ÃƒÂ©',
        self::LETTER_GENERATED       => 'Lettre gÃƒÂ©nÃƒÂ©rÃƒÂ©e',
        self::LETTER_SIGNED_UPLOADED => 'Lettre signÃƒÂ©e uploadÃƒÂ©e',
        self::CAUTION_PAID           => 'Caution payÃƒÂ©e',
        self::TAXES_PAID             => 'Taxes payÃƒÂ©es',
        self::PERMIT_READY           => 'Permis d\'exploiter',
        self::PERMIT_ISSUED          => 'Permis d\'exploiter',
        self::PV_INSTALLATION_DONE   => 'PV d\'installation fait',
        self::VEHICLES_DECLARED      => 'VÃƒÂ©hicules dÃƒÂ©clarÃƒÂ©s',
        self::TRANCHES_IN_PROGRESS   => 'Tranches en cours',
        self::COLPORTAGE_ACTIVE      => 'Colportage actif',
        self::RECOLEMENT_PENDING     => 'RÃƒÂ©colement en attente',
        self::MAINLEVEE_DONE         => 'MainlevÃƒÂ©e ÃƒÂ©mise',
        self::CLOSED                 => 'ClÃƒÂ´turÃƒÂ©',
    ];

    // Allowed forward transitions per state
    const TRANSITIONS = [
        self::DRAFT_ARTICLE          => [self::CONTRACT_CREATED],
        self::CONTRACT_CREATED       => [self::LETTER_GENERATED],
        self::LETTER_GENERATED       => [self::LETTER_SIGNED_UPLOADED],
        self::LETTER_SIGNED_UPLOADED => [self::CAUTION_PAID],
        self::CAUTION_PAID           => [self::TAXES_PAID],
        self::TAXES_PAID             => [self::PERMIT_READY],
        self::PERMIT_READY           => [self::PERMIT_ISSUED],
        self::PERMIT_ISSUED          => [self::PV_INSTALLATION_DONE],
        self::PV_INSTALLATION_DONE   => [self::VEHICLES_DECLARED],
        self::VEHICLES_DECLARED      => [self::TRANCHES_IN_PROGRESS],
        self::TRANCHES_IN_PROGRESS   => [self::COLPORTAGE_ACTIVE, self::RECOLEMENT_PENDING],
        self::COLPORTAGE_ACTIVE      => [self::RECOLEMENT_PENDING],
        self::PROROGATION_PENDING    => [self::PROROGATION_APPROVED, self::TRANCHES_IN_PROGRESS],
        self::PROROGATION_APPROVED   => [self::TRANCHES_IN_PROGRESS],
        self::RECOLEMENT_PENDING     => [self::MAINLEVEE_DONE],
        self::MAINLEVEE_DONE         => [self::CLOSED],
    ];

    // -------------------------------------------------------------------------
    // Main transition method
    // -------------------------------------------------------------------------

    /**
     * Transition the article to a new workflow state.
     *
     * @throws \RuntimeException if the transition is not allowed
     */
    public function transition(Article $article, string $newState, ?int $userId = null): void
    {
        $currentState = $article->workflow_state ?? self::DRAFT_ARTICLE;

        if (!$this->canTransition($currentState, $newState)) {
            throw new \RuntimeException(
                "Transition impossible de [{$currentState}] vers [{$newState}]."
            );
        }

        $this->guardPrerequisites($article, $newState);

        DB::transaction(function () use ($article, $newState, $userId) {
            $article->update([
                'workflow_state'            => $newState,
                'workflow_state_updated_at' => now(),
                'workflow_state_updated_by' => $userId ?? Auth::id(),
            ]);

            // Resolve any alerts that are satisfied by this transition
            $this->resolveAlertsOnTransition($article, $newState);
        });
    }

    /**
     * Check whether a transition is structurally allowed.
     */
    public function canTransition(string $from, string $to): bool
    {
        return in_array($to, self::TRANSITIONS[$from] ?? [], true);
    }

    // -------------------------------------------------------------------------
    // Guards: prerequisites that must be met before entering a state
    // -------------------------------------------------------------------------

    /**
     * Raise an exception if business prerequisites for entering $newState
     * are not yet satisfied.
     *
     * @throws \RuntimeException
     */
    public function guardPrerequisites(Article $article, string $newState): void
    {
        match ($newState) {
            self::CONTRACT_CREATED       => $this->requireContract($article),
            self::LETTER_GENERATED       => $this->requireContract($article),
            self::LETTER_SIGNED_UPLOADED => $this->requireLetterSigned($article),
            self::CAUTION_PAID           => $this->requireLetterSigned($article),
            self::TAXES_PAID             => $this->requireCautionPaid($article),
            self::PERMIT_READY           => $this->requireTaxesPaid($article),
            self::PERMIT_ISSUED          => $this->requirePermitExists($article),
            self::PV_INSTALLATION_DONE   => $this->requirePermitIssued($article),
            self::VEHICLES_DECLARED      => $this->requireVehiclesDeclared($article),
            self::RECOLEMENT_PENDING     => $this->requireContractExpired($article),
            self::MAINLEVEE_DONE         => $this->requireRecolementSubmitted($article),
            self::CLOSED                 => $this->requireMainlevee($article),
            default                      => null,
        };
    }

    // Individual guards Ã¢â‚¬â€ keep these focused and reusable

    private function requireContract(Article $article): void
    {
        if (!$article->contractVentes()->exists()) {
            throw new \RuntimeException('Un contrat de vente doit ÃƒÂªtre crÃƒÂ©ÃƒÂ© pour passer ÃƒÂ  l\'ÃƒÂ©tape Contrat de vente.');
        }
    }

    private function requireLetterSigned(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        if (!$contract || !$contract->letter_signed_file) {
            throw new \RuntimeException('La lettre adjudicataire signÃƒÂ©e doit ÃƒÂªtre uploadÃƒÂ©e avant cette ÃƒÂ©tape.');
        }
    }

    private function requireCautionPaid(Article $article): void
    {
        $contract = $article->contractVentes()->with('chargeApayer.payments')->latest()->first();
        if (!$contract) {
            throw new \RuntimeException('Aucun contrat trouvÃƒÂ©.');
        }

        $cautionCharge = $contract->chargeApayer
            ->first(fn($charge) => str_contains(strtolower($charge->nom), 'caution'));

        $paid = $cautionCharge
            ? (bool) $cautionCharge->payments->first()?->is_paye
            : $contract->payments()
                ->where('type', 'caution')
                ->where('is_paye', true)
                ->exists();

        if (!$paid) {
            throw new \RuntimeException('La caution doit ÃƒÂªtre payÃƒÂ©e avant cette ÃƒÂ©tape.');
        }
    }

    private function requireTaxesPaid(Article $article): void
    {
        $contract = $article->contractVentes()->with('chargeApayer.payments')->latest()->first();
        if (!$contract) {
            throw new \RuntimeException('Aucun contrat trouvÃƒÂ©.');
        }

        $taxCharges = $contract->chargeApayer->filter(function ($charge) {
            $normalizedName = strtolower($charge->nom);

            return !str_starts_with($normalizedName, 'tranche')
                && !str_contains($normalizedName, 'caution');
        });

        $allTaxesPaid = $taxCharges->isNotEmpty()
            ? $taxCharges->every(fn($charge) => (bool) $charge->payments->first()?->is_paye)
            : $contract->payments()
                ->where('type', 'taxe')
                ->where('is_paye', false)
                ->doesntExist();

        if (!$allTaxesPaid) {
            throw new \RuntimeException('Toutes les taxes doivent ÃƒÂªtre payÃƒÂ©es avant cette ÃƒÂ©tape.');
        }
    }

    private function requirePermitExists(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        if (!$contract || !$contract->permisExploiter()->exists()) {
            throw new \RuntimeException('Le permis d\'exploiter doit ÃƒÂªtre crÃƒÂ©ÃƒÂ© avant cette ÃƒÂ©tape.');
        }
    }

    private function requirePermitIssued(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        $permit = $contract?->permisExploiter()->first();
        if (!$permit) {
            throw new \RuntimeException('Le permis d\'exploiter doit ÃƒÂªtre ÃƒÂ©mis avant cette ÃƒÂ©tape.');
        }
    }

    private function requireVehiclesDeclared(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        if (!$contract || !$contract->vehicleDeclarations()->exists()) {
            throw new \RuntimeException('Au moins un vÃƒÂ©hicule doit ÃƒÂªtre dÃƒÂ©clarÃƒÂ© avant cette ÃƒÂ©tape.');
        }
    }

    private function requireContractExpired(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        if (!$contract || !$contract->date_expiration) {
            throw new \RuntimeException('La date d\'expiration du contrat est requise pour initier le rÃƒÂ©colement.');
        }
        if ($contract->date_expiration->isFuture()) {
            throw new \RuntimeException('Le contrat n\'a pas encore expirÃƒÂ©.');
        }
    }

    private function requireRecolementSubmitted(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        $recolement = $contract ? Recolement::where('contract_vente_id', $contract->id)
            ->where('status', Recolement::STATUS_PV_SUBMITTED)
            ->exists() : false;
        if (!$recolement) {
            throw new \RuntimeException('Le PV de rÃƒÂ©colement doit ÃƒÂªtre soumis avant d\'ÃƒÂ©mettre la mainlevÃƒÂ©e.');
        }
    }

    private function requireMainlevee(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        $done = $contract ? Recolement::where('contract_vente_id', $contract->id)
            ->where('status', Recolement::STATUS_MAINLEVEE_ISSUED)
            ->exists() : false;
        if (!$done) {
            throw new \RuntimeException('La mainlevÃƒÂ©e doit ÃƒÂªtre ÃƒÂ©mise avant la clÃƒÂ´ture.');
        }
    }

    // -------------------------------------------------------------------------
    // Determine what actions are available in the current state
    // -------------------------------------------------------------------------

    /**
     * Return an array of step info for UI rendering:
     * [state => ['label' => ..., 'status' => done|active|blocked, 'blocked_reason' => ...]]
     */
    public function getStepStatuses(Article $article): array
    {
        $currentState = $article->workflow_state ?? self::DRAFT_ARTICLE;

        // Map side-states (e.g. prorogation) to their effective parent state for display
        if (isset(self::SIDE_STATE_PARENTS[$currentState])) {
            $currentState = self::SIDE_STATE_PARENTS[$currentState];
        }

        $allStates = array_keys(self::LABELS);
        $currentIndex = array_search($currentState, $allStates, true);
        $lastIndex = count($allStates) - 1;

        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        $steps = [];
        foreach ($allStates as $i => $state) {
            if ($currentIndex === 0) {
                if ($i === 0) {
                    $status = 'active';
                    $blockedReason = null;
                } elseif ($i === 1) {
                    try {
                        $this->guardPrerequisites($article, $state);
                        $status = 'pending';
                        $blockedReason = null;
                    } catch (\RuntimeException $e) {
                        $status = 'blocked';
                        $blockedReason = $e->getMessage();
                    }
                } else {
                    $status = 'blocked';
                    $blockedReason = 'Les ÃƒÂ©tapes prÃƒÂ©cÃƒÂ©dentes doivent ÃƒÂªtre complÃƒÂ©tÃƒÂ©es d\'abord.';
                }
            } elseif ($currentIndex === $lastIndex) {
                if ($i < $currentIndex) {
                    $status = 'done';
                    $blockedReason = null;
                } elseif ($i === $currentIndex) {
                    $status = 'active';
                    $blockedReason = null;
                } else {
                    $status = 'pending';
                    $blockedReason = null;
                }
            } elseif ($i <= $currentIndex) {
                $status = 'done';
                $blockedReason = null;
            } elseif ($i === $currentIndex + 1) {
                try {
                    $this->guardPrerequisites($article, $state);
                    $status = 'active';
                    $blockedReason = null;
                } catch (\RuntimeException $e) {
                    $status = 'blocked';
                    $blockedReason = $e->getMessage();
                }
            } else {
                $status = 'blocked';
                $blockedReason = 'Les ÃƒÂ©tapes prÃƒÂ©cÃƒÂ©dentes doivent ÃƒÂªtre complÃƒÂ©tÃƒÂ©es d\'abord.';
            }

            $steps[$state] = [
                'label'          => self::LABELS[$state] ?? $state,
                'status'         => $status,
                'blocked_reason' => $blockedReason,
            ];
        }

        return $steps;
    }

    // -------------------------------------------------------------------------
    // Alert resolution
    // -------------------------------------------------------------------------

    private function resolveAlertsOnTransition(Article $article, string $newState): void
    {
        $resolveMap = [
            self::CAUTION_PAID   => Alert::TYPE_DECHEANCE_CAUTION,
            self::TAXES_PAID     => Alert::TYPE_RETARD_TAXE,
            self::CLOSED         => Alert::TYPE_EXPIRATION_CONTRAT,
        ];

        if (isset($resolveMap[$newState])) {
            Alert::active()
                ->forEntity(Article::class, $article->id)
                ->ofType($resolveMap[$newState])
                ->get()
                ->each(fn($alert) => $alert->archive('Resolved by workflow transition to ' . $newState));
        }
    }
}
