<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurses extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'gender',
        'department_id',
        'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
