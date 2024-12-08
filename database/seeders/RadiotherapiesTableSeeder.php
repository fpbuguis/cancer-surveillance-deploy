<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RadiotherapiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('radiotherapies')->insert([
            [
                'patient_id' => 4,
                'radRx_type' => 1,
                'radRx_initial_date' => '2023-08-25',
                'radRx_last_date' => '2023-09-25',
                'radRx_dose' => 60,
                'radRx_bodySite' => 'Brain',
                'radRx_status' => 'Completed',
                'radRx_isCompleted' => true,
                'radRx_facility' => 1,
                'radRx_doctor' => 2,
                'radRx_encoder' => 1,
            ],
            // Additional radiotherapy entries...
        ]);
    }
}
