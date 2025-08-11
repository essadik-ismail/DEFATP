<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DRANEF extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dranefs';

    protected $fillable = [
        'nom',
        'description',
        'is_deleted'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
}
