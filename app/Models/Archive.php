<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archive extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'date',
        'numero',
        'expediteur',
        'num_expediteur',
        'date_expediteur',
        'object',
        'departement',
        'service',
        'suite',
    ];

    protected $casts = [
        'date' => 'date',
        'date_expediteur' => 'date',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}

