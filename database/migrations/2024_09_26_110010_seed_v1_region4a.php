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
        // Seed Provinces of Region 4A (CALABARZON)
        $provinces = [
            ['Cavite', 4],
            ['Laguna', 4],
            ['Batangas', 4],
            ['Rizal', 4],
            ['Quezon', 4],
        ];

        $provinceIdStart = 17; // Starting ID for Region 4A provinces
        foreach ($provinces as $index => $province) {
            DB::table('provinces')->insert([
                'province_id' => $provinceIdStart + $index,
                'province_name' => $province[0],
                'region_id' => $province[1],
            ]);
        }

        // Seed Cities of Region 4A (CALABARZON)
        $cities = [
            // Cavite
            ['Bacoor City', 17, 4],
            ['Carmona City', 17, 4],
            ['Cavite City', 17, 4],
            ['Dasmariñas City', 17, 4],
            ['General Trias City', 17, 4],
            ['Imus City', 17, 4],
            ['Tagaytay City', 17, 4],
            ['Trece Martires City', 17, 4],
            // Laguna
            ['Biñan City', 18, 4],
            ['Cabuyao City', 18, 4],
            ['Calamba City', 18, 4],
            ['San Pablo City', 18, 4],
            ['San Pedro City', 18, 4],
            ['Santa Rosa City', 18, 4],
            // Batangas
            ['Batangas City', 19, 4],
            ['Calaca City', 19, 4],
            ['Lipa City', 19, 4],
            ['Santo Tomas City', 19, 4],
            ['Tanauan City', 19, 4],
            // Rizal
            ['Antipolo City', 20, 4],
            // Quezon
            ['Lucena City', 21, 4],
            ['Tayabas City', 21, 4],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city[0],
                'province_id' => $city[1],
                'region_id' => $city[2],
            ]);
        }

        // Seed Municipalities of Region 4A (CALABARZON)
        $municipalities = [
            // Cavite
            ['Alfonso', 17, 4],
            ['Amadeo', 17, 4],
            ['General Emilio Aguinaldo', 17, 4],
            ['General Mariano Alvarez', 17, 4],
            ['Indang', 17, 4],
            ['Kawit', 17, 4],
            ['Magallanes', 17, 4],
            ['Maragondon', 17, 4],
            ['Mendez', 17, 4],
            ['Naic', 17, 4],
            ['Noveleta', 17, 4],
            ['Rosario', 17, 4],
            ['Silang', 17, 4],
            ['Tanza', 17, 4],
            ['Ternate', 17, 4],
            
            // Laguna
            ['Alaminos', 18, 4],
            ['Bay', 18, 4],
            ['Calauan', 18, 4],
            ['Famy', 18, 4],
            ['Kalayaan', 18, 4],
            ['Liliw', 18, 4],
            ['Los Baños', 18, 4],
            ['Luisiana', 18, 4],
            ['Lumban', 18, 4],
            ['Mabitac', 18, 4],
            ['Magdalena', 18, 4],
            ['Majayjay', 18, 4],
            ['Nagcarlan', 18, 4],
            ['Paete', 18, 4],
            ['Pagsanjan', 18, 4],
            ['Pakil', 18, 4],
            ['Pangil', 18, 4],
            ['Pila', 18, 4],
            ['Rizal', 18, 4],
            ['Santa Cruz', 18, 4],
            ['Santa Maria', 18, 4],
            ['Siniloan', 18, 4],
            ['Victoria', 18, 4],
            
            // Batangas
            ['Agoncillo', 19, 4],
            ['Alitagtag', 19, 4],
            ['Balayan', 19, 4],
            ['Balete', 19, 4],
            ['Bauan', 19, 4],
            ['Calatagan', 19, 4],
            ['Cuenca', 19, 4],
            ['Ibaan', 19, 4],
            ['Laurel', 19, 4],
            ['Lemery', 19, 4],
            ['Lian', 19, 4],
            ['Lobo', 19, 4],
            ['Mabini', 19, 4],
            ['Malvar', 19, 4],
            ['Mataas na Kahoy', 19, 4],
            ['Nasugbu', 19, 4],
            ['Padre Garcia', 19, 4],
            ['Rosario', 19, 4],
            ['San Jose', 19, 4],
            ['San Juan', 19, 4],
            ['San Luis', 19, 4],
            ['San Nicolas', 19, 4],
            ['San Pascual', 19, 4],
            ['Santa Teresita', 19, 4],
            ['Taal', 19, 4],
            ['Talisay', 19, 4],
            ['Taysan', 19, 4],
            ['Tingloy', 19, 4],
            ['Tuy', 19, 4],
            
            // Rizal
            ['Angono', 20, 4],
            ['Baras', 20, 4],
            ['Binangonan', 20, 4],
            ['Cainta', 20, 4],
            ['Cardona', 20, 4],
            ['Jalajala', 20, 4],
            ['Morong', 20, 4],
            ['Pililla', 20, 4],
            ['Rodriguez', 20, 4],
            ['San Mateo', 20, 4],
            ['Tanay', 20, 4],
            ['Taytay', 20, 4],
            ['Teresa', 20, 4],
            
            // Quezon
            ['Agdangan', 21, 4],
            ['Alabat', 21, 4],
            ['Atimonan', 21, 4],
            ['Buenavista', 21, 4],
            ['Burdeos', 21, 4],
            ['Calauag', 21, 4],
            ['Candelaria', 21, 4],
            ['Catanauan', 21, 4],
            ['Dolores', 21, 4],
            ['General Luna', 21, 4],
            ['General Nakar', 21, 4],
            ['Guinayangan', 21, 4],
            ['Gumaca', 21, 4],
            ['Infanta', 21, 4],
            ['Jomalig', 21, 4],
            ['Lopez', 21, 4],
            ['Lucban', 21, 4],
            ['Macalelon', 21, 4],
            ['Mauban', 21, 4],
            ['Mulanay', 21, 4],
            ['Padre Burgos', 21, 4],
            ['Pagbilao', 21, 4],
            ['Panukulan', 21, 4],
            ['Patnanungan', 21, 4],
            ['Perez', 21, 4],
            ['Pitogo', 21, 4],
            ['Plaridel', 21, 4],
            ['Polillo', 21, 4],
            ['Quezon', 21, 4],
            ['Real', 21, 4],
            ['Sampaloc', 21, 4],
            ['San Andres', 21, 4],
            ['San Antonio', 21, 4],
            ['San Francisco', 21, 4],
            ['San Narciso', 21, 4],
            ['Sariaya', 21, 4],
            ['Tagkawayan', 21, 4],
            ['Tiaong', 21, 4],
            ['Unisan', 21, 4],
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
        DB::table('municipalities')->where('region_id', 4)->delete();
        DB::table('cities')->where('region_id', 4)->delete();
        DB::table('provinces')->where('region_id', 4)->delete();
    }
};