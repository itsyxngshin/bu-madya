<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkageProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_name',
        'contact_person',
        'email',
        'phone',
        'partnership_type',
        'title',
        'message',
        'file_path',
        'status',
    ];
}