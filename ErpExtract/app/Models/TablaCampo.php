<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaCampo extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tabla_id',
        'nombre',
        'descripcion',
        'obligatorio',
    ];

    public function tabla()
    {
        return $this->belongsTo(Tabla::class);
    }
}
