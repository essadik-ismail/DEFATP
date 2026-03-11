<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carnet extends Model
{
    use SoftDeletes;

    protected $table = 'carnets';

    public const STATUS_DISPONIBLE = 'disponible';
    public const STATUS_EPUISE = 'epuise';
    public const STATUS_PERDU = 'perdu';
    public const STATUS_UTILISE = 'utilise';

    protected $fillable = [
        'serie',
        'num',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_DISPONIBLE,
    ];

    /**
     * Scope: only carnets with status "disponible" (for dropdown in permis de colportage).
     */
    public function scopeDisponible($query)
    {
        return $query->where('status', self::STATUS_DISPONIBLE);
    }

    /**
     * Scope: not deleted (soft deletes).
     */
    public function scopeListable($query)
    {
        return $query->orderBy('serie')->orderBy('num');
    }

    /**
     * Get the permis de colportage records for this carnet.
     */
    public function colportages(): HasMany
    {
        return $this->hasMany(ColportageEnlever::class, 'carnet_id');
    }

    public function canBeMarkedPerdu(): bool
    {
        return !in_array($this->status, [self::STATUS_EPUISE, self::STATUS_PERDU], true);
    }
}
