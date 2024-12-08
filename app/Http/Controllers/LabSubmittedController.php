<?php

namespace App\Http\Controllers;

use App\Models\LabsSubmitted;
use App\Models\Workup;
use App\Models\Doctor;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;


class LabSubmittedController extends Controller
{
    //submissions

    public function getLatestLabSubmittedByPatient($patientId)
    {
        $labs_submitted = LabsSubmitted::where('patient_id', $patientId)
            ->with(['patient.user', 'workup'])
            ->orderBy('labSubmitted_id', 'desc')
            ->first();

        return response()->json($labs_submitted);
    }

    public function getLabsSubmittedByPatient($patientId)
    {
        $labs_submitted = LabsSubmitted::where('patient_id', $patientId)
            ->with(['patient.user', 'workup'])
            ->get();

        return response()->json($labs_submitted);
    }

    public function getAllLabSubmissions(){
        return response()->json(LabsSubmitted::all());
    }

    public function getLabSubmissionByDoctor($userId){
        $doctor = Doctor::where('user_id', $userId)->first();
        return response()->json(LabsSubmitted::where('doctor_id', $doctor->doctor_id)->get());
    }

    // public function getWorkup($workupName){
    //     $workup = Workup::where('workup_name', $workupName)->first();
    // }

    public function getWorkupById($workupId){
        $workup = Workup::where('workup_id', $workupId)->first();
        return response()->json($workup);
    }

    // public function submitLabResults(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'workupSubmitted' => 'required|string',
    //         'other_workups' => 'nullable|string',
    //         'labResults' => 'required|array', // Validate that we have multiple files
    //         'labResults.*' => 'mimes:pdf,jpeg,jpg,png|max:2048', // Validate file types and size
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $workup_details = Workup::where('workup_name', $request['workupSubmitted'])->first();

    //     if (!$workup_details) {
    //         return response()->json(['error' => 'Workup not found', 'workup_name' => $request['workupSubmitted']], 404);
    //     }

    //     // Retrieve necessary data from the request
    //     $workupId = $workup_details->workup_id;

    //     // Assuming you have the patient ID and doctor ID available from the session or auth
    //     $patientId = $request['patient_id']; // Get the patient's ID from the request
    //     $doctorId = $request['doctor_id']; // Get the doctor's ID from the request
    //     $otherWorkups = $request['other_workups'];

    //     // Prepare to store the files as blobs
    //     $filePaths = [];
    //     $labFiles = $request->file('labResults');
    //     foreach ($labFiles as $file) {
    //         $filePath = $file->getPathname();
    //         $fileContents = file_get_contents($filePath); // Get the file content

    //         // Store the file in a database-friendly way (e.g., store as BLOB in MySQL)
    //         $filePaths[] = $fileContents;
    //     }

    //     // Create the LabSubmitted entry
    //     $labSubmission = LabsSubmitted::create([
    //         'patient_id' => $patientId,
    //         'doctor_id' => $doctorId,
    //         'labSubmission_date' => now(),
    //         'workup_id' => $workupId,
    //         'labFileSubmissions' => json_encode($filePaths), // Store files as a JSON-encoded array (or directly as BLOBs depending on DB setup)
    //         'labSubmission_notes' => $otherWorkups, // Store the text input
    //     ]);

    //     return response()->json(['message' => 'Lab results submitted successfully!', 'labSubmission' => $labSubmission]);
    // }

    public function submitLabResults(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workupSubmitted' => 'required|string',
            'other_workups' => 'nullable|string',
            'labResults' => 'required|array',
            'labResults.*' => 'mimes:pdf,jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $workup_details = Workup::where('workup_name', $request['workupSubmitted'])->first();

        if (!$workup_details) {
            return response()->json(['error' => 'Workup not found'], 404);
        }

        $patientId = $request->input('patient_id');
        $doctorId = $request->input('doctor_id');
        $otherWorkups = $request->input('other_workups');
        $workupId = $workup_details->workup_id;

        // Save files and store paths
        $filePaths = [];
        if ($request->hasFile('labResults')) {
            foreach ($request->file('labResults') as $file) {
                $path = $file->store('lab_results', 'public'); // Store file in storage/app/public/lab_results
                $filePaths[] = $path;
            }
        }

        $labSubmission = LabsSubmitted::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'labSubmission_date' => now(),
            'workup_id' => $workupId,
            'labFileSubmissions' => json_encode($filePaths), // Store as JSON
            'labSubmission_notes' => $otherWorkups,
        ]);

        return response()->json(['message' => 'Lab results submitted successfully!', 'labSubmission' => $labSubmission]);
    }

