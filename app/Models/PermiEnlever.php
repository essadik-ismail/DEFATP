<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermiEnlever extends Model
{
    use SoftDeletes;

    protected $table = 'permi_enlevers';

    protected $fillable = [
        'permis_id',
        'contract_vente_id',
        'num',
        'num_quittance',
        'date',
        'num_tranche_paye',
        'date_paiement',
        'percepteur',
        'volume',
        'fichier_permis_signe',
        'signed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'date_paiement' => 'date',
        'num_tranche_paye' => 'integer',
        'volume' => 'decimal:2',
        'signed_at' => 'datetime',
    ];

    /**
     * Get the permis for this permi enlever.
     */
    public function permis(): BelongsTo
    {
        return $this->belongsTo(Permis::class, 'permis_id');
    }

    /**
     * Get the contrat de vente associated with this permis d'enlever.
     */
    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }

    /**
     * Many-to-many: products and essences (permisenlever_product).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'permisenlever_product', 'permis_id', 'product_id')
            ->withPivot('id_essence', 'quantity')
            ->withTimestamps();
    }

    /**
     * Many-to-many: essences through permisenlever_product.
     */
    public function essences(): BelongsToMany
    {
        return $this->belongsToMany(Essence::class, 'permisenlever_product', 'permis_id', 'id_essence')
            ->withPivot('product_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * Many-to-many: products and essences (colportage_enlever).
     */
    public function colportageProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'colportage_enlever', 'id_permis_enlever', 'product_id')
            ->withPivot('id_essence', 'quantity')
            ->withTimestamps();
    }

    /**
     * Many-to-many: essences through colportage_enlever.
     */
    public function colportageEssences(): BelongsToMany
    {
        return $this->belongsToMany(Essence::class, 'colportage_enlever', 'id_permis_enlever', 'id_essence')
            ->withPivot('product_id', 'quantity')
            ->withTimestamps();
    }

    /**
     * One-to-many: Permis d'Enlever has many Permis de Colportage rows.
     */
    public function colportages(): HasMany
    {
        return $this->hasMany(ColportageEnlever::class, 'id_permis_enlever');
    }
}
