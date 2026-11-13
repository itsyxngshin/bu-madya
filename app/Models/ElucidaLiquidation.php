<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ElucidaLiquidation extends Model {
    protected $guarded = [];
    protected $casts = [
        'implementation_start' => 'date',
        'implementation_end' => 'date',
        'report_date' => 'date'
    ];

    public function transaction() {
        return $this->belongsTo(ElucidaTransaction::class, 'transaction_id');
    }

    public function accountableOfficer() {
        return $this->belongsTo(User::class, 'accountable_officer_id');
    }

    public function items() {
        return $this->hasMany(ElucidaLiquidationItem::class, 'liquidation_id');
    }

    public function signatories() {
        return $this->morphMany(ElucidaSignatory::class, 'signable')->orderBy('hierarchy_order');
    }
}
