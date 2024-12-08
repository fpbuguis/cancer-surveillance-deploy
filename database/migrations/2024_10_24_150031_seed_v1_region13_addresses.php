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
        $provinces = [
            ['Agusan del Norte', 14],  
            ['Agusan del Sur', 14],    
            ['Dinagat Islands', 14],    
            ['Surigao del Norte', 14],  
            ['Surigao del Sur', 14],    
        ];

        $provinceIdStart = 63; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 14
        $cities = [
            ['Butuan City', 63, 14],      
            ['Bayugan City', 64, 14],     
            ['Cabadbaran City', 63, 14],  
            ['Surigao City', 66, 14],     
            ['Bislig City', 67, 14],       
            ['Tandag City', 67, 14],       
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 14
        $municipalities = [
            // Agusan del Norte
            ['Alegria', 63, 14],
            ['Bacuag', 63, 14],
            ['Bunawan', 63, 14],
            ['Carmen', 63, 14],
            ['Jabonga', 63, 14],
            ['Kitcharao', 63, 14],
            ['Las Nieves', 63, 14],
            ['Magallanes', 63, 14],
            ['Nasipit', 63, 14],
            ['Santiago', 63, 14],
            ['Tubay', 63, 14],

            // Agusan del Sur
            ['Bunawan', 64, 14],
            ['Esperanza', 64, 14],
            ['La Paz', 64, 14],
            ['Loreto', 64, 14],
            ['San Francisco', 64, 14],
            ['San Luis', 64, 14],
            ['Trento', 64, 14],

            // Dinagat Islands
            ['Bislig', 65, 14],
            ['Cagdianao', 65, 14],
            ['Dinagat', 65, 14],
            ['San Jose', 65, 14],
            ['Tubajon', 65, 14],

            // Surigao del Norte
            ['Baclayon', 66, 14],
            ['Carrascal', 66, 14],
            ['Claver', 66, 14],
            ['Dapa', 66, 14],
            ['Del Carmen', 66, 14],
            ['General Luna', 66, 14],
            ['Gigaquit', 66, 14],
            ['Malimono', 66, 14],
            ['San Benito', 66, 14],
            ['San Francisco', 66, 14],
            ['Surigao City', 66, 14],
            ['Tagana-an', 66, 14],

            // Surigao del Sur
            ['Barobo', 67, 14],
            ['Cagwait', 67, 14],
            ['Carmen', 67, 14],
            ['Lingig', 67, 14],
            ['Madrid', 67, 14],
            ['Marihatag', 67, 14],
            ['San Miguel', 67, 14],
            ['Tagbina', 67, 14],
            ['Tandag', 67, 14],
            ['Trento', 67, 14],
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
        DB::table('municipalities')->where('region_id', 14)->delete();
        DB::table('cities')->where('region_id', 14)->delete();
        DB::table('provinces')->where('region_id', 14)->delete();
    }
};
