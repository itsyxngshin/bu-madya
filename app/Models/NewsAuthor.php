<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsAuthor extends Model
{
    protected $fillable = [
        'news_id', 'user_id', 'name', 'type'
    ];
    public function news(){
        return $this->belongsTo(News::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
