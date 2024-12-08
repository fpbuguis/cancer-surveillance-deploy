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


class PatientController extends Controller
{

    public function getAllPatients() {
        $patients = Patient::with('user')->get(); // Assuming you want user details as well
        return response()->json($patients);
    }

    public function getPatientById($patientId) {
        $patient = Patient::where('patient_id', $patientId)
            ->with([
                'user',
                'user.address',
                'user.address.region',
                'user.address.province',
                'user.address.city',
                'user.address.municipality'
            ])
            ->first();

        return response()->json($patient);
    }

    public function getPatientByUserId($userId) {
        $patient = Patient::where('user_id', $userId)
        ->with(['user'])
        ->first();

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json($patient);
    }

    public function getUserById($userId) {
        $user = User::with([
            'address',
            'address.region',
            'address.province',
            'address.city',
            'address.municipality'
        ])
        ->where('user_id', $userId)
        ->first();

        return response()->json($user);
    }

    //submissions

    // public function getLabsSubmittedByPatient($patientId)
    // {
    //     $labs_submitted = LabsSubmitted::where('patient_id', $patientId)
    //         ->with(['patient.user', 'workup'])
    //         ->orderBy('labSubmitted_id', 'desc')
    //         ->first();

    //     return response()->json($labs_submitted);
    // }

    // public function getSurveyResponsesByPatient($patientId)
    // {
    //     $survey_response = SurveyResponse::where('patient_id', $patientId)
    //     ->with(['patient.user','symptomSurvey.cancerType', 'symptomSurvey.category'])
    //     ->orderBy('surveyResponse_id', 'desc')
    //     ->first();

    //     return response()->json($survey_response);
    // }

    // treatment

    public function getPatientWithTreatments($patientId)
    {
        $patient = Patient::with([
            'user',
            'user.address',
            'user.address.region',
            'user.address.province',
            'user.address.city',
            'user.address.municipality',
            'treatments',
            'rxtypes',
            'surgeries',
            'surgeries.doctor',
            'surgeries.doctor.user',
            'surgeries.hospital',
            'chemotherapies',
            'chemotherapies.protocol',
            'chemotherapies.doctor.user',
            'chemotherapies.hospital',
            'immunotherapies',
            'immunotherapies.doctor.user',
            'immunotherapies.hospital',
            'hormonals',
            'hormonals.doctor.user',
            'radiotherapies',
            'radiotherapies.type',
            'radiotherapies.doctor.user',
            'radiotherapies.hospital',
        ])
        ->where('patient_id', $patientId)
        ->first();

        return response()->json($patient);
    }

    public function onboard(Request $request)
    {
        try {
            $validated = $request->validate([
                'patientId' => 'required|exists:patients,patient_id',
                'doctorId' => 'required|exists:doctors,doctor_id',
            ]);

            $onboard = Onboard::create([
                'patient_id' => $validated['patientId'],
                'doctor_id' => $validated['doctorId'],
            ]);

            return response()->json([
                'message' => 'Onboard record created successfully.',
                'data' => $onboard,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $input)
    {
        Validator::make($input->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact_number' => ['required', 'string', 'max:15'],
            'password' => $this->passwordRules(),
            'birthday' => ['required', 'date'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            // 'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $address = Address::create([
            'address_number' => $input['address_number'],
            'address_street' => $input['address_street'],
            'address_brgy' => $input['address_brgy'],
            'address_zipcode' => $input['address_zipcode'],
            'region_id' => $input['region_id'],
            'province_id' => $input['province_id'],
            'city_id' => $input['city_id'],
            'municipality_id' => $input['municipality_id'],
        ]);

        $address_id = $address->id ?? $address->address_id;

        $user = User::create([
            'firstname' => $input['firstname'],
            'middlename' => $input['middlename'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'contact_number' => $input['contact_number'],
            'password' => Hash::make($input['password']),
            'birthday' => $input['birthday'],
            'birthplace' => $input['birthplace'],
            'gender' => $input['gender'],
            'marital_status' => $input['marital_status'],
            'address_id' => $address->address_id, // Adjusted to use correct ID
            'role_id' => '3',
        ]);

        // $user_id = $user->id ?? $user->user_id;

        $patient = Patient::create([
            'user_id' => $user->user_id,
        ]);

        // Return the new patient ID in the response
        return response()->json([
            'message' => 'Patient registered successfully.',
            'patient_id' => $patient->patient_id,
        ], 200);
    }

    protected function passwordRules()
    {
        return ['required', 'string', 'min:8', 'confirmed'];
    }

   
    public function updatePatient(Request $request, $patientId)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact_number' => ['required', 'string', 'max:15'],
            'gender' => ['nullable', 'in:male,female,other'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'birthday' => ['required', 'date'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'address.address_number' => ['nullable', 'string', 'max:255'],
            'address.address_street' => ['nullable', 'string', 'max:255'],
            'address.address_brgy' => ['nullable', 'string', 'max:255'],
            'address.address_zipcode' => ['nullable', 'string', 'max:10'],
            'address.region_id' => ['nullable', 'integer'],
            'address.province_id' => ['nullable', 'integer'],
            'address.city_id' => ['nullable', 'integer'],
            'address.municipality_id' => ['nullable', 'integer'],
        ]);

        // Fetch the patient record
        $patient = Patient::with('user.address')->findOrFail($patientId);
        $user = $patient->user;
        $address = $user->address;

        // Update the user's details
        $user->update([
            'firstname' => $validatedData['firstname'],
            'middlename' => $validatedData['middlename'] ?? $user->middlename,
            'lastname' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'contact_number' => $validatedData['contact_number'],
            'gender' => $validatedData['gender'] ?? $user->gender,
            'marital_status' => $validatedData['marital_status'] ?? $user->marital_status,
            'birthday' => $validatedData['birthday'],
            'birthplace' => $validatedData['birthplace'] ?? $user->birthplace,
        ]);

        // Update the address if provided
        if (isset($validatedData['address'])) {
            $addressData = $validatedData['address'];
            if ($address) {
                $address->update($addressData);
            } else {
                $address = Address::create($addressData);
                $user->address_id = $address->address_id;
                $user->save();
            }
        }

        return response()->json([
            'message' => 'Patient information updated successfully.',
            'data' => $patient->load('user.address'), // Load the updated relations
        ]);
    }


}
