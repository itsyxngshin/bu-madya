<?php

namespace App\Models;
use App\Models\Scopes\ActiveYearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'order' // We added this for sorting
    ];

    /**
     * Get all assignments (history of people who held this position).
     */
    public function assignments()
    {
        return $this->hasMany(DirectorAssignment::class);
    }

    /**
     * Helper to get the current person holding this position.
     * (Assumes you have a scope or logic for 'current' academic year)
     */
    public function currentAssignment()
    {
        return $this->hasOne(DirectorAssignment::class)->latestOfMany();
    }
   
}
