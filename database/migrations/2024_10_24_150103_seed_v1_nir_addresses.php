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
        // Seed Provinces of Negros Island Region (NIR)
        $provinces = [
            ['Negros Occidental', 17],
            ['Negros Oriental', 17],
            ['Siquijor', 17],
        ];

        $provinceIdStart = 75; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of NIR
        $cities = [
            ['Bacolod City', 75, 17], 
            ['Silay City', 75, 17], 
            ['Talisay City', 75, 17], 
            ['Victorias City', 75, 17], 
            ['Kabankalan City', 75, 17], 
            ['Bago City', 75, 17], 
            ['San Carlos City', 75, 17], 
            ['La Carlota City', 75, 17], 
            ['Himamaylan City', 75, 17], 
            ['Guihulngan City', 76, 17], 
            ['Bayawan City', 76, 17], 
            ['Dumaguete City', 76, 17], 
            ['Tanjay City', 76, 17], 
            ['Siquijor', 77, 17], 
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of NIR
        $municipalities = [
            // Negros Occidental
            ['Adelaide', 75, 17],
            ['Binalbagan', 75, 17],
            ['Candoni', 75, 17],
            ['Cauayan', 75, 17],
            ['Hinigaran', 75, 17],
            ['Ilog', 75, 17],
            ['Isabela', 75, 17],
            ['La Castellana', 75, 17],
            ['Moises Padilla', 75, 17],
            ['Murcia', 75, 17],
            ['Pontevedra', 75, 17],
            ['Pulupandan', 75, 17],
            ['San Enrique', 75, 17],
            ['San Rafael', 75, 17],
            ['Siligay', 75, 17],
            ['Toboso', 75, 17],
            ['Valladolid', 75, 17],

            // Negros Oriental
            ['Amlan', 76, 17],
            ['Ayungon', 76, 17],
            ['Bacong', 76, 17],
            ['Basay', 76, 17],
            ['Bayawan', 76, 17],
            ['Canlaon', 76, 17],
            ['Dauin', 76, 17],
            ['Dumaguete', 76, 17],
            ['Guihulngan', 76, 17],
            ['Jimalalud', 76, 17],
            ['Mabinay', 76, 17],
            ['Negros Oriental', 76, 17],
            ['San Jose', 76, 17],
            ['Santa Catalina', 76, 17],
            ['Sierra Bullones', 76, 17],
            ['Valencia', 76, 17],
            ['Zamboanguita', 76, 17],

             // Siquijor
             ['Enrique Villanueva', 77, 17],
             ['Larena', 77, 17],
             ['Lazi', 77, 17],
             ['Maria', 77, 17],
             ['San Juan', 77, 17],
             ['Siquijor', 77, 17],  
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
        DB::table('municipalities')->where('region_id', 17)->delete();
        DB::table('cities')->where('region_id', 17)->delete();
        DB::table('provinces')->where('region_id', 17)->delete();
    }
};
