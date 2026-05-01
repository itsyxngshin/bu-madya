<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'excerpt',
        'content', 'cover_image_path', 'gallery', // Add gallery here
        'is_published', 'is_featured', 'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'gallery' => 'array', // Magic cast: handles JSON encoding/decoding automatically
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments() {
        return $this->hasMany(PostComment::class)->latest();
    }

    public function elements() {
        return $this->hasMany(Element::class);
    }
}
