<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dranef extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'dranef',
        'Abréviation',
        'adresse',
        'tel',
        'fax',
    ];

    public function getCODEAttribute(): ?string
    {
        return $this->code;
    }

    public function getDRANEFAttribute(): ?string
    {
        return $this->dranef;
    }

    public function getDPANEFAttribute(): ?string
    {
        return $this->dpanefsByCode()->orderBy('dpanef')->value('dpanef')
            ?? $this->dpanefs()->orderBy('dpanef')->value('dpanef');
    }

    public function getENTITEAttribute(): ?string
    {
        return $this->getAttribute('Abréviation');
    }

    /**
     * Get the dpanefs for this dranef (by ID).
     */
    public function dpanefs(): HasMany
    {
        return $this->hasMany(Dpanef::class, 'dranef_id');
    }

    /**
     * Get the dpanefs for this dranef (by code).
     */
    public function dpanefsByCode(): HasMany
    {
        return $this->hasMany(Dpanef::class, 'dranef_code', 'code');
    }
}
