<?php

use Illuminate\Database\Eloquent\Model;

class TransparencyDocument extends Model
{
    protected $guarded = [];

    // Ensure Published Date is cast to Date
    protected $casts = [
        'published_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(TransparencyCategory::class, 'category_id');
    }
}