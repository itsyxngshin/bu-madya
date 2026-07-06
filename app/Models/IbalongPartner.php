<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbalongPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'logo_path',
        'emphasis',
        'display_order',
        'is_active',
    ];
}
