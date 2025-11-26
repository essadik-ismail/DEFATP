<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'odf_id',
        'odf_diagnostic_id',
    ];

    /**
     * Get the ODF that owns this member.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }

    /**
     * Get the ODF diagnostic associated with this member.
     */
    public function odfDiagnostic(): BelongsTo
    {
        return $this->belongsTo(OdfDiagnostic::class, 'odf_diagnostic_id');
    }
}
