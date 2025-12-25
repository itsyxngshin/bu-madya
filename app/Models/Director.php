<?php

namespace App\Models;
use App\Models\Scopes\ActiveYearScope;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ActiveYearScope);
    }
}
