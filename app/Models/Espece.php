<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Espece extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the contracts for this espece.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'espece_id');
    }
}
