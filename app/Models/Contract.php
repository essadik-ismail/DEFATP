<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Contract extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'annee',
        'contarct',
        'date',
        'localisation_id',
        'situation_administrative_id',
        'coperative_id',
        'superficie',
        'gardiennage_nbjour',
        'gardiennage_superficie',
        'gardiennage_parcelle',
        'prevention_incendies_nbjour',
        'prevention_incendies_superficie',
        'prevention_incendies_parcelle',
        'valeurs_des_produits',
        'valeur_des_prestations',
        'redevances',
        'taxes',
        'total_avenant',
        'resiliation',
        'date_resiliation',
    ];

    protected $casts = [
        'date' => 'date',
        'date_resiliation' => 'date',
    ];


    /**
     * Get the situation administrative for this contract.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }

    /**
     * Get the essences for this contract (many-to-many relationship).
     */
    public function essences(): BelongsToMany
    {
        return $this->belongsToMany(Essence::class, 'contact_essence', 'contact_id', 'essence_id')
            ->withTimestamps();
    }

    /**
     * Get the forets for this contract (many-to-many relationship).
     */
    public function forets(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Foret::class, 'contact_foret', 'contact_id', 'foret_id')
            ->withTimestamps();
    }


    /**
     * Get the coperative for this contract.
     */
    public function coperative(): BelongsTo
    {
        return $this->belongsTo(Coperative::class, 'coperative_id');
    }

    /**
     * Get avenants related to this contract.
     */
    public function avenants(): HasMany
    {
        return $this->hasMany(Avenant::class, 'contact_id');
    }

    /**
     * Get the products for this contract (many-to-many relationship).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'contract_product', 'contract_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get the prestations for this contract (many-to-many relationship).
     */
    public function prestations(): BelongsToMany
    {
        return $this->belongsToMany(Prestation::class, 'contract_prestation', 'contract_id', 'prestation_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
