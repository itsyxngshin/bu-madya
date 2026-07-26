<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IbalongResourceGroup extends Model
{
    protected $fillable = ['title', 'description', 'available_at', 'is_visible'];
    protected $casts = ['available_at' => 'datetime', 'is_visible' => 'boolean'];

    public function files()
    {
        return $this->hasMany(IbalongResourceFile::class, 'group_id');
    }
}