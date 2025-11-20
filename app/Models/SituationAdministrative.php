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
    ];

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_situation_administrative', 'situation_administrative_id', 'article_id')
            ->withTimestamps();
    }
}
