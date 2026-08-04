<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IbalongEvaluation extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($evaluation) {
            if (empty($evaluation->slug)) {
                $evaluation->slug = Str::slug($evaluation->title);
            }
        });
    }

    public function creator()
    {
        // Adjust User::class to your admin/auth model if different
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(IbalongEvaluationQuestion::class, 'evaluation_id');
    }

    public function responses()
    {
        return $this->hasMany(IbalongEvaluationResponse::class, 'evaluation_id');
    }
}