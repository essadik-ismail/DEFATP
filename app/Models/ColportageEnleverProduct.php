<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColportageEnleverProduct extends Model
{
    protected $table = 'colportage_enlever_product';

    protected $fillable = [
        'colportage_enlever_id',
        'product_id',
        'id_essence',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function colportageEnlever(): BelongsTo
    {
        return $this->belongsTo(ColportageEnlever::class, 'colportage_enlever_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function essence(): BelongsTo
    {
        return $this->belongsTo(Essence::class, 'id_essence');
    }
}
