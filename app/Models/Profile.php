<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 
        'first_name', 
        'middle_name', 
        'last_name',
        'college_id', 
        'course', 
        'year_level'
    ];

    // 1. Helper: "Juan A. Dela Cruz"
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->first_name . ' ' . ($this->middle_name ? substr($this->middle_name, 0, 1) . '. ' : '') . $this->last_name),
        );
    }

    // 2. Helper: "Dela Cruz, Juan" (Great for lists)
    public function formalName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->last_name . ', ' . $this->first_name,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
