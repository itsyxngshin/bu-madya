<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug', 
        'created_by',
        'description',
        'type',
        'is_active',
        'project_id'
    ];

    /**
     * KEY CHANGE: Tell Laravel to bind routes using the 'slug' column.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Optional: Boot method to auto-generate slug on creation if missing
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($evaluation) {
            if (empty($evaluation->slug)) {
                $evaluation->slug = Str::slug($evaluation->title);
            }
        });
    }

    public function questions()
    {
        return $this->hasMany(EvaluationQuestion::class);
    }

    public function responses()
    {
        return $this->hasMany(EvaluationResponse::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }    
}
