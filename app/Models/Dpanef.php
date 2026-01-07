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
        'code',
        'dranef_id',
        'dranef_code',
        'dpanef',
    ];

    /**
     * Get the dranef for this dpanef (by ID).
     */
    public function dranef(): BelongsTo
    {
        return $this->belongsTo(Dranef::class, 'dranef_id');
    }

    /**
     * Get the dranef for this dpanef (by code).
     */
    public function dranefByCode(): BelongsTo
    {
        return $this->belongsTo(Dranef::class, 'dranef_code', 'code');
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

    /**
     * Get the depots for this dpanef.
     */
    public function depots(): HasMany
    {
        return $this->hasMany(Depot::class, 'id_dpanef');
    }

    /**
     * Get the dfps for this dpanef.
     */
    public function dfps(): HasMany
    {
        return $this->hasMany(Dfp::class, 'dpanef_code', 'code');
    }
}
