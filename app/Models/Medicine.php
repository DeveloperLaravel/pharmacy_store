<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'scientific_name',
        'sku',
        'barcode',
        'dosage_form',
        'strength',
        'unit',
        'price',
        'cost',
        'quantity',
        'reorder_level',
        'expires_at',
        'requires_prescription',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'expires_at' => 'date',
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
