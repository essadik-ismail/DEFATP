<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Commune extends Model
{
    protected $fillable = [
        'nom',
        'province_id',
    ];

    /**
     * Get the province that owns this commune.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Many-to-many: articles.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_commune', 'commune_id', 'article_id')
            ->withTimestamps();
    }
}
