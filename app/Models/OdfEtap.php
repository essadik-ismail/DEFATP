<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdfEtap extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'lieu',
        'participant',
        'description',
        'resultat',
        'fichierjoin',
        'odf_id',
    ];

    /**
     * Get the ODF for this etap.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }
}
