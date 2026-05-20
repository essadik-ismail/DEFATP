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
 * Primary 11-step progression:
 *   DRAFT_ARTICLE          (article created, awaiting explicit validation)
 *     -> ARTICLE_READY     (article validated by user — unlocks next steps)
 *     -> CONTRACT_CREATED  (contrat de vente attached)
 *     -> LETTER_SIGNED_UPLOADED (lettre adjudicataire signée)
 *     -> CAUTION_PAID
 *     -> TAXES_PAID
 *     -> PERMIT_ISSUED
 *     -> PV_INSTALLATION_DONE
 *     -> TRANCHES_IN_PROGRESS
 *     -> RECOLEMENT_PENDING
 *     -> MAINLEVEE_DONE
 *     -> CLOSED
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
    const COLPORTAGE_ACTIVE      = 'COLPORTAGE_ACTIVE'; // legacy — mapped to TRANCHES_IN_PROGRESS
    const PROROGATION_PENDING    = 'PROROGATION_PENDING';
    const PROROGATION_APPROVED   = 'PROROGATION_APPROVED';
    const RECOLEMENT_PENDING     = 'RECOLEMENT_PENDING';
    const MAINLEVEE_DONE         = 'MAINLEVEE_DONE';
    const CLOSED                 = 'CLOSED';

    // Numeric ordering for state comparison (side-states share rank with their parent)
    const STATE_ORDER = [
        self::DRAFT_ARTICLE          => 0,
        self::ARTICLE_READY          => 1,
        self::CONTRACT_CREATED       => 2,
        self::LETTER_GENERATED       => 2,
        self::LETTER_SIGNED_UPLOADED => 3,
        self::CAUTION_PAID           => 4,
        self::TAXES_PAID             => 5,
        self::PERMIT_READY           => 6,
        self::PERMIT_ISSUED          => 6,
        self::PV_INSTALLATION_DONE   => 7,
        self::VEHICLES_DECLARED      => 7,
        self::TRANCHES_IN_PROGRESS   => 8,
        self::PROROGATION_PENDING    => 8,
        self::PROROGATION_APPROVED   => 8,
        self::COLPORTAGE_ACTIVE      => 8,
        self::RECOLEMENT_PENDING     => 9,
        self::MAINLEVEE_DONE         => 10,
        self::CLOSED                 => 11,
    ];

    // Side-states not in the primary workflow; map each to its effective parent for display
    const SIDE_STATE_PARENTS = [
        self::PROROGATION_PENDING    => self::TRANCHES_IN_PROGRESS,
        self::PROROGATION_APPROVED   => self::TRANCHES_IN_PROGRESS,
        // Backwards compatibility: old intermediate states removed from primary flow
        self::LETTER_GENERATED       => self::CONTRACT_CREATED,
        self::PERMIT_READY           => self::TAXES_PAID,
        self::VEHICLES_DECLARED      => self::PV_INSTALLATION_DONE,
        self::COLPORTAGE_ACTIVE      => self::TRANCHES_IN_PROGRESS,
    ];

    // Human-readable labels for UI — exactly 11 primary workflow steps
    const LABELS = [
        self::DRAFT_ARTICLE          => 'Création de l\'article',
        self::CONTRACT_CREATED       => 'Contrat de vente',
        self::LETTER_SIGNED_UPLOADED => 'Lettre adjudicataire',
        self::CAUTION_PAID           => 'Paiement caution',
        self::TAXES_PAID             => 'Paiement des taxes',
        self::PERMIT_ISSUED          => 'Permis d\'exploiter',
        self::PV_INSTALLATION_DONE   => 'PV d\'installation',
        self::TRANCHES_IN_PROGRESS   => 'Paiement des tranches',
        self::RECOLEMENT_PENDING     => 'Récolement',
        self::MAINLEVEE_DONE         => 'Mainlevée',
        self::CLOSED                 => 'Clôture',
    ];

    // Allowed forward transitions per state
    const TRANSITIONS = [
        self::DRAFT_ARTICLE          => [self::ARTICLE_READY],
        self::ARTICLE_READY          => [self::CONTRACT_CREATED],
        self::CONTRACT_CREATED       => [self::LETTER_SIGNED_UPLOADED],
        self::LETTER_SIGNED_UPLOADED => [self::CAUTION_PAID],
        self::CAUTION_PAID           => [self::TAXES_PAID],
        self::TAXES_PAID             => [self::PERMIT_ISSUED],
        self::PERMIT_ISSUED          => [self::PV_INSTALLATION_DONE],
        self::PV_INSTALLATION_DONE   => [self::TRANCHES_IN_PROGRESS],
        self::TRANCHES_IN_PROGRESS   => [self::RECOLEMENT_PENDING],
        self::PROROGATION_PENDING    => [self::PROROGATION_APPROVED, self::TRANCHES_IN_PROGRESS],
        self::PROROGATION_APPROVED   => [self::TRANCHES_IN_PROGRESS],
        self::RECOLEMENT_PENDING     => [self::MAINLEVEE_DONE],
        self::MAINLEVEE_DONE         => [self::CLOSED],
    ];

    // -------------------------------------------------------------------------
    // State comparison helpers
    // -------------------------------------------------------------------------

    /**
     * Return true if the article's current workflow state is at or past the given state.
     * Used to enforce read-only locks on completed steps.
     */
    public function isAtOrPast(Article $article, string $state): bool
    {
        $current = self::STATE_ORDER[$article->workflow_state ?? self::DRAFT_ARTICLE] ?? 0;
        $target  = self::STATE_ORDER[$state] ?? 0;
        return $current >= $target;
    }

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
            self::LETTER_SIGNED_UPLOADED => $this->requireLetterSigned($article),
            self::CAUTION_PAID           => $this->requireCautionPaid($article),
            self::TAXES_PAID             => $this->requireTaxesPaid($article),
            self::PERMIT_ISSUED          => $this->requirePermitExists($article),
            self::PV_INSTALLATION_DONE   => $this->requirePvInstallationPrereqs($article),
            self::RECOLEMENT_PENDING     => $this->requireContractExpired($article),
            self::MAINLEVEE_DONE         => $this->requireRecolementSubmitted($article),
            self::CLOSED                 => $this->requireMainlevee($article),
            default                      => null,
        };
    }

    // Individual guards — keep these focused and reusable

    private function requireContract(Article $article): void
    {
        if (!$article->contractVentes()->exists()) {
            throw new \RuntimeException('Un contrat de vente doit être créé pour passer à l\'étape Contrat de vente.');
        }
    }

    private function requireLetterSigned(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        if (!$contract || !$contract->letter_signed_file) {
            throw new \RuntimeException('La lettre adjudicataire signée doit être uploadée avant cette étape.');
        }
    }

    private function requireCautionPaid(Article $article): void
    {
        $contract = $article->contractVentes()->with('chargeApayer.payments')->latest()->first();
        if (!$contract) {
            throw new \RuntimeException('Aucun contrat trouvé.');
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
            throw new \RuntimeException('La caution doit être payée avant cette étape.');
        }

        if ($cautionCharge) {
            $payment = $cautionCharge->payments->first();
            if ($payment) {
                if (!$payment->date_payment) {
                    throw new \RuntimeException('La date de paiement de la caution est obligatoire.');
                }
                if (!$payment->fichier_joint) {
                    throw new \RuntimeException('La quittance (fichier) de la caution est obligatoire.');
                }
            }
        }
    }

    private function requireTaxesPaid(Article $article): void
    {
        $contract = $article->contractVentes()->with('chargeApayer.payments')->latest()->first();
        if (!$contract) {
            throw new \RuntimeException('Aucun contrat trouvé.');
        }

        $taxCharges = $contract->chargeApayer->filter(function ($charge) {
            $normalizedName = strtolower($charge->nom);

            return !str_starts_with($normalizedName, 'tranche')
                && !str_contains($normalizedName, 'caution')
                && !str_contains($normalizedName, 'anef');
        });

        $allTaxesPaid = $taxCharges->isNotEmpty()
            ? $taxCharges->every(fn($charge) => (bool) $charge->payments->first()?->is_paye)
            : $contract->payments()
                ->where('type', 'taxe')
                ->where('is_paye', false)
                ->doesntExist();

        if (!$allTaxesPaid) {
            throw new \RuntimeException('Toutes les taxes doivent être payées avant cette étape.');
        }
    }

    private function requirePermitExists(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        if (!$contract || !$contract->permisExploiter()->exists()) {
            throw new \RuntimeException('Le permis d\'exploiter doit être créé avant cette étape.');
        }
    }

    private function requirePvInstallationPrereqs(Article $article): void
    {
        $this->requirePermitExists($article);
    }

    private function requireContractExpired(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        if (!$contract || !$contract->date_expiration) {
            throw new \RuntimeException('La date d\'expiration du contrat est requise pour initier le récolement.');
        }
        if ($contract->date_expiration->isFuture()) {
            throw new \RuntimeException('Le contrat n\'a pas encore expiré.');
        }
    }

    private function requireRecolementSubmitted(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        $recolement = $contract ? Recolement::where('contract_vente_id', $contract->id)
            ->where('status', Recolement::STATUS_PV_SUBMITTED)
            ->exists() : false;
        if (!$recolement) {
            throw new \RuntimeException('Le PV de récolement doit être soumis avant d\'émettre la mainlevée.');
        }
    }

    private function requireMainlevee(Article $article): void
    {
        $contract = $article->contractVentes()->latest()->first();
        $done = $contract ? Recolement::where('contract_vente_id', $contract->id)
            ->where('status', Recolement::STATUS_MAINLEVEE_ISSUED)
            ->exists() : false;
        if (!$done) {
            throw new \RuntimeException('La mainlevée doit être émise avant la clôture.');
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
        $actualState  = $article->workflow_state ?? self::DRAFT_ARTICLE;
        $currentState = $actualState;

        // ARTICLE_READY means step 1 (DRAFT_ARTICLE) is explicitly validated.
        // Display: step 1 done, step 2 (CONTRACT_CREATED) is next to act on.
        $articleValidated = ($actualState === self::ARTICLE_READY);

        // Map side-states (e.g. prorogation, old intermediate states) to their effective parent
        if (!$articleValidated && isset(self::SIDE_STATE_PARENTS[$currentState])) {
            $currentState = self::SIDE_STATE_PARENTS[$currentState];
        }

        if ($articleValidated) {
            $currentState = self::DRAFT_ARTICLE; // step 0 in allStates
        }

        $allStates    = array_keys(self::LABELS);
        $currentIndex = array_search($currentState, $allStates, true);
        $lastIndex    = count($allStates) - 1;

        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        $steps = [];
        foreach ($allStates as $i => $state) {
            if ($articleValidated) {
                // Step 0 (DRAFT_ARTICLE) is done; step 1 (CONTRACT_CREATED) is always next/active
                if ($i === 0) {
                    $status = 'done';
                    $blockedReason = null;
                } elseif ($i === 1) {
                    // CONTRACT_CREATED is always the actionable next step when article is validated
                    $status = 'active';
                    $blockedReason = null;
                } else {
                    $status = 'blocked';
                    $blockedReason = 'Les étapes précédentes doivent être complétées d\'abord.';
                }
            } elseif ($currentIndex === 0) {
                // Article still in DRAFT — only step 0 is active, rest are blocked
                if ($i === 0) {
                    $status = 'active';
                    $blockedReason = null;
                } else {
                    $status = 'blocked';
                    $blockedReason = $i === 1
                        ? 'L\'article doit être validé à l\'étape 1 avant de continuer.'
                        : 'Les étapes précédentes doivent être complétées d\'abord.';
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
                // Next step is always actionable — guards run on actual transition server-side
                $status = 'active';
                $blockedReason = null;
            } else {
                $status = 'blocked';
                $blockedReason = 'Les étapes précédentes doivent être complétées d\'abord.';
            }

            // PV d'installation is always accessible (never fully locked)
            if ($state === self::PV_INSTALLATION_DONE && $status === 'blocked') {
                $status = 'pending';
                $blockedReason = null;
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
