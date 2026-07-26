<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongNotification extends Model
{
    protected $fillable = ['user_id', 'type', 'message', 'link', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}