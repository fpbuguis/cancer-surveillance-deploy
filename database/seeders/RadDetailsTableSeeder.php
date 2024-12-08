<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RadDetailsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('rad_details')->insert([
            ['radDetails_name' => 'Gamma Knife'],
            ['radDetails_name' => 'Proton Therapy'],
            // Additional radiation details if needed
        ]);
    }
}
