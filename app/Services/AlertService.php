<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Article;
use App\Models\Carnet;
use App\Models\ContractVente;
use App\Models\PermiEnlever;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Centralized alert management service.
 *
 * Design principles:
 *  - Never create Alert records directly in controllers — use this service.
 *  - Every raise*() method is idempotent: calling it twice will not produce
 *    a duplicate active alert for the same (type, entity, dedup_key) triplet.
 *  - Every resolve*() method archives matching active alerts.
 *  - The daily scheduler calls runDailyChecks() which evaluates all rules and
 *    auto-resolves alerts whose triggering condition has cleared.
 *
 * Adding a new alert rule:
 *  1. Add a TYPE_ constant on the Alert model.
 *  2. Add a raise*() and optionally a resolve*() helper here.
 *  3. Add the check + auto-resolve calls inside runDailyChecks().
 */
class AlertService
{
    // =========================================================================
    // CORE: generic raise / resolve
    // =========================================================================

    /**
     * Create an alert if one of the same (type, entity, dedup_key) is not
     * already active. Returns the existing alert if dedup applies.
     *
     * @param  string|null  $dedupKey  Optional sub-discriminator within the
     *                                  (type, entity) space. Use this when a
     *                                  single alert type can fire multiple times
     *                                  for the same entity with different causes
     *                                  — e.g. expiration thresholds or per-tranche
     *                                  retard alerts.
     */
    public function raise(
        string  $type,
        string  $entityType,
        int     $entityId,
        string  $title,
        string  $severity  = Alert::SEVERITY_WARNING,
        string  $message   = '',
        array   $data      = [],
        ?string $dedupKey  = null
    ): Alert {
        $existing = Alert::active()
            ->forEntity($entityType, $entityId)
            ->ofType($type)
            ->withDedupKey($dedupKey)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Alert::create([
            'type'        => $type,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'dedup_key'   => $dedupKey,
            'title'       => $title,
            'message'     => $message,
            'severity'    => $severity,
            'status'      => Alert::STATUS_ACTIVE,
            'data'        => $data ?: null,
        ]);
    }

    /**
     * Archive all active alerts matching (type, entity, dedup_key).
     * Pass $dedupKey = null to resolve all dedup variants of a type.
     */
    public function resolve(
        string  $type,
        string  $entityType,
        int     $entityId,
        string  $reason              = 'Résolu automatiquement',
        ?int    $resolvedByUserId    = null,
        ?string $dedupKey            = null,
        bool    $allDedupVariants    = false
    ): void {
        $query = Alert::active()
            ->forEntity($entityType, $entityId)
            ->ofType($type);

        if (!$allDedupVariants) {
            $query->withDedupKey($dedupKey);
        }

        $query->get()->each(fn($a) => $a->archive($reason, $resolvedByUserId));
    }

    // =========================================================================
    // RULE 1 — DÉCHÉANCE
    // Trigger: caution définitive not paid within 50 days of adjudication.
    // The contract stores date_de_decheance (= date_adjudication + 50 days).
    // We warn 7 days before and raise critical once the deadline passes.
    // =========================================================================

    public function raiseCautionDecheance(ContractVente $contract, Carbon $deadline): Alert
    {
        $daysLeft = (int) now()->diffInDays($deadline, false);
        $severity = $daysLeft <= 0 ? Alert::SEVERITY_CRITICAL : Alert::SEVERITY_WARNING;
        $title    = $daysLeft <= 0
            ? 'Caution non payée — délai dépassé'
            : 'Caution non payée — déchéance imminente';
        $message  = $daysLeft <= 0
            ? "Le délai de paiement de la caution définitive est dépassé (échéance : {$deadline->format('d/m/Y')}). Le contrat #{$contract->id} est en déchéance."
            : "La caution doit être payée avant le {$deadline->format('d/m/Y')} ({$daysLeft} jour(s) restant(s)). Contrat #{$contract->id}.";

        return $this->raise(
            Alert::TYPE_DECHEANCE_CAUTION,
            Article::class,
            $contract->article_id,
            $title,
            $severity,
            $message,
            ['deadline' => $deadline->toDateString(), 'contract_id' => $contract->id, 'days_left' => $daysLeft]
        );
    }

