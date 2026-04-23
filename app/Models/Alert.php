<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Alert extends Model
{
    // -------------------------------------------------------------------------
    // Alert type constants — add new constants here to extend the rule engine
    // -------------------------------------------------------------------------
    const TYPE_DECHEANCE_CAUTION             = 'DECHEANCE_CAUTION';
    const TYPE_RESILIATION_CONTRAT           = 'RESILIATION_CONTRAT';
    const TYPE_RETARD_TAXE                   = 'RETARD_TAXE';
    const TYPE_RETARD_TRANCHE                = 'RETARD_TRANCHE';
    const TYPE_EXPIRATION_CONTRAT            = 'EXPIRATION_CONTRAT';
    const TYPE_DEPASSEMENT_VOLUME_COLPORTAGE = 'DEPASSEMENT_VOLUME_COLPORTAGE';
    const TYPE_SERIE_COLPORTAGE_NON_UTILISEE = 'SERIE_COLPORTAGE_NON_UTILISEE';
    const TYPE_LETTER_UNSIGNED               = 'LETTER_UNSIGNED';
    const TYPE_RECOLEMENT_OVERDUE            = 'RECOLEMENT_OVERDUE';

    const SEVERITY_INFO     = 'info';
    const SEVERITY_WARNING  = 'warning';
    const SEVERITY_CRITICAL = 'critical';

    const STATUS_ACTIVE   = 'active';
    const STATUS_ARCHIVED = 'archived';

    // -------------------------------------------------------------------------
    // Dedup key prefixes — combine with a discriminator to form a dedup_key.
    // These are conventions, not enforced by the DB.
    // -------------------------------------------------------------------------
    const DEDUP_EXPIRATION_90D       = 'expiration_90d';
    const DEDUP_EXPIRATION_60D       = 'expiration_60d';
    const DEDUP_EXPIRATION_30D       = 'expiration_30d';
    const DEDUP_RESILIATION_NO_TAXES = 'resiliation_no_taxes';
    const DEDUP_RESILIATION_EXPIRED  = 'resiliation_expired_unpaid';
    // RETARD_TRANCHE uses "tranche_{order}" built dynamically in AlertService

    protected $fillable = [
        'type',
        'entity_type',
        'entity_id',
        'dedup_key',       // added: fine-grained dedup within (type, entity)
        'title',
        'message',
        'severity',
        'status',
        'archived_at',
        'archived_reason',
        'assigned_to',
        'archived_by',
        'data',
    ];

    protected $casts = [
        'data'        => 'array',
        'archived_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }

    public function scopeForEntity(Builder $query, string $entityType, int $entityId): Builder
    {
        return $query->where('entity_type', $entityType)->where('entity_id', $entityId);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeWithDedupKey(Builder $query, ?string $dedupKey): Builder
    {
        return $dedupKey === null
            ? $query->whereNull('dedup_key')
            : $query->where('dedup_key', $dedupKey);
    }

    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('severity', self::SEVERITY_CRITICAL);
    }

    public function scopeWarningOrAbove(Builder $query): Builder
    {
        return $query->whereIn('severity', [self::SEVERITY_WARNING, self::SEVERITY_CRITICAL]);
    }

    // -------------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------------

    public function archive(string $reason = '', ?int $archivedByUserId = null): void
    {
        $this->update([
            'status'          => self::STATUS_ARCHIVED,
            'archived_at'     => now(),
            'archived_reason' => $reason,
            'archived_by'     => $archivedByUserId,
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
