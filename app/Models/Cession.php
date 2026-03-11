<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cession extends Model
{
    protected $table = 'groupe_cession';

    protected $fillable = [
        'dranef_id',
        'mode_cession',
        'Exercice',
        'numAO',
        'dateAO',
        'DateAdj',
        'Statut',
    ];

    protected $casts = [
        'Exercice' => 'integer',
        'dateAO' => 'date',
        'DateAdj' => 'date',
    ];

    public function dranef(): BelongsTo
    {
        return $this->belongsTo(Dranef::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'groupe_cession_id');
    }

    /**
     * Accessor-style helpers to align with spec naming.
     */
    public function getTypeAttribute(): ?string
    {
        return $this->mode_cession;
    }

    public function setTypeAttribute(?string $value): void
    {
        $this->mode_cession = $value;
    }

    public function getAnneeExerciceAttribute(): ?int
    {
        return $this->Exercice ? (int) $this->Exercice : null;
    }

    public function setAnneeExerciceAttribute(?int $value): void
    {
        $this->Exercice = $value;
    }

    public function getDateAdjudicationAttribute()
    {
        return $this->DateAdj;
    }

    public function setDateAdjudicationAttribute($value): void
    {
        $this->DateAdj = $value;
    }

    public function getNumeroAoAttribute(): ?string
    {
        return $this->numAO;
    }

    public function setNumeroAoAttribute(?string $value): void
    {
        $this->numAO = $value;
    }

    public function getDateAttributionAttribute()
    {
        return $this->dateAO;
    }

    public function setDateAttributionAttribute($value): void
    {
        $this->dateAO = $value;
    }

    public function getStatusAttribute(): ?string
    {
        return $this->Statut;
    }

    public function setStatusAttribute(?string $value): void
    {
        $this->Statut = $value;
    }
}

