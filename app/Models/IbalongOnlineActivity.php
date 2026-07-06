<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbalongOnlineActivity extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'ibalong_online_activities';

    // The fields that can be mass assigned
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * REVERSE RELATIONSHIP
     * Get all the registered teams that committed to this specific activity.
     */
    public function teams()
    {
        return $this->belongsToMany(
            IbalongRegistration::class, 
            'ibalong_team_online_participations', 
            'online_activity_id', 
            'team_id'
        )->withTimestamps();
    }
}