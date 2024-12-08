<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = ['user_id'];

    protected $primaryKey = 'patient_id';

    public function onboards()
    {
        return $this->hasMany(Onboard::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function diseases()
    {
        return $this->hasMany(Disease::class, 'patient_id', 'patient_id');
    }

    // treatment

    public function rxtypes()
    {
        return $this->hasMany(RxType::class, 'patient_id');
    }

    public function surgeries()
    {
        return $this->hasMany(Surgery::class, 'patient_id');
    }

    public function chemotherapies()
    {
        return $this->hasMany(Chemotherapy::class, 'patient_id');
    }

    public function immunotherapies()
    {
        return $this->hasMany(Immunotherapy::class, 'patient_id');
    }

    public function hormonals()
    {
        return $this->hasMany(Hormonal::class, 'patient_id');
    }

    public function radiotherapies()
    {
        return $this->hasMany(Radiotherapy::class, 'patient_id');
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'patient_id');
    }

    // checkup

    public function checkups()
    {
        return $this->hasMany(Checkup::class, 'patient_id');
    }

    public function checkupSchedules()
    {
        return $this->hasMany(CheckupSchedule::class, 'patient_id');
    }

    // lab submitted

    public function labsDownloads()
    {
        return $this->hasMany(LabsDownload::class, 'patient_id');
    }

    public function labsSubmitted()
    {
        return $this->hasMany(LabsSubmitted::class, 'patient_id');
    }

    // survey response

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class, 'patient_id');
    }

    // notification
    public function receivedNotifications()
    {
        return $this->hasMany(NotificationLog::class, 'notification_reciever', 'patient_id');
    }

}
