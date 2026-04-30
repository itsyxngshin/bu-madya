<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'name',
        'color',
        'logo_path',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
