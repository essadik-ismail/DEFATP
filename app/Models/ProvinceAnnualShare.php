<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProvinceAnnualShare extends Model
{
    protected $fillable = [
        'situation_administrative_id',
        'year',
        'llege',
        'bols_charbon_tanin',
        'alfa',
        'produits_divers',
        'interets_retard',
        'total_province',
    ];

    protected $casts = [
        'year' => 'integer',
        'llege' => 'decimal:2',
        'bols_charbon_tanin' => 'decimal:2',
        'alfa' => 'decimal:2',
        'produits_divers' => 'decimal:2',
        'interets_retard' => 'decimal:2',
        'total_province' => 'decimal:2',
    ];

    /**
     * Get the situation administrative for this province annual share.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }
}
