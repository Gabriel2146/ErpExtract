<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    public $incrementing = false; // No autoincrement
    protected $keyType = 'string'; // GUID

    protected $fillable = [
        'id',
        'name',
    ];

    // Generar GUID automáticamente al crear
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
