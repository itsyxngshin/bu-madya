<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongPostImage extends Model
{
    protected $fillable = ['post_id', 'image_path'];

    public function post()
    {
        return $this->belongsTo(IbalongPost::class, 'post_id');
    }
}
