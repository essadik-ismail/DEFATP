<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractVente extends Model
{
    use SoftDeletes;

    protected $table = 'contract_ventes';

    protected $fillable = [
        'date_adjudication',
        'prix_vente',
        'prix_de_retrait',
        'article_id',
        'exploitant_id',
        'date_de_decheance',
        'duree_decheache',
        'id_decheance',
        'date_de_resiliation',
        'is_resiliation',
        'nombre_tranche',
        'date_limite_tranche',
        'date_limite_taxes',
        'date_expiration',
        'type',
        'numeraAO',
        'Current_state',
        'letter_generated_at',
        'letter_signed_file',
        'letter_signed_at',
        'bois_chauffage_volume_st',
        'percepteur',
        'is_validated',
        'validated_at',
    ];

    protected $casts = [
        'date_adjudication'  => 'date',
        'date_de_decheance'  => 'date',
        'date_de_resiliation'=> 'date',
        'date_limite_tranche'=> 'date',
        'date_limite_taxes'  => 'date',
        'date_expiration'    => 'date',
        'letter_generated_at'=> 'datetime',
        'letter_signed_at'   => 'datetime',
        'prix_vente'              => 'decimal:2',
        'bois_chauffage_volume_st' => 'decimal:2',
        'is_resiliation'     => 'boolean',
        'nombre_tranche'     => 'integer',
        'is_validated'       => 'boolean',
        'validated_at'       => 'datetime',
    ];

    /**
     * Get the article for this contract.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * Get the exploitant for this contract.
     */
    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'exploitant_id');
    }

    /**
     * Get the payments for this contract.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'contract_vente_id');
    }

    /**
     * Get the pv installations for this contract.
     */
    public function pvInstallations(): HasMany
    {
        return $this->hasMany(PvInstallation::class, 'contract_vente_id');
    }

    /**
     * Get the denombrements for this contract.
     */
    public function denombrements(): HasMany
    {
        return $this->hasMany(\App\Models\Denombrement::class, 'contract_vente_id');
    }

    /**
     * Get the charges to pay for this contract.
     */
    public function chargeApayer(): HasMany
    {
        return $this->hasMany(ChargeApayer::class, 'contrat_vente_id');
    }

    /**
     * Get the permis exploiter for this contract (single).
     */
    public function permisExploiter(): HasOne
    {
        return $this->hasOne(PermisExploiter::class, 'contrat_vente_id');
    }

    /**
     * Get the permis exploiter for this contract (collection).
     */
    public function permisExploiters(): HasMany
    {
        return $this->hasMany(PermisExploiter::class, 'contrat_vente_id');
    }

    /**
     * Many-to-many: forets.
     */
    public function forets(): BelongsToMany
    {
        return $this->belongsToMany(Foret::class, 'contract_foret', 'contract_id', 'foret_id')
            ->withTimestamps();
    }

    /**
     * Many-to-many: products.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'contract_vente_product', 'contract_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function vehicleDeclarations(): HasMany
    {
        return $this->hasMany(VehicleDeclaration::class, 'contract_vente_id');
    }

    public function prorogations(): HasMany
    {
        return $this->hasMany(Prorogation::class, 'contract_vente_id');
    }

    public function recolement(): HasOne
    {
        return $this->hasOne(Recolement::class, 'contract_vente_id');
    }
}
