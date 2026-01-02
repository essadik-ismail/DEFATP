<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'id_decheance',
        'date_de_resiliation',
        'is_resiliation',
        'nombre_tranche',
    ];

    protected $casts = [
        'date_adjudication' => 'date',
        'date_de_decheance' => 'date',
        'date_de_resiliation' => 'date',
        'prix_vente' => 'decimal:2',
        'prix_de_retrait' => 'decimal:2',
        'is_resiliation' => 'boolean',
        'nombre_tranche' => 'integer',
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
}
