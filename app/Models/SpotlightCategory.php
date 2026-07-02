<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotlightCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    public function spotlights()
    {
        return $this->hasMany(Spotlight::class);
    }
}
