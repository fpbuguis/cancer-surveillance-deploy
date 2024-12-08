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
        // Seed Provinces of Region 7 (Central Visayas)
        $provinces = [
            ['Bohol', 8],
            ['Cebu', 8],
        ];

        $provinceIdStart = 38; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 7
        $cities = [
            ['Tagbilaran City', 38, 8], 
            ['Cebu City', 39, 8], 
            ['Lapu-Lapu City', 39, 8], 
            ['Mandaue City', 39, 8], 
            ['Toledo City', 39, 8], 
            ['Talisay City', 39, 8], 
            ['Bogo City', 39, 8], 
            ['Carcar City', 39, 8], 
            ['Danao City', 39, 8], 
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 7
        $municipalities = [
            // Bohol
            ['Alburquerque', 38, 8],
            ['Alicia', 38, 8],
            ['Anda', 38, 8],
            ['Antequera', 38, 8],
            ['Baclayon', 38, 8],
            ['Balilihan', 38, 8],
            ['Batuan', 38, 8],
            ['Bien Unido', 38, 8],
            ['Bilar', 38, 8],
            ['Buenavista', 38, 8],
            ['Calape', 38, 8],
            ['Candijay', 38, 8],
            ['Carmen', 38, 8],
            ['Catigbian', 38, 8],
            ['Clarin', 38, 8],
            ['Corella', 38, 8],
            ['Cortes', 38, 8],
            ['Dagohoy', 38, 8],
            ['Danao', 38, 8],
            ['Dauis', 38, 8],
            ['Dimiao', 38, 8],
            ['Duero', 38, 8],
            ['Garcia Hernandez', 38, 8],
            ['Getafe', 38, 8],
            ['Guindulman', 38, 8],
            ['Inabanga', 38, 8],
            ['Jagna', 38, 8],
            ['Lila', 38, 8],
            ['Loay', 38, 8],
            ['Loboc', 38, 8],
            ['Loon', 38, 8],
            ['Mabini', 38, 8],
            ['Maribojoc', 38, 8],
            ['Panglao', 38, 8],
            ['Pilar', 38, 8],
            ['Pres. Carlos P. Garcia', 38, 8],
            ['Sagbayan', 38, 8],
            ['San Isidro', 38, 8],
            ['San Miguel', 38, 8],
            ['Sevilla', 38, 8],
            ['Sierra Bullones', 38, 8],
            ['Sikatuna', 38, 8],
            ['Talibon', 38, 8],
            ['Trinidad', 38, 8],
            ['Tubigon', 38, 8],
            ['Ubay', 38, 8],
            ['Valencia', 38, 8],

            // Cebu
            ['Alcantara', 39, 8],
            ['Alcoy', 39, 8],
            ['Alegria', 39, 8],
            ['Aloguinsan', 39, 8],
            ['Argao', 39, 8],
            ['Asturias', 39, 8],
            ['Badian', 39, 8],
            ['Balamban', 39, 8],
            ['Bantayan', 39, 8],
            ['Barili', 39, 8],
            ['Boljoon', 39, 8],
            ['Borbon', 39, 8],
            ['Carmen', 39, 8],
            ['Catmon', 39, 8],
            ['Compostela', 39, 8],
            ['Consolacion', 39, 8],
            ['Cordova', 39, 8],
            ['Daanbantayan', 39, 8],
            ['Dalaguete', 39, 8],
            ['Ginatilan', 39, 8],
            ['Liloan', 39, 8],
            ['Madridejos', 39, 8],
            ['Malabuyoc', 39, 8],
            ['Medellin', 39, 8],
            ['Minglanilla', 39, 8],
            ['Moalboal', 39, 8],
            ['Oslob', 39, 8],
            ['Pilar', 39, 8],
            ['Pinamungahan', 39, 8],
            ['Poro', 39, 8],
            ['Ronda', 39, 8],
            ['Samboan', 39, 8],
            ['San Fernando', 39, 8],
            ['San Francisco', 39, 8],
            ['San Remigio', 39, 8],
            ['Santa Fe', 39, 8],
            ['Santander', 39, 8],
            ['Sibonga', 39, 8],
            ['Sogod', 39, 8],
            ['Tabogon', 39, 8],
            ['Tabuelan', 39, 8],
            ['Tuburan', 39, 8],
            ['Tudela', 39, 8],
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
        DB::table('municipalities')->where('region_id', 8)->delete();
        DB::table('cities')->where('region_id', 8)->delete();
        DB::table('provinces')->where('region_id', 8)->delete();
    }
};
