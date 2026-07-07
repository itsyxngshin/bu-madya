<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbalongCommitteeMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_id', 'name', 'affiliation', 'designation', 'role', 'photo_path', 'display_order', 'is_active'
    ];

    public function committee()
    {
        return $this->belongsTo(IbalongCommittee::class, 'committee_id');
    }
}
