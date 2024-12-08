<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomSurvey extends Model
{
    use HasFactory;

    protected $primaryKey = 'symptomSurvey_id';

    protected $fillable = [
        'cancer_type',
        'symptom_name',
        'symptom_medicalTerm',
        'symptom_category',
        'symptom_filipino',
        'symptom_alertable',
    ];

    public function cancerType()
    {
        return $this->belongsTo(BodySite::class, 'cancer_type', 'body_site_id');
    }

    public function category()
    {
        return $this->belongsTo(SymptomCategory::class, 'symptom_category', 'symptomCategory_id');
    }

    public function surveyResponses()
    {
        return $this->belongsToMany(SurveyResponse::class, 'surveyResponses_symptomSurvey', 'symptomSurvey_id', 'surveyResponse_id');
    }

}
