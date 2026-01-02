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
        'sdtf',
        'dpanef_id',
    ];

    /**
     * Get the dpanef for this zdtf.
     */
    public function dpanef(): BelongsTo
    {
        return $this->belongsTo(Dpanef::class, 'dpanef_id');
    }

    /**
     * Get the articles for this zdtf.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'wdtf_id');
    }
}
