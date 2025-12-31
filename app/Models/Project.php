<?php

namespace App\Models;

use App\Models\Scopes\ActiveYearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProjectSdg;
use App\Models\ProjectCategory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category', 'status', 'implementation_date',
        'location', 'beneficiaries', 'description', 'cover_img', 'project_category_id',
        'objectives', 'impact_stats', 'partners_list', 'sdg_ids', 'academic_year_id'
    ];

    // Automatically convert JSON from DB into PHP Arrays
    protected $casts = [
        'implementation_date' => 'date',
        'impact_stats' => 'array',
        'partners_list' => 'array',
    ];

    // Apply the Active Year Scope automatically to show only current projects
    protected static function booted()
    {
        static::addGlobalScope(new ActiveYearScope);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function projectHead()
    {
        return $this->belongsTo(User::class, 'project_head_id');
    }

    // Helper to display the proponent
    public function getProponentLabelAttribute()
    {
        if ($this->committee) {
            return $this->committee->name;
        }
        return $this->proponent_text;
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function objectives()
    {
        return $this->hasMany(ProjectObjective::class);
    }

    public function sdgs()
    {
        // Uses the pivot table 'project_sdg'
        return $this->belongsToMany(Sdg::class, 'project_sdgs');
    }
    
    public function projectLinkages()
    {
        return $this->hasMany(LinkageProject::class);
    }

    // Helper to get a clean list of names/roles for the UI
    public function getPartnersListAttribute()
    {
        return $this->projectLinkages->map(function ($row) {
            return [
                'name' => $row->linkage_id ? $row->linkage->name : $row->manual_name,
                'role' => $row->role,
                'is_official' => !is_null($row->linkage_id),
            ];
        });
    }
    public function partners()
    {
        // You can name this 'linkages' or 'partners'
        return $this->belongsToMany(Linkage::class, 'linkage_project')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function proponents()
    {
        return $this->hasMany(ProjectProponent::class);
    }

    
    // Helper to get the main lead name easily
    public function getLeadProponentAttribute(){
        return $this->proponents->first()?->name ?? 'N/A';
    }

    public function linkages(){
        return $this->belongsToMany(Linkage::class, 'linkage_projects')
                    ->withPivot('role') // We want to access the 'role' column
                    ->withTimestamps();
    }

    public function galleries()
    {
        return $this->hasMany(ProjectGallery::class);
    }
}
