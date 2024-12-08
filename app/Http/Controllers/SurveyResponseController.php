<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Models\LabsSubmitted;
use App\Models\Patient;
use App\Models\Onboard;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Laravel\Jetstream\Jetstream;
use Inertia\Inertia;


class SurveyResponseController extends Controller
{

    public function getSurveyResponsesByPatient($patientId)
    {
        $survey_response = SurveyResponse::where('patient_id', $patientId)
            ->with([
                'patient.user',
                'symptomSurveys'
            ])
            ->orderBy('surveyResponse_id', 'desc')
            ->first();

        return response()->json($survey_response);
    }

}
