<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Canton extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'foret_id',
        'canton',
    ];

    /**
     * Get the foret for this canton.
     */
    public function foret(): BelongsTo
    {
        return $this->belongsTo(Foret::class, 'foret_id');
    }

    /**
     * Get the parcelles for this canton.
     */
    public function parcelles(): HasMany
    {
        return $this->hasMany(Parcelle::class, 'canton_id');
    }
}