    public function resolveCautionDecheance(ContractVente $contract): void
    {
        $this->resolve(
            Alert::TYPE_DECHEANCE_CAUTION,
            Article::class,
            $contract->article_id,
            'Caution définitive payée'
        );
    }

    // =========================================================================
    // RULE 2 — RÉSILIATION
    // Three distinct sub-conditions, each with its own dedup_key so they can
    // coexist on the same contract without collapsing into one record.
    // =========================================================================

    /** Sub-condition A: aucune taxe n'a été payée après la date limite. */
    public function raiseResiliationAucuneTaxe(ContractVente $contract): Alert
    {
        return $this->raise(
            Alert::TYPE_RESILIATION_CONTRAT,
            Article::class,
            $contract->article_id,
            'Résiliation — aucune taxe payée',
            Alert::SEVERITY_CRITICAL,
            "Aucune taxe n'a été payée pour le contrat #{$contract->id}. Le contrat est susceptible d'être résilié.",
            ['contract_id' => $contract->id, 'reason' => 'no_taxes_paid'],
            Alert::DEDUP_RESILIATION_NO_TAXES
        );
    }

    /** Sub-condition B: contrat expiré avec paiements restants impayés. */
    public function raiseResiliationExpiredImpaye(ContractVente $contract): Alert
    {
        return $this->raise(
            Alert::TYPE_RESILIATION_CONTRAT,
            Article::class,
            $contract->article_id,
            'Résiliation — contrat expiré avec impayés',
            Alert::SEVERITY_CRITICAL,
            "Le contrat #{$contract->id} est expiré et des paiements restent impayés. Résiliation à envisager.",
            ['contract_id' => $contract->id, 'reason' => 'expired_with_unpaid'],
            Alert::DEDUP_RESILIATION_EXPIRED
        );
    }

    public function resolveResiliation(ContractVente $contract, string $reason = 'Situation régularisée'): void
    {
        $this->resolve(
            Alert::TYPE_RESILIATION_CONTRAT,
            Article::class,
            $contract->article_id,
            $reason,
            null,
            null,
            true // archive all dedup variants
        );
    }

    // =========================================================================
    // RULE 3 — TAXES EN RETARD
    // Raised per-contract when any unpaid tax is past its due date.
    // For individual named taxes (FNF, Reforestation, Provincial) the nom is
    // stored in the dedup_key so a separate alert fires for each.
    // =========================================================================

    public function raiseRetardTaxe(ContractVente $contract, Carbon $deadline, string $taxNom = ''): Alert
    {
        $dedupKey = $taxNom ? 'taxe_' . \Str::slug($taxNom) : null;
        $label    = $taxNom ?: 'Taxe';
        $title    = "Retard de paiement — {$label}";
        $message  = $taxNom
            ? "Retard de paiement taxe {$taxNom} pour le contrat #{$contract->id}. Échéance : {$deadline->format('d/m/Y')}."
            : "La date limite de paiement des taxes ({$deadline->format('d/m/Y')}) est dépassée pour le contrat #{$contract->id}.";

        return $this->raise(
            Alert::TYPE_RETARD_TAXE,
            Article::class,
            $contract->article_id,
            $title,
            Alert::SEVERITY_WARNING,
            $message,
            ['deadline' => $deadline->toDateString(), 'contract_id' => $contract->id, 'tax_nom' => $taxNom],
            $dedupKey
        );
    }

    public function resolveRetardTaxe(ContractVente $contract, string $taxNom = ''): void
    {
        $dedupKey = $taxNom ? 'taxe_' . \Str::slug($taxNom) : null;
        $this->resolve(
            Alert::TYPE_RETARD_TAXE,
            Article::class,
            $contract->article_id,
            'Taxe payée',
            null,
            $dedupKey
        );
    }

    // =========================================================================
    // RULE 4 — TRANCHE EN RETARD
    // One alert per tranche (dedup_key = "tranche_{order}").
    // =========================================================================

