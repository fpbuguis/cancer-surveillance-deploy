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
        // Seed Provinces of Region 3
        $provinces = [
            ['Aurora', 3],
            ['Bataan', 3],
            ['Bulacan', 3],
            ['Nueva Ecija', 3],
            ['Pampanga', 3],
            ['Tarlac', 3],
            ['Zambales', 3],
        ];

        $provinceIdStart = 10; // Starting ID for Region 3 provinces
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 3
        $cities = [
            ['Balanga City', 11, 3],
            ['Malolos City', 12, 3],
            ['Meycauayan City', 12, 3],
            ['San Jose del Monte City', 12, 3],
            ['Cabanatuan City', 13, 3],
            ['Gapan City', 13, 3],
            ['Muñoz City', 13, 3],
            ['Palayan City', 13, 3],
            ['San Jose City', 13, 3],
            ['Angeles City', 14, 3],
            ['San Fernando City', 14, 3],
            ['Tarlac City', 15, 3],
            ['Olongapo City', 16, 3],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 3
        $municipalities = [
            // Aurora
            ['Baler', 10, 3],
            ['Casiguran', 10, 3],
            ['Dilasag', 10, 3],
            ['Dinalungan', 10, 3],
            ['Dingalan', 10, 3],
            ['Dipaculao', 10, 3],
            ['Maria Aurora', 10, 3],
            ['San Luis', 10, 3],

            // Bataan
            ['Abucay', 11, 3],
            ['Bagac', 11, 3],
            ['Dinalupihan', 11, 3],
            ['Hermosa', 11, 3],
            ['Limay', 11, 3],
            ['Mariveles', 11, 3],
            ['Morong', 11, 3],
            ['Orani', 11, 3],
            ['Orion', 11, 3],
            ['Pilar', 11, 3],
            ['Samal', 11, 3],

            // Bulacan
            ['Angat', 12, 3],
            ['Balagtas', 12, 3],
            ['Baliuag', 12, 3],
            ['Bocaue', 12, 3],
            ['Bulakan', 12, 3],
            ['Calumpit', 12, 3],
            ['Doña Remedios Trinidad', 12, 3],
            ['Guiguinto', 12, 3],
            ['Hagonoy', 12, 3],
            ['Marilao', 12, 3],
            ['Norzagaray', 12, 3],
            ['Obando', 12, 3],
            ['Pandi', 12, 3],
            ['Paombong', 12, 3],
            ['Plaridel', 12, 3],
            ['Pulilan', 12, 3],
            ['San Ildefonso', 12, 3],
            ['San Miguel', 12, 3],
            ['San Rafael', 12, 3],
            ['Santa Maria', 12, 3],

            // Nueva Ecija
            ['Aliaga', 13, 3],
            ['Bongabon', 13, 3],
            ['Cabiao', 13, 3],
            ['Carranglan', 13, 3],
            ['Cuyapo', 13, 3],
            ['Gabaldon', 13, 3],
            ['General Mamerto Natividad', 13, 3],
            ['General Tinio', 13, 3],
            ['Guimba', 13, 3],
            ['Jaen', 13, 3],
            ['Laur', 13, 3],
            ['Licab', 13, 3],
            ['Llanera', 13, 3],
            ['Lupao', 13, 3],
            ['Nampicuan', 13, 3],
            ['Pantabangan', 13, 3],
            ['Peñaranda', 13, 3],
            ['Quezon', 13, 3],
            ['Rizal', 13, 3],
            ['San Antonio', 13, 3],
            ['San Isidro', 13, 3],
            ['San Leonardo', 13, 3],
            ['Santa Rosa', 13, 3],
            ['Santo Domingo', 13, 3],
            ['Talavera', 13, 3],
            ['Talugtug', 13, 3],
            ['Zaragoza', 13, 3],

            // Pampanga
            ['Apalit', 14, 3],
            ['Arayat', 14, 3],
            ['Bacolor', 14, 3],
            ['Candaba', 14, 3],
            ['Floridablanca', 14, 3],
            ['Guagua', 14, 3],
            ['Lubao', 14, 3],
            ['Mabalacat', 14, 3],
            ['Macabebe', 14, 3],
            ['Magalang', 14, 3],
            ['Masantol', 14, 3],
            ['Mexico', 14, 3],
            ['Minalin', 14, 3],
            ['Porac', 14, 3],
            ['San Luis', 14, 3],
            ['San Simon', 14, 3],
            ['Santa Ana', 14, 3],
            ['Santa Rita', 14, 3],
            ['Santo Tomas', 14, 3],
            ['Sasmuan', 14, 3],

            // Tarlac
            ['Anao', 15, 3],
            ['Bamban', 15, 3],
            ['Camiling', 15, 3],
            ['Capas', 15, 3],
            ['Concepcion', 15, 3],
            ['Gerona', 15, 3],
            ['La Paz', 15, 3],
            ['Mayantoc', 15, 3],
            ['Moncada', 15, 3],
            ['Paniqui', 15, 3],
            ['Pura', 15, 3],
            ['Ramos', 15, 3],
            ['San Clemente', 15, 3],
            ['San Jose', 15, 3],
            ['San Manuel', 15, 3],
            ['Santa Ignacia', 15, 3],
            ['Victoria', 15, 3],

            // Zambales
            ['Botolan', 16, 3],
            ['Cabangan', 16, 3],
            ['Candelaria', 16, 3],
            ['Castillejos', 16, 3],
            ['Iba', 16, 3],
            ['Masinloc', 16, 3],
            ['Palauig', 16, 3],
            ['San Antonio', 16, 3],
            ['San Felipe', 16, 3],
            ['San Marcelino', 16, 3],
            ['San Narciso', 16, 3],
            ['Santa Cruz', 16, 3],
            ['Subic', 16, 3],
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
        DB::table('municipalities')->where('region_id', 3)->delete();
        DB::table('cities')->where('region_id', 3)->delete();
        DB::table('provinces')->where('region_id', 3)->delete();
    }
};