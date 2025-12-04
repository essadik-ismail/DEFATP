<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prestation extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the contracts for this prestation (many-to-many).
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contract_prestation', 'prestation_id', 'contract_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get the avenants for this prestation (many-to-many).
     */
    public function avenants(): BelongsToMany
    {
        return $this->belongsToMany(Avenant::class, 'avenant_prestation', 'prestation_id', 'avenant_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}

