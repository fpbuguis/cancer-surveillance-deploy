<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Models\DiseaseProfile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'canResetPassword' => Route::has('password.request'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// // Authenticated and verified users only
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',  // Middleware to ensure email is verified
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/patient-enrollment', function () {
        return Inertia::render('PatientEnrollmentPage');
    })->name('patient-enrollment');

    Route::get('/patient-details-update', function () {
        return Inertia::render('PatientDetailsUpdatePage');
    })->name('patient-details-update');

    Route::get('/disease-profile/{patientId?}', function ($patientId = null) {
        $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
        return Inertia::render('DiseaseProfilePage', $patientData);
    })->name('disease-profile');

    Route::get('/disease-profile-update/{patientId?}', function ($patientId = null) {
        $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
        return Inertia::render('DiseaseProfileUpdatePage', $patientData);
    })->name('disease-profile-update');


    // Route::get('/treatment-history/{patientId?}', function ($patientId = null) {
    //     $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
    //     return Inertia::render('TreatmentHistoryPage', $patientData);
    // })->name('treatment-history');

    // Route::get('/treatment-history/surgery/{patientId?}', function ($patientId = null) {
    //     $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
    //     return Inertia::render('SurgeryPage', $patientData);
    // })->name('surgery');

    // Route::get('/treatment-history/radiotherapy/{patientId?}', function ($patientId = null) {
    //     $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
    //     return Inertia::render('RadiotherapyPage', $patientData);
    // })->name('radiotherapy');

    // Route::get('/treatment-history/chemotherapy/{patientId?}', function ($patientId = null) {
    //     $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
    //     return Inertia::render('ChemotherapyPage', $patientData);
    // })->name('chemotherapy');

    // Route::get('/treatment-history/immunotherapy/{patientId?}', function ($patientId = null) {
    //     $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
    //     return Inertia::render('ImmunotherapyPage', $patientData);
    // })->name('immunotherapy');

    // Route::get('/treatment-history/hormonal/{patientId?}', function ($patientId = null) {
    //     $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
    //     return Inertia::render('HormonalPage', $patientData);
    // })->name('hormonal');

    // Route::get('/treatment-history/{patientId?}/{otherId?}', function ($patientId = null, $otherId = null) {
    //     return Inertia::render('TreatmentHistoryPage', [
    //         'patientId' => $patientId,
    //         'otherId' => $otherId,
    //         'lastname' => '',
    //         'email' => '',
    //     ]);
    // })->name('treatment-history');

    Route::get('/treatment-history/surgery/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId
            ? ['patientId' => $patientId, 'otherId' => $otherId ?? null]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

            // dd('surgery', $patientData);

        return Inertia::render('SurgeryPage', $patientData);
    })->name('surgery');

    Route::get('/treatment-history/radiotherapy/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('RadiotherapyPage', $patientData);
    })->name('radiotherapy');

    Route::get('/treatment-history/chemotherapy/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('ChemotherapyPage', $patientData);
    })->name('chemotherapy');

    Route::get('/treatment-history/immunotherapy/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('ImmunotherapyPage', $patientData);
    })->name('immunotherapy');

    Route::get('/treatment-history/hormonal/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('HormonalPage', $patientData);
    })->name('hormonal');

    Route::get('/treatment-history/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

            // dd('primary', $patientData);

        return Inertia::render('TreatmentHistoryPage', $patientData);
    })->name('treatment-history');

    // update treatment

    Route::get('/treatment-history-update/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('TreatmentHistoryUpdatePage', $patientData);
    })->name('treatment-history-update');

    Route::get('/treatment-history-update-surgery/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId
            ? ['patientId' => $patientId, 'otherId' => $otherId ?? null]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('SurgeryUpdatePage', $patientData);
    })->name('treatment-history-update-surgery');

    Route::get('/treatment-history-update-radiotherapy/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('RadiotherapyUpdatePage', $patientData);
    })->name('treatment-history-update-radiotherapy');

    Route::get('/treatment-history-update-chemotherapy/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('ChemotherapyUpdatePage', $patientData);
    })->name('/treatment-history-update-chemotherapy');

    Route::get('/treatment-history-update-immunotherapy/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('ImmunotherapyUpdatePage', $patientData);
    })->name('/treatment-history-update-immunotherapy');

    Route::get('/treatment-history-update-hormonal/{otherId?}/{patientId?}', function ($patientId = null, $otherId = null) {
        $patientData = $patientId || $otherId
            ? ['patientId' => $patientId, 'otherId' => $otherId]
            : ['patientId' => null, 'otherId' => null, 'lastname' => '', 'email' => ''];

        return Inertia::render('HormonalUpdatePage', $patientData);
    })->name('/treatment-history-update-hormonal');

    // end of treatment

    Route::get('/consult/{patientId?}', function ($patientId = null) {
        $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
        return Inertia::render('ConsultPage', $patientData);
    })->name('consult');

    Route::get('/symptomsreport', function () {
        return Inertia::render('SymptomsReportPage');
    })->name('symptomsreport');

    Route::get('/laboratoryrequest', function () {
        return Inertia::render('LaboratoryRequestPage');
    })->name('laboratoryrequest');

    Route::get('/laboratoryresult', function () {
        return Inertia::render('LaboratoryResultPage');
    })->name('laboratoryresult');

    Route::get('/doctornotification', function () {
        return Inertia::render('DoctorNotificationPage');
    })->name('doctornotification');

    Route::get('/patientnotification', function () {
        return Inertia::render('PatientNotificationPage');
    })->name('patientnotification');

    // Route::get('/message', function () {
    //     return Inertia::render('MessagePage');
    // })->name('message');

    Route::get('/message/{patientId?}', function ($patientId = null) {
        $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
        return Inertia::render('MessagePage', $patientData);
    })->name('message');

    Route::get('/schedconsult/{patientId?}', function ($patientId = null) {
        $patientData = $patientId ? ['patientId' => $patientId] : ['patientId' => null, 'lastname' => '', 'email' => ''];
        return Inertia::render('SchedConsultPage', $patientData);
    })->name('schedconsult');

    Route::get('/prescription', function () {
        return Inertia::render('PrescriptionPage');
    })->name('prescription');

    Route::get('/labrequest', function () {
        return Inertia::render('LabRequestPage');
    })->name('labrequest');

    Route::get('/clinicalabstract', function () {
        return Inertia::render('ClinicalAbstractPage');
    })->name('clinicalabstract');

    Route::get('/medcertification', function () {
        return Inertia::render('MedCertificationPage');
    })->name('medcertification');

    Route::get('/referralform', function () {
        return Inertia::render('ReferralFormPage');
    })->name('referralform');

});

    // Routes that do not require authentication and verification

    Route::get('/patient', function () {
        return Inertia::render('Patient');
    })->name('patient');

    Route::get('/doctor', function () {
        return Inertia::render('Doctor');
    })->name('doctor');

    Route::get('/tutorial', function () {
        return Inertia::render('Tutorial');
    })->name('tutorial');

    Route::get('/about', function () {
        return Inertia::render('About');
    })->name('about');

    Route::get('/contact', function () {
        return Inertia::render('Contact');
    })->name('contact');

    Route::get('/search', function (Request $request) {
        $name = $request->query('name', null);
        return Inertia::render('SearchPage', [
            'name' => $name,
        ]);
    })->name('search');




