<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProponent extends Model
{
    protected $fillable = ['project_id', 'user_id', 'name', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
