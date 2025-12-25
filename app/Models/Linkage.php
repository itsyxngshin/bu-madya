<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Linkage extends Model
{
    use HasFactory;
    protected $fillable = [
        // 1. Identity
        'name', 
        'slug', 
        'acronym',

        // 2. Foreign Keys (The new lookup connections)
        'linkage_type_id',      // Replaces 'type'
        'linkage_status_id',    // Replaces 'status'
        'agreement_level_id',   // Replaces 'agreement_level'

        // 3. Dates
        'established_at', 
        'expires_at',

        // 4. Branding & Details
        'logo_path', 
        'cover_img_path',
        'description',

        // 5. Contact Info
        'contact_person', 
        'email', 
        'website', 
        'address'
    ];

    // Ensure dates are Carbon instances so you can format them ($linkage->established_at->format('M Y'))
    protected $casts = [
        'established_at' => 'date',
        'expires_at' => 'date',
    ];

    public function type(){
        return $this->belongsTo(LinkageType::class, 'linkage_type_id');
    }

    public function status(){
        return $this->belongsTo(LinkageStatus::class, 'linkage_status_id'); 
    } 

    public function sdgs()
    {
        return $this->belongsToMany(
            Sdg::class,      // 1. The Related Model
            'linkage_sdgs',   // 2. The Pivot Table Name (Force Singular)
            'linkage_id',    // 3. The Local Key (Linkage)
            'sdg_id'         // 4. The Foreign Key (Force 'sdg_id', NOT 's_d_g_id')
        );
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'linkage_projects')
                    ->withPivot('role') // Important: lets you access the 'role' column
                    ->withTimestamps();
    }

    /**
     * The history of activities with this partner.
     */
    public function activities()
    {
        return $this->hasMany(LinkageActivity::class);
    }

    public function agreementLevel(){
        return $this->belongsTo(AgreementLevel::class, 'agreement_level_id');
    }
}
