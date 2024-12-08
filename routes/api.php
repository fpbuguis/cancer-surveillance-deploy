<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HistologyController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\CheckupController;
use App\Http\Controllers\LabDownloadController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SymptomSurveyController;
use App\Http\Controllers\LabMonitorController;
use App\Http\Controllers\LabSubmittedController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SurveyResponseController;
use App\Models\Checkup;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/regions', [AddressController::class, 'getRegions']);
Route::get('/provinces/{regionId}', [AddressController::class, 'getProvinces']);
Route::get('/chemoprotocols', [TreatmentController::class, 'getChemoprotocols']);
Route::get('/cities/{provinceId}', [AddressController::class, 'getCities']);
Route::get('/municipalities/{provinceId}', [AddressController::class, 'getMunicipalities']);
Route::get('/departments', [DoctorController::class, 'getDepartments']);
Route::get('/hospitals', [DoctorController::class, 'getHospitals']);
Route::get('/specialties', [DoctorController::class, 'getSpecialties']);
Route::get('/all-doctors', [DoctorController::class, 'getAllDoctors'])->name('allDoctors');
Route::get('/all-patients', [PatientController::class, 'getAllPatients'])->name('allPatients');
Route::get('/cities/{provinceId}', [AddressController::class, 'getCities']);
Route::get('/departments', [DoctorController::class, 'getDepartments']);
Route::get('/disease/disease-status-list', [DiseaseController::class, 'getDiseaseStatuses']);
Route::get('/disease/{patientId}/profile', [DiseaseController::class, 'getDiseaseProfile']);
Route::get('/latestdisease/{patientId}/profile', [DiseaseController::class, 'getLatestDiseaseProfile']);
Route::get('/disease/options', [DiseaseController::class, 'getOptions']);
Route::get('/doctor-profile/{userId}', [DoctorController::class, 'getDoctorByUserId']);
Route::get('/doctors/{doctorId}/patients', [DoctorController::class, 'getPatientsByDoctor']);
Route::get('/doctors/{doctorId}/checkups', [DoctorController::class, 'getCheckupsByDoctor']);
Route::get('/find-patient', [DiseaseController::class, 'getPatientId']);

Route::get('/get-doctor/{doctorId}', [DoctorController::class, 'getDoctorById']);
Route::get('/get-patient/{patientId}', [PatientController::class, 'getPatientById']);
Route::get('/get-user/{userId}', [PatientController::class, 'getUserById']);

Route::get('/hospitals', [DoctorController::class, 'getHospitals']);
Route::get('/municipalities/{provinceId}', [AddressController::class, 'getMunicipalities']);
Route::get('/provinces/{regionId}', [AddressController::class, 'getProvinces']);
Route::get('/onboards', [DoctorController::class, 'getOnboards']);
Route::get('/latestonboards/{patientId}', [DoctorController::class, 'getLatestOnboards']);
Route::get('/rad-details', [TreatmentController::class, 'getRadDetails']);
Route::get('/regions', [AddressController::class, 'getRegions']);
Route::get('/specialties', [DoctorController::class, 'getSpecialties']);
Route::get('/user-loggedIn', [TreatmentController::class, 'getCurrentUser'])->middleware('auth');
Route::get('/patient/{patientId}/treatments', [PatientController::class, 'getPatientWithTreatments']);

Route::get('/doctors/{doctorId}/checkup-schedules', [DoctorController::class, 'getCheckupSchedulesByDoctor']);
Route::get('/doctors/{doctorId}/labs-submitted', [DoctorController::class, 'getLabsSubmittedByDoctor']);
Route::get('/doctors/{doctorId}/survey-responses', [DoctorController::class, 'getSurveyResponsesByDoctor']);
Route::get('/all-symptoms', [SymptomSurveyController::class, 'getAllSymptoms']);
Route::get('/symptoms/primary-site/{bodySiteId}', [SymptomSurveyController::class, 'getSymptomsByPrimarySite']);
Route::get('/symptom-category/{symptomCategoryId}', [SymptomSurveyController::class, 'getSymptomsByCategory']);
Route::get('/all-labMonitors', [LabMonitorController::class, 'getAllLabMonitors']);
Route::get('/labMonitors/primary-site/{bodySiteId}', [LabMonitorController::class, 'getLabMonitorsByPrimarySite']);

// get checkup, labsubmitted, survey response by patient
Route::get('/checkups/{patientId}', [CheckupController::class, 'fetchCheckup']);
Route::get('/patients/{patientId}/latest-labs-submitted', [LabSubmittedController::class, 'getLatestLabSubmittedByPatient']);
Route::get('/patients/{patientId}/labs-submitted', [LabSubmittedController::class, 'getLabsSubmittedByPatient']);
Route::get('/patients/{patientId}/survey-responses', [SurveyResponseController::class, 'getSurveyResponsesByPatient']);
Route::get('/labs/files/{labSubmittedId}', [LabSubmittedController::class, 'getLabFiles']);
Route::get('/labs/download/{filePath}', [LabSubmittedController::class, 'downloadLabFile'])
    ->where('filePath', '.*'); // Match any character in the path
Route::get('/labs-submission/{labSubmittedId}/files', [LabSubmittedController::class, 'getFilesByLabSubmissionId']);

Route::get('/patient-profile/{userId}', [PatientController::class, 'getPatientByUserId']);
Route::get('/patient/{patientId}/latest-schedule', [ScheduleController::class, 'getLatestScheduleByPatient']);

Route::get('/notifications/receiver/{userId}', [NotificationController::class, 'getNotificationsByReceiver']);
Route::get('/notifications/{notifLogId}', [NotificationController::class, 'getNotificationById']);
Route::post('/check-notification', [NotificationController::class, 'checkNotification']);

