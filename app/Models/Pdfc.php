<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pdfc extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date_de_début',
        'date_de_fin',
        'etat',
        'user_id',
    ];

    protected $casts = [
        'date_de_début' => 'date',
        'date_de_fin' => 'date',
    ];

    /**
     * Get the next state in the cycle de vie
     */
    public function getNextState(): ?string
    {
        $states = ['Non élaboré', 'élaboré', 'validé', 'validé C.C'];
        $currentIndex = array_search($this->etat, $states);
        
        if ($currentIndex !== false && $currentIndex < count($states) - 1) {
            return $states[$currentIndex + 1];
        }
        
        return null;
    }

    /**
     * Check if PDFC can transition to next state
     */
    public function canTransitionToNextState(): bool
    {
        switch ($this->etat) {
            case 'Non élaboré':
                // Can transition to "élaboré" if dates are set
                return $this->date_de_début && $this->date_de_fin;
            case 'élaboré':
                // Can transition to "validé" if at least one phase exists
                return $this->phases()->count() > 0;
            case 'validé':
                // Can transition to "validé C.C" if all phases are validated
                $phases = $this->phases;
                if ($phases->count() === 0) return false;
                foreach ($phases as $phase) {
                    if ($phase->etat !== 'validée') return false;
                }
                return true;
            case 'validé C.C':
                // Final state, cannot transition further
                return false;
            default:
                return false;
        }
    }

    /**
     * Get the user that owns this PDFC.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the phases for this PDFC.
     */
    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class, 'pdfc_id');
    }
}
