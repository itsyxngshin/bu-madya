<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongPostComment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'author_display', 'content'];

    public function user()
    {
        return $this->belongsTo(IbalongUser::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(IbalongPost::class, 'post_id');
    }
}
