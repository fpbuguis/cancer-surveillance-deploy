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
        // Seed Provinces of Region 8 (Eastern Visayas)
        $provinces = [
            ['Eastern Samar', 9],
            ['Leyte', 9],
            ['Northern Samar', 9],
            ['Samar', 9], 
            ['Southern Leyte', 9],
            ['Biliran', 9],
        ];

        // Starting province_id from 40
        $provinceIdStart = 40; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index, 
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 8
        $cities = [
            ['Borongan City', 40, 9], // Eastern Samar
            ['Tacloban City', 41, 9], // Leyte
            ['Ormoc City', 41, 9], // Leyte
            ['Calbayog City', 42, 9], // Samar
            ['Catbalogan City', 42, 9], // Samar
            ['Maasin City', 43, 9], // Southern Leyte
            ['Naval', 44, 9], // Biliran (technically a municipality but provincial capital)
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 8
        $municipalities = [
            // Eastern Samar
            ['Arteche', 40, 9],
            ['Balangiga', 40, 9],
            ['Balangkayan', 40, 9],
            ['Can-avid', 40, 9],
            ['Dolores', 40, 9],
            ['General MacArthur', 40, 9],
            ['Giporlos', 40, 9],
            ['Guiuan', 40, 9],
            ['Hernani', 40, 9],
            ['Jipapad', 40, 9],
            ['Lawaan', 40, 9],
            ['Llorente', 40, 9],
            ['Maslog', 40, 9],
            ['Maydolong', 40, 9],
            ['Mercedes', 40, 9],
            ['Oras', 40, 9],
            ['Quinapondan', 40, 9],
            ['Salcedo', 40, 9],
            ['San Julian', 40, 9],
            ['San Policarpo', 40, 9],
            ['Sulat', 40, 9],
            ['Taft', 40, 9],

            // Leyte
            ['Abuyog', 41, 9],
            ['Alangalang', 41, 9],
            ['Albuera', 41, 9],
            ['Babatngon', 41, 9],
            ['Barugo', 41, 9],
            ['Bato', 41, 9],
            ['Baybay', 41, 9], // Now a city but listed here for reference
            ['Burauen', 41, 9],
            ['Calubian', 41, 9],
            ['Capoocan', 41, 9],
            ['Carigara', 41, 9],
            ['Dagami', 41, 9],
            ['Dulag', 41, 9],
            ['Hilongos', 41, 9],
            ['Hindang', 41, 9],
            ['Inopacan', 41, 9],
            ['Isabel', 41, 9],
            ['Jaro', 41, 9],
            ['Javier', 41, 9],
            ['Julita', 41, 9],
            ['Kananga', 41, 9],
            ['La Paz', 41, 9],
            ['Leyte', 41, 9],
            ['MacArthur', 41, 9],
            ['Mahaplag', 41, 9],
            ['Matag-ob', 41, 9],
            ['Matalom', 41, 9],
            ['Mayorga', 41, 9],
            ['Merida', 41, 9],
            ['Palo', 41, 9],
            ['Palompon', 41, 9],
            ['Pastrana', 41, 9],
            ['San Isidro', 41, 9],
            ['San Miguel', 41, 9],
            ['Santa Fe', 41, 9],
            ['Tabango', 41, 9],
            ['Tabontabon', 41, 9],
            ['Tanauan', 41, 9],
            ['Tolosa', 41, 9],
            ['Tunga', 41, 9],
            ['Villaba', 41, 9],

            // Northern Samar
            ['Allen', 42, 9],
            ['Biri', 42, 9],
            ['Bobon', 42, 9],
            ['Capul', 42, 9],
            ['Catarman', 42, 9],
            ['Catubig', 42, 9],
            ['Gamay', 42, 9],
            ['Laoang', 42, 9],
            ['Lapinig', 42, 9],
            ['Las Navas', 42, 9],
            ['Lavezares', 42, 9],
            ['Lope de Vega', 42, 9],
            ['Mapanas', 42, 9],
            ['Mondragon', 42, 9],
            ['Palapag', 42, 9],
            ['Pambujan', 42, 9],
            ['Rosario', 42, 9],
            ['San Antonio', 42, 9],
            ['San Isidro', 42, 9],
            ['San Jose', 42, 9],
            ['San Roque', 42, 9],
            ['San Vicente', 42, 9],
            ['Silvino Lobos', 42, 9],
            ['Victoria', 42, 9],

            // Samar (Western Samar)
            ['Almagro', 43, 9],
            ['Basey', 43, 9],
            ['Calbiga', 43, 9],
            ['Daram', 43, 9],
            ['Gandara', 43, 9],
            ['Hinabangan', 43, 9],
            ['Jiabong', 43, 9],
            ['Marabut', 43, 9],
            ['Matuguinao', 43, 9],
            ['Motiong', 43, 9],
            ['Pagsanghan', 43, 9],
            ['Paranas', 43, 9],
            ['Pinabacdao', 43, 9],
            ['San Jorge', 43, 9],
            ['San Jose de Buan', 43, 9],
            ['Santa Margarita', 43, 9],
            ['Santa Rita', 43, 9],
            ['Santo Niño', 43, 9],
            ['Tagapul-an', 43, 9],
            ['Talalora', 43, 9],
            ['Tarangnan', 43, 9],
            ['Villareal', 43, 9],
            ['Zumarraga', 43, 9],

            // Southern Leyte
            ['Anahawan', 44, 9],
            ['Bontoc', 44, 9],
            ['Hinunangan', 44, 9],
            ['Hinundayan', 44, 9],
            ['Libagon', 44, 9],
            ['Liloan', 44, 9],
            ['Limasawa', 44, 9],
            ['Macrohon', 44, 9],
            ['Malitbog', 44, 9],
            ['Padre Burgos', 44, 9],
            ['Pintuyan', 44, 9],
            ['Saint Bernard', 44, 9],
            ['San Francisco', 44, 9],
            ['San Juan', 44, 9],
            ['San Ricardo', 44, 9],
            ['Silago', 44, 9],
            ['Sogod', 44, 9],
            ['Tomas Oppus', 44, 9],

            // Biliran
            ['Almeria', 45, 9],
            ['Biliran', 45, 9],
            ['Cabucgayan', 45, 9],
            ['Caibiran', 45, 9],
            ['Culaba', 45, 9],
            ['Kawayan', 45, 9],
            ['Maripipi', 45, 9],
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
        DB::table('municipalities')->where('region_id', 9)->delete();
        DB::table('cities')->where('region_id', 9)->delete();
        DB::table('provinces')->where('region_id', 9)->delete();
    }
};
