<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ContractVente;

class Permis extends Model
{
    use SoftDeletes;

    protected $table = 'permis';

    protected $fillable = [
        'contract_vente_id',
        'payment_id',
        'num_permis',
        'date',
        'nom_chauffeur',
        'cin',
        'marque_vehicule',
        'matricule_vehicule',
        'start_date',
        'expire_date',
        'date_edition',
        'num_assurance',
        'percepteur',
        'cloture',
        'num_quittance',
    ];

    protected $casts = [
        'date' => 'date',
        'start_date' => 'date',
        'expire_date' => 'date',
        'date_edition' => 'date',
        'cloture' => 'boolean',
    ];

    /**
     * Get the contract vente for this permis.
     */
    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }

    /**
     * Get the payment for this permis.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Get the permi enlevers for this permis.
     */
    public function permiEnlevers(): HasMany
    {
        return $this->hasMany(PermiEnlever::class, 'permis_id');
    }
}
