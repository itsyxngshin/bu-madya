<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OjtBlog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'report_date',
        'title',
        'content',
        'attachment_path', // Don't forget this for the photo uploads!
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
