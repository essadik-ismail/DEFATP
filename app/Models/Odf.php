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
        'présidente',
        'vice_présidente',
        'trésorière',
        'reçu_du_dépôt',
        'constitution',
        'user_id',
        'localisation_id',
        'situation_administrative_id',
    ];

    /**
     * Get the user that owns this ODF.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
