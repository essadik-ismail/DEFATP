<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcelle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'foret_id',
        'canton_id',
        'parcelle',
    ];

    /**
     * Get the foret for this parcelle.
     */
    public function foret(): BelongsTo
    {
        return $this->belongsTo(Foret::class, 'foret_id');
    }

    /**
     * Get the canton for this parcelle.
     */
    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class, 'canton_id');
    }
}
