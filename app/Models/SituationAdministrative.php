<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class SituationAdministrative extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'commune',
        'province',
        'region',
    ];

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_situation_administrative', 'situation_administrative_id', 'article_id')
            ->withTimestamps();
    }

    /**
     * Get province annual shares for this situation administrative.
     */
    public function provinceAnnualShares(): HasMany
    {
        return $this->hasMany(ProvinceAnnualShare::class, 'situation_administrative_id');
    }

    /**
     * Get regional budgets for this situation administrative.
     */
    public function regionalBudgets(): HasMany
    {
        return $this->hasMany(RegionalBudget::class, 'situation_administrative_id');
    }

    /**
     * Get monthly revenues for this situation administrative.
     */
    public function monthlyRevenues(): HasMany
    {
        return $this->hasMany(MonthlyRevenue::class, 'situation_administrative_id');
    }
}
