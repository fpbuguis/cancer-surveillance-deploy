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
        // PRELIMINARY/STATIC TABLES
        Schema::create('symptom_categories', function (Blueprint $table) {
            $table->id('symptomCategory_id');
            $table->string('symptom_category');
            // $table->timestamps();
        });

        Schema::create('workups', function (Blueprint $table) {
            $table->id('workup_id');
            $table->string('workup_name');
            // $table->timestamps();
        });

        Schema::create('workup_types', function (Blueprint $table) {
            $table->id('workupType_id');
            $table->string('workupType_name');
            // $table->timestamps();
        });

        Schema::create('checkup_statuses', function (Blueprint $table) {
            $table->id('checkupStatus_id');
            $table->string('notifStatus_name');
            // $table->timestamps();
        });

        Schema::create('notification_statuses', function (Blueprint $table) {
           $table->id('notifStatus_id');
           $table->string('notifStatus_name');
        //    $table->timestamps();
        });

        Schema::create('notification_types', function (Blueprint $table) {
            $table->id('notifType_id');
            $table->string('notificationType_name');
            // $table->timestamps();
        });


        // RELATED TABLES
        Schema::create('labs_downloads', function (Blueprint $table) {
            $table->id('labReq_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('workup_id')->references('workup_id')->on('workups');     //to get workup_name
            $table->date('labReq_date');
            $table->timestamps();
        });

        Schema::create('labs_submitted', function (Blueprint $table) {
            $table->id('labSubmitted_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors');
            $table->date('labSubmission_date');
            $table->foreignId('workup_id')->references('workup_id')->on('workups');     //to get workup_name
            // $table->string('labFileLocation');
            $table->json('labFileSubmissions')->nullable();
            $table->string('labSubmission_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('lab_monitors', function (Blueprint $table) {
            $table->id('labMonitor_id');
            $table->foreignId('cancer_type')->references('body_site_id')->on('body_sites');
            $table->foreignId('workup_id')->references('workup_id')->on('workups');     //to get workup_name
            $table->integer('workup_frequency');
            $table->foreignId('workupType_id')->references('workupType_id')->on('workup_types');
            $table->string('workup_indication');
            $table->integer('workup_duration');
            $table->string('workup_referral')->nullable();
            $table->timestamps();
        });

        Schema::create('checkup_schedules', function (Blueprint $table) {
            $table->id('checkupSched_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors');
            $table->date('checkupRequest_date');
            $table->date('checkupConfirmed_date')->nullable();
            $table->time('checkup_startTime');
            $table->time('checkup_endTime');
            $table->foreignId('checkupStatus_id')->references('checkupStatus_id')->on('checkup_statuses');
            $table->timestamps();
        });

        Schema::create('checkups', function (Blueprint $table) {
            $table->id('checkup_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors');
            $table->date('checkup_date');
            $table->string('checkup_subjective');
            $table->string('checkup_objective');
            $table->string('checkup_assessment');
            $table->string('checkup_plan');
            $table->string('checkup_survWorkup');
            $table->string('checkup_patientStatus');
            $table->foreignId('checkup_schedule')->references('checkupSched_id')->on('checkup_schedules');
            $table->timestamps();
        });
        
        Schema::create('symptom_surveys', function (Blueprint $table) {
            $table->id('symptomSurvey_id');
            $table->foreignId('cancer_type')->references('body_site_id')->on('body_sites');
            $table->string('symptom_name');
            $table->string('symptom_medicalTerm')->nullable();
            $table->foreignId('symptom_category')->references('symptomCategory_id')->on('symptom_categories');
            $table->string('symptom_filipino')->nullable();
            $table->boolean('symptom_alertable')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id('surveyResponse_id');
            $table->foreignId('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('doctor_id')->on('doctors');
            $table->date('surveyResponse_date');
            // $table->foreignId('symptomSurvey_id')->references('symptomSurvey_id')->on('symptom_surveys');
            $table->string('response_notes')->nullable();
            $table->string('treatment_completion')->nullable();
            $table->timestamps();
        });

        Schema::create('surveyResponses_symptomSurvey', function (Blueprint $table) {
            $table->id('surveyResponses_symptomSurvey_id'); 
            
            $table->foreignId('symptomSurvey_id') ->references('symptomSurvey_id') 
                  ->on('symptom_surveys')
                  ->onDelete('cascade');
            
            $table->foreignId('surveyResponse_id')->references('surveyResponse_id') 
                  ->on('survey_responses')
                  ->onDelete('cascade');
        
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id('notifLog_id');
            $table->date('notification_date');
            $table->foreignId('notification_type')->references('notifType_id')->on('notification_types');
            $table->foreignId('notification_status')->references('notifStatus_id')->on('notification_statuses');
            $table->foreignId('notification_sender')->nullable()->references('user_id')->on('users')->onDelete('cascade');
            $table->foreignId('notification_receiver')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('notification_notes')->nullable();
            $table->string('notification_subject');
            $table->foreignId('surveyResponse_id')->nullable()->references('surveyResponse_id')->on('survey_responses');
            $table->foreignId('labSubmitted_id')->nullable()->references('labSubmitted_id')->on('labs_submitted');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptom_categories');
        Schema::dropIfExists('symptom_surveys');
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('surveyResponses_symptomSurvey');
        Schema::dropIfExists('checkups');
        Schema::dropIfExists('checkup_schedules');
        Schema::dropIfExists('checkup_statuses');
        Schema::dropIfExists('labs_submitted');
        Schema::dropIfExists('labs_downloads');
        Schema::dropIfExists('lab_monitors');
        Schema::dropIfExists('workups');
        Schema::dropIfExists('workup_types');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_statuses');
        Schema::dropIfExists('notification_types');
    }
};
