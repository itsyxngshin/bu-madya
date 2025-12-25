<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SDG extends Model
{
    protected $fillable = ['number', 'name', 'slug', 'color_hex', 'icon_path']; 

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_sdg');
    }
}
