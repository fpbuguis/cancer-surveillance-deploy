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
        // Seed Provinces of Region 2
        $provinces = [
            ['Batanes', 2],
            ['Cagayan', 2],
            ['Isabela', 2],
            ['Nueva Vizcaya', 2],
            ['Quirino', 2],
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->insert([
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 2
        $cities = [
            ['Tuguegarao City', 6, 2],
            ['Cauayan City', 7, 2],
            ['Ilagan City', 7, 2],
            ['Santiago City', 7, 2],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 2
        $municipalities = [
            // Batanes
            ['Basco', 5, 2],
            ['Itbayat', 5, 2],
            ['Ivana', 5, 2],
            ['Mahatao', 5, 2],
            ['Sabtang', 5, 2],
            ['Uyugan', 5, 2],

            // Cagayan
            ['Abulug', 6, 2],
            ['Alcala', 6, 2],
            ['Allacapan', 6, 2],
            ['Amulung', 6, 2],
            ['Aparri', 6, 2],
            ['Baggao', 6, 2],
            ['Ballesteros', 6, 2],
            ['Buguey', 6, 2],
            ['Calayan', 6, 2],
            ['Calamaniugan', 6, 2],
            ['Claveria', 6, 2],
            ['Enrile', 6, 2],
            ['Gattaran', 6, 2],
            ['Gonzaga', 6, 2],
            ['Iguig', 6, 2],
            ['Lal-lo', 6, 2],
            ['Lasam', 6, 2],
            ['Pamplona', 6, 2],
            ['Peñablanca', 6, 2],
            ['Piat', 6, 2],
            ['Rizal', 6, 2],
            ['Sanchez-Mira', 6, 2],
            ['Santa Ana', 6, 2],
            ['Santa Praxedes', 6, 2],
            ['Santa Teresita', 6, 2],
            ['Santo Niño', 6, 2],
            ['Solana', 6, 2],
            ['Tuao', 6, 2],

            // Isabela
            ['Alicia', 7, 2],
            ['Angadanan', 7, 2],
            ['Aurora', 7, 2],
            ['Benito Soliven', 7, 2],
            ['Burgos', 7, 2],
            ['Cabagan', 7, 2],
            ['Cabatuan', 7, 2],
            ['Cordon', 7, 2],
            ['Dinapigue', 7, 2],
            ['Divilacan', 7, 2],
            ['Echague', 7, 2],
            ['Gamu', 7, 2],
            ['Jones', 7, 2],
            ['Luna', 7, 2],
            ['Maconacon', 7, 2],
            ['Delfin Albano', 7, 2],
            ['Mallig', 7, 2],
            ['Naguilian', 7, 2],
            ['Palanan', 7, 2],
            ['Quezon', 7, 2],
            ['Quirino', 7, 2],
            ['Ramon', 7, 2],
            ['Reina Mercedes', 7, 2],
            ['Roxas', 7, 2],
            ['San Agustin', 7, 2],
            ['San Guillermo', 7, 2],
            ['San Isidro', 7, 2],
            ['San Manuel', 7, 2],
            ['San Mariano', 7, 2],
            ['San Mateo', 7, 2],
            ['San Pablo', 7, 2],
            ['Santa Maria', 7, 2],
            ['Santo Tomas', 7, 2],
            ['Tumauini', 7, 2],

            // Nueva Vizcaya
            ['Ambaguio', 8, 2],
            ['Aritao', 8, 2],
            ['Bagabag', 8, 2],
            ['Bambang', 8, 2],
            ['Bayombong', 8, 2],
            ['Diadi', 8, 2],
            ['Dupax del Norte', 8, 2],
            ['Dupax del Sur', 8, 2],
            ['Kasibu', 8, 2],
            ['Kayapa', 8, 2],
            ['Quezon', 8, 2],
            ['Santa Fe', 8, 2],
            ['Solano', 8, 2],
            ['Villaverde', 8, 2],
            ['Alfonso Castaneda', 8, 2],

            // Quirino
            ['Aglipay', 9, 2],
            ['Cabarroguis', 9, 2],
            ['Diffun', 9, 2],
            ['Madela', 9, 2],
            ['Saguday', 9, 2],
            ['Nagtipunan', 9, 2],
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
        DB::table('municipalities')->delete();
        DB::table('provinces')->delete();
        DB::table('cities')->delete();
    }
};
