<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongPostLike extends Model
{
    protected $fillable = ['post_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(IbalongUser::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(IbalongPost::class, 'post_id');
    }
}
