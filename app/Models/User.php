<?php

namespace App\Models;
use App\Models\Profile;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // Helper to get role easily (optional)
    public function isAdmin() 
    {
        return $this->role === 'admin' || $this->role === 'director';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function assignments()
    {
        return $this->hasMany(DirectorAssignment::class);
    }

    // Get ONLY the current active assignment
    public function currentAssignment()
    {
        return $this->hasOne(DirectorAssignment::class)
                    ->where('academic_year_id', AcademicYear::current()->id);
    }

    // Helper: "Is this user the current Director-General?"
    public function isDirectorGeneral()
    {
        return $this->currentAssignment && 
               $this->currentAssignment->committee_id === null; // The logic: No committee = DG
    }
    
    // Helper: "Is this user a Committee Director?"
    public function isCommitteeDirector()
    {
        return $this->currentAssignment && 
               $this->currentAssignment->committee_id !== null;
    }

    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }

    public function portfolioSet() {
        // Since PortfolioSet belongs to a Profile, and User belongs to a Profile,
        // we access it via the Profile relationship.
        return $this->hasOneThrough(
            PortfolioSet::class, 
            Profile::class, 
            'id', // Foreign key on profiles table (user.profile_id is actually on users table, so we might need a different approach if schema is strict)
            'profile_id', // Foreign key on portfolio_sets table
            'profile_id', // Local key on users table
            'id' // Local key on profiles table
        );
    }

    public function directorAssignment()
    {
        // A User "has one" current assignment
        return $this->hasOne(DirectorAssignment::class); 
    }
    
    /**
     * Optional: If you want to access ALL past assignments
     */
    public function directorAssignments()
    {
        return $this->hasMany(DirectorAssignment::class);
    }

    public function committeeMember()
    {
        // A user might be a member of a committee
        return $this->hasOne(CommitteeMember::class)->latest(); // Gets the latest/current assignment
    }

    public function committeeMembers()
    {
        // A user has many committee memberships over time (history)
        return $this->hasMany(CommitteeMember::class);
    }

    public function proponents()
    {
        return $this->hasMany(ProjectProponent::class);
    }

    public function roundtable_topic()
    {
        return $this->hasMany(RoundtableTopic::class);
    }

    public function roundtable_replies()
    {
        return $this->hasMany(RoundtableReply::class);
    }
}
