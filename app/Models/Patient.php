<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'national_id', 'age', 'gender', 'phone', 'blood_type', 'address', 'balance',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function rechargeCards()
    {
        return $this->hasMany(RechargeCard::class, 'used_by');
    }

    public function bedAssignments()
    {
        return $this->hasMany(BedAssignment::class);
    }
}
