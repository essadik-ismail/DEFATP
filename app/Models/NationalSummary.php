<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NationalSummary extends Model
{
    protected $fillable = [
        'year',
        'month',
        'budget_general_frais_adjudication',
        'budget_general_taj',
        'budget_general_taxe_reconnaissance',
        'budget_general_total',
        'part_etat',
        'cas_fnf_total',
        'cas_chasse_peche_total',
        'communes_bois_tanin',
        'communes_liege',
        'communes_pam_produits_divers',
        'communes_redevances_parcours',
        'communes_occupations_temporaires',
        'communes_autres_taxes',
        'communes_total',
        'provinces_liege',
        'provinces_bois_tanin',
        'provinces_Alfa',
        'provinces_pam_produits_divers',
        'provinces_interets_retard',
        'provinces_total',
        'total_general',
        'situation_administrative_id',
        'localisation_id',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'budget_general_frais_adjudication' => 'decimal:2',
        'budget_general_taj' => 'decimal:2',
        'budget_general_taxe_reconnaissance' => 'decimal:2',
        'budget_general_total' => 'decimal:2',
        'part_etat' => 'decimal:2',
        'cas_fnf_total' => 'decimal:2',
        'cas_chasse_peche_total' => 'decimal:2',
        'communes_bois_tanin' => 'decimal:2',
        'communes_liege' => 'decimal:2',
        'communes_pam_produits_divers' => 'decimal:2',
        'communes_redevances_parcours' => 'decimal:2',
        'communes_occupations_temporaires' => 'decimal:2',
        'communes_autres_taxes' => 'decimal:2',
        'communes_total' => 'decimal:2',
        'provinces_liege' => 'decimal:2',
        'provinces_bois_tanin' => 'decimal:2',
        'provinces_Alfa' => 'decimal:2',
        'provinces_pam_produits_divers' => 'decimal:2',
        'provinces_interets_retard' => 'decimal:2',
        'provinces_total' => 'decimal:2',
        'total_general' => 'decimal:2',
    ];

    /**
     * Get the situation administrative for this national summary.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }

    /**
     * Get the localisation for this national summary.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'localisation_id');
    }
}
