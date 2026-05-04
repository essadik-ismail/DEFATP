<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargeApayer extends Model
{
    use SoftDeletes;

    protected $table = 'charge_apayer';

    protected $fillable = [
        'nom',
        'montant',
        'date_echeance',
        'contrat_vente_id',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_echeance' => 'date',
    ];

    /**
     * Get the contract vente for this charge.
     */
    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contrat_vente_id');
    }

    /**
     * Get the payments for this charge.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'chargeapayer_id');
    }
}

