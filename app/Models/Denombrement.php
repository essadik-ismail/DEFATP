<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denombrement extends Model
{
    protected $table = 'denombrements';

    protected $fillable = [
        'contract_vente_id',
        'date_denombrement',
        'agent_responsable',
        'volume_denombre',
        'observations',
        'fichier_pv',
    ];

    protected $casts = [
        'date_denombrement' => 'date',
        'volume_denombre' => 'decimal:3',
    ];

    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }
}
