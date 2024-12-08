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
        // Seed Provinces of Region 6 (Western Visayas)
        $provinces = [
            ['Aklan', 7],
            ['Antique', 7],
            ['Capiz', 7],
            ['Iloilo', 7],
            ['Guimaras', 7],
        ];

        $provinceIdStart = 33; // Starting ID for Region 6 provinces
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 6 (Western Visayas)
        $cities = [
            // Capiz
            ['Roxas City', 35, 7],
            // Iloilo
            ['Passi City', 36, 7],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 6 (Western Visayas)
        $municipalities = [
            // Aklan
            ['Altavas', 33, 7],
            ['Balete', 33, 7],
            ['Banga', 33, 7],
            ['Batan', 33, 7],
            ['Buruanga', 33, 7],
            ['Ibajay', 33, 7],
            ['Kalibo', 33, 7],
            ['Lezo', 33, 7],
            ['Libacao', 33, 7],
            ['Madalag', 33, 7],
            ['Makato', 33, 7],
            ['Malay', 33, 7],
            ['Malinao', 33, 7],
            ['Nabas', 33, 7],
            ['New Washington', 33, 7],
            ['Numancia', 33, 7],
            ['Tangalan', 33, 7],

            // Antique
            ['Anini-Y', 34, 7],
            ['Barbaza', 34, 7],
            ['Belison', 34, 7],
            ['Bugasong', 34, 7],
            ['Caluya', 34, 7],
            ['Culasi', 34, 7],
            ['Tobias Fornier', 34, 7],
            ['Hamtic', 34, 7],
            ['Laua-An', 34, 7],
            ['Libertad', 34, 7],

            // Capiz
            ['Cuartero', 35, 7],
            ['Dao', 35, 7],
            ['Dumalag', 35, 7],
            ['Dumarao', 35, 7],
            ['Ivisan', 35, 7],
            ['Jamindan', 35, 7],
            ['Ma-Ayon', 35, 7],
            ['Mambusao', 35, 7],
            ['Panay', 35, 7],
            ['Panitan', 35, 7],
            ['Pilar', 35, 7],
            ['Pontevedra', 35, 7],
            ['President Roxas', 35, 7],
            ['Sapi-An', 35, 7],
            ['Sigma', 35, 7],
            ['Tapaz', 35, 7],

            // Iloilo
            ['Ajuy', 36, 7],
            ['Alimodian', 36, 7],
            ['Anilao', 36, 7],
            ['Badiangan', 36, 7],
            ['Balasan', 36, 7],
            ['Banate', 36, 7],
            ['Barotac Nuevo', 36, 7],
            ['Barotac Viejo', 36, 7],
            ['Batad', 36, 7],
            ['Bingawan', 36, 7],
            ['Cabatuan', 36, 7],
            ['Calinog', 36, 7],
            ['Carles', 36, 7],
            ['Concepcion', 36, 7],
            ['Dingle', 36, 7],
            ['Dueñas', 36, 7],
            ['Dumangas', 36, 7],
            ['Estancia', 36, 7],
            ['Guimbal', 36, 7],
            ['Igbaras', 36, 7],
            ['Janiuay', 36, 7],
            ['Lambunao', 36, 7],
            ['Leganes', 36, 7],
            ['Lemery', 36, 7],
            ['Leon', 36, 7],
            ['Maasin', 36, 7],
            ['Miagao', 36, 7],
            ['Mina', 36, 7],
            ['New Lucena', 36, 7],
            ['Oton', 36, 7],
            ['Pavia', 36, 7],
            ['Pototan', 36, 7],
            ['San Dionisio', 36, 7],
            ['San Enrique', 36, 7],
            ['San Joaquin', 36, 7],
            ['San Miguel', 36, 7],
            ['San Rafael', 36, 7],
            ['Santa Barbara', 36, 7],
            ['Sara', 36, 7],
            ['Tigbauan', 36, 7],
            ['Tubungan', 36, 7],
            ['Zarraga', 36, 7],

            // Guimaras
            ['Buenavista', 37, 7],
            ['Jordan', 37, 7],
            ['Nueva Valencia', 37, 7],
            ['San Lorenzo', 37, 7],
            ['Sibunag', 37, 7],
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
        DB::table('municipalities')->where('region_id', 7)->delete();
        DB::table('cities')->where('region_id', 7)->delete();
        DB::table('provinces')->where('region_id', 7)->delete();
    }
};