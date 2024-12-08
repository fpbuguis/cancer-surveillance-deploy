<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $primaryKey = 'doctor_id';

    protected $fillable = [
        'user_id',
        'doctor_hospital',
        'doctor_department',
        'doctor_specialty',
        'doctor_eSignature',
        'doctor_licenseNumber',
        'doctor_licenseExpiry',
        // 'doctor_schedule'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Make sure 'user_id' is specified
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'doctor_hospital', 'hospital_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'doctor_department', 'department_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'doctor_specialty', 'specialty_id');
    }

    // lab submitted

    public function labsSubmitted()
    {
        return $this->hasMany(LabsSubmitted::class, 'doctor_id');
    }

    // survey response
    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class, 'doctor_id');
    }

    // notification
    public function sentNotifications()
    {
        return $this->hasMany(NotificationLog::class, 'notification_sender', 'doctor_id');
    }

}
