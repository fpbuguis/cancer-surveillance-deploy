<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Pages/Layout.vue';

import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const form = useForm({
    firstname: '',
    middlename: '',
    lastname: '',
    email: '',
    contact_number: '',
    password: '',
    password_confirmation: '',
    birthday: '',
    birthplace: '',
    gender: '',
    marital_status: '',
    address_brgy: '',
    address_street: '',
    address_number: '',
    address_zipcode: '',
    region_id: null,
    province_id: null,
    city_id: null,
    municipality_id: null,
    terms: false,
    user_role: '2',
    specialty: '',
    department: '',
    hospital: '',
    licensenum: '',
    licenseexp: '',
    // schedule
    esignature: null,
});

const regions = ref([]);
const provinces = ref([]);
const cities = ref([]);
const municipalities = ref([]);
const isDoctor = ref(false);  // if the selected role is doctor
const departments = ref([]);
const hospitals = ref([]);
const specialties = ref([]);

onMounted(async () => {
    try {
        const [regionsResponse, deptsResponse, hospsResponse, specsResponse] = await Promise.all([
            axios.get('/api/regions'),
            axios.get('/api/departments'),
            axios.get('/api/hospitals'),
            axios.get('/api/specialties')
        ]);

        regions.value = regionsResponse.data;
        departments.value = deptsResponse.data;
        hospitals.value = hospsResponse.data;
        specialties.value = specsResponse.data;
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
});

watch(() => form.region_id, async (newRegionId) => {
    if (newRegionId) {
        try {
            const response = await axios.get(`/api/provinces/${newRegionId}`);
            provinces.value = response.data;
            cities.value = [];
            municipalities.value = [];
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }
});

watch(() => form.province_id, async (newProvinceId) => {
    if (newProvinceId) {
        try {
            const [citiesResponse, municipalitiesResponse] = await Promise.all([
                axios.get(`/api/cities/${newProvinceId}`),
                axios.get(`/api/municipalities/${newProvinceId}`)
            ]);
            cities.value = citiesResponse.data;
            municipalities.value = municipalitiesResponse.data;
        } catch (error) {
            console.error('Error loading cities and municipalities:', error);
        }
    }
});

// watch(() => form.user_role, (newRole) => {
//     isDoctor.value = (newRole === '2');
// });

function validateContactNumber() {
    const contactRegex = /^09\d{9}$/;
    if (!contactRegex.test(form.contact_number)) {
        form.errors.contact_number = 'Format must be in 09xxxxxxxxx.';
        return false;
    } else {
        form.errors.contact_number = null;
        return true; 
    }
}

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.esignature = file; 
    }
};

