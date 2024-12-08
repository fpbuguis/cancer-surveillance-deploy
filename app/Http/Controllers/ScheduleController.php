<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CheckupSchedule;
use App\Models\CheckupStatus;
use App\Models\User;

use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;


class ScheduleController extends Controller
{
    public function getLatestScheduleByPatient($patientId)
    {
        $latestSchedule = CheckupSchedule::where('patient_id', $patientId)
            ->orderBy('checkupSched_id', 'desc')
            ->first();

        if (!$latestSchedule) {
            return response()->json(['message' => 'No schedule found for the specified patient'], 404);
        }

        return response()->json($latestSchedule);
    }

    public function updateSchedule(Request $request, $patientId)
    {
        // Find the latest schedule for the patient
        $latestSchedule = CheckupSchedule::where('patient_id', $patientId)
            ->orderBy('checkupSched_id', 'desc')
            ->first();

        if (!$latestSchedule) {
            return response()->json(['message' => 'No schedule found for the specified patient'], 404);
        }

        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'checkupStatus_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the status
        $latestSchedule->checkupStatus_id = $request->input('checkupStatus_id');
        $latestSchedule->save();

        return response()->json(['message' => 'Schedule updated successfully', 'schedule' => $latestSchedule], 200);
    }

    public function createScheduleCheckup(Request $request)
    {
        // dd($request);
        $patient = Patient::where('patient_id', $request['patient_id'])->first();
        $doctor = Doctor::where('doctor_id', $request['doctor_id'])->first();
        $checkupStatus = CheckupStatus::where('notifStatus_name', $request['checkup_status'])->first();

        // dd($patient);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        if ($checkupStatus) {
            $checkupStatusId = $checkupStatus->checkupStatus_id;
        } else {
            return response()->json(['message' => 'Checkup status not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'patient_id' => 'required|exists:patients,patient_id',
            'lastname' => 'required',
            'email' => 'required',
            'checkupRequest_date' => 'required',
            // 'checkupConfirmed_date' => 'required',
            'checkup_startTime' => 'required',
            'checkup_endTime' => 'required',
            'checkup_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $schedConsult = CheckupSchedule::create([
            'patient_id' => $patient->patient_id,
            'doctor_id' =>  $doctor->doctor_id,
            'checkupRequest_date' => $request['checkupRequest_date'],
            'checkupConfirmed_date' => $request['checkupConfirmed_date'],
            'checkup_startTime' => $request['checkup_startTime'],
            'checkup_endTime' => $request['checkup_endTime'],
            'checkupStatus_id' => $checkupStatusId,
        ]);

        return Inertia::render('SchedConsultPage', [
            'message' => 'Consult record created successfully',
            'schedConsult' => $schedConsult->checkupSched_id
        ]);
        // return response()->json(['schedConsult' => $schedConsult], 200);
    }
}
