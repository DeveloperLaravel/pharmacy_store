<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radiology extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'visit_id', 'scan_type', 'result', 'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
