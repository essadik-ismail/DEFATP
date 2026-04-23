<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'transport_nuit',
        'distance_km',
        'numero_permis',
        'volume',
        'carnet_id',
        'fichier_joint',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'volume' => 'decimal:2',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'transport_nuit' => 'boolean',
        'distance_km' => 'decimal:2',
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

    /**
     * Get the quantity rows attached to this colportage.
     */
    public function detailRows(): HasMany
    {
        return $this->hasMany(ColportageEnleverProduct::class, 'colportage_enlever_id');
    }
}
