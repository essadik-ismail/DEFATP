<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etape4AnalyseDegreAcceptation extends Model
{
    use SoftDeletes;

    protected $table = 'etape4_analyse_degre_acceptations';

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


