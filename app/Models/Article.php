<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    protected $fillable = [
        'annee',
        'numero',
        'date_adjudication',
        'invendu',
        'prix_de_retrait',
        'situation_administrative_id',
        'foret_id',
        'essence_id',
        'nature_de_coupe_id',
        'localisation_id',
        'lot',
        'parcelle',
        'superficie',
        'bo_m3',
        'bi_m3',
        'bf_st',
        'tanin_t',
        'fleur_acacia_t',
        'caroube_t',
        'romarin_t',
        'ps_t',
        'charbon_bois_ox',
        'prix_vente',
        'fourniture_mise_charge',
        'lat',
        'log',
        'date_dr',
        'observations',
        'is_deleted',
        'dc',
        'rc',
        'type',
        'exploitant_id',
        'is_validated',
        'adjudicatire',
        'numero_adjudication',
        'date_de_resiliation',
        'date_de_decheance',
    ];

    protected $casts = [
        'date_adjudication' => 'date',
        'date_dr' => 'date',
        'date_de_resiliation' => 'date',
        'date_de_decheance' => 'date',
        'invendu' => 'boolean',
        'bo_m3' => 'integer',
        'bi_m3' => 'integer',
        'bf_st' => 'integer',
        'tanin_t' => 'integer',
        'fleur_acacia_t' => 'integer',
        'caroube_t' => 'integer',
        'romarin_t' => 'integer',
        'ps_t' => 'integer',
        'charbon_bois_ox' => 'integer',
        'prix_de_retrait' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'fourniture_mise_charge' => 'decimal:2',
        'dc' => 'boolean',
        'rc' => 'boolean',
        'is_deleted' => 'boolean',
        'is_validated' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Global scope to exclude deleted articles
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

    /**
     * Get the administrative situation for this article.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }

    /**
     * Get the forest for this article.
     */
    public function foret(): BelongsTo
    {
        return $this->belongsTo(Foret::class, 'foret_id');
    }

    /**
     * Get the tree species for this article.
     */
    public function essence(): BelongsTo
    {
        return $this->belongsTo(Essence::class, 'essence_id');
    }

    /**
     * Get the cutting nature for this article.
     */
    public function natureDeCoupe(): BelongsTo
    {
        return $this->belongsTo(NatureDeCoupe::class, 'nature_de_coupe_id');
    }

    /**
     * Get the location for this article.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'localisation_id');
    }

    /**
     * Get the operator for this article.
     */
    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'exploitant_id');
    }

    /**
     * Get the total volume of the article.
     */
    public function getTotalVolumeAttribute(): float
    {
        return ($this->bo_m3 ?? 0) + ($this->bi_m3 ?? 0);
    }

    /**
     * Get the formatted total volume.
     */
    public function getFormattedTotalVolumeAttribute(): string
    {
        return number_format($this->total_volume, 2) . ' m³';
    }

    /**
     * Get the status badge for the article.
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_validated) {
            return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Validé</span>';
        }
        return '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>En attente</span>';
    }

    /**
     * Get the type badge for the article.
     */
    public function getTypeBadgeAttribute(): string
    {
        $type = $this->type ?? '';
        if ($type === 'appel_doffre') {
            return '<span class="badge bg-info">Appel d\'Offre</span>';
        }
        return '<span class="badge bg-primary">Adjudication</span>';
    }

    /**
     * Scope for validated articles.
     */
    public function scopeValidated(Builder $query): Builder
    {
        return $query->where('is_validated', true);
    }

    /**
     * Scope for pending articles.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_validated', false);
    }

    /**
     * Scope for sold articles.
     */
    public function scopeSold(Builder $query): Builder
    {
        return $query->where('invendu', false);
    }

    /**
     * Scope for unsold articles.
     */
    public function scopeUnsold(Builder $query): Builder
    {
        return $query->where('invendu', true);
    }

    /**
     * Scope for articles by year.
     */
    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('annee', $year);
    }

    /**
     * Scope for articles by forest.
     */
    public function scopeByForest(Builder $query, int $forestId): Builder
    {
        return $query->where('foret_id', $forestId);
    }

    /**
     * Scope for articles by essence.
     */
    public function scopeByEssence(Builder $query, int $essenceId): Builder
    {
        return $query->where('essence_id', $essenceId);
    }

    /**
     * Scope for articles by type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for articles with price range.
     */
    public function scopePriceRange(Builder $query, float $minPrice = null, float $maxPrice = null): Builder
    {
        if ($minPrice !== null) {
            $query->where('prix_vente', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('prix_vente', '<=', $maxPrice);
        }
        return $query;
    }

    /**
     * Scope for articles by date range.
     */
    public function scopeDateRange(Builder $query, string $startDate = null, string $endDate = null): Builder
    {
        if ($startDate) {
            $query->where('date_adjudication', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date_adjudication', '<=', $endDate);
        }
        return $query;
    }

    /**
     * Scope for recent articles.
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for articles with high value.
     */
    public function scopeHighValue(Builder $query, float $threshold = 10000): Builder
    {
        return $query->where('prix_vente', '>=', $threshold);
    }
}
