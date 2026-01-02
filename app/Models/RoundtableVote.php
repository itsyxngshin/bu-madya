<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundtableVote extends Model
{
    protected $fillable = [
        'user_id',
        'roundtable_reply_id',
        'vote',
    ];

    public function reply()
    {
        return $this->hasMany(RoundtableReply::class);
    }
}
