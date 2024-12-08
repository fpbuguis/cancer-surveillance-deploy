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
        // PREREQUISITE TABLES
        Schema::create('accesses', function (Blueprint $table) {
            $table->id('access_id');
            $table->boolean('can_Enroll_patient');
            $table->boolean('can_Edit_patient_info');
            $table->boolean('can_View_patient_info');
            $table->boolean('can_Delete_patient_info');
            $table->boolean('can_Delete_user');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->foreignId('access_id')->nullable()->references('access_id')->on('accesses');
            $table->string('role_name');
            $table->string('role_description');
        });
        
        Schema::create('regions', function (Blueprint $table) {
            $table->id('region_id');
            $table->string('region_name');
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id('province_id');
            $table->string('province_name');
            $table->foreignId('region_id')->references('region_id')->on('regions')->onDelete('cascade');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id('city_id');
            $table->string('city_name');
            $table->foreignId('province_id')->references('province_id')->on('provinces')->onDelete('cascade');
            $table->foreignId('region_id')->references('region_id')->on('regions')->onDelete('cascade');
        });

        Schema::create('municipalities', function (Blueprint $table) {
            $table->id('municipality_id');
            $table->string('municipality_name');
            $table->foreignId('province_id')->references('province_id')->on('provinces')->onDelete('cascade');
            $table->foreignId('region_id')->references('region_id')->on('regions')->onDelete('cascade');
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id('address_id');
            $table->string('address_number')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_brgy')->nullable();
            $table->string('address_zipcode')->nullable();
            $table->foreignId('region_id')->references('region_id')->on('regions');
            $table->foreignId('province_id')->references('province_id')->on('provinces');
            $table->foreignId('city_id')->nullable()->references('city_id')->on('cities');
            $table->foreignId('municipality_id')->nullable()->references('municipality_id')->on('municipalities');  
        });

        Schema::create('specialties', function (Blueprint $table) {
            $table->id('specialty_id');
            $table->string('specialty_name');
            $table->string('specialty_description');
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->string('department_name');
        });

        Schema::create('hospitals', function (Blueprint $table) {
           $table->id('hospital_id');
           $table->string('hospital_name');
           $table->string('hospital_address')->nullable();
           $table->string('hospital_contact_number')->nullable(); 
        });
        // END OF PREREQUISITE TABLES


        // MAIN TABLES
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('email')->unique();
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->date('birthday');
            $table->string('birthplace')->nullable();
            $table->foreignId('address_id')->nullable()->references('address_id')->on('addresses');
            $table->string('contact_number');
            $table->boolean('is_verified')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('role_id')->nullable()->references('role_id')->on('roles');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps(); // for created_at and updated_at
            $table->softDeletes(); // for deleted_at ("delete" a record without actually removing it from the database -> allowing for easy recovery later)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('accesses');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('municipalities');
        Schema::dropIfExists('specialties');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('hospitals');
    }
};
