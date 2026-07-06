<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IbalongRole extends Model
{
    protected $table = 'ibalong_roles';
    protected $fillable = ['name', 'description'];
    
    public function users()
    {
        return $this->hasMany(IbalongUser::class, 'role_id');
    }
}