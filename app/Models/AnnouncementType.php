<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementType extends Model
{
    protected $fillable = ['name', 'slug', 'color_theme', 'icon_svg'];

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
