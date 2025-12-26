<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    protected $fillables = [
        'user_id', 'news_id', 'content'
    ]; 
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function news(){
        return $this->belongsTo(News::class); 
    }
}
