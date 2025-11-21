<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OdfEntite extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'localisation_id',
        'situation_administrative_id',
    ];

    /**
     * Get the localisation for this ODF entite.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'localisation_id');
    }

    /**
     * Get the situation administrative for this ODF entite.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }

    /**
     * Get the ODFs for this entite.
     */
    public function odfs(): HasMany
    {
        return $this->hasMany(Odf::class, 'odf_entite_id');
    }
}