    public function raiseRetardTranche(ContractVente $contract, int $trancheOrder, string $nom, Carbon $deadline): Alert
    {
        $dedupKey = "tranche_{$trancheOrder}";

        return $this->raise(
            Alert::TYPE_RETARD_TRANCHE,
            Article::class,
            $contract->article_id,
            "Retard paiement {$nom}",
            Alert::SEVERITY_WARNING,
            "La {$nom} (n°{$trancheOrder}) était due le {$deadline->format('d/m/Y')} et n'a pas été réglée. Contrat #{$contract->id}.",
            ['tranche_order' => $trancheOrder, 'deadline' => $deadline->toDateString(), 'contract_id' => $contract->id],
            $dedupKey
        );
    }

    public function resolveRetardTranche(ContractVente $contract, int $trancheOrder): void
    {
        $this->resolve(
            Alert::TYPE_RETARD_TRANCHE,
            Article::class,
            $contract->article_id,
            'Tranche payée',
            null,
            "tranche_{$trancheOrder}"
        );
    }

    // =========================================================================
    // RULE 5 — EXPIRATION PROCHES
    // Three distinct alerts per contract (90 / 60 / 30 days) using dedup_key.
    // =========================================================================

    public function raiseExpirationContrat(ContractVente $contract, Carbon $expirationDate): Alert
    {
        $daysLeft = (int) now()->diffInDays($expirationDate, false);
        $severity = $daysLeft <= 30 ? Alert::SEVERITY_CRITICAL : Alert::SEVERITY_WARNING;

        [$dedupKey, $label] = match (true) {
            $daysLeft > 60  => [Alert::DEDUP_EXPIRATION_90D, '3 mois'],
            $daysLeft > 30  => [Alert::DEDUP_EXPIRATION_60D, '2 mois'],
            $daysLeft >= 0  => [Alert::DEDUP_EXPIRATION_30D, '1 mois'],
            default         => [Alert::DEDUP_EXPIRATION_30D, 'passée'],
        };

        $message = $daysLeft > 0
            ? "{$label} reste pour l'expiration du contrat #{$contract->id} (date d'expiration : {$expirationDate->format('d/m/Y')})."
            : "Le contrat #{$contract->id} est expiré depuis le {$expirationDate->format('d/m/Y')}.";

        return $this->raise(
            Alert::TYPE_EXPIRATION_CONTRAT,
            Article::class,
            $contract->article_id,
            $daysLeft > 0 ? "Expiration dans {$daysLeft} jour(s)" : 'Contrat expiré',
            $severity,
            $message,
            ['expiration_date' => $expirationDate->toDateString(), 'days_left' => $daysLeft, 'contract_id' => $contract->id],
            $dedupKey
        );
    }

    // =========================================================================
    // RULE 6 — COLPORTAGE
    // =========================================================================

    /** Volume cumulatif de colportage dépasse le volume autorisé du permis. */
    public function raiseDepassementVolumeColportage(PermiEnlever $permis, float $authorized, float $used): Alert
    {
        $contract = $permis->contractVente;

        return $this->raise(
            Alert::TYPE_DEPASSEMENT_VOLUME_COLPORTAGE,
            Article::class,
            $contract->article_id,
            'Dépassement du volume de colportage autorisé',
            Alert::SEVERITY_CRITICAL,
            "Volume de colportage utilisé ({$used} m³) dépasse le volume autorisé ({$authorized} m³) pour le permis #{$permis->id}. Contrat #{$contract->id}.",
            ['permis_id' => $permis->id, 'authorized' => $authorized, 'used' => $used, 'contract_id' => $contract->id],
            "permis_{$permis->id}"
        );
    }

    public function resolveDepassementVolumeColportage(PermiEnlever $permis): void
    {
        $contract = $permis->contractVente;
        $this->resolve(
            Alert::TYPE_DEPASSEMENT_VOLUME_COLPORTAGE,
            Article::class,
            $contract->article_id,
            'Volume corrigé',
            null,
            "permis_{$permis->id}"
        );
    }

    /** Carnet de colportage disponible depuis plus de 30 jours sans être utilisé. */
    public function raiseSerieNonUtilisee(Carnet $carnet): Alert
    {
        return $this->raise(
            Alert::TYPE_SERIE_COLPORTAGE_NON_UTILISEE,
            Carnet::class,
            $carnet->id,
            "Carnet de colportage non utilisé : {$carnet->serie}-{$carnet->num}",
            Alert::SEVERITY_INFO,
            "Le carnet n°{$carnet->num} de la série {$carnet->serie} est marqué disponible depuis plus de 30 jours sans avoir été utilisé.",
            ['serie' => $carnet->serie, 'num' => $carnet->num, 'carnet_id' => $carnet->id]
        );
    }

