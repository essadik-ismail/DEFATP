<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'annee',
        'avenant',
        'contact_id',
        'coperative_id',
        'date',
        'superficie',
        'gardiennage_nbjour',
        'gardiennage_superficie',
        'gardiennage_parcelle',
        'prevention_incendies_nbjour',
        'prevention_incendies_superficie',
        'prevention_incendies_parcelle',
        'elagage',
        'eclaircie',
        'rajeunissement_romarin',
        'bo_m3',
        'bi_m3',
        'bf_st',
        'tanin_t',
        'laurier_sauce',
        'myrte',
        'callune',
        'thym',
        'bruyetre',
        'lichen',
        'tanin',
        'romarin',
        'liege_male',
        'liege_de_reproduction',
        'sauge',
        'lavande',
        'armoise',
        'origan',
        'alfa',
        'lentisque',
        'ciste',
        'fleur_acacia_t',
        'valeurs_des_produits',
        'valeur_des_prestations',
        'redevances',
        'taxes',
        'total_avenant',
    ];

    protected $casts = [
        'date' => 'date',
        'superficie' => 'decimal:2',
        'elagage' => 'decimal:2',
        'eclaircie' => 'decimal:2',
        'rajeunissement_romarin' => 'decimal:2',
        'valeurs_des_produits' => 'decimal:2',
        'valeur_des_prestations' => 'decimal:2',
        'redevances' => 'decimal:2',
        'taxes' => 'decimal:2',
        'total_avenant' => 'decimal:2',
    ];

    /**
     * Get the contract for this avenant.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contact_id');
    }

    /**
     * Get the coperative for this avenant.
     */
    public function coperative(): BelongsTo
    {
        return $this->belongsTo(Coperative::class, 'coperative_id');
    }

    /**
     * Get the products for this avenant (hasMany relationship).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'avenant_id');
    }

    /**
     * Get the prestations for this avenant (hasMany relationship).
     */
    public function prestations(): HasMany
    {
        return $this->hasMany(Prestation::class, 'avenant_id');
    }

}
