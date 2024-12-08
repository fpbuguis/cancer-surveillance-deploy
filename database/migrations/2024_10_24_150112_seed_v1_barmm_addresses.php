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
            ['Basilan', 18],              
            ['Lanao del Sur', 18],      
            ['Maguindanao del Norte', 18], 
            ['Maguindanao del Sur', 18], 
            ['Sulu', 18],                
            ['Tawi-Tawi', 18],           
        ];

        $provinceIdStart = 78; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of BARMM
        $cities = [
            ['Isabela City', 78, 18],     
            ['Cotabato City', 78, 18],     
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of BARMM
        $municipalities = [
            // Basilan
            ['Akbar', 78, 18], 
            ['Al-Barka', 78, 18],
            ['Bacungan', 78, 18],
            ['Bongao', 78, 18],
            ['Lantawan', 78, 18],
            ['Maluso', 78, 18],
            ['Sumisip', 78, 18],
            ['Tuburan', 78, 18],

            // Lanao del Sur
            ['Bacolod-Calawan', 79, 18], 
            ['Balindong', 79, 18],
            ['Balo-i', 79, 18],
            ['Bayang', 79, 18],
            ['Bubong', 79, 18],
            ['Butig', 79, 18],
            ['Calanogas', 79, 18],
            ['Kapai', 79, 18],
            ['Lumbatan', 79, 18],
            ['Malabang', 79, 18],
            ['Marantao', 79, 18],
            ['Marawi City', 79, 18],
            ['Pualas', 79, 18],
            ['Saguiaran', 79, 18],
            ['Sultan Gumander', 79, 18],
            ['Sultan Naga Dimaporo', 79, 18],
            ['Tagoloan II', 79, 18],

            // Maguindanao del Norte
            ['Ampatuan', 80, 18], 
            ['Datu Abdullah Sangki', 80, 18],
            ['Datu Anggal Midtimbang', 80, 18],
            ['Datu Odin Sinsuat', 80, 18],
            ['Datu Piang', 80, 18],
            ['Kabuntalan', 80, 18],
            ['Mamasapano', 80, 18],
            ['Mangudadatu', 80, 18],
            ['Shariff Aguak', 80, 18],
            ['Sultan Kudarat', 80, 18],
            ['Talitay', 80, 18],

            // Maguindanao del Sur
            ['Balay', 81, 18], 
            ['Buluan', 81, 18],
            ['Cotabato City', 81, 18],
            ['Datu Paglas', 81, 18],
            ['Maguindanao', 81, 18],

            // Sulu
            ['Banguingui', 82, 18], 
            ['Holoh', 82, 18],
            ['Jolo', 82, 18],
            ['Maimbung', 82, 18],
            ['Parang', 82, 18],
            ['Siasi', 82, 18],
            ['Talipao', 82, 18],
            ['Tapul', 82, 18],

            // Tawi-Tawi
            ['Bongao', 83, 18], 
            ['Languyan', 83, 18],
            ['Mapun', 83, 18],
            ['Simunul', 83, 18],
            ['Sapa-Sapa', 83, 18],
            ['Tandubas', 83, 18],
            ['Tawi-Tawi', 83, 18],
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
        DB::table('municipalities')->where('region_id', 18)->delete();
        DB::table('cities')->where('region_id', 18)->delete();
        DB::table('provinces')->where('region_id', 18)->delete();
    }
};
