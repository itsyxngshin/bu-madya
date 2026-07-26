<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IbalongResourceFile extends Model
{
    protected $fillable = ['group_id', 'file_name', 'file_path', 'file_size'];

    public function group()
    {
        return $this->belongsTo(IbalongResourceGroup::class, 'group_id');
    }
}