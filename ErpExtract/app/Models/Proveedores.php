<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // para poder usar Auth
use Illuminate\Notifications\Notifiable;

class Proveedores extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'proveedores';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'password',
        'name',
        'country',
        'country_id',
        'icon',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function domain()
    {
        return $this->hasOne(LdapDomain::class, 'country', 'country');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function systemNotifications()
    {
        return $this->hasMany(SystemNotification::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }
}
