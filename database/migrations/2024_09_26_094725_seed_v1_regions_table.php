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
         // Seed for regions table
         $regions = [
            ['Region I - Ilocos Region'],
            ['Region II - Cagayan Valley'],
            ['Region III - Central Luzon'],
            ['Region IV-A - CALABARZON'],
            ['Region IV-B - MIMAROPA'],
            ['Region V - Bicol Region'],
            ['Region VI - Western Visayas'],
            ['Region VII - Central Visayas'],
            ['Region VIII - Eastern Visayas'],
            ['Region IX - Zamboanga Peninsula'],
            ['Region X - Northern Mindanao'],
            ['Region XI - Davao Region'],
            ['Region XII - SOCCSKSARGEN'],
            ['Region XIII - Caraga'],
            ['NCR - National Capital Region'],
            ['CAR - Cordillera Administrative Region'],
            ['NIR - Negros Island Region'],
            ['BARMM - Bangsamoro Autonomous Region in Muslim Mindanao'],
        ];

        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'region_name' => $region[0],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regions')->wherein('region_name', [
            'Region I - Ilocos Region',
            'Region II - Cagayan Valley',
            'Region III - Central Luzon',
            'Region IV-A - CALABARZON',
            'Region IV-B - MIMAROPA',
            'Region V - Bicol Region',
            'Region VI - Western Visayas',
            'Region VII - Central Visayas',
            'Region VIII - Eastern Visayas',
            'Region IX - Zamboanga Peninsula',
            'Region X - Northern Mindanao',
            'Region XI - Davao Region',
            'Region XII - SOCCSKSARGEN',
            'Region XIII - Caraga',
            'NCR - National Capital Region',
            'CAR - Cordillera Administrative Region',
            'NIR - Negros Island Region',
            'BARMM - Bangsamoro Autonomous Region in Muslim Mindanao',
        ])->delete();
    }
};
