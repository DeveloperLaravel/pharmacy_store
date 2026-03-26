<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'medicine_id',
        'user_id',
        'type',
        'quantity',
        'reference',
        'notes',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
