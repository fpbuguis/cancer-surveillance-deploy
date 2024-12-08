<?php

namespace App\Http\Controllers;

use App\Models\SymptomSurvey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SymptomSurveyController extends Controller
{
    public function getAllSymptoms() {
        // Retrieve all symptoms with their related cancer type and category
        $symptoms = SymptomSurvey::with(['cancerType', 'category'])->get();
        return response()->json($symptoms, 200); // Return a JSON response with HTTP 200 status
    }

    public function getSymptomsByPrimarySite($bodySiteId)
    {
        $validator = Validator::make(['bodySiteId' => $bodySiteId], [
            'bodySiteId' => 'required|integer|exists:body_sites,body_site_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid or non-existent body site ID.'], 422);
        }

        $symptoms = SymptomSurvey::with(['cancerType', 'category'])
            ->where('cancer_type', $bodySiteId)
            ->get();

        if ($symptoms->isEmpty()) {
            return response()->json(['message' => 'No symptoms found for the specified body site.'], 404);
        }

        return response()->json($symptoms, 200);
    }

    public function getSymptomsByCategory($symptomCategoryId)
    {
        // Retrieve symptoms related to the specific symptom category
        $symptoms = SymptomSurvey::with(['cancerType', 'category'])
            ->where('symptom_category', $symptomCategoryId)
            ->get();

        // Check if symptoms exist for the given category
        if ($symptoms->isEmpty()) {
            return response()->json([
                'message' => 'No symptoms found for the specified symptom category.',
            ], 404);
        }

        return response()->json($symptoms, 200);
    }

    public function createSymptomsReportRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,patient_id',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'surveyResponse_date' => 'required|date',
            'symptom_surveys' => 'required|array|min:1',
            'symptom_surveys.*' => 'exists:symptom_surveys,symptomSurvey_id',
            'response_notes' => 'nullable|string',
            'treatment_completion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the survey response record
        $surveyResponse = SurveyResponse::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'surveyResponse_date' => $request->surveyResponse_date,
            'response_notes' => $request->response_notes,
            'treatment_completion' => $request->treatment_completion,
        ]);

        // Attach symptom surveys using the pivot table
        $surveyResponse->symptomSurveys()->attach($request->symptom_surveys);

        return response()->json([
            'message' => 'Survey response record created successfully.',
            'surveyResponse_id' => $surveyResponse->surveyResponse_id,
        ], 201);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'surveyResponse_date' => 'required|date',
            'symptom_surveys' => 'required|array',
        ]);

        try {
            // Process your data here
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