    public function resolveSerieNonUtilisee(Carnet $carnet): void
    {
        $this->resolve(
            Alert::TYPE_SERIE_COLPORTAGE_NON_UTILISEE,
            Carnet::class,
            $carnet->id,
            'Carnet utilisé ou statut mis à jour'
        );
    }

    // =========================================================================
    // SCHEDULER — daily check entry point
    // Called from: php artisan alerts:daily-check  (scheduled at 07:00)
    // =========================================================================

    public function runDailyChecks(): void
    {
        // Order matters: raise alerts first, then resolve cleared conditions.
        $this->checkDecheanceCaution();
        $this->checkTaxesOverdue();
        $this->checkTranchesOverdue();
        $this->checkExpirationProche();
        $this->checkResiliationExpiredImpaye();
        $this->checkColportageVolume();
        $this->checkCarnetsNonUtilises();

        // Auto-resolve conditions that have since been cleared
        $this->autoResolveCleared();
    }

    // =========================================================================
    // PRIVATE RULE CHECKERS
    // Each method focuses on a single alert family. Add new families here.
    // =========================================================================

    /**
     * RULE 1 — DÉCHÉANCE
     * Fire when: caution not paid AND date_de_decheance is within 7 days or past.
     *
     * The contract stores date_de_decheance (adjudication + 50 days).
     * We start warning 7 days before the deadline so the team can still act.
     */
    private function checkDecheanceCaution(): void
    {
        ContractVente::whereNotNull('date_de_decheance')
            ->where('date_de_decheance', '<=', now()->addDays(7)->toDateString())
            ->whereNull('deleted_at')
            ->each(function (ContractVente $contract) {
                $cautionPaid = $contract->payments()
                    ->where('type', 'caution')
                    ->where('is_paye', true)
                    ->exists();

                if (!$cautionPaid) {
                    $this->raiseCautionDecheance(
                        $contract,
                        Carbon::parse($contract->date_de_decheance)
                    );
                }
            });
    }

    /**
     * RULE 2A + RULE 3 — TAXES EN RETARD & RÉSILIATION (aucune taxe payée)
     *
     * When the global tax deadline has passed:
     *  - Raise RETARD_TAXE for each individual unpaid tax (by nom).
     *  - If NO tax at all has been paid, additionally raise RÉSILIATION.
     */
    private function checkTaxesOverdue(): void
    {
        ContractVente::whereNull('deleted_at')
            ->each(function (ContractVente $contract) {
                $taxes = $contract->payments()->where('type', 'taxe')->get();

                if ($taxes->isEmpty()) {
                    return;
                }

                $anyPaid = $taxes->where('is_paye', true)->isNotEmpty();
                $unpaid  = $taxes->where('is_paye', false);

                foreach ($unpaid as $taxe) {
                    if (!$taxe->date_decheace) {
                        continue;
                    }
                    $deadline = Carbon::parse($taxe->date_decheace);

                    if ($deadline->isPast()) {
                        $this->raiseRetardTaxe($contract, $deadline, (string) $taxe->nom);
                    }
                }

                if (!$anyPaid) {
                    $this->raiseResiliationAucuneTaxe($contract);
                }
            });
    }

    /**
     * RULE 4 — TRANCHES EN RETARD
     * One alert per overdue unpaid tranche, keyed by tranche order number.
     *
     * Also raises RÉSILIATION sub-condition B if any tranche is overdue
     * (the résiliation alert is shared at contract level, not per-tranche).
     */
    private function checkTranchesOverdue(): void
    {
        // Load contracts that have at least one overdue unpaid tranche
        ContractVente::whereHas('payments', function ($q) {
            $q->where('type', 'tranche')
              ->where('is_paye', false)
              ->where('date_decheace', '<', now()->toDateString());
        })
        ->whereNull('deleted_at')
        ->each(function (ContractVente $contract) {
            $overdueTranches = $contract->payments()
                ->where('type', 'tranche')
                ->where('is_paye', false)
                ->whereNotNull('date_decheace')
                ->where('date_decheace', '<', now()->toDateString())
                ->orderBy('order')
                ->get();

            foreach ($overdueTranches as $tranche) {
                $this->raiseRetardTranche(
                    $contract,
                    (int) $tranche->order,
                    (string) ($tranche->nom ?: "Tranche {$tranche->order}"),
                    Carbon::parse($tranche->date_decheace)
                );
            }
        });
    }

