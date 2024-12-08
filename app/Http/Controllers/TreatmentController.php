<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Surgery;
use App\Models\User;
use App\Models\Rad_Detail;
use App\Models\Radiotherapy;
use App\Models\Chemoprotocol;
use App\Models\Chemotherapy;
use App\Models\Immunotherapy;
use App\Models\Hormonal;
use App\Models\Rxtype;

use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class TreatmentController extends Controller
{
    //GET OPTIONS
    public function getRadDetails()
    {
        return Rad_Detail::all();
    }

    public function getChemoProtocols()
    {
        return Chemoprotocol::all();
    }

    //CREATE RECORDS

    public function createTreatmentRecord(Request $request)
    {
        $user = User::where('lastname', $request->lastname)
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $patient = Patient::where('user_id', $user->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'treatment_purpose' => 'required',
            'primary_type' => 'required',
            'primary_treatment' => 'required',
            'initial_treatment_date' => 'required|date',
            'planned_additional_treatment' => 'required|array',
            'other_planned_additional_treatment' => 'required_if:planned_additional_treatment.*,Others',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create array with default false values for all treatment types
        $treatmentTypes = [
            'rxtype_surgery' => false,
            'rxtype_chemotherapy' => false,
            'rxtype_radiotherapy' => false,
            'rxtype_immunotherapy' => false,
            'rxtype_hormonaltherapy' => false,
            'rxtype_others' => false
        ];

        // Map frontend strings to database column names
        $treatmentMapping = [
            'Surgery' => 'rxtype_surgery',
            'Chemotherapy' => 'rxtype_chemotherapy',
            'Radiotherapy' => 'rxtype_radiotherapy',
            'Immunotherapy/Cryotherapy' => 'rxtype_immunotherapy',
            'Hormonal Therapy' => 'rxtype_hormonaltherapy',
            'Others' => 'rxtype_others'
        ];

        // Set boolean true for selected treatments
        foreach ($request['planned_additional_treatment'] as $treatment) {
            if (isset($treatmentMapping[$treatment])) {
                $treatmentTypes[$treatmentMapping[$treatment]] = true;
            }
        }

        // Create Rxtype record with all fields
        $rxtypeData = array_merge([
            'patient_id' => $patient->patient_id
        ], $treatmentTypes);

        // Only add rxtype_other_treatment if Others is selected
        if ($treatmentTypes['rxtype_others']) {
            $rxtypeData['rxtype_other_treatment'] = $request['other_planned_additional_treatment'];
        }

        $treatment_plan = Rxtype::create($rxtypeData);

        $treatment = Treatment::create([
            'patient_id' => $patient->patient_id,
            'treatment_primaryRxType' => $request['primary_type'],
            'treatment_primaryRxName' => $request['primary_treatment'],
            'treatment_initialRxDate' => $request['initial_treatment_date'],
            'treatment_purpose' => $request['treatment_purpose'],
            'treatment_other_purpose' => $request['other_treatment_purpose'],
            'treatment_plan' => $treatment_plan->rxtype_id,
        ]);

        return Inertia::render('TreatmentHistoryPage', [
            'message' => 'Treatment history record saved.',
            'treatment_id' => $treatment->treatment_id,
        ]);
    }

    // Create Hormonal Record
    public function createHormonalRecord(Request $request)
    {
        $user = User::where('lastname', $request->lastname)
        ->where('email', $request->email)
        ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $patient = Patient::where('user_id', $user->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'patient_id' => 'required|exists:patients,patient_id',
            'hormonal_drug' => 'required',
            'hormonal_dose' => 'required',
            'hormonal_startDate' => 'required|date',
            'hormonal_endDate' => 'required|date',
            'hormonal_status' => 'required',
            // 'hormonal_notes' => 'required',
            'hormonal_doctor' => 'required|exists:doctors,doctor_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hormonal = Hormonal::create([
            'patient_id' => $patient->patient_id,
            'hormonal_drugs' => $request['hormonal_drug'],
            'hormonal_dose' => $request['hormonal_dose'],
            'hormonal_initial_date' => $request['hormonal_startDate'],
            'hormonal_end_date' => $request['hormonal_endDate'],
            'hormonal_status' => $request['hormonal_status'],
            'hormonal_notes' => $request['hormonal_notes'],
            'hormonal_doctor' => $request['hormonal_doctor'],
        ]);

        return Inertia::render('HormonalPage', [
            'message' => 'Hormonal therapy record saved.',
            'chemo_id' => $hormonal->hormonal_id,
        ]);
    }

    // Create Immunotherapy Record
    public function createImmunotherapyRecord(Request $request)
    {
        $user = User::where('lastname', $request->lastname)
        ->where('email', $request->email)
        ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $patient = Patient::where('user_id', $user->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'patient_id' => 'required|exists:patients,patient_id',
            'immunoRx_drug' => 'required',
            'immunoRx_initialDate' => 'required|date',
            'immunoRx_endDate' => 'required|date',
            'immunoRx_status' => 'required',
            // 'immunoRx_notes' => 'required',
            'immunoRx_isCompleted' => 'required',
            'immunoRx_facility' => 'required|exists:hospitals,hospital_id',
            'immunoRx_doctor' => 'required|exists:doctors,doctor_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $immuno = Immunotherapy::create([
            'patient_id' => $patient->patient_id,
            'immunoRx_drugs' => $request['immunoRx_drug'],
            'immunoRx_initial_date' => $request['immunoRx_initialDate'],
            'immunoRx_end_date' => $request['immunoRx_endDate'],
            'immunoRx_status' => $request['immunoRx_status'],
            'immunoRx_notes' => $request['immunoRx_notes'],
            'immunoRx_isCompleted' => $request['immunoRx_isCompleted'],
            'immunoRx_facility' => $request['immunoRx_facility'],
            'immunoRx_doctor' => $request['immunoRx_doctor'],
        ]);

        return Inertia::render('ImmunotherapyPage', [
            'message' => 'Immunotherapy record saved.',
            'chemo_id' => $immuno->immunoRx_id,
        ]);
    }

    // Create Chemotherapy Record
    public function createChemotherapyRecord(Request $request)
    {
        $user = User::where('lastname', $request->lastname)
        ->where('email', $request->email)
        ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $patient = Patient::where('user_id', $user->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'patient_id' => 'required|exists:patients,patient_id',
            'chemo_type' => 'required|string|max:255',
            'chemotherapy_given' => 'required',
            'chemo_initialDate' => 'required|date',
            'chemo_endDate' => 'required|date',
            'chemo_cycleNumber' => 'required|integer',
            'chemo_status' => 'required',
            'chemo_isCompleted' => 'required',
            'chemo_facility' => 'required|exists:hospitals,hospital_id',
            'chemo_doctor' => 'required|exists:doctors,doctor_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $chemo = Chemotherapy::create([
            'patient_id' => $patient->patient_id,
            'chemo_type' => $request['chemo_type'],
            'chemo_protocol' => $request['chemotherapy_given'],
            'chemo_initial_date' => $request['chemo_initialDate'],
            'chemo_end_date' => $request['chemo_endDate'],
            'chemo_cycleNumGiven' => $request['chemo_cycleNumber'],
            'chemo_notes' => $request['chemo_notes'],
            'chemo_status' => $request['chemo_status'],
            'chemo_isCompleted' => $request['chemo_isCompleted'],
            'chemo_facility' => $request['chemo_facility'],
            'chemo_doctor' => $request['chemo_doctor'],
        ]);

        return Inertia::render('ChemotherapyPage', [
            'message' => 'Chemotherapy record saved.',
            'chemo_id' => $chemo->chemo_id,
        ]);

    }


    // Create Radiotherapy Record
    public function createRadTherapyRecord(Request $request)
    {
        $user = User::where('lastname', $request->lastname)
        ->where('email', $request->email)
        ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $patient = Patient::where('user_id', $user->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'patient_id' => 'required|exists:patients,patient_id',
            'radRx_type' => 'required',
            'radRx_initialDate' => 'required|date',
            'radRx_lastDate' => 'required|date',
            'radRx_dose' => 'required',
            'radRx_bodySite' => 'required',
            'radRx_status' => 'required',
            'radRx_isCompleted' => 'required',
            'radRx_facility' => 'required|exists:hospitals,hospital_id',
            'radRx_doctor' => 'required|exists:doctors,doctor_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rad = Radiotherapy::create([
            'patient_id' => $patient->patient_id,
            'radRx_type' => $request['radRx_type'],
            'radRx_initial_date' => $request['radRx_initialDate'],
            'radRx_last_date' => $request['radRx_lastDate'],
            'radRx_dose' => $request['radRx_dose'],
            'radRx_bodySite' => $request['radRx_bodySite'],
            'radRx_status' => $request['radRx_status'],
            'radRx_isCompleted' => $request['radRx_isCompleted'],
            'radRx_facility' => $request['radRx_facility'],
            'radRx_doctor' => $request['radRx_doctor'],
        ]);

        return Inertia::render('RadiotherapyPage', [
            'message' => 'Radiotherapy record saved.',
            'radRx_id' => $rad->radRx_id,
        ]);

    }

    // Create Surgery Record
    public function createSurgeryRecord(Request $request)
    {
        $user = User::where('lastname', $request->lastname)
                ->where('email', $request->email)
                ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $patient = Patient::where('user_id', $user->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'patient_id' => 'required|exists:patients,patient_id',
            'surgery_operation' => 'required',
            'surgery_date' => 'required|date',
            'surgery_findings' => 'required',
            'surgery_intent' => 'required',
            'surgery_surgeon' => 'required|exists:doctors,doctor_id',
            'surgery_hospital' => 'required|exists:hospitals,hospital_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $surgery = Surgery::create([
            'patient_id' => $patient->patient_id,
            'surgery_operation' => $request['surgery_operation'],
            'surgery_date' => $request['surgery_date'],
            'surgery_findings' => $request['surgery_findings'],
            'surgery_intent' => $request['surgery_intent'],
            'surgery_otherIntent' => $request['surgery_otherIntent'],
            'surgery_surgeon' => $request['surgery_surgeon'],
            'surgery_hospital' => $request['surgery_hospital'],
        ]);

        return Inertia::render('SurgeryPage', [
            'message' => 'Surgery record saved.',
            'surgery_id' => $surgery->surgery_id,
        ]);
    }

    public function getPatientId(Request $request)
    {
        $user_id = User::where('lastname', $request->lastname)
                    ->where('email', $request->email)
                    ->first();

        $patient = Patient::where('user_id', $user_id->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        return response()->json(['patient_id' => $patient->patient_id]);
    }

    public function updatePrimaryRecord(Request $request, $patientId)
{
    // Find the patient
    $patient = Patient::find($patientId);

    if (!$patient) {
        return response()->json(['error' => 'Patient not found'], 404);
    }

    // Ensure the treatment record is retrieved as an object, not a string
    $treatment = Treatment::where('patient_id', $patient->patient_id)->first();

    if (!$treatment) {
        return response()->json(['error' => 'Treatment record not found'], 404);
    }

    // Validate the request data
    $validator = Validator::make($request->all(), [
        'treatment_purpose' => 'required',
        'primary_type' => 'required',
        'primary_treatment' => 'required',
        'initial_treatment_date' => 'required|date',
        'planned_additional_treatment' => 'required|array',
        'other_planned_additional_treatment' => 'required_if:planned_additional_treatment.*,Others',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Update the treatment record
    $treatment->update([
        'treatment_primaryRxType' => $request['primary_type'],
        'treatment_primaryRxName' => $request['primary_treatment'],
        'treatment_initialRxDate' => $request['initial_treatment_date'],
        'treatment_purpose' => $request['treatment_purpose'],
        'treatment_other_purpose' => $request['other_treatment_purpose'],
    ]);

    // Check and update the rxtype object
    $rxtype = Rxtype::where('patient_id', $patient->patient_id)->first();

    // If no rxtype record exists, create a new one
    if (!$rxtype) {
        $rxtype = new Rxtype();
        $rxtype->patient_id = $patient->patient_id;
    }

    // Map treatment types based on request
    $treatmentTypes = [
        'rxtype_surgery' => false,
        'rxtype_chemotherapy' => false,
        'rxtype_radiotherapy' => false,
        'rxtype_immunotherapy' => false,
        'rxtype_hormonaltherapy' => false,
        'rxtype_others' => false
    ];

    $treatmentMapping = [
        'Surgery' => 'rxtype_surgery',
        'Chemotherapy' => 'rxtype_chemotherapy',
        'Radiotherapy' => 'rxtype_radiotherapy',
        'Immunotherapy/Cryotherapy' => 'rxtype_immunotherapy',
        'Hormonal Therapy' => 'rxtype_hormonaltherapy',
        'Others' => 'rxtype_others'
    ];

    foreach ($request['planned_additional_treatment'] as $treatment) {
        if (isset($treatmentMapping[$treatment])) {
            $treatmentTypes[$treatmentMapping[$treatment]] = true;
        }
    }

    // Update the rxtype record
    $rxtype->fill($treatmentTypes);

    // Only set the other treatment if "Others" is selected
    if ($treatmentTypes['rxtype_others']) {
        $rxtype->rxtype_other_treatment = $request['other_planned_additional_treatment'];
    }

    $rxtype->save(); // This will either update or create the record.

    return response()->json([
        'message' => 'Treatment record updated successfully.',
        'treatment' => $treatment,
        'rxtype' => $rxtype,
    ]);
}



    public function updateRadiotherapy(Request $request, $patientId)
    {
        // Validate the incoming request
        $request->validate([
            'radRx_initial_date' => 'required|date',
            'radRx_last_date' => 'required|date',
            'radRx_dose' => 'required|integer',
            'radRx_bodySite' => 'required|string',
            'radRx_status' => 'required|in:Ongoing,Completed,Not Completed',
            'radRx_isCompleted' => 'required|boolean',
            'radRx_facility' => 'required|exists:hospitals,hospital_id',
            'radRx_doctor' => 'required|exists:doctors,doctor_id',
        ]);

        // Find the Radiotherapy record
        $radiotherapy = Radiotherapy::where('patient_id', $patientId)->first();

        // Update the radiotherapy fields
        $radiotherapy->update([
            'radRx_initial_date' => $request->input('radRx_initial_date'),
            'radRx_last_date' => $request->input('radRx_last_date'),
            'radRx_dose' => $request->input('radRx_dose'),
            'radRx_bodySite' => $request->input('radRx_bodySite'),
            'radRx_status' => $request->input('radRx_status'),
            'radRx_isCompleted' => $request->input('radRx_isCompleted'),
            'radRx_facility' => $request->input('radRx_facility'),
            'radRx_doctor' => $request->input('radRx_doctor'),
        ]);

        return response()->json(['message' => 'Radiotherapy updated successfully!']);
    }

    public function updateSurgeryRecord(Request $request, $patientId)
    {
        // Validate the incoming request
        $request->validate([
            'surgery_operation' => 'required|string',
            'surgery_date' => 'required|date',
            'surgery_findings' => 'required|string',
            'surgery_intent' => 'required|in:Curative-complete,Curative-incomplete,Palliative only,Others',
            'surgery_otherIntent' => 'nullable|string',
            'surgery_surgeon' => 'required|exists:doctors,doctor_id',
            'surgery_hospital' => 'required|exists:hospitals,hospital_id',
        ]);

        // Find the Surgery record
        $surgery = Surgery::where('patient_id', $patientId)->first();

        // Update the surgery fields
        $surgery->update([
            'surgery_operation' => $request->input('surgery_operation'),
            'surgery_date' => $request->input('surgery_date'),
            'surgery_findings' => $request->input('surgery_findings'),
            'surgery_intent' => $request->input('surgery_intent'),
            'surgery_otherIntent' => $request->input('surgery_otherIntent'),
            'surgery_surgeon' => $request->input('surgery_surgeon'),
            'surgery_hospital' => $request->input('surgery_hospital'),
        ]);

        return response()->json(['message' => 'Surgery updated successfully!']);
    }

    /**
     * Update a hormonal therapy record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    public function updateHormonalRecord(Request $request, $patientId)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'hormonal_drugs' => 'required|string',
            'hormonal_dose' => 'required|integer',
            'hormonal_initial_date' => 'required|date',
            'hormonal_end_date' => 'required|date',
            'hormonal_status' => 'required|in:Ongoing,Completed,Not Completed',
            'hormonal_notes' => 'nullable|string',
            'hormonal_doctor' => 'required|exists:doctors,doctor_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the Hormonal record
        $hormonal = Hormonal::where('patient_id', $patientId)->first();

        if (!$hormonal) {
            return response()->json(['message' => 'Hormonal record not found'], 404);
        }

        // Update the record
        $hormonal->update([
            'hormonal_drugs' => $request->hormonal_drugs,
            'hormonal_dose' => $request->hormonal_dose,
            'hormonal_initial_date' => $request->hormonal_initial_date,
            'hormonal_end_date' => $request->hormonal_end_date,
            'hormonal_status' => $request->hormonal_status,
            'hormonal_notes' => $request->hormonal_notes,
            'hormonal_doctor' => $request->hormonal_doctor
        ]);

        return response()->json(['message' => 'Hormonal therapy record updated successfully', 'data' => $hormonal], 200);
    }

    /**
     * Update an immunotherapy record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    public function updateImmunotherapyRecord(Request $request, $patientId)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'immunoRx_drugs' => 'required|string',
            'immunoRx_initial_date' => 'required|date',
            'immunoRx_end_date' => 'required|date',
            'immunoRx_status' => 'required|in:Ongoing,Completed,Not Completed',
            'immunoRx_notes' => 'nullable|string',
            'immunoRx_isCompleted' => 'required|boolean',
            'immunoRx_facility' => 'required|exists:hospitals,hospital_id',
            'immunoRx_doctor' => 'required|exists:doctors,doctor_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the Immunotherapy record
        $immunotherapy = Immunotherapy::where('patient_id', $patientId)->first();

        if (!$immunotherapy) {
            return response()->json(['message' => 'Immunotherapy record not found'], 404);
        }

        // Update the record
        $immunotherapy->update([
            'immunoRx_drugs' => $request->immunoRx_drugs,
            'immunoRx_initial_date' => $request->immunoRx_initial_date,
            'immunoRx_end_date' => $request->immunoRx_end_date,
            'immunoRx_status' => $request->immunoRx_status,
            'immunoRx_notes' => $request->immunoRx_notes,
            'immunoRx_isCompleted' => $request->immunoRx_isCompleted,
            'immunoRx_facility' => $request->immunoRx_facility,
            'immunoRx_doctor' => $request->immunoRx_doctor
        ]);

        return response()->json(['message' => 'Immunotherapy record updated successfully', 'data' => $immunotherapy], 200);
    }

    /**
     * Update a chemotherapy record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateChemotherapyRecord(Request $request, $patientId)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'chemo_type' => 'required|string|max:255',
            'chemotherapy_given' => 'required|exists:chemoprotocols,chemo_protocol_id', 
            'chemo_initialDate' => 'required|date',
            'chemo_endDate' => 'required|date|after_or_equal:chemo_initialDate',
            'chemo_cycleNumber' => 'required|integer|min:1',
            'chemo_status' => 'required|in:Ongoing,Completed,Not Completed',
            'chemo_isCompleted' => 'required|boolean',
            'chemo_facility' => 'required|exists:hospitals,hospital_id',
            'chemo_doctor' => 'required|exists:doctors,doctor_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the Chemotherapy record
        $chemotherapy = Chemotherapy::where('patient_id', $patientId)->first();

        if (!$chemotherapy) {
            return response()->json(['message' => 'Chemotherapy record not found'], 404);
        }

        // Update the record
        $chemotherapy->update([
            'chemo_type' => $request->chemo_type,
            'chemo_protocol' => $request->chemotherapy_given, 
            'chemo_initial_date' => $request->chemo_initialDate,
            'chemo_end_date' => $request->chemo_endDate,
            'chemo_cycleNumGiven' => $request->chemo_cycleNumber,
            'chemo_notes' => $request->chemo_notes,
            'chemo_status' => $request->chemo_status,
            'chemo_isCompleted' => $request->chemo_isCompleted,
            'chemo_facility' => $request->chemo_facility,
            'chemo_doctor' => $request->chemo_doctor
        ]);

        return response()->json([
            'message' => 'Chemotherapy record updated successfully',
            'data' => $chemotherapy
        ], 200);
    }


}
