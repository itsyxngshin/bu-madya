<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sdg extends Model
{
    protected $table = 'sdgs';
    protected $fillable = ['number', 'name', 'slug', 'color_hex', 'icon_path']; 

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_sdgs');
    }

    public function news()
    {
        return $this->belongsToMany(News::class, 'news_sdg');
    }
}
