<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ChemoProtocolsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('chemoprotocols')->insert([
            [
                'chemo_drugs' => 'Doxorubicin',
                'chemo_dosage' => 50,
                'chemo_NoCycle' => 6,
                'chemo_diluent' => 'Saline'
            ],
            // Additional protocols if needed
        ]);
    }
}
