<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = [
            ['Smith', 'james', 'gomez', 'smithjames@gmail.com', 'smj022078', 'male', 'single', '1978-02-20', 'manila', '9239929384', 3],
            ['johnson', 'micheal', 'phillips', 'jmphilips@gmail.com', 'jmp030489', 'male', 'married', '1989-03-04', 'bicol', '9281094839', 3],
            ['williams', 'robert', 'evans', 'rewillams@gmail.com', 'wre051093', 'male', 'widowed', '1993-05-10', 'manila', '9170293544', 3],
            ['brown', 'john', 'turner', 'bjturner@gmail.com', 'bjt102000', 'male', 'single', '2000-10-20', 'negros occidental', '9281984545', 3],
            ['jones', 'david', 'diaz', 'jddiaz@gmail.com', 'jdd043967', 'male', 'married', '1967-04-29', 'manila', '9180398474', 3],
            ['garcia', 'william', 'parker', 'gwparker@gmail.com', 'gwp030677', 'male', 'widowed', '1977-03-06', 'laguna', '9170927823', 2],
            ['miller', 'richard', 'cruz', 'mrcruz@gmail.com', 'mrc101488', 'male', 'single', '1988-10-14', 'cavite', '9160293849', 2],
            ['davis', 'joseph', 'edwards', 'jedavis@gmail.com', 'dje092369', 'male', 'married', '1969-09-23', 'batangas', '9270946748', 2],
            ['rodriguez', 'thomas', 'morris', 'tmrodriguez@gmail.com', 'rtm072383', 'male', 'widowed', '1983-07-23', 'manila', '9180238943', 3],
            ['martinez', 'christopher', 'collins', 'ccmartinez@gmail.com', 'mcc091973', 'male', 'single', '1973-09-19', 'quezon', '9170293437', 3],
            ['fernandez', 'charles', 'reyes', 'crfernandez@gmail.com', 'fcr010587', 'male', 'married', '1987-01-05', 'gensan', '9140294623', 3],
            ['lopez', 'daniel', 'stewart', 'sdLopez@gmail.com', 'lds040881', 'male', 'widowed', '1981-04-08', 'iloilo', '9176233647', 4],
            ['gonzalez', 'matthew', 'cook', 'mcgonzalez@gmail.com', 'gmc091075', 'male', 'single', '1975-09-10', 'cebu', '9179204738', 2],
            ['wilson', 'anthony', 'rogers', 'arwilson@gmail.com', 'war101172', 'male', 'married', '1972-10-11', 'manila', '9182059283', 2],
            ['anderson', 'mark', 'gutierrez', 'mganderson@gmail.com', 'amg052669', 'male', 'single', '1969-05-26', 'ilocos norte', '9180293842', 3],
            ['thomas', 'olivia', 'ortiz', 'ooThomas@gmail.com', 'too080284', 'female', 'married', '1984-08-02', 'pangasinan', '9162933478', 1],
            ['taylor', 'emma', 'morgan', 'emTaylor@gmail.com', 'tem090273', 'female', 'widowed', '1973-09-02', 'tarlac', '9279827383', 2],
            ['moore', 'charlotte', 'cooper', 'ccmoore@gmail.com', 'mcc102975', 'female', 'single', '1975-10-29', 'manila', '9207665765', 3],
            ['jackson', 'amelia', 'peterson', 'apjackson@gmail.com', 'jap091777', 'female', 'married', '1977-09-17', 'bicol', '9283443563', 1],
            ['martinez', 'sophia', 'bailey', 'sbmartinez@gmail.com', 'msb120964', 'female', 'widowed', '1964-12-09', 'manila', '9134887283', 3],
            ['lee', 'mia', 'reed', 'mrlee@gmail.com', 'lmr111969', 'female', 'single', '1969-11-19', 'negros occidental', '9187230837', 2],
            ['perez', 'isabella', 'howard', 'ihperez@gmail.com', 'pih052362', 'female', 'married', '1962-05-23', 'manila', '9172093433', 2],
            ['thomspon', 'ava', 'ramos', 'arthompson@gmail.com', 'tar091959', 'female', 'widowed', '1959-09-19', 'laguna', '9178293840', 2],
            ['white', 'evelyn', 'kim', 'ekwhite@gmail.com', 'wek020387', 'female', 'single', '1987-02-03', 'cavite', '9162034766', 3],
            ['harris', 'luna', 'cox', 'lcharris@gmail.com', 'hlc010174', 'female', 'married', '1974-01-01', 'batangas', '9172893728', 3],
            ['sanchez', 'andrew', 'ward', 'awsanchez@gmail.com', 'saw082981', 'male', 'single', '1981-08-29', 'manila', '9182936723', 2],
            ['clark', 'paul', 'richardson', 'prclark@gmail.com', 'cpr022865', 'male', 'single', '1965-02-28', 'quezon', '9482938911', 4],
            ['ramirez', 'joshua', 'watson', 'jwramirez@gmail.com', 'rjw071473', 'male', 'married', '1973-07-14', 'gensan', '9281726378', 3],
            ['lewis', 'patricia', 'brooks', 'pblewis@gmail.com', 'lpb091967', 'female', 'widowed', '1967-09-19', 'iloilo', '9283744377', 3],
            ['robinson', 'katrina', 'chavez', 'kcrobinson@gmail.com', 'rkc101001', 'female', 'single', '2001-10-10', 'cebu', '9283498289', 3],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'lastname' => $user[0],
                'firstname' => $user[1],
                'middlename' => $user[2],
                'email' => $user[3],
                'password' => Hash::make($user[4]),
                'gender' => $user[5],
                'marital_status' => $user[6],
                'birthday' => $user[7],
                'birthplace' => $user[8],
                'contact_number' => $user[9],
                'role_id' => $user[10],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->delete();
    }
};
