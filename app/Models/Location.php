<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Location extends Model
{
    protected $fillable = [
        'mat',
        'x',
        'y',
        'lat',
        'log',
        'article_id',
    ];

    protected $casts = [
        'x' => 'decimal:6',
        'y' => 'decimal:6',
        'lat' => 'decimal:6',
        'log' => 'decimal:6',
    ];

    /**
     * Get the article that owns this location.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Scope for locations by article.
     */
    public function scopeByArticle(Builder $query, int $articleId): Builder
    {
        return $query->where('article_id', $articleId);
    }

    /**
     * Scope for locations by material.
     */
    public function scopeByMaterial(Builder $query, string $mat): Builder
    {
        return $query->where('mat', 'like', "%{$mat}%");
    }

    /**
     * Scope for locations within coordinates.
     */
    public function scopeWithinCoordinates(Builder $query, float $minX, float $maxX, float $minY, float $maxY): Builder
    {
        return $query->whereBetween('x', [$minX, $maxX])
                    ->whereBetween('y', [$minY, $maxY]);
    }

    /**
     * Get formatted coordinates.
     */
    public function getFormattedCoordinatesAttribute(): string
    {
        return "X: {$this->x}, Y: {$this->y}";
    }
}