<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Province extends Model
{
    protected $fillable = [
        'nom',
        'commune_id',
    ];

    /**
     * Get the commune that owns this province.
     */
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
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
