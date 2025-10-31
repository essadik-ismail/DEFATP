<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    protected $fillable = [
        'annee',
        'numero',
        'date_adjudication',
        'numero_adjudication',
        'lot',
        'type',
        'exploitant_id',
        'nature_juridique',
        'parcelle',
        'lat',
        'log',
        'superficie',
        'bo_m3',
        'bi_m3',
        'bf_st',
        'tanin_t',
        'fleur_acacia_t',
        'caroube_t',
        'romarin_t',
        'ps_t',
        'liége_st',
        'charbon_bois_ox',
        'prix_de_retrait',
        'prix_vente',
        'invendu',
        'dc',
        'rc',
        'fourniture_mise_charge',
        'date_de_resiliation',
        'date_de_decheance',
        'is_deleted',
    ];

    protected $casts = [
        'date_adjudication' => 'date',
        'date_de_resiliation' => 'date',
        'date_de_decheance' => 'date',
        'bo_m3' => 'decimal:2',
        'bi_m3' => 'decimal:2',
        'bf_st' => 'decimal:2',
        'tanin_t' => 'decimal:2',
        'fleur_acacia_t' => 'decimal:2',
        'caroube_t' => 'decimal:2',
        'romarin_t' => 'decimal:2',
        'ps_t' => 'decimal:2',
        'liége_st' => 'decimal:2',
        'charbon_bois_ox' => 'decimal:2',
        'prix_de_retrait' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'fourniture_mise_charge' => 'decimal:2',
        'superficie' => 'decimal:2',
        'invendu' => 'boolean',
        'dc' => 'boolean',
        'rc' => 'boolean',
        'is_deleted' => 'boolean',
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
     * Many-to-many: this article may be linked to multiple forests.
     */
    public function forets(): BelongsToMany
    {
        return $this->belongsToMany(Foret::class, 'article_foret', 'article_id', 'foret_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: this article may be linked to multiple essences.
     */
    public function essences(): BelongsToMany
    {
        return $this->belongsToMany(Essence::class, 'article_essence', 'article_id', 'essence_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: this article may be linked to multiple administrative situations.
     */
    public function situationsAdministratives(): BelongsToMany
    {
        return $this->belongsToMany(SituationAdministrative::class, 'article_situation_administrative', 'article_id', 'situation_administrative_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: this article may be linked to multiple natures de coupe.
     */
    public function naturesDeCoupe(): BelongsToMany
    {
        return $this->belongsToMany(NatureDeCoupe::class, 'article_nature_de_coupe', 'article_id', 'nature_de_coupe_id')
            ->withTimestamps();
    }


    /**
     * Many-to-many: this article may be linked to multiple localisations.
     */
    public function localisations(): BelongsToMany
    {
        return $this->belongsToMany(Localisation::class, 'article_localisation', 'article_id', 'localisation_id')
            ->withTimestamps();
    }

    /**
     * Get the operator for this article.
     */
    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'exploitant_id');
    }

    /**
     * Get the products for this article.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the locations for this article.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
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
        return $query->whereHas('forets', function ($q) use ($forestId) {
            $q->where('forets.id', $forestId);
        });
    }

    /**
     * Scope for articles by essence.
     */
    public function scopeByEssence(Builder $query, int $essenceId): Builder
    {
        return $query->whereHas('essences', function ($q) use ($essenceId) {
            $q->where('essences.id', $essenceId);
        });
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
