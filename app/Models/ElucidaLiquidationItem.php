<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ElucidaLiquidationItem extends Model {
    protected $guarded = [];
    protected $casts = ['date_incurred' => 'date'];

    public function liquidation() {
        return $this->belongsTo(ElucidaLiquidation::class, 'liquidation_id');
    }
}
