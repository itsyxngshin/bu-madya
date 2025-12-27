<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalObjective extends Model
{
    use HasFactory;

    protected $fillable = ['proposal_id', 'objective'];
}