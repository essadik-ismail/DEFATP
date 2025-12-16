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
        'localisation_id',
        'situation_administrative_id',
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

    /**
     * Get the localisation for this PDFC.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'localisation_id');
    }

    /**
     * Get the situation administrative for this PDFC.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }

    /**
     * PDFC specific, named steps (Étapes 1 à 13)
     */
    public function etape1DiagnosticCommune(): BelongsTo
    {
        return $this->hasOne(Etape1DiagnosticCommune::class);
    }

    public function etape2DiagnosticSituationForestiere(): BelongsTo
    {
        return $this->hasOne(Etape2DiagnosticSituationForestiere::class);
    }

    public function etape3AnalyseUsagersForet(): BelongsTo
    {
        return $this->hasOne(Etape3AnalyseUsagersForet::class);
    }

    public function etape4AnalyseDegreAcceptation(): BelongsTo
    {
        return $this->hasOne(Etape4AnalyseDegreAcceptation::class);
    }

    public function etape5AnalyseProgrammesAnterieur(): BelongsTo
    {
        return $this->hasOne(Etape5AnalyseProgrammesAnterieur::class);
    }

    public function etape6ElaborationProjetProgramme(): BelongsTo
    {
        return $this->hasOne(Etape6ElaborationProjetProgramme::class);
    }

    public function etape7ConcertationPopulation(): BelongsTo
    {
        return $this->hasOne(Etape7ConcertationPopulation::class);
    }

    public function etape8ValidationDPANEF(): BelongsTo
    {
        return $this->hasOne(Etape8ValidationDPANEF::class);
    }

    public function etape9ValidationFinalePopulation(): BelongsTo
    {
        return $this->hasOne(Etape9ValidationFinalePopulation::class);
    }

    public function etape10FinalisationPCFC(): BelongsTo
    {
        return $this->hasOne(Etape10FinalisationPCFC::class);
    }

    public function etape11ValidationConseilCommunal(): BelongsTo
    {
        return $this->hasOne(Etape11ValidationConseilCommunal::class);
    }

    public function etape12MiseEnOeuvrePCFC(): BelongsTo
    {
        return $this->hasOne(Etape12MiseEnOeuvrePCFC::class);
    }

    public function etape13SuiviMiseEnOeuvre(): BelongsTo
    {
        return $this->hasOne(Etape13SuiviMiseEnOeuvre::class);
    }
}
