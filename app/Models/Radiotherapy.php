<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radiotherapy extends Model
{
    use HasFactory;

    protected $table = 'radiotherapies';
    protected $primaryKey = 'radRx_id';

    protected $fillable = [
        'patient_id',
        'radRx_type',
        'radRx_initial_date',
        'radRx_last_date',
        'radRx_dose',
        'radRx_bodySite',
        'radRx_status',
        'radRx_isCompleted',
        'radRx_facility',
        'radRx_doctor',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'radRx_doctor', 'doctor_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'radRx_facility', 'hospital_id');
    }

    public function type()
    {
        return $this->belongsTo(Rad_Detail::class, 'radRx_type', 'radDetails_id');
    }
}
