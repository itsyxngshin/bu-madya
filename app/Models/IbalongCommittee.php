<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbalongCommittee extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'display_order', 'is_active'];

    public function members()
    {
        return $this->hasMany(IbalongCommitteeMember::class, 'committee_id');
    }
}
