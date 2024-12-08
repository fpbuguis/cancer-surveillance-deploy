<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed Provinces of Region 9 (Zamboanga Peninsula) with IDs starting from 46
        $provinces = [
            ['Zamboanga del Norte', 10], 
            ['Zamboanga del Sur', 10],   
            ['Zamboanga Sibugay', 10],   
        ];

        $provinceIdStart = 46; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 9 with corresponding province IDs
        $cities = [
            ['Zamboanga City', 46, 10],  // Adjusted to match new province_id
            ['Dapitan City', 47, 10],   
            ['Dipolog City', 47, 10],    
            ['Pagadian City', 47, 10],   
            ['Zamboanga City', 46, 10],  
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 9
        $municipalities = [
            // Zamboanga del Norte
            ['Alicia', 46, 10],
            ['Baliguian', 46, 10],
            ['Bongao', 46, 10],
            ['Bucana', 46, 10],
            ['Cebu', 46, 10],
            ['Dapitan', 46, 10],
            ['Dumingag', 46, 10],
            ['Godod', 46, 10],
            ['Guipos', 46, 10],
            ['Labason', 46, 10],
            ['Lapuyan', 46, 10],
            ['Leon Postigo', 46, 10],
            ['Manukan', 46, 10],
            ['Mutia', 46, 10],
            ['Rizal', 46, 10],
            ['Sibutad', 46, 10],
            ['Sindangan', 46, 10],
            ['Tampilisan', 46, 10],

            // Zamboanga del Sur
            ['Aurora', 47, 10],
            ['Bayog', 47, 10],
            ['Dimataling', 47, 10],
            ['Dinas', 47, 10],
            ['Lapuyan', 47, 10],
            ['Mahayag', 47, 10],
            ['Margosatubig', 47, 10],
            ['Pitogo', 47, 10],
            ['San Miguel', 47, 10],
            ['Tukuran', 47, 10],
            ['Vincenzo A. Sagun', 47, 10],
            ['Zamboanga del Sur', 47, 10],

            // Zamboanga Sibugay
            ['Bungan', 48, 10],
            ['Diplahan', 48, 10],
            ['Imelda', 48, 10],
            ['Ipil', 48, 10],
            ['Malangas', 48, 10],
            ['Naga', 48, 10],
            ['Olutanga', 48, 10],
            ['Payao', 48, 10],
            ['Talusan', 48, 10],
            ['Titay', 48, 10],
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
        DB::table('municipalities')->where('region_id', 10)->delete();
        DB::table('cities')->where('region_id', 10)->delete();
        DB::table('provinces')->where('region_id', 10)->delete();
    }
};
