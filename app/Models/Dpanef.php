<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dpanef extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dranef_id',
        'dpanef',
    ];

    /**
     * Get the dranef for this dpanef.
     */
    public function dranef(): BelongsTo
    {
        return $this->belongsTo(Dranef::class, 'dranef_id');
    }

    /**
     * Get the forets for this dpanef.
     */
    public function forets(): HasMany
    {
        return $this->hasMany(Foret::class, 'dpanef_id');
    }

    /**
     * Get the zdtfs for this dpanef.
     */
    public function zdtfs(): HasMany
    {
        return $this->hasMany(Zdtf::class, 'dpanef_id');
    }
}
