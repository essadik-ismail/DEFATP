<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the articles that have this product.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_product', 'product_id', 'article_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get the contracts that have this product.
     */
    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class, 'contract_product', 'product_id', 'contract_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get the avenants that have this product.
     */
    public function avenants(): BelongsToMany
    {
        return $this->belongsToMany(Avenant::class, 'avenant_product', 'product_id', 'avenant_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Scope for products by name.
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', "%{$name}%");
    }
}