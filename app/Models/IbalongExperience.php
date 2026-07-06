<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IbalongExperience extends Model
{
    protected $table = 'ibalong_experiences';
    protected $fillable = ['name', 'description'];
}