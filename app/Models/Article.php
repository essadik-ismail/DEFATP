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
        'groupe_cession_id',
        'lot',
        'parcelle',
        'superficie',
        'fourniture_mise_charge',
        'taxe_refection_chemins',
        'date_echeance_taxe_refection_chemins',
        'service_rendu_anef',
        'date_echeance_service_rendu_anef',
        'bois_chauffage_volume',
        'bois_chauffage_destination',
        'date_livraison_bois_chauffage',
        'mise_en_charge_destination',
        'mise_en_charge_volume',
        'date_echeance_mise_en_charge',
        'date_payement_service_anef',
        'date_livaison_mise_en_charge_bf',
        'invendu',
        'dranef_code',
        'dpanef_code',
        'zdtf_code',
        'dfp_code',
        'nature_juridique',
        'canton',
        'particuliere',
        'limite_nord',
        'limite_sud',
        'limite_est',
        'limite_ouest',
        'limite_se',
        'limite_so',
        'limite_ne',
        'limite_no',
        'coordonnee_x',
        'coordonnee_y',
        'current_step',
        'workflow_state',
        'workflow_state_updated_at',
        'workflow_state_updated_by',
        'statut_special',
        'date_statut_special',
        'motif_statut_special',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'fourniture_mise_charge' => 'decimal:2',
        'taxe_refection_chemins' => 'decimal:2',
        'date_echeance_taxe_refection_chemins' => 'date',
        'service_rendu_anef' => 'decimal:2',
        'date_echeance_service_rendu_anef' => 'date',
        'bois_chauffage_volume' => 'decimal:2',
        'mise_en_charge_volume' => 'decimal:2',
        'date_echeance_mise_en_charge' => 'date',
        'date_payement_service_anef' => 'date',
        'date_livaison_mise_en_charge_bf' => 'date',
        'date_livraison_bois_chauffage' => 'date',
        'invendu'                    => 'boolean',
        'coordonnee_x'               => 'decimal:6',
        'coordonnee_y'               => 'decimal:6',
        'workflow_state_updated_at'  => 'datetime',
    ];

    /**
     * Get the cession (groupe_cession) this article belongs to.
     */
    public function cession(): BelongsTo
    {
        return $this->belongsTo(Cession::class, 'groupe_cession_id');
    }

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
     * Many-to-many: communes.
     */
    public function communes(): BelongsToMany
    {
        return $this->belongsToMany(Commune::class, 'article_commune', 'article_id', 'commune_id')
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

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(VehicleDeclaration::class, 'article_vehicle_declaration')
            ->withTimestamps();
    }

}

