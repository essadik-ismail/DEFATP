<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Espece extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the contracts for this espece (many-to-many relationship).
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contact_espece', 'espece_id', 'contact_id')
            ->withTimestamps();
    }
}
