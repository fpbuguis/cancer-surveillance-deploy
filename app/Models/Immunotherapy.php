<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immunotherapy extends Model
{
    use HasFactory;

    protected $table = 'immunotherapies';
    protected $primaryKey = 'immunoRx_id';

    protected $fillable = [
        'patient_id',
        'immunoRx_drugs',
        'immunoRx_initial_date',
        'immunoRx_end_date',
        'immunoRx_status',
        'immunoRx_notes',
        'immunoRx_isCompleted',
        'immunoRx_facility',
        'immunoRx_doctor',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'immunoRx_doctor', 'doctor_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'immunoRx_facility', 'hospital_id');
    }
}
