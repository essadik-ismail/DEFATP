<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Essence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'essence',
    ];

    /**
     * Many-to-many: articles linked via pivot table.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_essence', 'essence_id', 'article_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: contracts linked via pivot table.
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contact_essence', 'essence_id', 'contact_id')
            ->withTimestamps();
    }
}
