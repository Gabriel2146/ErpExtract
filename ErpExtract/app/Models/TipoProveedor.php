<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TipoProveedor extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'tipoproveedor';

    protected $fillable = [
        'descripcion',
        'country',
        'claseInterlocutor',
        'socioComercial',
        'cuentaContable',
        'estado',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
}
