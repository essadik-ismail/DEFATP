<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionalBudget extends Model
{
    protected $fillable = [
        'year',
        'situation_administrative_id',
        'taxe_adjudication_1_6',
        'taxe_reconnaissance_interets',
        'ta_saisie_caution',
        'budget_fmf',
        'remboursement_drs',
        'remboursement_fmf_autres',
        'taxe_fmf_20',
        'taxe_mise_en_charge',
        'chasse_peche',
        'taxe_12_bois_importes',
    ];

    protected $casts = [
        'year' => 'integer',
        'taxe_adjudication_1_6' => 'decimal:2',
        'taxe_reconnaissance_interets' => 'decimal:2',
        'ta_saisie_caution' => 'decimal:2',
        'budget_fmf' => 'decimal:2',
        'remboursement_drs' => 'decimal:2',
        'remboursement_fmf_autres' => 'decimal:2',
        'taxe_fmf_20' => 'decimal:2',
        'taxe_mise_en_charge' => 'decimal:2',
        'chasse_peche' => 'decimal:2',
        'taxe_12_bois_importes' => 'decimal:2',
    ];

    /**
     * Get the situation administrative for this regional budget.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }
}
