<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Carbon\Carbon;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $histologies = [    
            [1, 1, 1, 3, False, 0, 0, 12, True, 'II'],
            [2, 2, 2, 5, True, 1, 3, 15, True, 'III'],
            [3, 4, 3, 4, True, 1, 1, 0, True, 'III'],
            [4, 5, 4, 3.5, True, 0, 5, 18, True, 'III'],
            [5, 6, 5, 5.5, False, 0, 3, 10, True, 'III'],
            [6, 8, 6, 5, False, 1, 2, 13, False, 'III'],
            [7, 11, 7, 5.5, False, 3, 5, 14, True, 'IV'],
            [8, 13, 8, 3.5, False, 1, 2, 12, True, 'III'],
            [9, 14, 9, 4, False, 1, 2, 11, True, 'III'],
            [10, 16, 10, 3.5, False, 2, 0, 0, False, 'II'],
        ];

        foreach ($histologies as $histology) {
            DB::table('histologies')->insert([
                'histology_id' => $histology[0],
                'patient_id' => $histology[1],
                'histo_pathology' => $histology[2],
                'histo_tumorSize' => $histology[3],
                'histo_tumorExtension' => $histology[4],
                'histo_tumorGrade' => $histology[5],
                'histo_nodePositive' => $histology[6],
                'histo_nodeHarvest' => $histology[7],
                'histo_negativeMargins' => $histology[8],
                'histo_stage' => $histology[9],
            ]);
        }

        $diseases = [
            [1, 1, 9, '8/29/2020', 7, 1, 1, 2, 3, 0, False, 0, 1, NULL, 2, 0, 0,0, 0, 'II', 'pathologic','colon,cancer,I,Left', 1],
            [2, 2, 16, '06/19/2016', 7, 4, 2, 3, 5, 1, False, 0, 1, NULL, 3, 1, 0,1, 1, 'III', 'clinical','breast,tumor,II,Right', 2],
            [3, 3, 12, '09/23/2021', 2, 2, null, 3, 5.1, 1, False, 0, 1, NULL, 3, 1, 1,0, 2, 'III', 'clinical','colon,neoplasm,II,Mid', 3],
            [4, 4, 9, '10/02/2018', 7, 2, 3, 5, 4, 2, False, 0, 1, NULL, 2, 2, 0,0, 1, 'III', 'pathologic','breast,tumor,I,Bilateral', 4],
            [5, 5, 16, '09/28/2023', 7, 4, 4, 5, 3.5, 2, False, 0, 1, NULL, 2, 2, 0,1, 0, 'III', 'pathologic','colon,neoplasm,I,Left', 5],
            [6, 6, 19, '02/03/2018', 7, 1, 5, 3, 5.5, 1, False, 0, 1, NULL, 3, 1, 0,0, 0, 'III', 'clinical','lung,cancer,II,Right', 6],
            [7, 7, 12, '05/06/2020', 2, 3, null, 6, 6, 2, True, 1, 1, NULL, 3, 2, 1,0, 3, 'IV', 'clinical','stomach,tumor,III,Mid', 7],
            [8, 8, 1, '09/24/2022', 7, 2, 6, 4, 5, 1, False, 0, 1, NULL, 3, 1, 0,0, 1, 'III', 'pathologic','esophagus,neoplasm,IV,Bilateral', 8],
            [9, 9, 7, '08/19/2019', 2, 2, null, 3, 6, 0, False, 0, 1, NULL, 3, 0, 0,1, 2, 'II', 'clinical','lung,cancer,I,Left', 9],
            [10, 10, 10, '10/19/2018', 5, 6, null, 4, 5, 1, False, 0, 1, NULL, 3, 1, 0,1, 0, 'II', 'clinical','stomach,tumor,II,Right', 10],
            [11, 11, 16, '11/19/2023', 7, 4, 7, 6, 5.5, 2, True, 2, 1, NULL, 3, 2, 1,0, 3, 'IV', 'clinical','esophagus,neoplasm,III,Mid', 11],
            [12, 12, 12, '12/02/2018', 2, 2, null, 6, 3, 2, True, 3, 1, NULL, 2, 2, 1,0, 3, 'IV', 'clinical','colon,cancer,I,Bilateral', 12],
            [13, 13, 5, '03/04/2019', 5, 2, 8, 4, 3.5, 1, False, 0, 1, NULL, 2, 1, 0,1, 1, 'III', 'clinical','breast,tumor,II,Left', 13],
            [14, 14, 3, '02/02/2017', 7, 1, 9, 4, 4, 1, False, 0, 1, NULL, 2, 1, 0,1, 1, 'III', 'clinical','lung,neoplasm,I,Right', 14],
            [15, 15, 13, '12/30/2017', 7, 4, null, 4, 4, 1, False, 0, 1, NULL, 2, 1, 0,0, 2, 'III', 'clinical','stomach,malignancy,IV,Mid', 15],
            [16, 16, 15, '03/17/2019', 7, 1, 10, 2, 3.5, 0, False, 0, 1, NULL, 2, 0, 0,0, 2, 'II', 'clinical','breast,cancer,I,Bilateral', 16],
        ];

        foreach ($diseases as $disease) {
            DB::table('diseases')->insert([
                'disease_id' => $disease[0],
                'patient_id' => $disease[1],
                'disease_primarySite' => $disease[2],
                'disease_otherPrimarySite' => null,
                'disease_diagnosisDate' => Carbon::createFromFormat('m/d/Y', $disease[3])->format('Y-m-d'),
                'disease_basis' => $disease[4],
                'disease_laterality' => $disease[5],
                'disease_histology' => $disease[6] !== null ? $disease[6] : null,
                'disease_extent' => $disease[7],
                'disease_tumorSize' => $disease[8],
                'disease_lymphNode' => $disease[9],
                'disease_metastatic' => $disease[10],
                'disease_metastaticSite' => $disease[11] === 0 ? null : $disease[11],
                'disease_multiplePrimary' => $disease[12],
                'disease_otherSites' => $disease[13],
                'disease_t_stage' => $disease[14],
                'disease_n_stage' => $disease[15],
                'disease_m_stage' => $disease[16],
                'disease_g_stage' => $disease[17],
                'disease_grade' => $disease[18],
                'disease_stage' => $disease[19],
                'disease_stageType' => $disease[20],
                'disease_fullDiagnosis' => $disease[21],
                'disease_status' => $disease[22],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::table('diseases')->truncate();
        DB::table('histologies')->truncate();
    }
};
