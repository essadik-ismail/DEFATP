<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etape5AnalyseProgrammesAnterieur extends Model
{
    use SoftDeletes;

    protected $table = 'etape5_analyse_programmes_anterieurs';

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


