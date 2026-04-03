<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carnet extends Model
{
    use SoftDeletes;

    protected $table = 'carnets';

    public const TYPE_FORET_PRIVEE = "Bois de for\u{00EA}t priv\u{00E9}e";
    public const TYPE_FORET_DOMANIALE = "Bois de for\u{00EA}t domaniale";

    public const STATUS_DISPONIBLE = 'disponible';
    public const STATUS_EPUISE = 'epuise';
    public const STATUS_PERDU = 'perdu';
    public const STATUS_UTILISE = 'utilise';

    protected $fillable = [
        'serie',
        'type',
        'num',
        'status',
    ];

    protected $attributes = [
        'type' => self::TYPE_FORET_DOMANIALE,
        'status' => self::STATUS_DISPONIBLE,
    ];

    public static function types(): array
    {
        return [
            self::TYPE_FORET_PRIVEE,
            self::TYPE_FORET_DOMANIALE,
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_DISPONIBLE,
            self::STATUS_EPUISE,
            self::STATUS_PERDU,
            self::STATUS_UTILISE,
        ];
    }

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
        return $query->orderBy('type')->orderBy('serie')->orderBy('num');
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
