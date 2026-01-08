<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Province extends Model
{
    protected $fillable = [
        'nom',
    ];

    /**
     * Get communes for this province.
     */
    public function communes(): HasMany
    {
        return $this->hasMany(Commune::class);
    }

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_province', 'province_id', 'article_id')
            ->withTimestamps();
    }
}
