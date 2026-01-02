<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dranef extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dranef',
        'adresse',
        'tel',
        'fax',
    ];

    /**
     * Get the dpanefs for this dranef.
     */
    public function dpanefs(): HasMany
    {
        return $this->hasMany(Dpanef::class, 'dranef_id');
    }
}
