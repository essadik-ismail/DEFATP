<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    protected $fillable = [
        'annee',
        'numero',
        'date',
        'invendu',
        'prix_de_retrait',
        'situation_administrative_id',
        'foret_id',
        'essence_id',
        'nature_de_coupe_id',
        'session_adjudication_id',
        'localisation_id',
        'lot',
        'parcelle',
        'superficie',
        'bo_m3',
        'bi_m3',
        'bf_st',
        'tanin_t',
        'fleur_acacia_t',
        'caroube_t',
        'romarin_t',
        'ps_t',
        'liége_st',
        'charbon_bois_ox',
        'prix_vente',
        'fourniture_mise_charge',
        'lat',
        'log',
        'date_dr',
        'observations',
        'is_deleted',
    ];

    protected $casts = [
        'date' => 'date',
        'date_dr' => 'date',
        'invendu' => 'boolean',
        'bo_m3' => 'integer',
        'bi_m3' => 'integer',
        'bf_st' => 'integer',
        'tanin_t' => 'integer',
        'fleur_acacia_t' => 'integer',
        'caroube_t' => 'integer',
        'romarin_t' => 'integer',
        'ps_t' => 'integer',
        'liége_st' => 'integer',
        'charbon_bois_ox' => 'integer',
        'prix_de_retrait' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'fourniture_mise_charge' => 'decimal:2',
        'is_deleted' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Global scope to exclude deleted articles
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

    /**
     * Get the administrative situation for this article.
     */
    public function situationAdministrative(): BelongsTo
    {
        return $this->belongsTo(SituationAdministrative::class, 'situation_administrative_id');
    }

    /**
     * Get the forest for this article.
     */
    public function foret(): BelongsTo
    {
        return $this->belongsTo(Foret::class, 'foret_id');
    }

    /**
     * Get the essence for this article.
     */
    public function essence(): BelongsTo
    {
        return $this->belongsTo(Essence::class, 'essence_id');
    }

    /**
     * Get the nature de coupe for this article.
     */
    public function natureDeCoupe(): BelongsTo
    {
        return $this->belongsTo(NatureDeCoupe::class, 'nature_de_coupe_id');
    }

    /**
     * Get the session adjudication for this article.
     */
    public function sessionAdjudication(): BelongsTo
    {
        return $this->belongsTo(SessionAdjudication::class, 'session_adjudication_id');
    }

    /**
     * Get the localisation for this article.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'localisation_id');
    }
}
