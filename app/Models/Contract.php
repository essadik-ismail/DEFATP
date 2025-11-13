<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Contract extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'annee',
        'contarct',
        'localisation_id',
        'situation_administrative_id',
        'foret_id',
        'coperative_id',
        'superficie',
        'gardiennage',
        'elagage',
        'eclaircie',
        'rajeunissement_romarin',
        'valeurs_des_produits',
        'valeur_des_prestations',
        'redevances',
        'taxes',
        'total_avenant',
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
        'attribute13',
        'attribute14',
        'attribute15',
        'attribute16',
        'attribute17',
    ];

    /**
     * Get the localisation for this contract.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'localisation_id');
    }

    /**
     * Get the situation administrative for this contract.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }

    /**
     * Get the especes for this contract (many-to-many relationship).
     */
    public function especes(): BelongsToMany
    {
        return $this->belongsToMany(Espece::class, 'contact_espece', 'contact_id', 'espece_id')
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
     * Get avenants related to this contract (by year).
     */
    public function avenants(): HasMany
    {
        return $this->hasMany(Avenant::class, 'annee', 'annee');
    }
}
