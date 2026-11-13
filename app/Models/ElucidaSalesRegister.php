<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ElucidaSalesRegister extends Model {
    protected $guarded = [];
    protected $casts = ['sale_date' => 'date'];

    public function transaction() {
        return $this->belongsTo(ElucidaTransaction::class, 'transaction_id');
    }
}
