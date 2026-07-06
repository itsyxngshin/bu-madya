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

    public function members()
    {
        return $this->hasMany(IbalongTeamMember::class, 'team_id');
    }

    public function user()
    {
        return $this->belongsTo(IbalongUser::class, 'user_id');
    }

    public function communityAreas()
    {
        return $this->belongsToMany(IbalongCommunityArea::class, 'ibalong_team_community_areas', 'team_id', 'community_area_id')
                    ->withTimestamps();
    }

    public function experiences()
    {
        return $this->belongsToMany(IbalongExperience::class, 'ibalong_team_experiences', 'team_id', 'experience_id')
                    ->withTimestamps();
    }

    public function onlineActivities()
    {
        return $this->belongsToMany(IbalongOnlineActivity::class, 'ibalong_team_online_participations', 'team_id', 'online_activity_id')
                    ->withTimestamps();
    }
}