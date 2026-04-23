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
        'date_pv',
        'num_pv',
        'observations',
        'fichier_pv',
        'submitted_by',
        'date_mainlevee',
        'num_mainlevee',
        'fichier_mainlevee',
        'mainlevee_issued_by',
        'status',
    ];

    protected $casts = [
        'date_pv'         => 'date',
        'date_mainlevee'  => 'date',
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
