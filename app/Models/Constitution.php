<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Constitution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'lieu',
        'participant',
        'date_depot_odf',
        'fichier_joint_depot_odf',
        'lieu_depot_odf',
        'date_reçu_définitive',
        'fichier_joint_reçu_définitive',
        'lieu_reçu_définitive',
        'odf_id',
    ];

    /**
     * Get the ODF for this constitution.
     */
    public function odf(): BelongsTo
    {
        return $this->belongsTo(Odf::class, 'odf_id');
    }
}

