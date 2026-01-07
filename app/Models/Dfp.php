<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dfp extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'dfp',
        'zdtf_code',
        'dpanef_code',
    ];

    /**
     * Get the zdtf for this dfp (by code).
     */
    public function zdtf(): BelongsTo
    {
        return $this->belongsTo(Zdtf::class, 'zdtf_code', 'code');
    }

    /**
     * Get the dpanef for this dfp (by code).
     */
    public function dpanef(): BelongsTo
    {
        return $this->belongsTo(Dpanef::class, 'dpanef_code', 'code');
    }
}
