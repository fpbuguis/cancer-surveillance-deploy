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
        // Seed NCR (Metro Manila) provinces
        $provinces = [
            ['Metro Manila', 15],
        ];

        $provinceId = 68; 
        foreach ($provinces as $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceId,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed NCR (Metro Manila) cities
        $cities = [
            // 1st District
            ['City of Manila', 68, 15],
            
            // 2nd District
            ['Mandaluyong City', 68, 15],
            ['Marikina City', 68, 15],
            ['Pasig City', 68, 15],
            ['Quezon City', 68, 15],
            ['San Juan City', 68, 15],
            
            // 3rd District
            ['Caloocan City', 68, 15],
            ['Malabon City', 68, 15],
            ['Navotas City', 68, 15],
            ['Valenzuela City', 68, 15],
            
            // 4th District
            ['Las Piñas City', 68, 15],
            ['Makati City', 68, 15],
            ['Muntinlupa City', 68, 15],
            ['Parañaque City', 68, 15],
            ['Pasay City', 68, 15],
            ['Taguig City', 68, 15],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed NCR (Metro Manila) municipalities
        $municipalities = [
            ['Pateros', 68, 15]
        ];

        foreach ($municipalities as $municipality) {
            DB::table('municipalities')->insert([
                'municipality_name' => $municipality[0],
                'province_id' => $municipality[1],
                'region_id' => $municipality[2],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('municipalities')->where('region_id', 15)->delete();
        DB::table('cities')->where('region_id', 15)->delete();
        DB::table('provinces')->where('region_id', 15)->delete();
    }
};
