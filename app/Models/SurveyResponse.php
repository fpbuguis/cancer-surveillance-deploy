<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $primaryKey = 'surveyResponse_id';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'surveyResponse_date',
        'symptomSurvey_id',
        'response_notes',
        'treatment_completion'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function symptomSurvey()
    {
        return $this->belongsTo(SymptomSurvey::class, 'symptomSurvey_id');
    }

    public function symptomSurveys()
    {
        return $this->belongsToMany(SymptomSurvey::class, 'surveyResponses_symptomSurvey', 'surveyResponse_id', 'symptomSurvey_id');
    }
}
