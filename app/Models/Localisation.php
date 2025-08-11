<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Localisation extends Model
{
    protected $fillable = [
        'CODE',
        'DRANEF',
        'DPANEF',
        'ENTITE',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Global scope to exclude deleted localisations
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

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
     * Get the articles for this localisation.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get the exploitants for this localisation.
     */
    public function exploitants(): HasMany
    {
        return $this->hasMany(Exploitant::class);
    }
}
