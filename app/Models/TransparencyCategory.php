<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransparencyDocument;

class TransparencyCategory extends Model
{
    protected $guarded = [];

    public function documents()
    {
        return $this->hasMany(TransparencyDocument::class, 'category_id');
    }   
}