const submit = () => {
    console.log(form);

    if (!validateContactNumber()) {
        return; 
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};



</script>

<template>
    <Head title="Register" />
    <Header />

    <main class="m-10">
        <div class="page-heading">
            <h1 class="main-heading">REGISTRATION</h1>
        </div>

        <form @submit.prevent="submit" class="form-container">

            <div>
                <InputLabel for="lastname">
                    Last Name <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="lastname"
                    v-model="form.lastname"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="lastname"
                />
                <InputError class="mt-2" :message="form.errors.lastname" />
            </div>

            <div>
                <InputLabel for="firstname">
                    First Name <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="firstname"
                    v-model="form.firstname"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="firstname"
                />
                <InputError class="mt-2" :message="form.errors.firstname" />
            </div>

            <div>
                <InputLabel for="middlename">
                    Middle Name
                </InputLabel>
                <TextInput
                    id="middlename"
                    v-model="form.middlename"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="middlename"
                />
                <InputError class="mt-2" :message="form.errors.middlename" />
            </div>

            <div>
                <InputLabel for="email">
                    Email Address <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="contact_number">
                    Contact Number <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="contact_number"
                    v-model="form.contact_number"
                    type="tel"
                    class="mt-1 block w-full"
                    required
                    @input="validateContactNumber"
                    placeholder="ex. 09xxxxxxxxx"
                />
                <InputError class="mt-2" :message="form.errors.contact_number" />
            </div>

        <div class="mt-0">
            <div>
                <InputLabel for="specialty">
                    Specialty <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="specialty" v-model="form.specialty" class="mt-1 block w-full" required>
                    <option value="" disabled>Select specialty</option>
                    <option v-for="specialty in specialties" :key="specialty.specialty_id" :value="specialty.specialty_id">
                        {{ specialty.specialty_name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.specialty" />
            </div>
                    
            <div>
                <InputLabel for="department">
                    Department <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="department" v-model="form.department" class="mt-1 block w-full" required>
                    <option value="" disabled>Select department</option>
                    <option v-for="department in departments" :key="department.department_id" :value="department.department_id">
                        {{ department.department_name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.department" />
            </div>

            <div>
                <InputLabel for="hospital">
                    Hospital <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="hospital" v-model="form.hospital" class="mt-1 block w-full" required>
                    <option value="" disabled>Select hospital</option>
                    <option v-for="hospital in hospitals" :key="hospital.hospital_id" :value="hospital.hospital_id">
                        {{ hospital.hospital_name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.hospital" />
            </div>

            <div>
                <InputLabel for="licensenum">
                    License Number <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="licensenum"
                     v-model="form.licensenum"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    pattern="\d{6,}"
                    title="Please enter atleast 6 numeric digits."
                    autofocus
                    autocomplete="licensenum"
                />
                <InputError class="mt-2" :message="form.errors.licensenum" />
            </div>

            <div>
                <InputLabel for="licenseexp">
                    License Expiry <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="licenseexp"
                    v-model="form.licenseexp"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    title="Your licence is already expired."
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.licenseexp" />
            </div>

        </div>

            <div>
                <InputLabel for="password">
                    Password <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation">
                    Confirm Password <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div>
                <InputLabel for="birthday">
                    Birthday <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="birthday"
                    v-model="form.birthday"
                    type="date"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.birthday" />
            </div>

            <div>
                <InputLabel for="birthplace" value="Birthplace" />
                <TextInput
                    id="birthplace"
                    v-model="form.birthplace"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="form.errors.birthplace" />
            </div>

            <div>
                <InputLabel for="gender" value="Gender" />
                <select id="gender" v-model="form.gender" class="mt-1 block w-full">
                    <option value="" disabled>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <InputError class="mt-2" :message="form.errors.gender" />
            </div>

            <div>
                <InputLabel for="marital_status" value="Marital Status" />
                <select id="marital_status" v-model="form.marital_status" class="mt-1 block w-full">
                    <option value="" disabled>Select Marital Status</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                </select>
                <InputError class="mt-2" :message="form.errors.marital_status" />
            </div>

            <div>
                <InputLabel for="region_id">
                    Region <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="region_id" v-model="form.region_id" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Region</option>
                    <option v-for="region in regions" :key="region.region_id" :value="region.region_id">{{ region.region_name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.region_id" />
            </div>

            <div>
                <InputLabel for="province_id">
                    Province <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="province_id" v-model="form.province_id" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Province</option>
                    <option v-for="province in provinces" :key="province.province_id" :value="province.province_id">{{ province.province_name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.province_id" />
            </div>

            <div>
                <InputLabel for="city_id">
                    City
                </InputLabel>
                <select id="city_id" v-model="form.city_id" class="mt-1 block w-full">
                    <option value="" disabled>Select City</option>
                    <option v-for="city in cities" :key="city.city_id" :value="city.city_id">{{ city.city_name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.city_id" />
            </div>

            <div>
                <InputLabel for="municipality_id">
                    Municipality
                </InputLabel>
                <select id="municipality_id" v-model="form.municipality_id" class="mt-1 block w-full">
                    <option value="" disabled>Select Municipality</option>
                    <option v-for="municipality in municipalities" :key="municipality.municipality_id" :value="municipality.municipality_id">{{ municipality.municipality_name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.municipality_id" />
            </div>

            <div>
                <InputLabel for="address_brgy">
                    Barangay <span class="red-asterisk">*</span>
                </InputLabel>
                    <TextInput
                        id="address_brgy"
                        v-model="form.address_brgy"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                <InputError class="mt-2" :message="form.errors.address_brgy" />
            </div>

             <div>
                <InputLabel for="address_number">
                    Block/House Number <span class="red-asterisk">*</span>
                </InputLabel>
                    <TextInput
                        id="address_number"
                        v-model="form.address_number"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                <InputError class="mt-2" :message="form.errors.address_number" />
            </div>

            <div>
                <InputLabel for="address_street">
                    Street <span class="red-asterisk">*</span>
                </InputLabel>
                    <TextInput
                        id="address_street"
                        v-model="form.address_street"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                <InputError class="mt-2" :message="form.errors.address_street" />
            </div>

            <div>
                <InputLabel for="address_zipcode">
                    Zipcode <span class="red-asterisk">*</span>
                </InputLabel>
                    <TextInput
                        id="address_zipcode"
                        v-model="form.address_zipcode"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                <InputError class="mt-2" :message="form.errors.address_zipcode" />
            </div>

            <div>
                <InputLabel for="esignature">
                    E-signature <span class="red-asterisk">*</span>
                </InputLabel>
                <input
                    id="esignature"
                    type="file"
                    accept=".png,.jpg,.jpeg"
                    @change="onFileChange"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.esignature" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="ms-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>


           <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </main>


    <Footer />
</template>

<style scoped>

@import '../../../css/GeneralPage.css';

.form-checkbox, .form-radio {
  padding: 0;
  margin: 0;
  display: flex;
  align-items: center;
}


</style>
