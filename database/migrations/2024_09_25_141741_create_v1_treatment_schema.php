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
        Schema::create('rxtypes', function (Blueprint $table) {
            $table->id('rxtype_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->boolean('rxtype_surgery');
            $table->boolean('rxtype_chemotherapy');
            $table->boolean('rxtype_radiotherapy');
            $table->boolean('rxtype_immunotherapy');
            $table->boolean('rxtype_hormonaltherapy');
            $table->boolean('rxtype_others');
            $table->string('rxtype_other_treatment')->nullable();
            $table->string('rxtype_notes')->nullable();
            $table->foreignId('rxtype_encoder')->nullable()->references('user_id')->on('users');
            $table->timestamps();
        });

        Schema::create('surgeries', function (Blueprint $table) {
           $table->id('surgery_id');
           $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
           $table->string('surgery_operation');
           $table->date('surgery_date');
           $table->string('surgery_findings');
           $table->enum('surgery_intent', ['Curative-complete', 'Curative-incomplete', 'Palliative only', 'Others']);
           $table->string('surgery_otherIntent')->nullable();
           $table->foreignId('surgery_surgeon')->references('doctor_id')->on('doctors');
           $table->foreignId('surgery_hospital')->references('hospital_id')->on('hospitals');
           $table->foreignId('surgery_encoder')->nullable()->references('user_id')->on('users');
           $table->timestamps();
           $table->softDeletes();
        });

        Schema::create('chemoprotocols', function (Blueprint $table) {
           $table->id('chemo_protocol_id');
           $table->string('chemo_drugs');
           $table->integer('chemo_dosage');
           $table->integer('chemo_NoCycle');
           $table->string('chemo_diluent'); 
        });

        Schema::create('chemotherapies', function (Blueprint $table) {
           $table->id('chemo_id');
           $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
           $table->enum('chemo_type', ['Neoadjuvant', 'Adjuvant', 'Palliative']); 
           $table->foreignId('chemo_protocol')->references('chemo_protocol_id')->on('chemoprotocols');
           $table->date('chemo_initial_date');
           $table->date('chemo_end_date');
           $table->integer('chemo_cycleNumGiven');
           $table->string('chemo_notes')->nullable();
           $table->boolean('chemo_isCompleted');
           $table->enum('chemo_status', ['Ongoing', 'Completed', 'Not Completed']);
           $table->foreignId('chemo_facility')->references('hospital_id')->on('hospitals');
           $table->foreignId('chemo_doctor')->references('doctor_id')->on('doctors');
           $table->foreignId('chemo_encoder')->nullable()->references('user_id')->on('users');
           $table->timestamps();
        });

        Schema::create('immunotherapies', function (Blueprint $table) {
           $table->id('immunoRx_id');
           $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
           $table->string('immunoRx_drugs');
           $table->date('immunoRx_initial_date');
           $table->date('immunoRx_end_date');
           $table->enum('immunoRx_status', ['Ongoing', 'Completed', 'Not Completed']);
           $table->string('immunoRx_notes')->nullable(); 
           $table->boolean('immunoRx_isCompleted');
           $table->foreignId('immunoRx_facility')->references('hospital_id')->on('hospitals');
           $table->foreignId('immunoRx_doctor')->references('doctor_id')->on('doctors');
           $table->foreignId('immunoRx_encoder')->nullable()->references('user_id')->on('users');
           $table->timestamps();
        });

        Schema::create('hormonals', function (Blueprint $table) {
           $table->id('hormonal_id');
           $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
           $table->string('hormonal_drugs');
           $table->integer('hormonal_dose');
           $table->date('hormonal_initial_date');
           $table->date('hormonal_end_date');
           $table->enum('hormonal_status', ['Ongoing', 'Completed', 'Not Completed']);
           $table->string('hormonal_notes')->nullable();
           $table->foreignId('hormonal_doctor')->references('doctor_id')->on('doctors');
           $table->foreignId('hormonal_encoder')->nullable()->references('user_id')->on('users');
           $table->timestamps(); 
        });

        Schema::create('rad_details', function (Blueprint $table) {
           $table->id('radDetails_id');
           $table->string('radDetails_name'); 
        });

        Schema::create('radiotherapies', function (Blueprint $table) {
           $table->id('radRx_id');
           $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
           $table->foreignId('radRx_type')->references('radDetails_id')->on('rad_details');
           $table->date('radRx_initial_date');
           $table->date('radRx_last_date');
           $table->integer('radRx_dose');
           $table->string('radRx_bodySite');
           $table->enum('radRx_status', ['Ongoing', 'Completed', 'Not Completed']);
           $table->boolean('radRx_isCompleted');
           $table->foreignId('radRx_facility')->references('hospital_id')->on('hospitals');
           $table->foreignId('radRx_doctor')->references('doctor_id')->on('doctors');
           $table->foreignId('radRx_encoder')->nullable()->references('user_id')->on('users');
           $table->timestamps();
        });

        Schema::create('treatments', function (Blueprint $table) {
           $table->id('treatment_id');
           $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
           $table->string('treatment_primaryRxType');
           $table->string('treatment_primaryRxName');
           $table->date('treatment_initialRxDate');
           $table->enum('treatment_purpose', ['Curative-complete', 'Curative-incomplete', 'Palliative only', 'Others']);
           $table->string('treatment_other_purpose')->nullable();
           $table->foreignId('treatment_plan')->references('rxtype_id')->on('rxtypes');
           
           $table->foreignId('treatment_encoder')->nullable()->references('user_id')->on('users');
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('radiotherapies');
        Schema::dropIfExists('hormonals');
        Schema::dropIfExists('immunotherapies');
        Schema::dropIfExists('chemotherapies');
        Schema::dropIfExists('chemoprotocols');
        Schema::dropIfExists('surgeries');
        Schema::dropIfExists('rad_details');
        Schema::dropIfExists('rxtypes');
    }
};
