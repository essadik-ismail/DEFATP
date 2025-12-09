<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuiviContractProgramme extends Model
{
    protected $fillable = [
        'localisation_id',
        'foret_id',
        'partenariat_id',
        'CT',
        'DPF',
        'Parcelle',
        'Projet_CP',
        'Année',
        'Superficie_prévue_CP_ha',
        'Montant_prévu_CP_dh',
        'Superficie_engagée_ha',
        'Montant_engagé_dh',
        'Superficie_payée_ha',
        'Montant_payé_dh',
        'Superficie_non_payée',
        'Motif_du_Non_paiement',
    ];

    protected $casts = [
        'Année' => 'integer',
        'Superficie_prévue_CP_ha' => 'decimal:2',
        'Montant_prévu_CP_dh' => 'decimal:2',
        'Superficie_engagée_ha' => 'decimal:2',
        'Montant_engagé_dh' => 'decimal:2',
        'Superficie_payée_ha' => 'decimal:2',
        'Montant_payé_dh' => 'decimal:2',
        'Superficie_non_payée' => 'decimal:2',
    ];

    /**
     * Get the localisation for this suivi contract programme.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class);
    }

    /**
     * Get the foret for this suivi contract programme.
     */
    public function foret(): BelongsTo
    {
        return $this->belongsTo(Foret::class);
    }

    /**
     * Get the partenariat for this suivi contract programme.
     */
    public function partenariat(): BelongsTo
    {
        return $this->belongsTo(Partenariat::class);
    }
}
