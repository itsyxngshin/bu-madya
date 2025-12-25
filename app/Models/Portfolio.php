<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['designation', 'description', 'duration', 'status', 'place'];

    public function portfolioSet()
    {
        return $this->hasMany(PortfolioSet::class);
    }
}
