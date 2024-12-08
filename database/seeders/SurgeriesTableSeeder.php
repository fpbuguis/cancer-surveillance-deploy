<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SurgeriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('surgeries')->insert([
            [
                'patient_id' => 4,
                'surgery_operation' => 'Appendectomy',
                'surgery_date' => '2023-06-15',
                'surgery_findings' => 'Appendicitis',
                'surgery_intent' => 'Curative-complete',
                'surgery_surgeon' => 1,
                'surgery_hospital' => 1,
                'surgery_encoder' => 1,
            ],
            // Repeat similar entries for other surgeries...
        ]);
    }
}
