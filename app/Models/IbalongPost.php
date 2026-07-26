<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongPost extends Model
{
    protected $fillable = ['user_id', 'content', 'author_display', 'is_announcement'];

    // Assuming your users are in IbalongUser or standard User model
    public function user()
    {
        return $this->belongsTo(IbalongUser::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(IbalongPostImage::class, 'post_id');
    }

    public function likes()
    {
        return $this->hasMany(IbalongPostLike::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(IbalongPostComment::class, 'post_id')->latest();
    }
}
