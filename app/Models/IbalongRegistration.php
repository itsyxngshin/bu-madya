<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbalongRegistration extends Model
{
    use HasFactory;

    protected $table = 'ibalong_registrations';

    protected $fillable = [
        'user_id',
        'team_name',
        'slug',
        'team_about',
        'affiliation',
        'province_id',
        'citymun_id',
        'barangay_id',
        'team_member_demographics',
        'number_of_team_members',
        'onsite_commitment',
        'does_not_automatically_apply_clause',
        'selection_on_icp',
        'media_consent',
        'data_privacy_consent',
        'status',
        'account_creation_status',
    ];

    /**
     * CORE RELATIONSHIPS
     */

    // A team has many members (Team Leader, Team Members)
    public function members()
    {
        return $this->hasMany(IbalongTeamMember::class, 'team_id');
    }

    // Links to the generated user account upon approval
    public function user()
    {
        return $this->belongsTo(IbalongUser::class, 'user_id');
    }

    /**
     * PIVOT RELATIONSHIPS (Many-to-Many)
     */

    // Team's collective skills
    public function skills()
    {
        return $this->belongsToMany(IbalongSkill::class, 'ibalong_team_skills', 'team_id', 'skill_id')
                    ->withTimestamps();
    }

    // Target Community Areas of Interest
    public function communityAreas()
    {
        return $this->belongsToMany(IbalongCommunityArea::class, 'ibalong_team_community_areas', 'team_id', 'community_area_id')
                    ->withTimestamps();
    }

    // Previous Cohort Experiences
    public function experiences()
    {
        return $this->belongsToMany(IbalongExperience::class, 'ibalong_team_experiences', 'team_id', 'experience_id')
                    ->withTimestamps();
    }

    // Commitment to Online Activities
    public function onlineActivities()
    {
        return $this->belongsToMany(IbalongOnlineActivity::class, 'ibalong_team_online_participations', 'team_id', 'online_activity_id')
                    ->withTimestamps();
    }
}