<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgreementLevel extends Model
{
    protected $fillable = ['name', 'slug', 'description'];
    
    public function linkages() {
        return $this->hasMany(Linkage::class);
    }
}
