<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depot extends Model
{
    use SoftDeletes;

    protected $table = 'depot';

    protected $fillable = [
        'nom',
        'id_dpanef',
    ];

    /**
     * Get the dpanef for this depot.
     */
    public function dpanef(): BelongsTo
    {
        return $this->belongsTo(Dpanef::class, 'id_dpanef');
    }

    /**
     * Many-to-many: articles.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'depot_article', 'id_depot', 'id_article')
            ->withTimestamps();
    }
}

