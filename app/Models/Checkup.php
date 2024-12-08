<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkup extends Model
{
    use HasFactory;

    // Table name (optional if matches naming conventions)
    protected $table = 'checkups';

    // Primary key (optional if matches naming conventions)
    protected $primaryKey = 'checkup_id';

    // Fillable fields
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'checkup_date',
        'checkup_subjective',
        'checkup_objective',
        'checkup_assessment',
        'checkup_plan',
        'checkup_survWorkup',
        'checkup_patientStatus',
        'checkup_schedule',
    ];

    // Relationships

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patientStatus()
    {
        return $this->belongsTo(DiseaseStatus::class, 'checkup_patientStatus', 'dxStatus_id');
    }

    public function checkupSchedules()
    {
        return $this->belongsTo(CheckupSchedule::class, 'checkup_schedule', 'checkupSched_id');
    }

    // In Checkup model
    // public function checkupSchedules()
    // {
    //     return $this->hasMany(CheckupSchedule::class, 'checkup_schedule');
    // }

}
