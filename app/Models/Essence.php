<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
            ->withPivot('product_id', 'quantity')
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

    /**
     * Many-to-many: permi enlevers through permisenlever_product.
     */
    public function permiEnlevers(): BelongsToMany
    {
        return $this->belongsToMany(PermiEnlever::class, 'permisenlever_product', 'id_essence', 'permis_id')
            ->withPivot('product_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * Many-to-many: permi enlevers through colportage_enlever.
     */
    public function colportageEnlevers(): BelongsToMany
    {
        return $this->belongsToMany(PermiEnlever::class, 'colportage_enlever', 'id_essence', 'id_permis_enlever')
            ->withPivot('product_id', 'quantity')
            ->withTimestamps();
    }
}
