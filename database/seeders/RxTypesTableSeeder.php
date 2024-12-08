<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RxTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('rxtypes')->insert([
            [
                'patient_id' => 4,
                'rxtype_surgery' => true,
                'rxtype_chemotherapy' => true,
                'rxtype_radiotherapy' => true,
                'rxtype_immunotherapy' => true,
                'rxtype_hormonaltherapy' => true,
                'rxtype_others' => false,
                'rxtype_notes' => 'Scheduled for surgery',
                'rxtype_encoder' => 1
            ],
            // Repeat similar entries for other patients...
        ]);
    }
}
