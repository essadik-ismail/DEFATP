<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SessionAdjudication extends Model
{
    protected $fillable = [
        'type',
        'date',
        'numero',
        'nature_juridique',
        'adjudicatire',
        'dc',
        'rc',
        'date_de_resiliation',
        'date_de_decheance',
        'exploitant_id',
        'is_validated',
        'is_deleted',
    ];

    protected $casts = [
        'date' => 'date',
        'date_de_resiliation' => 'date',
        'date_de_decheance' => 'date',
        'dc' => 'boolean',
        'rc' => 'boolean',
        'is_validated' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Global scope to exclude deleted session adjudications
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

    /**
     * Get the exploitant for this session adjudication.
     */
    public function exploitant(): BelongsTo
    {
        return $this->belongsTo(Exploitant::class, 'exploitant_id');
    }
}
