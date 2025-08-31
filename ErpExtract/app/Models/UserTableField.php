<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTableField extends Model
{
    use HasFactory;

    public $incrementing = false; // no auto-increment
    protected $keyType = 'string';
    protected $fillable = ['user_id', 'tabla_id', 'field_name'];
}
