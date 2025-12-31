<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipApplication extends Model
{
    protected $guarded = [];
    
    public function college()
    {
        return $this->belongsTo(College::class, 'college_id');
    }

    // 2. Link to 1st Choice
    public function firstCommittee()
    {
        return $this->belongsTo(Committee::class, 'committee_1_id');
    }

    // 3. Link to 2nd Choice
    public function secondCommittee()
    {
        return $this->belongsTo(Committee::class, 'committee_2_id');
    }
    
    public function membershipWave()
    {
        return $this->belongsTo(MembershipWave::class);
    }

    public function assignedCommittee()
    {
        return $this->belongsTo(Committee::class, 'assigned_committee_id');
    }
}
