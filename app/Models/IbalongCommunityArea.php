<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IbalongCommunityArea extends Model
{
    protected $table = 'ibalong_community_areas';
    protected $fillable = ['name', 'description'];
}