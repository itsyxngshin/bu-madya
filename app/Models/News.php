<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\NewsVote;
use App\Models\NewsComment;
use App\Models\NewsCategory;
use App\Models\NewsSdg;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'news_category_id', 'author', 'content', 
        'cover_img', 'tags', 'published_at', 'is_featured', 'user_id',
        'photo_credit', 'show_drop_cap', 'status', 'summary',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_featured' => 'boolean',
    ];

    // Helper: Turn "Youth, Leadership" string into an array ['Youth', 'Leadership']
    public function getTagListAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

// Helper to get the name to show
    public function getBylineAttribute()
    {
        // If there is a linked user, return their name (or formal name from profile)
        if ($this->author) {
            return $this->author->name;
        }
        
        // Otherwise return the custom text string
        return $this->author_display_name ?? 'BU MADYA';
    }

    public function category()
    {
        // Points to the specific NewsCategory model
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function sdgs()
    {
        return $this->belongsToMany(Sdg::class, 'news_sdgs');
    }
    public function comments(){
        return $this->hasMany(NewsComment::class)->latest(); 
    }

    public function votes(){
        return $this->hasMany(NewsVote::class); 
    }
    
}
