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
        // Seed Provinces of Region 1
        $provinces = [
            ['Ilocos Norte', 1],
            ['Ilocos Sur', 1],
            ['La Union', 1],
            ['Pangasinan', 1],
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->insert([
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 1
        $cities = [
            ['Laoag City', 1, 1],
            ['Batac City', 1, 1],
            ['Vigan City', 2, 1],
            ['Candon City', 2, 1],
            ['San Fernando City', 3, 1],
            ['Alaminos City', 4, 1],
            ['Dagupan City', 4, 1],
            ['San Carlos City', 4, 1],
            ['Urdaneta City', 4, 1],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 1
        $municipalities = [
            // Ilocos Norte
            ['Adams', 1, 1],
            ['Bacarra', 1, 1],
            ['Badoc', 1, 1],
            ['Bangui', 1, 1],
            ['Burgos', 1, 1],
            ['Carasi', 1, 1],
            ['Currimao', 1, 1],
            ['Dingras', 1, 1],
            ['Dumalneg', 1, 1],
            ['Banna (Espiritu)', 1, 1],
            ['Marcos', 1, 1],
            ['Nueva Era', 1, 1],
            ['Pagudpud', 1, 1],
            ['Paoay', 1, 1],
            ['Pasuquin', 1, 1],
            ['Piddig', 1, 1],
            ['Pinili', 1, 1],
            ['San Nicolas', 1, 1],
            ['Sarrat', 1, 1],
            ['Solsona', 1, 1],
            ['Vintar', 1, 1],

            // Ilocos Sur
            ['Alilem', 2, 1],
            ['Banayoyo', 2, 1],
            ['Bantay', 2, 1],
            ['Burgos', 2, 1],
            ['Cabugao', 2, 1],
            ['Caoayan', 2, 1],
            ['Cervantes', 2, 1],
            ['Galimuyod', 2, 1],
            ['Gregorio del Pilar', 2, 1],
            ['Lidlidda', 2, 1],
            ['Magsingal', 2, 1],
            ['Nagbukel', 2, 1],
            ['Narvacan', 2, 1],
            ['Quirino', 2, 1],
            ['Salcedo', 2, 1],
            ['San Emilio', 2, 1],
            ['San Esteban', 2, 1],
            ['San Ildefonso', 2, 1],
            ['San Juan', 2, 1],
            ['San Vicente', 2, 1],
            ['Santa', 2, 1],
            ['Santa Catalina', 2, 1],
            ['Santa Cruz', 2, 1],
            ['Santa Lucia', 2, 1],
            ['Santa Maria', 2, 1],
            ['Santiago', 2, 1],
            ['Santo Domingo', 2, 1],
            ['Sigay', 2, 1],
            ['Sinait', 2, 1],
            ['Sugpon', 2, 1],
            ['Suyo', 2, 1],
            ['Tagudin', 2, 1],

            // La Union
            ['Agoo', 3, 1],
            ['Aringay', 3, 1],
            ['Bacnotan', 3, 1],
            ['Bagulin', 3, 1],
            ['Balaoan', 3, 1],
            ['Bangar', 3, 1],
            ['Bauang', 3, 1],
            ['Burgos', 3, 1],
            ['Caba', 3, 1],
            ['Luna', 3, 1],
            ['Naguilian', 3, 1],
            ['Pugo', 3, 1],
            ['Rosario', 3, 1],
            ['San Gabriel', 3, 1],
            ['San Juan', 3, 1],
            ['Santo Tomas', 3, 1],
            ['Santol', 3, 1],
            ['Sudipen', 3, 1],
            ['Tubao', 3, 1],
            
            // Pangasinan
            ['Agno', 4, 1],
            ['Aguilar', 4, 1],
            ['Alcala', 4, 1],
            ['Anda', 4, 1],
            ['Asingan', 4, 1],
            ['Balungao', 4, 1],
            ['Bani', 4, 1],
            ['Basista', 4, 1],
            ['Bautista', 4, 1],
            ['Bayambang', 4, 1],
            ['Binalonan', 4, 1],
            ['Binmaley', 4, 1],
            ['Bolinao', 4, 1],
            ['Bugallon', 4, 1],
            ['Burgos', 4, 1],
            ['Calasiao', 4, 1],
            ['Dasol', 4, 1],
            ['Infanta', 4, 1],
            ['Labrador', 4, 1],
            ['Lingayen', 4, 1],
            ['Mabini', 4, 1],
            ['Malasiqui', 4, 1],
            ['Manaoag', 4, 1],
            ['Mangaldan', 4, 1],
            ['Mangatarem', 4, 1],
            ['Mapandan', 4, 1],
            ['Natividad', 4, 1],
            ['Pozzorubio', 4, 1],
            ['Rosales', 4, 1],
            ['San Fabian', 4, 1],
            ['San Jacinto', 4, 1],
            ['San Miguel', 4, 1],
            ['San Nicolas', 4, 1],
            ['San Quintin', 4, 1],
            ['Santa Barbara', 4, 1],
            ['Santa Maria', 4, 1],
            ['Santo Tomas', 4, 1],
            ['Sison', 4, 1],
            ['Sual', 4, 1],
            ['Tayug', 4, 1],
            ['Umingan', 4, 1],
            ['Urbiztondo', 4, 1],
            ['Villasis', 4, 1],
            ['Laoac', 4, 1],
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
