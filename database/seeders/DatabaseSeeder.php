<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed symptom_categories
        DB::table('symptom_categories')->insert([
            ['symptomCategory_id' => 1, 'symptom_category' => 'Local Symptom'],
            ['symptomCategory_id' => 2, 'symptom_category' => 'Systemic Symptom'],
            ['symptomCategory_id' => 3, 'symptom_category' => 'Quality of Life'],
            ['symptomCategory_id' => 4, 'symptom_category' => 'Treatment Side Effect'],
        ]);

        // Seed workups
        DB::table('workups')->insert([
            ['workup_id' => 1, 'workup_name' => 'Complete Blood Count'],
            ['workup_id' => 2, 'workup_name' => 'Prothrombin Time'],
            ['workup_id' => 3, 'workup_name' => 'Thrombin Time'],
            ['workup_id' => 4, 'workup_name' => 'Mammogram'],
            ['workup_id' => 5, 'workup_name' => 'MRI Breast'],
            ['workup_id' => 6, 'workup_name' => 'Bone Density Testing (DEXA)'],
            ['workup_id' => 7, 'workup_name' => '2D Echo'],
            ['workup_id' => 8, 'workup_name' => 'Thyroglobulin'],
            ['workup_id' => 9, 'workup_name' => 'Neck Ultrasound'],
            ['workup_id' => 10, 'workup_name' => 'Thyroid Stimulating Hormone (TSH)'],
            ['workup_id' => 11, 'workup_name' => 'Free T4'],
            ['workup_id' => 12, 'workup_name' => 'RAI Scan'],
            ['workup_id' => 13, 'workup_name' => '12L-ECG'],
            ['workup_id' => 14, 'workup_name' => 'Chest CT with IV Contrast'],
            ['workup_id' => 15, 'workup_name' => 'Abdominal CT with Oral and IV Contrast'],
            ['workup_id' => 16, 'workup_name' => 'Abdominopelvic CT with Triple Contrast'],
            ['workup_id' => 17, 'workup_name' => 'Carcinoembryonic Antigen (CEA)'],
            ['workup_id' => 18, 'workup_name' => 'Alkaline Phosphatase'],
            ['workup_id' => 19, 'workup_name' => 'AST'],
            ['workup_id' => 20, 'workup_name' => 'ALT'],
            ['workup_id' => 21, 'workup_name' => 'Esophagogastroduodenoscopy'],
            ['workup_id' => 22, 'workup_name' => 'Colonoscopy'],
            ['workup_id' => 23, 'workup_name' => 'Nutritional Assessment'],
            ['workup_id' => 24, 'workup_name' => 'Other'],
        ]);
        

        // Seed workup_types
        DB::table('workup_types')->insert([
            ['workupType_id' => 1, 'workupType_name' => 'Imaging'],
            ['workupType_id' => 2, 'workupType_name' => 'Blood Testing'],
            ['workupType_id' => 3, 'workupType_name' => 'Cardiac Test'],
            ['workupType_id' => 4, 'workupType_name' => 'Endoscopy'],
            ['workupType_id' => 5, 'workupType_name' => 'Clinical Test'],
        ]);


        // Seed checkup_statuses
        DB::table('checkup_statuses')->insert([
            ['checkupStatus_id' => 1, 'notifStatus_name' => 'request'],
            ['checkupStatus_id' => 2, 'notifStatus_name' => 'confirmed'],
            ['checkupStatus_id' => 3, 'notifStatus_name' => 'done'],
            ['checkupStatus_id' => 4, 'notifStatus_name' => 'cancelled'],
        ]);

        // Seed notification_statuses
        DB::table('notification_statuses')->insert([
            ['notifStatus_id' => 1, 'notifStatus_name' => 'Unread'],
            ['notifStatus_id' => 2, 'notifStatus_name' => 'Viewed'],
            ['notifStatus_id' => 3, 'notifStatus_name' => 'Action Taken'],
        ]);

        // Seed notification_types
        DB::table('notification_types')->insert([
            ['notifType_id' => 1, 'notificationType_name' => 'Symptom Survey'],
            ['notifType_id' => 2, 'notificationType_name' => 'Laboratory Submission'],
            ['notifType_id' => 3, 'notificationType_name' => 'Document Request'],
            ['notifType_id' => 4, 'notificationType_name' => 'Checkup Scheduling'],
            ['notifType_id' => 5, 'notificationType_name' => 'Treatment Info Update'],
        ]);

        // Seed labs_downloads
        DB::table('labs_downloads')->insert([
            [
                'labReq_id' => 1,
                'patient_id' => 17,
                'workup_id' => 1,
                'labReq_date' => '2024-11-18',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed labs_submitted
        DB::table('labs_submitted')->insert([
            [
                'labSubmitted_id' => 1,
                'patient_id' => 17,
                'doctor_id' => 11,
                'labSubmission_date' => '2024-11-18',
                'workup_id' => 1,
                'labFileSubmissions' => NULL,
                'labSubmission_notes' => 'Submitted successfully',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed lab_monitors
        DB::table('lab_monitors')->insert([
            [
                'labMonitor_id' => 1,
                'cancer_type' => 1,
                'workup_id' => 1,
                'workup_frequency' => 3,
                'workupType_id' => 1,
                'workup_indication' => 'Routine check-up',
                'workup_duration' => 6,
                'workup_referral' => 'Specialist A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed checkup_schedules
        DB::table('checkup_schedules')->insert([
            [
                'checkupSched_id' => 1,
                'patient_id' => 17,
                'doctor_id' => 11,
                'checkupRequest_date' => '2024-11-18',
                'checkupConfirmed_date' => '2024-11-19',
                'checkup_startTime' => '10:00:00',
                'checkup_endTime' => '11:00:00',
                'checkupStatus_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed checkups
        DB::table('checkups')->insert([
            [
                'checkup_id' => 1,
                'patient_id' => 17,
                'doctor_id' => 11,
                'checkup_date' => '2024-11-19',
                'checkup_subjective' => 'Patient feels dizzy',
                'checkup_objective' => 'Elevated heart rate',
                'checkup_assessment' => 'Possible dehydration',
                'checkup_plan' => 'Hydration and rest',
                'checkup_survWorkup' => 'Follow-up required',
                'checkup_patientStatus' => 'Improved',
                'checkup_schedule' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        
        // Seed symptom_surveys
        DB::table('symptom_surveys')->insert([
            [
                'symptomSurvey_id' => 1,
                'cancer_type' => 1,
                'symptom_name' => 'Headache',
                'symptom_medicalTerm' => 'Cephalalgia',
                'symptom_category' => 1,
                'symptom_filipino' => 'Sakit ng Ulo',
                'symptom_alertable' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed survey_responses
        DB::table('survey_responses')->insert([
            [
                'surveyResponse_id' => 1,
                'patient_id' => 17,
                'doctor_id' => 11,
                'surveyResponse_date' => '2024-11-18',
                // 'symptomSurvey_id' => 1,
                'response_notes' => 'Mild and occasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed notification_logs
        DB::table('notification_logs')->insert([
            [
                'notifLog_id' => 1,
                'notification_date' => '2024-11-18',
                'notification_type' => 1,
                'notification_status' => 1,
                'notification_receiver' => 17,
                'notification_sender' => 11,
                'notification_notes' => 'Reminder for upcoming checkup',
                'notification_subject' => 'Submission of Lab',
                'surveyResponse_id' => 1,
                'labSubmitted_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        // Seed surveyResponses_symptomSurvey
        // DB::table('surveyResponses_symptomSurvey')->insert([
        //     [
        //         'surveyResponses_symptomSurvey_id' => 1,
        //         'symptomSurvey_id' => 5,
        //         'surveyResponse_id' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ]);
    }
}
