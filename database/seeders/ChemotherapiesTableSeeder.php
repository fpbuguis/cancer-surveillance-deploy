<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ChemotherapiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('chemotherapies')->insert([
            [
                'patient_id' => 4,
                'chemo_type' => 'Palliative',
                'chemo_protocol' => 1,
                'chemo_initial_date' => '2023-04-10',
                'chemo_end_date' => '2023-09-10',
                'chemo_cycleNumGiven' => 6,
                'chemo_notes' => 'Stable response',
                'chemo_isCompleted' => true,
                'chemo_status' => 'Completed',
                'chemo_facility' => 1,
                'chemo_doctor' => 2,
                'chemo_encoder' => 1,
            ],
            // Add more chemotherapies as needed...
        ]);
    }
}
