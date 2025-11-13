<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vocation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the coperatives for this vocation.
     */
    public function coperatives(): HasMany
    {
        return $this->hasMany(Coperative::class, 'vocation_id');
    }
}
