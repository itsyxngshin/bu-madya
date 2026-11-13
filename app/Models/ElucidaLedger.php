<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ElucidaLedger extends Model {
    protected $guarded = [];

    // Automatically generate UUID on creation
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // Tell Laravel to use 'uuid' instead of 'id' for route binding
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function transactions() {
        return $this->hasMany(ElucidaTransaction::class, 'ledger_id');
    }

    public function getEndingBalanceAttribute() {
        $inflows = $this->transactions()->where('type', 'inflow')->sum('amount');
        $outflows = $this->transactions()->where('type', 'outflow')->sum('amount');
        return ($this->beginning_bank + $this->beginning_cash) + $inflows - $outflows;
    }

    public function signatories() {
        return $this->morphMany(ElucidaSignatory::class, 'signable')->orderBy('order_sequence');
    }
}
