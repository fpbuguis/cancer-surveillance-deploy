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
        Schema::create('clinic_hours', function (Blueprint $table) {
            $table->id('clinic_hours_id');
            $table->date('clinic_hours_date');
            $table->time('clinic_hours_start_time');
            $table->time('clinic_hours_end_time');
            $table->boolean('clinic_hours_is_recurring');
            $table->timestamps();
        });
        
        Schema::create('doctors', function (Blueprint $table) {
            $table->id('doctor_id');
            $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreignId('doctor_hospital')->references('hospital_id')->on('hospitals');
            $table->foreignId('doctor_department')->references('department_id')->on('departments');
            $table->foreignId('doctor_specialty')->references('specialty_id')->on('specialties');
            $table->binary('doctor_eSignature')->nullable();
            $table->string('doctor_licenseNumber', 20);
            $table->date('doctor_licenseExpiry');
            $table->foreignId('doctor_schedule')->nullable()->references('clinic_hours_id')->on('clinic_hours');
            $table->timestamps();
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id('patient_id');
            $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('onboards', function (Blueprint $table) {
            $table->id('onboard_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors')->onDelete('cascade');
            $table->foreignId('clinic_hours_id')->references('clinic_hours_id')->on('clinic_hours')->onDelete('cascade');
            $table->date('appointment_date');
            $table->time('appointment_start_time');
            $table->time('appointment_end_time');
            $table->string('appointment_status');
            $table->string('appointment_notes')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('clinic_hours');
        Schema::dropIfExists('onboards');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('appointments');
    }
};
