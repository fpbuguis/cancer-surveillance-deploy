<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Checkup;
use App\Models\Patient;
use App\Models\User;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\CheckupSchedule;
use Carbon\Carbon;


use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class CheckupController extends Controller
{

    public function fetchCheckup($id) {
        $checkup = Checkup::where('patient_id', $id)->orderBy('checkup_id', 'desc')->first();
        return response()->json(['checkup' => $checkup], 200);
    }

    //
    // public function createCheckup(Request $request)
    // {
    //     $patient = Patient::where('patient_id', $request['patient_id'])->first();
    //     $doctor = Doctor::where('doctor_id', $request['doctor_id'])->first();

    //     if (!$patient) {
    //         return response()->json(['error' => 'Patient not found'], 404);
    //     }

    //     if (!$doctor) {
    //         return response()->json(['error' => 'Doctor not found'], 404);
    //     }

    //     // Fetch the most recent checkup schedule for the patient and doctor
    //     $checkupSchedule = CheckupSchedule::where('doctor_id', $doctor->doctor_id)
    //         ->where('patient_id', $patient->patient_id)
    //         ->orderBy('created_at', 'desc')
    //         ->first();

    //     if (!$checkupSchedule) {
    //         return response()->json(['error' => 'No checkup schedule found for this doctor and patient'], 404);
    //     }

    //     $diseaseByPatient = Disease::where('patient_id', $patient->patient_id)->first();

    //     if (!$diseaseByPatient) {
    //         return response()->json(['error' => 'Disease Profile not found'], 404);
    //     }

    //     $diseaseStatus_id = $diseaseByPatient->disease_status;

    //     $validator = Validator::make($request->all(), [
    //         // 'patient_id' => 'required|exists:patients,patient_id',
    //         'subjective' => 'required',
    //         'objective' => 'required',
    //         'surveillance_workup' => 'required',
    //         'assessment' => 'required',
    //         'plan' => 'required',
    //         'patient_status' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $checkup = Checkup::create([
    //         'patient_id' => $patient->patient_id,
    //         'doctor_id' => $doctor->doctor_id,
    //         'checkup_date' => $request->input('checkup_date', now()),
    //         'checkup_subjective' => $request['subjective'],
    //         'checkup_objective' => $request['objective'],
    //         'checkup_survWorkup' => $request['surveillance_workup'],
    //         'checkup_assessment' => $request['assessment'],
    //         'checkup_plan' => $request['plan'],
    //         // 'consult_disease_status' => $diseaseStatus_id,
    //         'checkup_patientStatus' => $request['patient_status'],
    //         'checkup_schedule' => $checkupSchedule->checkupSched_id,

    //     ]);

    //     return Inertia::render('ConsultPage', [
    //         'message' => 'Consult record created successfully',
    //         'consult' => $checkup->checkup_id
    //     ]);
    // }

    public function createCheckup(Request $request)
    {
        $patient = Patient::where('patient_id', $request['patient_id'])->first();
        $doctor = Doctor::where('doctor_id', $request['doctor_id'])->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Fetch the most recent checkup schedule for the patient and doctor
        $checkupSchedule = CheckupSchedule::where('doctor_id', $doctor->doctor_id)
            ->where('patient_id', $patient->patient_id)
            ->orderBy('checkupSched_id', 'desc')
            ->first();

        if (!$checkupSchedule) {
            return response()->json(['error' => 'No checkup schedule found for this doctor and patient'], 404);
        }

        $diseaseByPatient = Disease::where('patient_id', $patient->patient_id)->first();

        if (!$diseaseByPatient) {
            return response()->json(['error' => 'Disease Profile not found'], 404);
        }

        $diseaseStatus_id = $diseaseByPatient->disease_status;

        $validator = Validator::make($request->all(), [
            'subjective' => 'required',
            'objective' => 'required',
            'surveillance_workup' => 'required',
            'assessment' => 'required',
            'plan' => 'required',
            'patient_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$checkupSchedule->checkupConfirmed_date) {
            return response()->json(['error' => 'No checkupConfirmed_date found in the schedule'], 404);
        }

        $checkupConfirmedDate = Carbon::parse($checkupSchedule->checkupConfirmed_date);
        $currentDate = Carbon::now();

        $checkupStatusId = $checkupConfirmedDate->isToday() || $checkupConfirmedDate->isFuture() ? 3 : 4;

        $checkupSchedule->update([
            'checkupStatus_id' => $checkupStatusId,
        ]);

        // Create the new checkup record
        $checkup = Checkup::create([
            'patient_id' => $patient->patient_id,
            'doctor_id' => $doctor->doctor_id,
            'checkup_date' => $request->input('checkup_date', now()),
            'checkup_subjective' => $request['subjective'],
            'checkup_objective' => $request['objective'],
            'checkup_survWorkup' => $request['surveillance_workup'],
            'checkup_assessment' => $request['assessment'],
            'checkup_plan' => $request['plan'],
            'checkup_patientStatus' => $request['patient_status'],
            'checkup_schedule' => $checkupSchedule->checkupSched_id,
        ]);

        return Inertia::render('ConsultPage', [
            'message' => 'Consult record created successfully',
            'consult' => $checkup->checkup_id
        ]);
    }

}
