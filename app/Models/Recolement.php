<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recolement extends Model
{
    const STATUS_PENDING_PV      = 'pending_pv';
    const STATUS_PV_SUBMITTED    = 'pv_submitted';
    const STATUS_MAINLEVEE_ISSUED = 'mainlevee_issued';
    const STATUS_CLOSED          = 'closed';

    protected $fillable = [
        'contract_vente_id',
        'date_recolement', 'adjudication', 'num_marche',
        'commission', 'marteau', 'marque', 'souches_reserves',
        'la_coupe', 'les_limites', 'le_vidange', 'nettoyage_coupe',
        'le_recru', 'travaux_imposes', 'fourniture_mise_en_charge', 'delits_constates',
        'bois_oeuvre', 'bois_industrie', 'bois_service', 'bois_chauffage',
        'brins_cedre', 'liege_male', 'liege_reproduction', 'ecorce_tanin', 'bois_carboniser',
        'produits_abandonnes',
        'date_pv', 'num_pv', 'observations', 'fichier_pv', 'submitted_by',
        'date_mainlevee', 'num_mainlevee', 'fichier_mainlevee', 'mainlevee_issued_by',
        'status',
    ];

    protected $casts = [
        'date_recolement'    => 'date',
        'date_pv'            => 'date',
        'date_mainlevee'     => 'date',
        'commission'         => 'array',
        'souches_reserves'   => 'array',
        'produits_abandonnes'=> 'array',
    ];

    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function mainleveeIssuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mainlevee_issued_by');
    }
}
