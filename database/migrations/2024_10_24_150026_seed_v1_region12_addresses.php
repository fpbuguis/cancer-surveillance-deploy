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
            ['Cotabato', 13],        
            ['Sarangani', 13],       
            ['South Cotabato', 13],  
            ['Sultan Kudarat', 13],  
        ];

        $provinceIdStart = 59; 
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 13
        $cities = [
            ['Kidapawan City', 59, 13], 
            ['Koronadal City', 61, 13], 
            ['General Santos City', 60, 13], 
            ['Tacurong City', 62, 13], 
            ['Polomolok', 61, 13], 
            ['Tupi', 61, 13], 
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 13
        $municipalities = [
            // Cotabato
            ['Aleosan', 59, 13],
            ['Antipas', 59, 13],
            ['Arakan', 59, 13],
            ['Bagalnga', 59, 13],
            ['Banisilan', 59, 13],
            ['Carmen', 59, 13],
            ['Kidapawan', 59, 13],
            ['Magpet', 59, 13],
            ['Midsayap', 59, 13],
            ['Pikit', 59, 13],
            ['Pigcawayan', 59, 13],
            ['Pres. Roxas', 59, 13],
            ['Tulunan', 59, 13],

            // Sarangani
            ['Alabel', 60, 13],
            ['Glan', 60, 13],
            ['Maitum', 60, 13],
            ['Malapatan', 60, 13],
            ['Sarangani', 60, 13],
            ['Sarangani Province', 60, 13],
            ['Tampakan', 60, 13],

            // South Cotabato
            ['Banga', 61, 13],
            ['Lake Sebu', 61, 13],
            ['Norala', 61, 13],
            ['Polomolok', 61, 13],
            ['Surallah', 61, 13],
            ['Tupi', 61, 13],
            ['General Santos City', 61, 13],

            // Sultan Kudarat
            ['Bagumbayan', 62, 13],
            ['Columbio', 62, 13],
            ['Isulan', 62, 13],
            ['Kalamansig', 62, 13],
            ['Lambayong', 62, 13],
            ['Lebak', 62, 13],
            ['Senator Ninoy Aquino', 62, 13],
            ['Tacurong', 62, 13],
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
        DB::table('municipalities')->where('region_id', 13)->delete();
        DB::table('cities')->where('region_id', 13)->delete();
        DB::table('provinces')->where('region_id', 13)->delete();
    }
};
