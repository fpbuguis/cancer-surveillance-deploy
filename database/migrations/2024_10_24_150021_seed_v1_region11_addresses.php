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
        
        $provinces = [
            ['Davao de Oro', 12],   
            ['Davao del Norte', 12], 
            ['Davao del Sur', 12],   
            ['Davao Occidental', 12], 
            ['Davao Oriental', 12],   
        ];

        $provinceIdStart = 54; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 12
        $cities = [
            ['Davao City', 55, 12], 
            ['Panabo City', 55, 12], 
            ['Tagum City', 54, 12], 
            ['Digos City', 56, 12], 
            ['Samal City', 56, 12], 
            ['Mati City', 58, 12], 
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 12
        $municipalities = [
            // Davao de Oro
            ['Asuncion', 54, 12],
            ['Braulio E. Dujali', 54, 12],
            ['Caraga', 54, 12],
            ['Carmen', 54, 12],
            ['Maragusan', 54, 12],
            ['New Bataan', 54, 12],
            ['Nabunturan', 54, 12],
            ['San Manuel', 54, 12],
            ['Sto. Tomas', 54, 12],

            // Davao del Norte
            ['Bacong', 55, 12],
            ['Kapalong', 55, 12],
            ['Laak', 55, 12],
            ['New Corella', 55, 12],
            ['Santo Tomas', 55, 12],
            ['Talaingod', 55, 12],

            // Davao del Sur
            ['Bansalan', 56, 12],
            ['Digos', 56, 12],
            ['Hagonoy', 56, 12],
            ['Kiblawan', 56, 12],
            ['Matanao', 56, 12],
            ['Magsaysay', 56, 12],
            ['Padada', 56, 12],
            ['Santa Cruz', 56, 12],
            ['Sulop', 56, 12],

            // Davao Occidental
            ['Don Marcelino', 57, 12],
            ['Jose Abad Santos', 57, 12],
            ['Malita', 57, 12],
            ['Santa Maria', 57, 12],

            // Davao Oriental
            ['Baganga', 58, 12],
            ['Banaybanay', 58, 12],
            ['Caraga', 58, 12],
            ['Dahican', 58, 12],
            ['Mati', 58, 12],
            ['San Isidro', 58, 12],
            ['Tarragona', 58, 12],
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
        DB::table('municipalities')->where('region_id', 12)->delete();
        DB::table('cities')->where('region_id', 12)->delete();
        DB::table('provinces')->where('region_id', 12)->delete();
    }
};
