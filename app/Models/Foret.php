<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foret extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'foret',
        'lat',
        'log',
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
     * Get the suivi contract programmes for this foret.
     */
    public function suiviContractProgrammes(): HasMany
    {
        return $this->hasMany(SuiviContractProgramme::class);
    }
}
