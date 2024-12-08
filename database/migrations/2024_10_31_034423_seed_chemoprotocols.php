<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $chemoProtocols = [
            ['chemo_drugs' => 'Cyclophosphamide', 'chemo_dosage' => 500, 'chemo_NoCycle' => 6, 'chemo_diluent' => 'Saline'],
            ['chemo_drugs' => 'Methotrexate', 'chemo_dosage' => 25, 'chemo_NoCycle' => 8, 'chemo_diluent' => 'Saline'],
            ['chemo_drugs' => 'Doxorubicin', 'chemo_dosage' => 75, 'chemo_NoCycle' => 4, 'chemo_diluent' => 'D5W'],
            ['chemo_drugs' => 'Cisplatin', 'chemo_dosage' => 100, 'chemo_NoCycle' => 3, 'chemo_diluent' => 'Saline'],
            ['chemo_drugs' => 'Paclitaxel', 'chemo_dosage' => 175, 'chemo_NoCycle' => 6, 'chemo_diluent' => 'D5W'],
        ];

        foreach ($chemoProtocols as $protocol) {
            DB::table('chemoprotocols')->insert($protocol);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('chemoprotocols')->whereIn('chemo_drugs', [
            'Cyclophosphamide',
            'Methotrexate',
            'Doxorubicin',
            'Cisplatin',
            'Paclitaxel'
        ])->delete();
    }
};
