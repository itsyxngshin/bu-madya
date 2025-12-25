<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioSet extends Model
{
    protected $fillable = ['profile_id', 'portfolio_id'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function workPortfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
