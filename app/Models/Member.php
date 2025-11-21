<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type_membre',
        'nom',
        'n_cin',
        'tel',
        'email',
        'type_odf',
        'type_odf_domaine_activite',
        'type_odf_nombre_de_membres',
        'odf_id',
        'commentaire',
    ];

    /**
     * Get the ODF that owns this member.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }
}
