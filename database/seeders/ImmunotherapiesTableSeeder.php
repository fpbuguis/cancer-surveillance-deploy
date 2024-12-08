<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ImmunotherapiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('immunotherapies')->insert([
            [
                'patient_id' => 4,
                'immunoRx_drugs' => 'Pembrolizumab',
                'immunoRx_initial_date' => '2022-12-01',
                'immunoRx_end_date' => '2023-01-15',
                'immunoRx_status' => 'Completed',
                'immunoRx_isCompleted' => true,
                'immunoRx_facility' => 2,
                'immunoRx_doctor' => 3,
                'immunoRx_encoder' => 1,
            ],
            // Add more immunotherapies as needed...
        ]);
    }
}
