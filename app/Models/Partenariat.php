<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partenariat extends Model
{
    protected $fillable = [
        'localisation_id',
        'nom_association',
        'nombre_adherents_association',
        'date_creation_association',
        'superficie',
        'nom_périmètre',
        'essence_id',
        'object_cmd',
        'num_contract',
        'date_signature_contract',
        'num_avenant',
        'nombre_avenant',
        'date_signature_avenant',
        'Superficie_Contrat_avenant',
        'Date_PV_etat_des_lieux',
        'Superficie_ha',
        'Taux_de_réussite',
        'Etat_de_la_clôture',
        'PV_évaluation',
        'observations',
        'Etat_peuplement',
        'Contraintes',
    ];

    protected $casts = [
        'date_creation_association' => 'date',
        'date_signature_contract' => 'date',
        'date_signature_avenant' => 'date',
        'Date_PV_etat_des_lieux' => 'date',
        'superficie' => 'decimal:2',
        'Superficie_Contrat_avenant' => 'decimal:2',
        'Superficie_ha' => 'decimal:2',
        'Taux_de_réussite' => 'decimal:2',
        'nombre_adherents_association' => 'integer',
        'nombre_avenant' => 'integer',
    ];

    /**
     * Get the localisation for this partenariat.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class);
    }

    /**
     * Get the essence for this partenariat.
     */
    public function essence(): BelongsTo
    {
        return $this->belongsTo(Essence::class);
    }

    /**
     * Get the suivi contract programmes for this partenariat.
     */
    public function suiviContractProgrammes(): HasMany
    {
        return $this->hasMany(SuiviContractProgramme::class);
    }
}
