<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('rxtypes')->truncate();
        DB::table('surgeries')->truncate();
        DB::table('chemo_protocols')->truncate();
        DB::table('chemotherapies')->truncate();
        DB::table('immunotherapies')->truncate();
        DB::table('hormonals')->truncate();
        DB::table('rad_details')->truncate();
        DB::table('radiotherapies')->truncate();
        DB::table('treatments')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
