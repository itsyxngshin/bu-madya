<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class IbalongUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'ibalong_users';

    protected $fillable = [
        'role_id',
        'name',
        'slug',
        'email',
        'password',
        'avatar_path',
        'mobile_number',
        'designation',
        'bio',
        'github_url',
        'linkedin_url',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(IbalongRole::class, 'role_id');
    }

    public function registration()
    {
        return $this->hasOne(IbalongRegistration::class, 'user_id');
    }
}