<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'num',
        'nom',
        'date',
        'date_de_début',
        'date_de_fin',
        'échéance',
        'etat',
        'pdfc_id',
    ];

    protected $casts = [
        'date' => 'date',
        'date_de_début' => 'date',
        'date_de_fin' => 'date',
        'échéance' => 'date',
    ];

    /**
     * Check if phase can be validated
     */
    public function canBeValidated(): bool
    {
        // All etapes must be validated
        $etapes = $this->etapes;
        if ($etapes->count() === 0) return false;
        
        foreach ($etapes as $etape) {
            if ($etape->etat !== 'validée') {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get the PDFC that owns this phase.
     */
    public function pdfc(): BelongsTo
    {
        return $this->belongsTo(Pdfc::class, 'pdfc_id');
    }

    /**
     * Get the etapes for this phase.
     */
    public function etapes(): HasMany
    {
        return $this->hasMany(Etape::class, 'phase_id');
    }
}
