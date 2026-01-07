<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zdtf extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'zdtf',
        'sdtf', // Keep for backward compatibility
        'dpanef_id',
        'dpanef_code',
    ];

    /**
     * Get the dpanef for this zdtf (by ID).
     */
    public function dpanef(): BelongsTo
    {
        return $this->belongsTo(Dpanef::class, 'dpanef_id');
    }

    /**
     * Get the dpanef for this zdtf (by code).
     */
    public function dpanefByCode(): BelongsTo
    {
        return $this->belongsTo(Dpanef::class, 'dpanef_code', 'code');
    }

    /**
     * Get the articles for this zdtf.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'wdtf_id');
    }

    /**
     * Get the dfps for this zdtf.
     */
    public function dfps(): HasMany
    {
        return $this->hasMany(Dfp::class, 'zdtf_code', 'code');
    }
}
