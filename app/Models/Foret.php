<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foret extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'foret',
        'lat',
        'log',
        'nature_juridique',
        'dpanef_id',
    ];

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

    /**
     * Get the dpanef for this foret.
     */
    public function dpanef(): BelongsTo
    {
        return $this->belongsTo(Dpanef::class, 'dpanef_id');
    }

    /**
     * Get the cantons for this foret.
     */
    public function cantons(): HasMany
    {
        return $this->hasMany(Canton::class, 'foret_id');
    }

    /**
     * Get the parcelles for this foret.
     */
    public function parcelles(): HasMany
    {
        return $this->hasMany(Parcelle::class, 'foret_id');
    }
}
