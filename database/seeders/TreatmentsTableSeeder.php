<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class TreatmentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('treatments')->insert([
            [
                'patient_id' => 4,
                'treatment_primaryRxType' => 'Surgery',
                'treatment_primaryRxName' => 'Appendectomy',
                'treatment_initialRxDate' => '2023-06-15',
                'treatment_purpose' => 'Curative-complete',
                'treatment_plan' => 1,
                'treatment_encoder' => 1,
            ],
            // Additional treatment entries...
        ]);
    }
}
