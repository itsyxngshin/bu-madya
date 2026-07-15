<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongEventAttendance extends Model
{
    protected $fillable = [
        'registration_id', 'scanned_at', 'scanned_by'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(IbalongEventRegistration::class, 'registration_id');
    }
}
