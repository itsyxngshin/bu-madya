<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkageProject extends Model
{
    protected $table = 'linkage_projects';
    
    protected $fillable = ['project_id', 'linkage_id', 'manual_name', 'role'];

    // Optional: Relationship to the official Linkage
    public function linkage()
    {
        return $this->belongsTo(Linkage::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
