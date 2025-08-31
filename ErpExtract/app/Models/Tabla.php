<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabla extends Model
{
    protected $table = 'tablas';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'modulo_id',
        'nombre',
        'codigo',
        'descripcion',
        'cds',
        'entity_type',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function campos()
    {
        return $this->hasMany(TablaCampo::class, 'tabla_id', 'id');
    }
}
