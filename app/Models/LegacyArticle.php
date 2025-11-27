<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LegacyArticle extends Model
{
    use HasFactory;

    protected $table = 'legacy_articles';

    protected $fillable = [
        'dref',
        'foret',
        'province',
        'date',
        'essence',
        'intervent',
        'surface',
        'bom3',
        'bim3',
        'bfst',
        'lcst',
        'ett',
        'pst',
        'acheteur',
        'ppdh',
        'dr',
        'source_file',
    ];

    protected $casts = [
        'surface' => 'decimal:2',
        'bom3' => 'decimal:2',
        'bim3' => 'decimal:2',
        'bfst' => 'decimal:2',
        'lcst' => 'decimal:2',
        'ett' => 'decimal:2',
        'pst' => 'decimal:2',
        'ppdh' => 'decimal:2',
    ];

    // Scope for filtering by province
    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    // Scope for filtering by essence
    public function scopeByEssence($query, $essence)
    {
        return $query->where('essence', $essence);
    }

    // Scope for filtering by year (extracted from date field)
    public function scopeByYear($query, $year)
    {
        return $query->where('date', 'like', $year . '%');
    }

    // Scope for filtering by forest
    public function scopeByForet($query, $foret)
    {
        return $query->where('foret', $foret);
    }

    // Accessor to get formatted date
    public function getFormattedDateAttribute()
    {
        if (!$this->date || strlen($this->date) !== 6) {
            return null;
        }
        
        $yy = (int) substr($this->date, 0, 2);
        $month = substr($this->date, 2, 2);
        $day = substr($this->date, 4, 2);
        
        // If YY >= 90, it means 19YY (1990-1999)
        // If YY < 90, it means 20YY (2000-2089)
        if ($yy >= 90) {
            $year = '19' . str_pad($yy, 2, '0', STR_PAD_LEFT);
        } else {
            $year = '20' . str_pad($yy, 2, '0', STR_PAD_LEFT);
        }
        
        return $year . '-' . $month . '-' . $day;
    }

    // Accessor to get year from date
    public function getYearAttribute()
    {
        if (!$this->date || strlen($this->date) !== 6) {
            return null;
        }
        
        $yy = (int) substr($this->date, 0, 2);
        
        // If YY >= 90, it means 19YY (1990-1999)
        // If YY < 90, it means 20YY (2000-2089)
        if ($yy >= 90) {
            return '19' . str_pad($yy, 2, '0', STR_PAD_LEFT);
        } else {
            return '20' . str_pad($yy, 2, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Get the products for this legacy article (many-to-many relationship).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'legacy_article_product', 'legacy_article_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