Route::get('/lab-submission-list', [LabSubmittedController::class, 'getAllLabSubmissions']);
Route::get('/doctor/lab-submissions/{userId}', [LabSubmittedController::class, 'getLabSubmissionByDoctor']);

Route::get('/patient/lab-downloads/{patientId}', [LabDownloadController::class, 'getLabDownloadsByPatient']);


// Route::get('/notifications/receiver/{userId}', [NotificationController::class, 'getNotificationsByReceiver']);

// Route::get('/workupByName/{workupName}', [LabSubmittedController::class, 'getWorkup']);
Route::get('/workupById/{workupId}', [LabSubmittedController::class, 'getWorkupById']);

Route::put('/patient/{patientId}/update-schedule', [ScheduleController::class, 'updateSchedule']);
Route::put('/notification/{userId}/update-status', [NotificationController::class, 'updateNotificationStatus']);
Route::put('update-patient-info/{patientId}', [PatientController::class, 'updatePatient']);
Route::put('/treatment/update/primary/{patientId}', [TreatmentController::class, 'updatePrimaryRecord']);
Route::put('/treatment/update/radiotherapy/{patientId}', [TreatmentController::class, 'updateRadiotherapy']);
Route::put('/treatment/update/surgery/{patientId}', [TreatmentController::class, 'updateSurgeryRecord']);
Route::put('/treatment/update/hormonal/{patientId}', [TreatmentController::class, 'updateHormonalRecord']);
Route::put('/treatment/update/immunotherapy/{patientId}', [TreatmentController::class, 'updateImmunotherapyRecord']);
Route::put('/treatment/uppdate/hormonal/{patientId}', [TreatmentController::class, 'updateHormonalRecord']);
Route::put('/treatment/uppdate/immunotherapy/{patientId}', [TreatmentController::class, 'updateImmunotherapyRecord']);
Route::put('/treatment/update/chemotherapy/{patientId}', [TreatmentController::class, 'updateChemotherapyRecord']);
Route::put('/disease-profile/update', [DiseaseController::class, 'updateDiseaseProfile']);

Route::post('/enroll-patient', [PatientController::class, 'store'])->name('enrollPatient');
Route::post('/onboard', [PatientController::class, 'onboard']);
Route::post('/create-surgery-record', [TreatmentController::class, 'createSurgeryRecord']);
Route::post('/send-email', [MessageController::class, 'sendEmail']);
Route::post('/create-consult', [ConsultController::class, 'createConsult']);
Route::post('/create-checkup', [CheckupController::class, 'createCheckup']);
Route::post('/create-schedule-checkup', [ScheduleController::class, 'createScheduleCheckup']);
Route::post('/create-treatment-record', [TreatmentController::class, 'createTreatmentRecord']);
Route::post('/create-radiotherapy-record', [TreatmentController::class, 'createRadTherapyRecord']);
Route::post('/create-chemotherapy-record', [TreatmentController::class, 'createChemotherapyRecord']);
Route::post('/create-immunotherapy-record', [TreatmentController::class, 'createImmunotherapyRecord']);
Route::post('/create-hormonal-record', [TreatmentController::class, 'createHormonalRecord']);
Route::post('/create-symptoms-report', [SymptomSurveyController::class, 'createSymptomsReportRecord']);
// Route::post('/submit-lab-results', [LabSubmittedController::class, 'submitLabResults']);
Route::post('/labs/submit', [LabSubmittedController::class, 'submitLabResults']);

Route::get('/pathologies/{term}', [DiseaseController::class, 'getHistologiesByPathologyTerm']);
Route::get('/histology-terms', [HistologyController::class, 'getHistologyTerms']);
Route::get('/pathologies', [DiseaseController::class, 'getAllPathologies']);

Route::post('/create-disease-record', [DiseaseController::class, 'store']);
Route::post('/create-notification-log', [NotificationController::class, 'createNotificationLog']);
Route::post('/create-lab-download',[LabDownloadController::class, 'createLabDownload']);

//For checking in postman
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'message' => 'User logged in successfully',
        'user' => $user
    ]);
});

// Route::get('/regions', [AddressController::class, 'getRegions']);
// Route::get('/provinces/{regionId}', [AddressController::class, 'getProvinces']);
// Route::get('/cities/{provinceId}', [AddressController::class, 'getCities']);
// Route::get('/municipalities/{provinceId}', [AddressController::class, 'getMunicipalities']);

// Route::get('/departments', [DoctorController::class, 'getDepartments']);
// Route::get('/hospitals', [DoctorController::class, 'getHospitals']);
// Route::get('/specialties', [DoctorController::class, 'getSpecialties']);
// Route::get('/all-doctors', [DoctorController::class, 'getAllDoctors'])->name('allDoctors');

// Route::post('/enroll-patient', [PatientController::class, 'store'])->name('enrollPatient');
// Route::get('/all-patients', [PatientController::class, 'getAllPatients'])->name('allPatients');

// Route::get('/disease/{patientId}/profile', [DiseaseController::class, 'getDiseaseProfile']);

// Route::get('/disease/{patientId}/profile', [DiseaseController::class, 'getDiseaseProfile']);
// Route::get('/disease/options', [DiseaseController::class, 'getOptions']);

// Route::get('/find-patient', [DiseaseController::class, 'getPatientId']);

// Route::get('/disease/{patientId}/profile', [DiseaseController::class, 'getDiseaseProfile']);

// Route::get('/specialties', [DoctorController::class, 'getSpecialties']);
