<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coperative extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'nombre_membres',
        'nombre_coperatives',
        'is_deleted',
    ];

    protected $casts = [
        'nombre_membres' => 'integer',
        'nombre_coperatives' => 'integer',
        'is_deleted' => 'boolean',
    ];

    /**
     * Get the contracts for this coperative.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'coperative_id');
    }

    /**
     * Get the avenants for this coperative.
     */
    public function avenants()
    {
        return $this->hasMany(Avenant::class, 'coperative_id');
    }
}
