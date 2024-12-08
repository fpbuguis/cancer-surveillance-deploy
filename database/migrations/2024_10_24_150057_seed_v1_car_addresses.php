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
        // Seed Provinces of CAR
        $provinces = [
            ['Abra', 16],
            ['Apayao', 16],
            ['Benguet', 16],
            ['Ifugao', 16],
            ['Kalinga', 16],
            ['Mountain Province', 16],
        ];

        $provinceIdStart = 69; 
        foreach ($provinces as $index => $province) {
            $provinceId = $provinceIdStart + $index; // Incrementing ID for each province
            DB::table('provinces')->insert([
                'province_id' => $provinceId,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of CAR
        $cities = [
            ['Baguio City', 71, 16],
            ['Tabuk City', 73, 16],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of CAR
        $municipalities = [
            // Abra
            ['Bangued', 69, 16],
            ['Bucloc', 69, 16],
            ['Boliney', 69, 16],
            ['Bucay', 69, 16],
            ['Daguioman', 69, 16],
            ['Danglas', 69, 16],
            ['Dolores', 69, 16],
            ['La Paz', 69, 16],
            ['Lacub', 69, 16],
            ['Lagangilang', 69, 16],
            ['Lagayan', 69, 16],
            ['Langiden', 69, 16],
            ['Licuan-Baay', 69, 16],
            ['Luba', 69, 16],
            ['Malibcong', 69, 16],
            ['Manabo', 69, 16],
            ['Peñarrubia', 69, 16],
            ['Sallapadan', 69, 16],
            ['San Isidro', 69, 16],
            ['San Juan', 69, 16],
            ['San Quintin', 69, 16],
            ['Tayum', 69, 16],
            ['Tineg', 69, 16],
            ['Tubo', 69, 16],
            ['Villaviciosa', 69, 16],

            // Apayao
            ['Calanasan', 70, 16],
            ['Conner', 70, 16],
            ['Flora', 70, 16],
            ['Kabugao', 70, 16],
            ['Luna', 70, 16],
            ['Pudtol', 70, 16],
            ['Santa Marcela', 70, 16],

            // Benguet
            ['Atok', 71, 16],
            ['Bakun', 71, 16],
            ['Benguet', 71, 16],
            ['Bokod', 71, 16],
            ['Buguias', 71, 16],
            ['Itogon', 71, 16],
            ['Kabayan', 71, 16],
            ['Kibungan', 71, 16],
            ['La Trinidad', 71, 16],
            ['Mankayan', 71, 16],
            ['Tuba', 71, 16],
            ['Sablan', 71, 16],
            ['Tublay', 71, 16],

            // Ifugao
            ['Aguinaldo', 72, 16],
            ['Alfonso Lista', 72, 16],
            ['Asipulo', 72, 16],
            ['Banaue', 72, 16],
            ['Hingyon', 72, 16],
            ['Hungduan', 72, 16],
            ['Kiangan', 72, 16],
            ['Lagawe', 72, 16],
            ['Lamut', 72, 16],
            ['Mayoyao', 72, 16],
            ['Tinoc', 72, 16],

            // Kalinga
            ['Balbalan', 73, 16],
            ['Lubuagan', 73, 16],
            ['Pasil', 73, 16],
            ['Pinukpuk', 73, 16],
            ['Rizal', 73, 16],
            ['Tanudan', 73, 16],
            ['Tinglayan', 73, 16],

            // Mountain Province
            ['Barlig', 74, 16],
            ['Bauko', 74, 16],
            ['Besao', 74, 16],
            ['Bontoc', 74, 16],
            ['Natonin', 74, 16],
            ['Paracelis', 74, 16],
            ['Sabangan', 74, 16],
            ['Sagada', 74, 16],
            ['Tadian', 74, 16],
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
        DB::table('municipalities')->where('region_id', 16)->delete();
        DB::table('cities')->where('region_id', 16)->delete();
        DB::table('provinces')->where('region_id', 16)->delete();
    }
};
