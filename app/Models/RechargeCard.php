<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'amount', 'is_used', 'used_by', 'used_at',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'used_by');
    }
}
