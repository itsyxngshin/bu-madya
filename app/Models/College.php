<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'logo_path', 
        'color_code'
    ];

    /**
     * Get the student profiles associated with this college.
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class); // Make sure 'Profile' model exists
    }
}
