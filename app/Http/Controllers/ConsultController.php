<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Consult;
use App\Models\Patient;
use App\Models\User;
use App\Models\Disease;

use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class ConsultController extends Controller
{
    //
    public function createConsult(Request $request)
    {
        $patient = Patient::where('patient_id', $request['patient_id'])->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $diseaseByPatient = Disease::where('patient_id', $patient->patient_id)->first();

        if (!$diseaseByPatient) {
            return response()->json(['error' => 'Disease Profile not found'], 404);
        }

        $diseaseStatus_id = $diseaseByPatient->disease_status;

        $validator = Validator::make($request->all(), [
            // 'patient_id' => 'required|exists:patients,patient_id',
            'subjective' => 'required',
            'objective' => 'required',
            'surveillance_workup' => 'required',
            'assessment' => 'required',
            'plan' => 'required',
            'patient_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $consult = Consult::create([
            'patient_id' => $patient->patient_id,
            'consult_subjective' => $request['subjective'],
            'consult_objective' => $request['objective'],
            'consult_surveillance_workup' => $request['surveillance_workup'],
            'consult_assessment' => $request['assessment'],
            'consult_plan' => $request['plan'],
            'consult_disease_status' => $diseaseStatus_id,
            'consult_patient_status' => $request['patient_status']
        ]);

        return Inertia::render('ConsultPage', [
            'message' => 'Consult record created successfully',
            'consult' => $consult->consult_id
        ]);
    }
}