// public function getLabFiles($labSubmittedId)
// {
//     $labSubmission = LabsSubmitted::find($labSubmittedId);

//     if (!$labSubmission) {
//         return response()->json(['error' => 'Lab submission not found'], 404);
//     }

//     $files = json_decode($labSubmission->labFileSubmissions, true);

//     return response()->json(['files' => $files]);
// }

//W0RKING
    public function getLabFiles($labSubmittedId)
    {
        $labSubmission = LabsSubmitted::find($labSubmittedId);

        if (!$labSubmission) {
            return response()->json(['error' => 'Lab submission not found'], 404);
        }

        $files = json_decode($labSubmission->labFileSubmissions, true);

        // Encode file paths for safe URL usage
        $encodedFiles = array_map(function ($file) {
            return urlencode($file);
        }, $files);

        return response()->json(['files' => $encodedFiles]);
    }

// public function getLabFiles($labSubmittedId)
// {
//     $labSubmission = LabsSubmitted::find($labSubmittedId);

//     if (!$labSubmission) {
//         return response()->json(['error' => 'Lab submission not found'], 404);
//     }

//     $files = json_decode($labSubmission->labFileSubmissions, true);

//     // Generate full URL for each file
//     $filesWithUrls = array_map(function ($file) {
//         return [
//             'file' => $file,
//             'url' => url("/labs/download/" . urlencode($file))
//         ];
//     }, $files);

//     return response()->json(['files' => $filesWithUrls]);
// }



// public function downloadLabFile($filePath)
// {
//     $fullPath = storage_path('app/public/' . $filePath);

//     if (!file_exists($fullPath)) {
//         return response()->json(['error' => 'File not found'], 404);
//     }

//     return response()->download($fullPath);
// }

    public function downloadLabFile($filePath)
    {
        // Decode the encoded file path
        $decodedPath = urldecode($filePath);

        // Construct the full path
        $fullPath = storage_path('app/public/' . $decodedPath);

        // Check if file exists
        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Return the file for download
        return response()->download($fullPath);
    }

// public function getFilesByLabSubmissionId($labSubmittedId)
// {
//     $labSubmission = LabsSubmitted::find($labSubmittedId);

//     if (!$labSubmission) {
//         return response()->json(['error' => 'Lab submission not found'], 404);
//     }

//     // Decode the JSON field containing file paths
//     $files = json_decode($labSubmission->labFileSubmissions, true);

//     if (empty($files)) {
//         return response()->json(['error' => 'No files found for this submission'], 404);
//     }

//     // Encode file paths for safe URL usage
//     $encodedFiles = array_map(function ($file) {
//         return url('storage/' . $file); // Generate full URL to the files
//     }, $files);

//     return response()->json([
//         'labSubmitted_id' => $labSubmission->labSubmitted_id,
//         'files' => $encodedFiles,
//     ]);
// }

    public function getFilesByLabSubmissionId($labSubmittedId)
    {
        $labSubmission = LabsSubmitted::find($labSubmittedId);

        if (!$labSubmission) {
            return response()->json(['error' => 'Lab submission not found'], 404);
        }

        $files = json_decode($labSubmission->labFileSubmissions, true);

        if (empty($files)) {
            return response()->json(['error' => 'No files found for this submission'], 404);
        }

        // Generate public URLs for the files
        $fileUrls = array_map(function ($file) {
            return url('storage/' . $file); // Generate public URL
        }, $files);

        return response()->json([
            'labSubmitted_id' => $labSubmission->labSubmitted_id,
            'files' => $fileUrls,
        ]);
    }
}