    /**
     * RULE 5 — EXPIRATION PROCHES
     * Three separate alerts per contract at 90, 60, and 30 day thresholds.
     * Each fires independently and uses a distinct dedup_key.
     */
    private function checkExpirationProche(): void
    {
        // Fetch contracts expiring within the next 90 days
        ContractVente::whereNotNull('date_expiration')
            ->where('date_expiration', '<=', now()->addDays(90)->toDateString())
            ->where('date_expiration', '>=', now()->toDateString()) // not yet expired
            ->whereNull('deleted_at')
            ->each(function (ContractVente $contract) {
                $expiration = Carbon::parse($contract->date_expiration);
                $daysLeft   = (int) now()->diffInDays($expiration, false);

                // Fire alerts at each threshold (each dedupes independently)
                if ($daysLeft <= 90) {
                    $this->raiseExpirationContrat($contract, $expiration);
                }
            });
    }

    /**
     * RULE 2C — RÉSILIATION: contrat expiré avec paiements impayés
     */
    private function checkResiliationExpiredImpaye(): void
    {
        ContractVente::whereNotNull('date_expiration')
            ->where('date_expiration', '<', now()->toDateString())
            ->whereNull('deleted_at')
            ->each(function (ContractVente $contract) {
                $hasUnpaid = $contract->payments()
                    ->where('is_paye', false)
                    ->exists();

                if ($hasUnpaid) {
                    $this->raiseResiliationExpiredImpaye($contract);
                    // Also raise the expiration alert itself (past threshold)
                    $this->raiseExpirationContrat($contract, Carbon::parse($contract->date_expiration));
                }
            });
    }

    /**
     * RULE 6A — COLPORTAGE: volume dépassé
     * For each PermiEnlever: sum the colportage volumes and compare with
     * the authorized volume on the permit.
     */
    private function checkColportageVolume(): void
    {
        PermiEnlever::whereNotNull('volume')
            ->where('volume', '>', 0)
            ->whereNull('deleted_at')
            ->with('contractVente')
            ->each(function (PermiEnlever $permis) {
                if (!$permis->contractVente) {
                    return;
                }

                $authorized = (float) $permis->volume;
                $used       = (float) $permis->colportages()->sum('volume');

                if ($used > $authorized) {
                    $this->raiseDepassementVolumeColportage($permis, $authorized, $used);
                } else {
                    // Auto-resolve if volume was previously over but is now within limit
                    $this->resolveDepassementVolumeColportage($permis);
                }
            });
    }

    /**
     * RULE 6B — COLPORTAGE: carnets disponibles non utilisés depuis 30+ jours
     */
    private function checkCarnetsNonUtilises(): void
    {
        Carnet::where('status', Carnet::STATUS_DISPONIBLE)
            ->where('updated_at', '<', now()->subDays(30))
            ->each(function (Carnet $carnet) {
                $this->raiseSerieNonUtilisee($carnet);
            });
    }

    // =========================================================================
    // AUTO-RESOLVE
    // Check conditions that may have cleared since yesterday and archive stale
    // active alerts. This keeps the alert dashboard clean and avoids noise.
    // =========================================================================

    private function autoResolveCleared(): void
    {
        $this->autoResolveCautionDecheance();
        $this->autoResolveRetardTaxes();
        $this->autoResolveRetardTranches();
        $this->autoResolveResiliationNoTaxes();
        $this->autoResolveSeriesNonUtilisees();
    }

