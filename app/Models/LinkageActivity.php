<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LinkageActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'linkage_id',
        'title',          // e.g., "MOU Signing Ceremony"
        'activity_date',  // When did it happen?
        'description',    // Short minutes or summary
        'photo_path'      // Evidence/Photo of the event
    ];

    // Ensure date is treated as a Carbon object for easy formatting
    protected $casts = [
        'activity_date' => 'date',
    ];

    /**
     * Get the linkage (partner) that owns this activity.
     */
    public function linkage()
    {
        return $this->belongsTo(Linkage::class);
    }
}