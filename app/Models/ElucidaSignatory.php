<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ElucidaSignatory extends Model {
    protected $guarded = [];
    protected $casts = [
        'has_signed' => 'boolean',
        'signed_at' => 'datetime'
    ];

    public function signable() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
