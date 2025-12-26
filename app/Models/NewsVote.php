<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsVote extends Model
{
    protected $fillables = [
        'user_id', 'news_id', 'is_like'
    ]; 

    protected $casts = [
        'is_like' => 'boolean'
    ]; 

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function news(){
        return $this->belongsTo(News::class); 
    }
}
