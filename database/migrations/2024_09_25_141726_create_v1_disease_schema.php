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
        Schema::create('body_sites', function (Blueprint $table) {
            $table->id('body_site_id');
            $table->string('body_site_name');
        });

        Schema::create('bases', function (Blueprint $table) {
            $table->id('basis_id');
            $table->string('basis_name');
            $table->string('basis_category'); 
        });
        
        Schema::create('disease_statuses', function (Blueprint $table) {
            $table->id('dxStatus_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->boolean('dxStatus_alive');
            $table->boolean('dxStatus_symptoms');
            $table->boolean('dxStatus_recurrence');
            $table->boolean('dxStatus_metastatic');
            $table->boolean('dxStatus_curative');
            // $table->foreignId('dxStatus_encoder')->references('user_id')->on('users');
            $table->timestamps(); 
        });

        Schema::create('disease_extents', function (Blueprint $table) {
            $table->id('extent_id');
            $table->string('extent_name');
        });

        Schema::create('lateralities', function (Blueprint $table) {
            $table->id('laterality_id');
            $table->string('laterality_name'); 
        });

        Schema::create('metastatic_sites', function (Blueprint $table) {
            $table->id('mets_id');   
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->boolean('mets_distantLN');
            $table->boolean('mets_bone');
            $table->boolean('mets_liver');
            $table->boolean('mets_lung');
            $table->boolean('mets_brain');
            $table->boolean('mets_ovary');
            $table->boolean('mets_skin');
            $table->boolean('mets_intestine');
            $table->boolean('mets_others');
            $table->string('mets_others_site')->nullable();
            $table->boolean('mets_unknown');
            $table->string('mets_notes')->nullable();
            $table->foreignId('mets_encoder')->nullable()->references('user_id')->on('users');
            $table->timestamps();
        });

        Schema::create('pathologies', function (Blueprint $table) {
            $table->id('pathology_id');
            $table->string('ICDO32')->nullable();;
            $table->string('pathology_level');
            $table->string('term');
            $table->string('code_reference')->nullable();;
            $table->string('obs')->nullable();;
            $table->string('see_also')->nullable();;
            $table->string('see_note')->nullable();;
            $table->string('includes')->nullable();;
            $table->string('excludes')->nullable();;
            $table->string('other_text')->nullable();;
            $table->timestamps();
        });


        Schema::create('histologies', function (Blueprint $table) {
            $table->id('histology_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('histo_pathology')->references('pathology_id')->on('pathologies'); // Change to foreign key reference
            $table->float('histo_tumorSize');
            $table->boolean('histo_tumorExtension');
            $table->integer('histo_tumorGrade');
            $table->integer('histo_nodePositive');
            $table->integer('histo_nodeHarvest');
            $table->boolean('histo_negativeMargins');
            $table->string('histo_stage');
            $table->foreignId('histo_encoder')->nullable()->references('user_id')->on('users');
            $table->timestamps();
        });


        Schema::create('other_primaries', function (Blueprint $table) {
            $table->id('op_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->boolean('op_Colon');
            $table->boolean('op_Brain');
            $table->boolean('op_UrinaryBladder');
            $table->boolean('op_GallBladder');
            $table->boolean('op_Thyroid');
            $table->boolean('op_UterineCervix');
            $table->boolean('op_Liver');
            $table->boolean('op_CorpusUteri');
            $table->boolean('op_Breast');
            $table->boolean('op_Ovary');
            $table->boolean('op_Blood');
            $table->boolean('op_Lung');
            $table->boolean('op_Esophagus');
            $table->boolean('op_Kidney');
            $table->boolean('op_OralCavity');
            $table->boolean('op_Stomach');
            $table->boolean('op_Pancreas');
            $table->boolean('op_Skin');
            $table->boolean('op_Nasopharynx');
            $table->boolean('op_Testis');
            $table->boolean('op_Prostate');
            $table->boolean('op_Rectum');
            $table->boolean('op_Others');
            $table->string('op_Others_Specify')->nullable();
            $table->foreignId('op_encoder')->nullable()->references('user_id')->on('users');
            $table->timestamps();
        });

        Schema::create('diseases', function (Blueprint $table) {
            $table->id('disease_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('disease_primarySite')->references('body_site_id')->on('body_sites');
            $table->string('disease_otherPrimarySite')->nullable();
            $table->date('disease_diagnosisDate');
            $table->foreignId('disease_basis')->references('basis_id')->on('bases');
            $table->foreignId('disease_laterality')->references('laterality_id')->on('lateralities');
            $table->foreignId('disease_histology')->nullable()->references('histology_id')->on('histologies');
            $table->foreignId('disease_extent')->references('extent_id')->on('disease_extents');
            $table->float('disease_tumorSize');
            $table->integer('disease_lymphNode');
            $table->boolean('disease_metastatic');
            $table->foreignId('disease_metastaticSite')->nullable()->references('mets_id')->on('metastatic_sites');
            $table->integer('disease_multiplePrimary');
            $table->foreignId('disease_otherSites')->nullable()->references('op_id')->on('other_primaries');
            $table->integer('disease_t_stage');
            $table->integer('disease_n_stage');
            $table->integer('disease_m_stage');
            $table->integer('disease_g_stage');
            $table->integer('disease_grade');
            $table->string('disease_stage');
            $table->string('disease_stageType');
            $table->string('disease_fullDiagnosis');
            $table->foreignId('disease_status')->references('dxStatus_id')->on('disease_statuses');
            $table->foreignId('disease_encoder')->nullable()->references('user_id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_sites');
        Schema::dropIfExists('bases');
        Schema::dropIfExists('disease_statuses');
        Schema::dropIfExists('histologies');
        Schema::dropIfExists('lateralities');
        Schema::dropIfExists('metastatic_sites');
        Schema::dropIfExists('disease_extents');
        Schema::dropIfExists('diseases');   
        Schema::dropIfExists('pathologies');
    }
};
