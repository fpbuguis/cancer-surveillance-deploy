<?php

namespace App\Http\Controllers;

use App\Models\LabsDownload;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LabDownloadController extends Controller
{

    public function createLabDownload(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'workup_id' => 'required|exists:workups,workup_id',
        ]);

        $labDownload = LabsDownload::create([
            'patient_id' => $request->patient_id,
            'workup_id' => $request->workup_id,
            'labReq_date' => Carbon::create(now())->toDateString(),
        ]);

        $filePath = public_path("Laboratory Request.pdf");

        if (file_exists($filePath)) {
            return response()->download($filePath, 'test.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>  'attachment; filename="test.pdf"'
            ]);
        } else {
            return response()->json([
                'message' => 'File not found.',
            ], 404);
        }
    }

    public function getLabDownloadsByPatient($patientId)
    {
        $labDownloads = LabsDownload::where('patient_id', $patientId)
        ->with(['workup'])
        ->get();

        if ($labDownloads->isEmpty()) {
            return response()->json([
                'message' => 'No lab downloads found for this patient.',
            ], 404);
        }

        return response()->json([
            'data' => $labDownloads,
        ]);
    }
}
