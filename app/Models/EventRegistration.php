<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'contact_number',     
        'classification',
        'school',             
        'college_id',        
        'program',           
        'year_level',         
        'organization_name',  
        'position',           
        'ticket_code',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
