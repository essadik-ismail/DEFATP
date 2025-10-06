<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class SituationAdministrative extends Model
{
    protected $fillable = [
        'commune',
        'province',
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

        // Global scope to exclude deleted situation administratives
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

    /**
     * Get the articles for this situation administrative.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articlesMany(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_situation_administrative', 'situation_administrative_id', 'article_id')
            ->withTimestamps();
    }
}
