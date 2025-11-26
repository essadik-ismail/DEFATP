<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Odf extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'odf_entite_id',
        'constitution',
        'commentaire',
    ];

    protected $casts = [
        'constitution' => 'boolean',
    ];

    /**
     * Get the ODF entite for this ODF.
     */
    public function odfEntite(): BelongsTo
    {
        return $this->belongsTo(OdfEntite::class, 'odf_entite_id');
    }

    /**
     * Get the activities for this ODF.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'odf_id');
    }

    /**
     * Get the members for this ODF.
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'odf_id');
    }

    /**
     * Get the odf etaps for this ODF.
     */
    public function odfEtaps(): HasMany
    {
        return $this->hasMany(OdfEtap::class, 'odf_id');
    }

    /**
     * Get the contract odf for this ODF.
     */
    public function contractOdf(): HasMany
    {
        return $this->hasMany(ContractOdf::class, 'odf_id');
    }

    /**
     * Get the odf modifications for this ODF.
     */
    public function odfModifications(): HasMany
    {
        return $this->hasMany(OdfModification::class, 'odf_id');
    }

    /**
     * Get the odf diagnostics for this ODF.
     */
    public function odfDiagnostics(): HasMany
    {
        return $this->hasMany(OdfDiagnostic::class, 'odf_id');
    }

    /**
     * Get the constitution for this ODF.
     */
    public function constitution(): HasMany
    {
        return $this->hasMany(Constitution::class, 'odf_id');
    }

    /**
     * Get the localisation for this ODF.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'localisation_id');
    }

    /**
     * Get the situation administrative for this ODF.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }
}
