<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PvInstallation extends Model
{
    use SoftDeletes;

    protected $table = 'pv_installations';

    protected $fillable = [
        'pvn',
        'date',
        'participants',
        'exploitant',
        'reserve',
        'emo',
        'charbonniére',
        'mise_en_charge',
        'ravalement_souches',
        'remarient',
        'mise_en_defens',
        'invitation_caporal',
        'contract_vente_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the contract vente for this PV installation.
     */
    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }
}
