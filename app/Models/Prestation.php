<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestation extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'avenant_id',
        'contract_id',
    ];

    /**
     * Get the contract for this prestation.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    /**
     * Get the avenant for this prestation.
     */
    public function avenant(): BelongsTo
    {
        return $this->belongsTo(Avenant::class, 'avenant_id');
    }
}

