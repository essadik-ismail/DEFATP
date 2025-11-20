<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class NatureDeCoupe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nature_de_coupe',
    ];

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_nature_de_coupe', 'nature_de_coupe_id', 'article_id')
            ->withTimestamps();
    }
}
