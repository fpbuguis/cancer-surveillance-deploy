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
            ['Bukidnon', 11],   
            ['Camiguin', 11],   
            ['Lanao del Norte', 11],   
            ['Misamis Occidental', 11], 
            ['Misamis Oriental', 11],   
        ];

        // Change the starting province ID to 49
        $provinceIdStart = 49; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        $cities = [
            ['Cagayan de Oro City', 53, 11],  
            ['El Salvador City', 53, 11],     
            ['Gingoog City', 53, 11],         
            ['Malaybalay City', 49, 11],     
            ['Valencia City', 49, 11],         
            ['Magsaysay', 49, 11],             
            ['Initao', 52, 11],                
            ['Jimenez', 52, 11],              
            ['Oroquieta', 52, 11],             
            ['Tangub City', 52, 11],          
            ['Iligan City', 51, 11],           
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 11
        $municipalities = [
            // Bukidnon
            ['Baungon', 49, 11],
            ['Bongabon', 49, 11],
            ['Cabanglasan', 49, 11],
            ['Damulog', 49, 11],
            ['Kadingilan', 49, 11],
            ['Kalilangan', 49, 11],
            ['Lantapan', 49, 11],
            ['Malitbog', 49, 11],
            ['Manolo Fortich', 49, 11],
            ['Maramag', 49, 11],
            ['Pangantucan', 49, 11],
            ['Quezon', 49, 11],
            ['San Fernando', 49, 11],
            ['Sumilao', 49, 11],
            ['Talakag', 49, 11],

            // Camiguin
            ['Mambajao', 50, 11],
            ['Balingoan', 50, 11],
            ['Gingoog', 50, 11],
            ['Salah', 50, 11],
            ['Catarman', 50, 11],

            // Lanao del Norte
            ['Apo Mabilog', 51, 11],
            ['Bacolod', 51, 11],
            ['Kapatagan', 51, 11],
            ['Lala', 51, 11],
            ['Magsaysay', 51, 11],
            ['Maigo', 51, 11],
            ['Tubod', 51, 11],

            // Misamis Occidental
            ['Aloran', 52, 11],
            ['Baliangao', 52, 11],
            ['Clarin', 52, 11],
            ['Jimenez', 52, 11],
            ['Lopez Jaena', 52, 11],
            ['Oroquieta', 52, 11],
            ['Tangub City', 52, 11],

            // Misamis Oriental
            ['Initao', 53, 11],
            ['Jasaan', 53, 11],
            ['Lagonglong', 53, 11],
            ['Libertad', 53, 11],
            ['Manticao', 53, 11],
            ['Medina', 53, 11],
            ['Naawan', 53, 11],
            ['Salay', 53, 11],
            ['Sugbongcogon', 53, 11],
            ['Villanueva', 53, 11],
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
        DB::table('municipalities')->where('region_id', 11)->delete();
        DB::table('cities')->where('region_id', 11)->delete();
        DB::table('provinces')->where('region_id', 11)->delete();
    }
};
