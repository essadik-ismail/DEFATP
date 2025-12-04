<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyRevenue extends Model
{
    protected $fillable = [
        'year',
        'month',
        'llege',
        'bols_charbon_tanin',
        'alfa',
        'produits_divers',
        'interets_retard',
        'total_part_province',
        'situation_administrative_id',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'llege' => 'decimal:2',
        'bols_charbon_tanin' => 'decimal:2',
        'alfa' => 'decimal:2',
        'produits_divers' => 'decimal:2',
        'interets_retard' => 'decimal:2',
        'total_part_province' => 'decimal:2',
    ];

    /**
     * Get the situation administrative for this monthly revenue.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }
}
