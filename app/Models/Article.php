<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'annee',
        'lot',
        'parcelle',
        'superficie',
        'fourniture_mise_charge',
        'taxe_refection_chemins',
        'service_rendu_anef',
        'bois_chauffage_volume',
        'bois_chauffage_destination',
        'date_payement_service_anef',
        'date_livaison_mise_en_charge_bf',
        'invandu',
        'dranef_code',
        'dpanef_code',
        'zdtf_code',
        'dfp_code',
    ];

    protected $casts = [
        'annee' => 'integer',
        'superficie' => 'decimal:2',
        'fourniture_mise_charge' => 'decimal:2',
        'taxe_refection_chemins' => 'decimal:2',
        'service_rendu_anef' => 'decimal:2',
        'bois_chauffage_volume' => 'decimal:2',
        'date_payement_service_anef' => 'date',
        'date_livaison_mise_en_charge_bf' => 'date',
        'invandu' => 'boolean',
    ];

    /**
     * Get the contract ventes for this article.
     */
    public function contractVentes(): HasMany
    {
        return $this->hasMany(ContractVente::class, 'article_id');
    }

    /**
     * Get the locations for this article.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'article_id');
    }

    /**
     * Many-to-many: mode exploitations.
     */
    public function modeExploitations(): BelongsToMany
    {
        return $this->belongsToMany(ModeExploitation::class, 'article_mode_exploitation', 'article_id', 'mode_exploitation_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: nature de coupes.
     */
    public function natureDeCoupes(): BelongsToMany
    {
        return $this->belongsToMany(NatureDeCoupe::class, 'article_nature_de_coupe', 'article_id', 'nature_de_coupe_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: essences and products.
     */
    public function essences(): BelongsToMany
    {
        return $this->belongsToMany(Essence::class, 'article_essence', 'article_id', 'essence_id')
            ->withPivot('product_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * Many-to-many: products through essences.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'article_essence', 'article_id', 'product_id')
            ->withPivot('essence_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * Many-to-many: provinces.
     */
    public function provinces(): BelongsToMany
    {
        return $this->belongsToMany(Province::class, 'article_province', 'article_id', 'province_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: forets.
     */
    public function forets(): BelongsToMany
    {
        return $this->belongsToMany(Foret::class, 'article_foret', 'article_id', 'foret_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: parcelles.
     */
    public function parcelles(): BelongsToMany
    {
        return $this->belongsToMany(Parcelle::class, 'article_parcelle', 'idarticle', 'idparcelle')
            ->withTimestamps();
    }

    /**
     * Many-to-many: depots.
     */
    public function depots(): BelongsToMany
    {
        return $this->belongsToMany(Depot::class, 'depot_article', 'id_article', 'id_depot')
            ->withTimestamps();
    }

    /**
     * Get the dranef for this article (by code).
     */
    public function dranef(): BelongsTo
    {
        return $this->belongsTo(Dranef::class, 'dranef_code', 'code');
    }

    /**
     * Get the dpanef for this article (by code).
     */
    public function dpanef(): BelongsTo
    {
        return $this->belongsTo(Dpanef::class, 'dpanef_code', 'code');
    }

    /**
     * Get the zdtf for this article (by code).
     */
    public function zdtf(): BelongsTo
    {
        return $this->belongsTo(Zdtf::class, 'zdtf_code', 'code');
    }

    /**
     * Get the dfp for this article (by code).
     */
    public function dfp(): BelongsTo
    {
        return $this->belongsTo(Dfp::class, 'dfp_code', 'code');
    }
}

