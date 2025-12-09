<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localisation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'CODE',
        'DRANEF',
        'DPANEF',
        'ENTITE',
    ];

    /**
     * Get localisations formatted for select dropdowns.
     */
    public static function getLocalisationsForSelect()
    {
        return static::orderBy('CODE')
            ->get()
            ->map(function ($localisation) {
                return [
                    'id' => $localisation->id,
                    'text' => $localisation->CODE . ' - ' . $localisation->DRANEF
                ];
            });
    }

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_localisation', 'localisation_id', 'article_id')
            ->withTimestamps();
    }

    /**
     * Get the exploitants for this localisation.
     */
    public function exploitants(): HasMany
    {
        return $this->hasMany(Exploitant::class);
    }

    /**
     * Get the partenariats for this localisation.
     */
    public function partenariats(): HasMany
    {
        return $this->hasMany(Partenariat::class);
    }

    /**
     * Get the suivi contract programmes for this localisation.
     */
    public function suiviContractProgrammes(): HasMany
    {
        return $this->hasMany(SuiviContractProgramme::class);
    }
}
