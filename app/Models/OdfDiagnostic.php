<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdfDiagnostic extends Model
{
    use SoftDeletes;

    protected $table = 'odf_diagnostic';

    protected $fillable = [
        'type',
        'nom',
        'activité',
        'présidente',
        'nombre_de_membres',
        'odf_id',
    ];

    /**
     * Get the ODF for this diagnostic.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }
}

