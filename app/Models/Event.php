<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'cover_image',
        'registration_link', 'registration_button_text',
        'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Helper to check if event is currently open
    public function isOpen()
    {
        if (!$this->is_active) return false;
        // If no end date is set, assume it's open indefinitely
        if (!$this->end_date) return true;
        return now()->lte($this->end_date);
    }
}