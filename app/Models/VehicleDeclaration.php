<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleDeclaration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_vente_id',
        'immatriculation',
        'marque',
        'capacite',
        'capacite_unite',
        'chauffeur_nom',
        'chauffeur_cin',
        'date_declaration',
        'declared_by',
    ];

    protected $casts = [
        'capacite'         => 'decimal:2',
        'date_declaration' => 'date',
    ];

    public function contractVente(): BelongsTo
    {
        return $this->belongsTo(ContractVente::class, 'contract_vente_id');
    }

    public function declaredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'declared_by');
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_vehicle_declaration')
            ->withTimestamps();
    }
}
