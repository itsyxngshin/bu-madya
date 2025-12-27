<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectObjective extends Model
{
    protected $fillable = ['project_id', 'objective'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
}
