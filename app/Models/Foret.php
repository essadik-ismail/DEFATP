<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Foret extends Model
{
    protected $fillable = [
        'foret',
        'lat',
        'log',
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

        // Global scope to exclude deleted forets
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_foret', 'foret_id', 'article_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: contracts linked via pivot table.
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contact_foret', 'foret_id', 'contact_id')
            ->withTimestamps();
    }
}
