<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LdapDomain extends Model
{
    protected $table = 'ldap_domains';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'domain',
        'host',
        'base_dn',
        'username',
        'password',
        'icon',
        'country',
    ];
}
