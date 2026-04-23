<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'nom',
        'type',
        'date_decheace',
        'date_payment',
        'is_paye',
        'fichier_join',
        'fichier_joint',
        'num_quittace',
        'percepteur',
        'order',
        'contract_vente_id',
        'chargeapayer_id',
    ];

    protected $casts = [
        'date_decheace' => 'date',
        'date_payment' => 'date',
        'is_paye' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the contract vente for this payment.
     */
    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }

    /**
     * Get the permis for this payment.
     */
    public function permis(): HasMany
    {
        return $this->hasMany(Permis::class, 'payment_id');
    }

    /**
     * Get the charge apayer for this payment.
     */
    public function chargeApayer(): BelongsTo
    {
        return $this->belongsTo(ChargeApayer::class, 'chargeapayer_id');
    }
}
