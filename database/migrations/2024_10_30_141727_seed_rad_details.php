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
        $radTypes = [
            'External Beam Radiotherapy',
            'Intensity-Modulated Radiation Therapy (IMRT)',
            'Stereotactic Body Radiation Therapy (SBRT)',
            'Proton Beam Therapy',
            'Brachytherapy'
        ];

        foreach ($radTypes as $radType) {
            DB::table('rad_details')->insert([
                'radDetails_name' => $radType
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('rad_details')->whereIn('radDetails_name',[
                'External Beam Radiotherapy',
                'Intensity-Modulated Radiation Therapy (IMRT)',
                'Stereotactic Body Radiation Therapy (SBRT)',
                'Proton Beam Therapy',
                'Brachytherapy'
            ])->delete();
    }
};
