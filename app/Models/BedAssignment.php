<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'room_id', 'start_date', 'end_date'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
