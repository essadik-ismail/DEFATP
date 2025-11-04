<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'annee',
        'coperative_id',
        'date',
        'superficie',
        'gardiennage',
        'prevention_incendies',
        'elagage',
        'eclaircie',
        'rajeunissement_romarin',
        'valeurs_des_produits',
        'valeur_des_prestations',
        'redevances',
        'taxes',
        'total_avenant',
    ];

    protected $casts = [
        'date' => 'date',
        'superficie' => 'decimal:2',
        'gardiennage' => 'decimal:2',
        'prevention_incendies' => 'decimal:2',
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
     * Get the coperative (exploitant) for this avenant.
     */
    public function coperative(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'coperative_id');
    }

    /**
     * Get the products for this avenant.
     */
    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'avenant_produit', 'avenant_id', 'produit_id')
            ->withTimestamps();
    }
}
