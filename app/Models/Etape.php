<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Etape extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'num',
        'objet',
        'content',
        'fichier_joint',
        'etat',
        'validated_by',
        'validated_at',
        'commentaire_validation',
        'phase_id',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    /**
     * Get the phase that owns this etape.
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class, 'phase_id');
    }

    /**
     * Get the user who validated this etape.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
