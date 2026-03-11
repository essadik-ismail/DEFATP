<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColportageEnlever extends Model
{
    use HasFactory;

    protected $table = 'colportage_enlever';

    protected $fillable = [
        'id_permis_enlever',
        'product_id',
        'id_essence',
        'quantity',
        'article_id',
        'date_debut',
        'date_fin',
        'vehicule_immatriculation',
        'chauffeur_nom',
        'chauffeur_cin',
        'destination',
        'numero_permis',
        'carnet_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Get the permis enlever that owns the colportage
     */
    public function permisEnlever()
    {
        return $this->belongsTo(PermiEnlever::class, 'id_permis_enlever');
    }

    /**
     * Get the product for this colportage
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the essence for this colportage
     */
    public function essence()
    {
        return $this->belongsTo(Essence::class, 'id_essence');
    }

    /**
     * Get the article for this colportage
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the carnet for this permis de colportage
     */
    public function carnet()
    {
        return $this->belongsTo(Carnet::class);
    }
}
