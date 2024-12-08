<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class HormonalsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('hormonals')->insert([
            [
                'patient_id' => 4,
                'hormonal_drugs' => 'Tamoxifen',
                'hormonal_dose' => 20,
                'hormonal_initial_date' => '2023-01-20',
                'hormonal_end_date' => '2023-06-20',
                'hormonal_status' => 'Completed',
                'hormonal_notes' => 'No adverse reactions',
                'hormonal_doctor' => 4,
                'hormonal_encoder' => 1,
            ],
            // Additional hormonal entries...
        ]);
    }
}
