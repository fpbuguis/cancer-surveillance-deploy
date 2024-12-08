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
        Schema::create('consults', function (Blueprint $table) {
            $table->id('consult_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->longtext('consult_subjective');
            $table->longtext('consult_objective');
            $table->longtext('consult_surveillance_workup');
            $table->longtext('consult_assessment');
            $table->longtext('consult_plan');
            $table->foreignId('consult_disease_status')->nullable()->references('dxStatus_id')->on('disease_statuses');
            $table->string('consult_patient_status')->nullable();
            $table->foreignId('consult_encoder')->nullable()->references('user_id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consult');
    }
};
