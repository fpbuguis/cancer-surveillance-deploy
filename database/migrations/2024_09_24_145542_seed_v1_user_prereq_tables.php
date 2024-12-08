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
        //Seed for Roles table
        $roles = [
            ['admin', 'manages the system database'],
            ['doctor', 'user with medical license'],
            ['patient', 'user undergoing treatment'],
            ['encoder', 'staff from the hospital who encodes user details'],
            ['sysadmin', 'maintains the system database']

        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'role_name' => $role[0],
                'role_description' => $role[1],
            ]);
        }

        //Seed for Department table
        $departments = [
            ['Medicine'],
            ['Surgery'],
            ['Pathology'],
            ['Radiology'],
            ['Family Medicine']  
        ];
  
        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'department_name' => $department[0],
            ]);
        }

        
        //Seed for Specialties table
        $specialties = [
            ['Surgical Oncology', 'Surgeon specialized in cancer operation'],
            ['Medical Oncology', 'Internist specialized in cancer medication'],
            ['Radiation Oncology', 'Doctor specialized in administering radiation medicine'],
            ['Pathology', 'Doctor specialized in identifying pathology from tissue samples'],
            ['Colorectal Surgery', 'Surgeon specialized in colon and rectal cancer'],
        ];
  
        foreach ($specialties as $specialty) {
            DB::table('specialties')->insert([
                'specialty_name' => $specialty[0],
                'specialty_description' => $specialty[1],
            ]);
        }


        //Seed for Hospital table
        $hospitals = [
            ['Philippine General Hospital'],
            ['Manila Medical Center'],
            ['Capitol Medical Center'],
            ['Asian Hospital'],
            ["St. Luke's Global"],  
        ];

        foreach ($hospitals as $hospital) {
            DB::table('hospitals')->insert([
                'hospital_name' => $hospital[0],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->whereIn('role_name', [
            'admin',
            'doctor',
            'patient',
            'encoder'
        ])->delete();

        DB::table('departments')->whereIn('department_name', [
            'Medicine',
            'Surgery',
            'Pathology',
            'Radiology',
            'Family Medicine'
        ])->delete();

        DB::table('specialties')->whereIn('specialty_name', [
            'Surgical Oncology',
            'Medical Oncology',
            'Radiation Oncology',
            'Pathology',
            'Colorectal Surgery'
        ])->delete();
    }
};
