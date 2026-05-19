<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_application_id',
        'title',
        'description',
        'target_month',
    ];

    public function application()
    {
        return $this->belongsTo(AccreditationApplication::class, 'accreditation_application_id');
    }
}