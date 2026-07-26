<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongPostComment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'author_display', 'parent_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust to your specific User model
    }

    public function replies()
    {
        return $this->hasMany(IbalongPostComment::class, 'parent_id')->latest();
    }
}