///

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->name('dashboard');

// Route::get('/patient', function () {
//     return Inertia::render('Patient');
// })->name('patient');

// Route::get('/doctor', function () {
//     return Inertia::render('Doctor');
// })->name('doctor');

// Route::get('/about', function () {
//     return Inertia::render('About');
// })->name('about');

// Route::get('/contact', function () {
//     return Inertia::render('Contact');
// })->name('contact');

// Route::get('/patient-enrollment', function () {
//     return Inertia::render('PatientEnrollmentPage');
// })->name('patient-enrollment');

// Route::get('/disease-profile', function () {
//     return Inertia::render('DiseaseProfilePage');
// })->name('disease-profile');

// Route::get('/treatment-history', function () {
//     return Inertia::render('TreatmentHistoryPage');
// })->name('treatment-history');

// Route::get('/consult', function () {
//     return Inertia::render('ConsultPage');
// })->name('consult');

// Route::get('/symptomsreport', function () {
//     return Inertia::render('SymptomsReportPage');
// })->name('symptomsreport');

// Route::get('/laboratoryrequest', function () {
//     return Inertia::render('LaboratoryRequestPage');
// })->name('laboratoryrequest');

// Route::get('/laboratoryresult', function () {
//     return Inertia::render('LaboratoryResultPage');
// })->name('laboratoryresult');

// Route::get('/search', function (Request $request) {
//     $name = $request->query('name', null);

//     return Inertia::render('SearchPage', [
//         'name' => $name,
//     ]);
// })->name('search');
