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
        // Seed Provinces of Region 5 (Bicol Region)
        $provinces = [
            ['Albay', 6],
            ['Camarines Norte', 6],
            ['Camarines Sur', 6],
            ['Catanduanes', 6],
            ['Masbate', 6],
            ['Sorsogon', 6],
        ];

        $provinceIdStart = 27; // Starting ID for Region 5 provinces
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 5 (Bicol Region)
        $cities = [
            // Albay
            ['Legazpi City', 27, 6],
            ['Ligao City', 27, 6],
            ['Tabaco City', 27, 6],
            // Camarines Sur
            ['Iriga City', 29, 6],
            ['Naga City', 29, 6],
            // Masbate
            ['Masbate City', 31, 6],
            // Sorsogon
            ['Sorsogon City', 32, 6],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 5 (Bicol Region)
        $municipalities = [
            // Albay
            ['Bacacay', 27, 6],
            ['Camalig', 27, 6],
            ['Daraga', 27, 6],
            ['Guinobatan', 27, 6],
            ['Jovellar', 27, 6],
            ['Libon', 27, 6],
            ['Malilipot', 27, 6],
            ['Malinao', 27, 6],
            ['Manito', 27, 6],
            ['Oas', 27, 6],
            ['Pio Duran', 27, 6],
            ['Polangui', 27, 6],
            ['Rapu-Rapu', 27, 6],
            ['Santo Domingo', 27, 6],
            ['Tiwi', 27, 6],

            // Camarines Norte
            ['Basud', 28, 6],
            ['Capalonga', 28, 6],
            ['Daet', 28, 6],
            ['Jose Panganiban', 28, 6],
            ['Labo', 28, 6],
            ['Mercedes', 28, 6],
            ['Paracale', 28, 6],
            ['San Lorenzo Ruiz', 28, 6],
            ['San Vicente', 28, 6],
            ['Santa Elena', 28, 6],
            ['Talisay', 28, 6],
            ['Vinzons', 28, 6],

            // Camarines Sur
            ['Baao', 29, 6],
            ['Balatan', 29, 6],
            ['Bato', 29, 6],
            ['Bombon', 29, 6],
            ['Buhi', 29, 6],
            ['Bula', 29, 6],
            ['Cabusao', 29, 6],
            ['Calabanga', 29, 6],
            ['Camaligan', 29, 6],
            ['Canaman', 29, 6],
            ['Caramoan', 29, 6],
            ['Del Gallego', 29, 6],
            ['Gainza', 29, 6],
            ['Garchitorena', 29, 6],
            ['Goa', 29, 6],
            ['Lagonoy', 29, 6],
            ['Libmanan', 29, 6],
            ['Lupi', 29, 6],
            ['Magarao', 29, 6],
            ['Milaor', 29, 6],
            ['Minalabac', 29, 6],
            ['Nabua', 29, 6],
            ['Ocampo', 29, 6],
            ['Pamplona', 29, 6],
            ['Pasacao', 29, 6],
            ['Pili', 29, 6],
            ['Presentacion', 29, 6],
            ['Ragay', 29, 6],
            ['Sagñay', 29, 6],
            ['San Fernando', 29, 6],
            ['San Jose', 29, 6],
            ['Sipocot', 29, 6],
            ['Siruma', 29, 6],
            ['Tigaon', 29, 6],
            ['Tinambac', 29, 6],

            // Catanduanes
            ['Bagamanoc', 30, 6],
            ['Baras', 30, 6],
            ['Bato', 30, 6],
            ['Caramoran', 30, 6],
            ['Gigmoto', 30, 6],
            ['Pandan', 30, 6],
            ['Panganiban', 30, 6],
            ['San Andres', 30, 6],
            ['San Miguel', 30, 6],
            ['Viga', 30, 6],
            ['Virac', 30, 6],

            // Masbate
            ['Aroroy', 31, 6],
            ['Baleno', 31, 6],
            ['Balud', 31, 6],
            ['Batuan', 31, 6],
            ['Cataingan', 31, 6],
            ['Cawayan', 31, 6],
            ['Claveria', 31, 6],
            ['Dimasalang', 31, 6],
            ['Esperanza', 31, 6],
            ['Mandaon', 31, 6],
            ['Milagros', 31, 6],
            ['Mobo', 31, 6],
            ['Monreal', 31, 6],
            ['Palanas', 31, 6],
            ['Pio V. Corpuz', 31, 6],
            ['Placer', 31, 6],
            ['San Fernando', 31, 6],
            ['San Jacinto', 31, 6],
            ['San Pascual', 31, 6],
            ['Uson', 31, 6],

            // Sorsogon
            ['Barcelona', 32, 6],
            ['Bulan', 32, 6],
            ['Bulusan', 32, 6],
            ['Casiguran', 32, 6],
            ['Castilla', 32, 6],
            ['Donsol', 32, 6],
            ['Gubat', 32, 6],
            ['Irosin', 32, 6],
            ['Juban', 32, 6],
            ['Magallanes', 32, 6],
            ['Matnog', 32, 6],
            ['Pilar', 32, 6],
            ['Prieto Diaz', 32, 6],
            ['Santa Magdalena', 32, 6],
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
        DB::table('municipalities')->where('region_id', 6)->delete();
        DB::table('cities')->where('region_id', 6)->delete();
        DB::table('provinces')->where('region_id', 6)->delete();
    }
};