    /** Resolve DECHEANCE_CAUTION if caution has since been paid. */
    private function autoResolveCautionDecheance(): void
    {
        Alert::active()
            ->ofType(Alert::TYPE_DECHEANCE_CAUTION)
            ->get()
            ->each(function (Alert $alert) {
                $contractId = $alert->data['contract_id'] ?? null;
                if (!$contractId) return;

                $contract = ContractVente::find($contractId);
                if (!$contract) return;

                $cautionPaid = $contract->payments()
                    ->where('type', 'caution')
                    ->where('is_paye', true)
                    ->exists();

                if ($cautionPaid) {
                    $alert->archive('Caution définitive payée — déchéance levée');
                }
            });
    }

    /** Resolve RETARD_TAXE per-tax when the individual payment is now paid. */
    private function autoResolveRetardTaxes(): void
    {
        Alert::active()
            ->ofType(Alert::TYPE_RETARD_TAXE)
            ->get()
            ->each(function (Alert $alert) {
                $contractId = $alert->data['contract_id'] ?? null;
                $taxNom     = $alert->data['tax_nom'] ?? null;
                if (!$contractId) return;

                $contract = ContractVente::find($contractId);
                if (!$contract) return;

                $query = $contract->payments()->where('type', 'taxe');
                if ($taxNom) {
                    $query->where('nom', $taxNom);
                }

                $stillUnpaid = $query->where('is_paye', false)->exists();

                if (!$stillUnpaid) {
                    $label = $taxNom ? "Taxe {$taxNom}" : 'Taxes';
                    $alert->archive("{$label} payée — retard résolu");
                }
            });
    }

    /** Resolve RETARD_TRANCHE when the specific tranche is now paid. */
    private function autoResolveRetardTranches(): void
    {
        Alert::active()
            ->ofType(Alert::TYPE_RETARD_TRANCHE)
            ->get()
            ->each(function (Alert $alert) {
                $contractId   = $alert->data['contract_id'] ?? null;
                $trancheOrder = $alert->data['tranche_order'] ?? null;
                if (!$contractId || $trancheOrder === null) return;

                $contract = ContractVente::find($contractId);
                if (!$contract) return;

                $stillUnpaid = $contract->payments()
                    ->where('type', 'tranche')
                    ->where('order', $trancheOrder)
                    ->where('is_paye', false)
                    ->exists();

                if (!$stillUnpaid) {
                    $alert->archive("Tranche n°{$trancheOrder} payée");
                }
            });
    }

    /** Resolve RESILIATION (no taxes) if at least one tax has since been paid. */
    private function autoResolveResiliationNoTaxes(): void
    {
        Alert::active()
            ->ofType(Alert::TYPE_RESILIATION_CONTRAT)
            ->withDedupKey(Alert::DEDUP_RESILIATION_NO_TAXES)
            ->get()
            ->each(function (Alert $alert) {
                $contractId = $alert->data['contract_id'] ?? null;
                if (!$contractId) return;

                $contract = ContractVente::find($contractId);
                if (!$contract) return;

                $anyPaid = $contract->payments()
                    ->where('type', 'taxe')
                    ->where('is_paye', true)
                    ->exists();

                if ($anyPaid) {
                    $alert->archive('Au moins une taxe a été payée — risque de résiliation levé');
                }
            });
    }

    /** Resolve SERIE_COLPORTAGE_NON_UTILISEE if carnet is no longer disponible. */
    private function autoResolveSeriesNonUtilisees(): void
    {
        Alert::active()
            ->ofType(Alert::TYPE_SERIE_COLPORTAGE_NON_UTILISEE)
            ->get()
            ->each(function (Alert $alert) {
                $carnetId = $alert->data['carnet_id'] ?? null;
                if (!$carnetId) return;

                $carnet = Carnet::find($carnetId);
                if (!$carnet || $carnet->status !== Carnet::STATUS_DISPONIBLE) {
                    $alert->archive('Carnet utilisé ou statut mis à jour');
                }
            });
    }

    // =========================================================================
    // QUERIES
    // =========================================================================

    /**
     * All active alerts for an article, ordered by severity (critical first).
     */
    public function getActiveAlertsForArticle(Article $article): Collection
    {
        return Alert::active()
            ->forEntity(Article::class, $article->id)
            ->orderByRaw("FIELD(severity, 'critical', 'warning', 'info')")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * All active critical alerts across the system (dashboard overview).
     */
    public function getAllCriticalAlerts(): Collection
    {
        return Alert::active()
            ->critical()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
