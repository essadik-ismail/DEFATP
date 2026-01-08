<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commune extends Model
{
    protected $fillable = [
        'nom',
        'province_id',
    ];

    /**
     * Get the province that owns this commune.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
