<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractOdf extends Model
{
    use SoftDeletes;

    protected $table = 'contract_odf';

    protected $fillable = [
        'date',
        'lieu',
        'signature1_nom',
        'signature2_nom',
        'signature1_type',
        'signature2_type',
        'fichier_join',
        'odf_id',
        'commentaire',
    ];

    /**
     * Get the ODF for this contract.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }
}
