<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commune extends Model
{
    protected $fillable = [
        'nom',
    ];

    /**
     * Get provinces for this commune.
     */
    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }
}
