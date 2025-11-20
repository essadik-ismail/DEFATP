<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coperative extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'vocation_id',
        'nombre_membres',
        'nombre_coperatives',
    ];

    protected $casts = [
        'nombre_membres' => 'integer',
        'nombre_coperatives' => 'integer',
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

    /**
     * Get the vocation for this coperative.
     */
    public function vocation()
    {
        return $this->belongsTo(Vocation::class, 'vocation_id');
    }
}
