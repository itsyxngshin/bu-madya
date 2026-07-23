<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongSetting extends Model
{
    protected $fillable = [
        'is_registration_open'
    ];

    protected $casts = [
        'is_registration_open' => 'boolean',
    ];
}
