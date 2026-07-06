<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IbalongSkill extends Model
{
    protected $table = 'ibalong_skills';
    protected $fillable = ['name', 'description'];
}