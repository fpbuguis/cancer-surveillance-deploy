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
        //Seed for Bases table
        $bases = [
            ['Death Certificates only', 'Non-Microscopic'],
            ['Clinical Investigation', 'Non-Microscopic'],
            ['Clinical Only', 'Non-Microscopic'],
            ['Specific Tumor Markers', 'Non-Microscopic'],
            ['Cytology or Hematology', 'Microscopic'],
            ['Histology of Metastasis', 'Microscopic'],
            ['Histology of Primary', 'Microscopic'],
            ['Unknown', 'Unknown'],

        ];

        foreach ($bases as $basis) {
            DB::table('bases')->insert([
                'basis_name' => $basis[0],
                'basis_category' => $basis[1],
            ]);
        }

        //Seed for Body Sites table
        $bodySites = [
            ['Colon'],
            ['Brain'],
            ['Urinary bladder'],
            ['Gall bladder'],
            ['Thyroid'],
            ['Uterine Cervix'],
            ['Liver'],
            ['Corpus Uteri'],
            ['Breast'],
            ['Blood'],
            ['Ovary'],
            ['Lung'],
            ['Esophagus'],
            ['Kidney'],
            ['Oral Cavity'],
            ['Stomach'],
            ['Pancreas'],
            ['Skin'],
            ['Nasopharynx'],
            ['Testis'],
            ['Prostate'],
            ['Rectum'],
            ['Negative'],
            ['All'],
            ['Others'],
        ];
        
        foreach ($bodySites as $bodySite) {
            DB::table('body_sites')->insert([
                'body_site_name' => $bodySite[0],
            ]);
        }

        //Seed for Disease Extent
        $diseaseExtents = [
            ['In-Situ'],
            ['Localized'],
            ['Direct Extension'],
            ['Regional Lymph Node'],
            ['Direct Extension and Regional Lymph Node'],
            ['Distant Metastasis'],
            ['Unknown'],
        ];

        foreach ($diseaseExtents as $extent) {
            DB::table('disease_extents')->insert([
                'extent_name' => $extent[0],
            ]);
        }


        $lateralities = [
            ['Left'],
            ['Right'],
            ['Bilateral'],
            ['Mid'],
            ['Not Stated'],
            ['Not Applicable'],  
        ];

        foreach ($lateralities as $lat) {
            DB::table('lateralities')->insert([
                'laterality_name' => $lat[0],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('bases')->whereIn('basis_name', [
            'Death Certificates only',
            'Clinical Investigation',
            'Clinical Only',
            'Specific Tumor Markers',
        ])->delete();

        DB::table('body_sites')->whereIn('body_site_name', [
            'Colon',
            'Brain',
            'Urinary bladder',
            'Gall bladder',
            'Thyroid',
            'Uterine Cervix',
            'Liver',
            'Corpus Uteri',
            'Breast',
            'Blood',
            'Ovary',
            'Lung',
            'Esophagus',
            'Kidney',
            'Oral Cavity',
            'Stomach',
            'Pancreas',
            'Skin',
            'Nasopharynx',
            'Testis',
            'Prostate',
            'Rectum',
            'Negative',
            'All'
            // 'Others',
        ])->delete();

        DB::table('disease_extents')->whereIn('extent_name', [
            'In-Situ',
            'Localized',
            'Direct Extension',
            'Regional Lymph Node',
            'Direct Extension and Regional Lymph Node',
            'Distant Metastasis',
            'Unknown',
        ])->delete();

        DB::table('lateralities')->whereIn('laterality_name', [
            'Left',
            'Right',
            'Bilateral',
            'Mid',
            'Not Stated',
            'Not Applicable',  
        ])->delete();
        
    }
};
