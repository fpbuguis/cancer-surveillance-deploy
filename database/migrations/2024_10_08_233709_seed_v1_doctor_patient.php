<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $patients = [
            [1, 25],
            [2, 29],
            [3, 28],
            [4, 1],
            [5, 4],
            [6, 10],
            [7, 2],
            [8, 9],
            [9, 11],
            [10, 15],
            [11, 18],
            [12, 5],
            [13, 3],
            [14, 30],
            [15, 20],
            [16, 24],
        ];

        foreach ($patients as $patient) {
            DB::table('patients')->insert([
                'patient_id' => $patient[0],
                'user_id' => $patient[1],
            ]);
        }

        $clinicHours = [
            [1, '2023-10-10', '09:00:00', '17:00:00', true, now(), now()],
            [2, '2023-10-11', '08:30:00', '16:30:00', false, now(), now()],
            [3, '2023-10-12', '10:00:00', '18:00:00', true, now(), now()],
            [4, '2023-10-13', '09:30:00', '17:30:00', true, now(), now()],
            [5, '2023-10-14', '08:00:00', '14:00:00', false, now(), now()],
            [6, '2023-10-15', '11:00:00', '19:00:00', true, now(), now()],
            [7, '2023-10-16', '09:00:00', '17:00:00', true, now(), now()],
            [8, '2023-10-17', '08:30:00', '16:30:00', false, now(), now()],
            [9, '2023-10-18', '10:00:00', '18:00:00', true, now(), now()],
            [10, '2023-10-19', '09:30:00', '17:30:00', true, now(), now()],
        ];

        foreach ($clinicHours as $hours) {
            DB::table('clinic_hours')->insert([
                'clinic_hours_id' => $hours[0],
                'clinic_hours_date' => $hours[1],
                'clinic_hours_start_time' => $hours[2],
                'clinic_hours_end_time' => $hours[3],
                'clinic_hours_is_recurring' => $hours[4],
                'created_at' => $hours[5],
                'updated_at' => $hours[6],
            ]);
        }

        $doctors = [
            [1, 14, 1, 1, 2, null, '123040', '2028-09-20', 1],
            [2, 22, 1, 1, 2, null, '110324', '2029-08-18', 2],
            [3, 7, 3, 4, 3, null, '100450', '2027-07-12', 3],
            [4, 17, 3, 4, 3, null, '118945', '2025-04-05', 4],
            [5, 8, 2, 3, 4, null, '137028', '2028-09-18', 5],
            [6, 26, 5, 3, 4, null, '108934', '2026-10-12', 6],
            [7, 23, 3, 2, 1, null, '100982', '2027-12-03', 7],
            [8, 6, 4, 2, 1, null, '119034', '2027-11-11', 8],
            [9, 21, 2, 1, 2, null, '128934', '2028-04-29', 9],
            [10, 13, 3, 2, 5, null, '101997', '2027-05-28', 10],
        ];

        foreach ($doctors as $doctor) {
            DB::table('doctors')->insert([
                'Doctor_ID' => $doctor[0],
                'user_id' => $doctor[1],
                'doctor_hospital' => $doctor[2],
                'doctor_department' => $doctor[3],
                'doctor_specialty' => $doctor[4],
                'doctor_eSignature' => $doctor[5],
                'doctor_licenseNumber' => $doctor[6],
                'doctor_licenseExpiry' => $doctor[7],
                'doctor_schedule' => $doctor[8],
            ]);
        }

        $onboards = [
            [1, 1, 7],
            [2, 1, 3],
            [3, 2, 2],
            [4, 3, 9],
            [5, 4, 8],
            [6, 4, 2],
            [7, 5, 1],
            [8, 6, 2],
            [9, 7, 2],
            [10, 8, 10],
            [11, 8, 9],
            [12, 8, 4],
            [13, 9, 9],
            [14, 10, 2],
            [15, 11, 8],
            [16, 11, 4],
            [17, 12, 1],
            [18, 13, 7],
            [19, 14, 7],
            [20, 14, 4],
            [21, 15, 9],
            [22, 16, 8],
        ];

        foreach ($onboards as $onboard) {
            DB::table('onboards')->insert([
                'onboard_id' => $onboard[0],
                'patient_id' => $onboard[1],
                'doctor_id' => $onboard[2],
            ]);
        }

        $metastaticSites = [
            [1, 7, False, False, True, False, False, False, False, False, False, False, null],
            [2, 11, False, False, False, True, False, False, False, False, False, False, null],
            [3, 12, False, False, True, False, False, False, False, False, False, False, null],
        ];

        foreach ($metastaticSites as $site) {
            DB::table('metastatic_sites')->insert([
                'mets_ID' => $site[0],
                'patient_ID' => $site[1],
                'mets_distantLN' => $site[2],
                'mets_bone' => $site[3],
                'mets_liver' => $site[4],
                'mets_lung' => $site[5],
                'mets_brain' => $site[6],
                'mets_ovary' => $site[7],
                'mets_skin' => $site[8],
                'mets_intestine' => $site[9],
                'mets_others' => $site[10],
                'mets_unknown' => $site[11],
                'mets_notes' => $site[12],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Seed disease statuses
        $diseaseStatuses = [
            [1, 1, True, False, False, False, True],
            [2, 2, True, True, False, False, False],
            [3, 3, True, True, False, False, False],
            [4, 4, True, False, False, False, True],
            [5, 5, True, True, False, False, False],
            [6, 6, True, False, False, False, True],
            [7, 7, True, False, False, True, False],
            [8, 8, True, False, False, False, True],
            [9, 9, True, True, False, False, False],
            [10, 10, True, False, False, False, False],
            [11, 11, True, False, False, True, False],
            [12, 12, True, True, False, True, False],
            [13, 13, True, False, False, False, True],
            [14, 14, True, False, False, False, True],
            [15, 15, True, True, False, False, False],
            [16, 16, True, False, False, False, True],
        ];

        foreach ($diseaseStatuses as $status) {
            DB::table('disease_statuses')->insert([
                'dxStatus_ID' => $status[0],
                'patient_ID' => $status[1],
                'dxStatus_alive' => $status[2],
                'dxStatus_symptoms' => $status[3],
                'dxStatus_recurrence' => $status[4],
                'dxStatus_metastatic' => $status[5],
                'dxStatus_curative' => $status[6],
            ]);
        }

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('patients')->truncate();
        DB::table('doctors')->truncate();
        DB::table('onboards')->truncate();
        DB::table('metastatic_sites')->truncate();
        DB::table('disease_statuses')->truncate();
        DB::table('clinic_hours')->truncate();
    }
};
