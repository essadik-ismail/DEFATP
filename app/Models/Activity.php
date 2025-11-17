<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'objet',
        'description',
        'participants',
        'lieu',
        'date',
        'fichier_joint',
        'odf_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the ODF that owns this activity.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }
}
