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
        // Seed Provinces of Region 4B (MIMAROPA)
        $provinces = [
            ['Occidental Mindoro', 5],
            ['Oriental Mindoro', 5],
            ['Marinduque', 5],
            ['Romblon', 5],
            ['Palawan', 5],
        ];

        $provinceIdStart = 22; // Starting ID for Region 4B provinces
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 4B (MIMAROPA)
        $cities = [
            // Oriental Mindoro
            ['Calapan City', 23, 5],
            // Palawan
            ['Puerto Princesa City', 26, 5],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 4B (MIMAROPA)
        $municipalities = [
            // Occidental Mindoro
            ['Abra de Ilog', 22, 5],
            ['Calintaan', 22, 5],
            ['Looc', 22, 5],
            ['Lubang', 22, 5],
            ['Magsaysay', 22, 5],
            ['Mamburao', 22, 5],
            ['Paluan', 22, 5],
            ['Rizal', 22, 5],
            ['Sablayan', 22, 5],
            ['San Jose', 22, 5],
            ['Santa Cruz', 22, 5],

            // Oriental Mindoro
            ['Baco', 23, 5],
            ['Bansud', 23, 5],
            ['Bongabong', 23, 5],
            ['Bulalacao', 23, 5],
            ['Gloria', 23, 5],
            ['Mansalay', 23, 5],
            ['Naujan', 23, 5],
            ['Pinamalayan', 23, 5],
            ['Pola', 23, 5],
            ['Puerto Galera', 23, 5],
            ['Roxas', 23, 5],
            ['San Teodoro', 23, 5],
            ['Socorro', 23, 5],
            ['Victoria', 23, 5],

            // Marinduque
            ['Boac', 24, 5],
            ['Buenavista', 24, 5],
            ['Gasan', 24, 5],
            ['Mogpog', 24, 5],
            ['Santa Cruz', 24, 5],
            ['Torrijos', 24, 5],

            // Romblon
            ['Alcantara', 25, 5],
            ['Banton', 25, 5],
            ['Cajidiocan', 25, 5],
            ['Calatrava', 25, 5],
            ['Concepcion', 25, 5],
            ['Corcuera', 25, 5],
            ['Ferrol', 25, 5],
            ['Looc', 25, 5],
            ['Magdiwang', 25, 5],
            ['Odiongan', 25, 5],
            ['Romblon', 25, 5],
            ['San Agustin', 25, 5],
            ['San Andres', 25, 5],
            ['San Fernando', 25, 5],
            ['San Jose', 25, 5],
            ['Santa Fe', 25, 5],
            ['Santa Maria', 25, 5],

            // Palawan
            ['Aborlan', 26, 5],
            ['Agutaya', 26, 5],
            ['Araceli', 26, 5],
            ['Balabac', 26, 5],
            ['Bataraza', 26, 5],
            ['Brooke\'s Point', 26, 5],
            ['Busuanga', 26, 5],
            ['Cagayancillo', 26, 5],
            ['Coron', 26, 5],
            ['Culion', 26, 5],
            ['Cuyo', 26, 5],
            ['Dumaran', 26, 5],
            ['El Nido', 26, 5],
            ['Kalayaan', 26, 5],
            ['Linapacan', 26, 5],
            ['Magsaysay', 26, 5],
            ['Narra', 26, 5],
            ['Quezon', 26, 5],
            ['Rizal', 26, 5],
            ['Roxas', 26, 5],
            ['San Vicente', 26, 5],
            ['Sofronio Española', 26, 5],
            ['Taytay', 26, 5],
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
        DB::table('municipalities')->where('region_id', 5)->delete();
        DB::table('cities')->where('region_id', 5)->delete();
        DB::table('provinces')->where('region_id', 5)->delete();
    }
};