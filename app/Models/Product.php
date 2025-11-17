<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'article_id',
        'contract_id',
        'avenant_id',
        'is_deleted',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_deleted' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Global scope to exclude deleted products
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

    /**
     * Get the article that owns this product.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the contract that owns this product.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the avenant that owns this product.
     */
    public function avenant(): BelongsTo
    {
        return $this->belongsTo(Avenant::class);
    }

    /**
     * Scope for products by article.
     */
    public function scopeByArticle(Builder $query, int $articleId): Builder
    {
        return $query->where('article_id', $articleId);
    }

    /**
     * Scope for products by name.
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', "%{$name}%");
    }
}