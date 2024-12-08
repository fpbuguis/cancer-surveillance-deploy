<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckupSchedule extends Model
{
    use HasFactory;

    // Table name (optional if matches naming conventions)
    protected $table = 'checkup_schedules';

    // Primary key (optional if matches naming conventions)
    protected $primaryKey = 'checkupSched_id';

    // Fillable fields
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'checkupRequest_date',
        'checkupConfirmed_date',
        'checkup_startTime',
        'checkup_endTime',
        'checkupStatus_id',
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

    public function status()
    {
        return $this->belongsTo(CheckupStatus::class, 'checkupStatus_id', 'checkupStatus_id');
    }

    public function checkups()
    {
        return $this->hasMany(Checkup::class, 'checkup_schedule', 'checkupSched_id');
    }
}
