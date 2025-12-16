<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etape7ConcertationPopulation extends Model
{
    use SoftDeletes;

    protected $table = 'etape7_concertation_populations';

    protected $fillable = [
        'pdfc_id',
        'user_id',
        'titre',
        'description',
        'document',
    ];

    public function pdfc(): BelongsTo
    {
        return $this->belongsTo(Pdfc::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


