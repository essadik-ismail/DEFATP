<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prorogation extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'contract_vente_id',
        'duration_months',
        'status',
        'motif',
        'decision_note',
        'original_expiry_date',
        'new_expiry_date',
        'requested_by',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'original_expiry_date' => 'date',
        'new_expiry_date'      => 'date',
        'decided_at'           => 'datetime',
        'duration_months'      => 'integer',
    ];

    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
