<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdfModification extends Model
{
    use SoftDeletes;

    protected $table = 'odf_modification';

    protected $fillable = [
        'date',
        'modification',
        'actions',
        'commentaire',
        'odf_id',
    ];

    /**
     * Get the ODF for this modification.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }
}
