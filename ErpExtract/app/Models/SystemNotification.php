<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemNotification extends Model
{
    use HasFactory;

    protected $table = 'system_notifications';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'message',
        'user_id',
        'notifiable_id',
        'notifiable_type',
        'read_at',
        'url',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
