<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkageStatus extends Model
{
    protected $fillable = ['name', 'slug', 'color'];
    
    public function linkages() {
        return $this->hasMany(Linkage::class);
    }
}