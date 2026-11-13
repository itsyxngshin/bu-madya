<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ElucidaTransaction extends Model {
    protected $guarded = [];
    protected $casts = ['transaction_date' => 'date'];

    public function ledger() {
        return $this->belongsTo(ElucidaLedger::class, 'ledger_id');
    }

    public function liquidation() {
        return $this->hasOne(ElucidaLiquidation::class, 'transaction_id');
    }

    public function sales() {
        return $this->hasMany(ElucidaSalesRegister::class, 'transaction_id');
    }
}
