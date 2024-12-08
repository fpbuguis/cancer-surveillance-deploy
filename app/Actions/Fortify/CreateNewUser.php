<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Address;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Municipality;
use App\Models\Doctor;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact_number' => ['required', 'string', 'max:15'],
            'password' => $this->passwordRules(),
            'birthday' => ['required', 'date'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'licensenum' => ['required', 'regex:/^\d{6,}$/'], // Minimum of 6 digits
            'licenseexp' => ['required', 'date', 'after_or_equal:today'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'licenseexp.after_or_equal' => 'Your license is already expired.',
        ])->validate();

        $address = Address::create([
            'address_number' => $input['address_number'],
            'address_street' => $input['address_street'],
            'address_brgy' => $input['address_brgy'],
            'address_zipcode' => $input['address_zipcode'],
            'region_id' => $input['region_id'],
            'province_id' => $input['province_id'],
            'city_id' => $input['city_id'],
            'municipality_id' => $input['municipality_id'],
        ]);


        $address_id = $address->id ?? $address->address_id;

        $user = User::create([
            'firstname' => $input['firstname'],
            'middlename' => $input['middlename'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'contact_number' => $input['contact_number'],
            'password' => Hash::make($input['password']),
            'birthday' => $input['birthday'],
            'birthplace' => $input['birthplace'],
            'gender' => $input['gender'],
            'marital_status' => $input['marital_status'],
            'address_id' => $address->address_id,
            'role_id' => $input['user_role'],
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->user_id, 
            'doctor_hospital' => $input['hospital'], 
            'doctor_department' => $input['department'], 
            'doctor_specialty' => $input['specialty'], 
            'doctor_eSignature' => $input['esignature'], 
            'doctor_licenseNumber' => $input['licensenum'],
            'doctor_licenseExpiry' => $input['licenseexp'],
            // 'doctor_schedule' => $input['schedule'],
        ]);

        return $user;
    }
}