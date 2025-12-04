<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NationalSummary extends Model
{
    protected $fillable = [
        'year',
        'budget_general_frais_adjudication',
        'budget_general_ta',
        'budget_general_taxe_reconnaissance',
        'budget_general_total',
        'part_etat',
        'cas_fmf_total',
        'cas_chasse_peche_total',
        'communes_bois_tanin',
        'communes_liege',
        'communes_pam_produits_divers',
        'communes_redevances_parcours',
        'communes_occupations_temporaires',
        'communes_autres_taxes',
        'communes_total',
        'provinces_bois_tanin',
        'provinces_liege',
        'provinces_pam_produits_divers',
        'provinces_interets_retard',
        'provinces_total',
        'total_general',
    ];

    protected $casts = [
        'year' => 'integer',
        'budget_general_frais_adjudication' => 'decimal:2',
        'budget_general_ta' => 'decimal:2',
        'budget_general_taxe_reconnaissance' => 'decimal:2',
        'budget_general_total' => 'decimal:2',
        'part_etat' => 'decimal:2',
        'cas_fmf_total' => 'decimal:2',
        'cas_chasse_peche_total' => 'decimal:2',
        'communes_bois_tanin' => 'decimal:2',
        'communes_liege' => 'decimal:2',
        'communes_pam_produits_divers' => 'decimal:2',
        'communes_redevances_parcours' => 'decimal:2',
        'communes_occupations_temporaires' => 'decimal:2',
        'communes_autres_taxes' => 'decimal:2',
        'communes_total' => 'decimal:2',
        'provinces_bois_tanin' => 'decimal:2',
        'provinces_liege' => 'decimal:2',
        'provinces_pam_produits_divers' => 'decimal:2',
        'provinces_interets_retard' => 'decimal:2',
        'provinces_total' => 'decimal:2',
        'total_general' => 'decimal:2',
    ];
}
