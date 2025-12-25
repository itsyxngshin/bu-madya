<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Helper to get the current active year
    public static function current()
    {
        return self::where('is_active', true)->first();
    }

    // Logic: When saving, if this is set to active, deactivate others
    protected static function booted()
    {
        static::saving(function ($year) {
            if ($year->is_active) {
                // Update all other rows to be inactive
                static::where('id', '!=', $year->id)->update(['is_active' => false]);
            }
        });
    }
}
