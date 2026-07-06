<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbalongTeamMember extends Model
{
    use HasFactory;

    protected $table = 'ibalong_team_members';

    protected $fillable = [
        'team_id',
        'user_id',
        'full_name',
        'slug',
        'email_address',
        'mobile_number',
        'birthday',
        'course',
        'role',
        'position',
        'affiliation',
        'team_role',
    ];

    public function team()
    {
        return $this->belongsTo(IbalongRegistration::class, 'team_id');
    }

    public function user()
    {
        return $this->belongsTo(IbalongUser::class, 'user_id');
    }

    public function skills()
    {
        return $this->belongsToMany(IbalongSkill::class, 'ibalong_team_member_skills', 'member_id', 'skill_id')
                    ->withTimestamps();
    }
    
}