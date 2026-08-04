<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbalongEvaluationResponse extends Model
{
    protected $guarded = [];

    public function evaluation()
    {
        return $this->belongsTo(IbalongEvaluation::class, 'evaluation_id');
    }

    public function team()
    {
        return $this->belongsTo(IbalongRegistration::class, 'team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(IbalongEvaluationAnswer::class, 'response_id');
    }
}
