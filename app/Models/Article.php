<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'annee',
        'numero',
        'date_adjudication',
        'numero_adjudication',
        'lot',
        'type',
        'exploitant_id',
        'parcelle',
        'lat',
        'log',
        'superficie',
        'ps_t',
        'nommer_a_la_vente',
        'fourniture_mise_charge',
        'taxe_refection_chemins',
        'service_rendu_anef',
        'bois_chauffage_volume',
        'bois_chauffage_destination',
        'date_payement_service_anef',
        'date_livaison_mise_en_charge_bf',
        'zdtf_id',
    ];

    protected $casts = [
        'date_adjudication' => 'date',
        'date_payement_service_anef' => 'date',
        'date_livaison_mise_en_charge_bf' => 'date',
        'ps_t' => 'decimal:2',
        'fourniture_mise_charge' => 'decimal:2',
        'taxe_refection_chemins' => 'decimal:2',
        'service_rendu_anef' => 'decimal:2',
        'bois_chauffage_volume' => 'decimal:2',
        'superficie' => 'decimal:2',
        'nommer_a_la_vente' => 'boolean',
    ];


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
     * The pivot table can also include product_id and quantity.
     */
    public function essences(): BelongsToMany
    {
        return $this->belongsToMany(Essence::class, 'article_essence', 'article_id', 'essence_id')
            ->withPivot('product_id', 'quantity')
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
     * Many-to-many: this article may be linked to multiple mode exploitations.
     */
    public function modeExploitations(): BelongsToMany
    {
        return $this->belongsToMany(ModeExploitation::class, 'article_mode_exploitation', 'article_id', 'mode_exploitation_id')
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
     * Get the products for this article (many-to-many relationship).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'article_product', 'article_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get the locations for this article.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Get the zdtf for this article.
     */
    public function zdtf(): BelongsTo
    {
        return $this->belongsTo(Zdtf::class, 'zdtf_id');
    }

    /**
     * Get the total volume of the article.
     */
    public function getTotalVolumeAttribute(): float
    {
        // Calculate total volume from products
        $boProduct = $this->products()->where('name', 'BO (m³)')->first();
        $biProduct = $this->products()->where('name', 'BI (m³)')->first();
        
        $boQuantity = $boProduct ? $boProduct->pivot->quantity : 0;
        $biQuantity = $biProduct ? $biProduct->pivot->quantity : 0;
        
        return $boQuantity + $biQuantity;
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


    // Removed scopeSold and scopeUnsold - invendu column was removed

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

    // Removed scopePriceRange - prix_vente column was removed

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

    // Removed scopeHighValue - prix_vente column was removed
}
