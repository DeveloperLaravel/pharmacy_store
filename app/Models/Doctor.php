<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'specialization',
        'license_number',
        'is_active',
        'department_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function radiologies()
    {
        return $this->hasMany(Radiology::class);
    }
}
