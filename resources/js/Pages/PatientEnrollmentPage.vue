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
import { Inertia } from '@inertiajs/inertia';

import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const userId = ref(0);
const selectedPatient = ref(0);

const { url,props } = usePage();
    if (url) {
      const currentUrl = new URL(url, window.location.origin);
      userId.value = currentUrl.searchParams.get('userId');
    } else {
      console.error('URL is undefined');
    }

    const goBack = () => {
    Inertia.visit(document.referrer);
}

const fetchUserInfo = async (userId) => {
    try {
        let response = await axios.get(`/api/get-user/${userId}`);
        selectedPatient.value = response.data;
    } catch (error) {
        console.error('Failed to fetch user:', error);
    }
};

onMounted(async () => {
    await fetchUserInfo(userId.value);
});

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
    // terms: false,
});

const regions = ref([]);
const provinces = ref([]);
const cities = ref([]);
const municipalities = ref([]);

onMounted(async () => {
    const response = await axios.get('/api/regions');
    regions.value = response.data;
});

watch(selectedPatient, (newPatient) => {
    if (newPatient) {
        form.firstname = newPatient.firstname || '';
        form.middlename = newPatient.middlename || '';
        form.lastname = newPatient.lastname || '';
        form.email = newPatient.email || '';
        form.contact_number = newPatient.contact_number || '';
        form.birthday = newPatient.birthday || '';
        form.birthplace = newPatient.birthplace || '';
        form.gender = newPatient.gender || '';
        form.marital_status = newPatient.marital_status || '';

        form.address_brgy = newPatient.address?.address_brgy || '';
        form.address_street = newPatient.address?.address_street || '';
        form.address_number = newPatient.address?.address_number || '';
        form.address_zipcode = newPatient.address?.address_zipcode || '';
        form.region_id = newPatient.address?.region_id || null;
        form.province_id = newPatient.address?.province_id || null;
        form.city_id = newPatient.address?.city_id || null;
        form.municipality_id = newPatient.address?.municipality_id || null;
    }
}, { immediate: true });

watch(() => form.region_id, async (newRegionId) => {
    if (newRegionId) {
        const response = await axios.get(`/api/provinces/${newRegionId}`);
        provinces.value = response.data;
        // form.province_id = null; // Reset province
        cities.value = []; // Reset cities
        municipalities.value = []; // Reset municipalities
    }
});

watch(() => form.province_id, async (newProvinceId) => {
    if (newProvinceId) {
        const responseCities = await axios.get(`/api/cities/${newProvinceId}`);
        cities.value = responseCities.data;
        // form.city_id = null; // Reset city
        const responseMunicipalities = await axios.get(`/api/municipalities/${newProvinceId}`);
        municipalities.value = responseMunicipalities.data;
        // form.municipality_id = null; // Reset municipality
    }
});

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

let currentId = props.auth.user.user_id;
const currentUser = ref(null);
let doctorId = ref(0);

const fetchCurrentDoctor = async (currentId) => {
    try {
        let response = await axios.get(`/api/doctor-profile/${currentId}`);
        currentUser.value = response.data;
        doctorId.value = currentUser.value.doctor_id;
        console.log(doctorId.value)
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${currentId}:`, error);
    }
};

onMounted(async () => {
    await fetchCurrentDoctor(currentId);
});

const submitOnly = async () => {

    if (!validateContactNumber()) {
        return;
    }

    try {
        const response = await axios.post(route('enrollPatient'), form);


        if (response.status === 200) {
            const patientId = response.data.patient_id;
            console.log(patientId)
            console.log(doctorId.value)

            await axios.post('/api/onboard', {
                patientId: patientId,
                doctorId: doctorId.value
            });

            form.reset();
            alert('Patient enrolled successfully.');
        }
    } catch (error) {
        // Handle errors, e.g., validation errors
        if (error.response && error.response.data.errors) {
            Object.keys(error.response.data.errors).forEach(key => {
                form.errors[key] = error.response.data.errors[key];
            });
        } else {
            // Handle general errors
            console.error("An error occurred:", error);
        }
    }
};

const submitAndDisease = async () => {
    if (!validateContactNumber()) {
        return;
    }

    try {
        const response = await axios.post(route('enrollPatient'), form);

        // Assuming the response is successful and you can redirect
        if (response.status === 200) {
            const patientId = response.data.patient_id;
            console.log(patientId)

            await axios.post('/api/onboard', {
                patientId: patientId,
                doctorId: doctorId.value
            });
            // Reset the form fields
            form.reset('password', 'password_confirmation');
            // Redirect to the disease profile
           window.location.href = `/disease-profile/${patientId}`;

        }
    } catch (error) {
        // Handle errors, e.g., validation errors
        if (error.response && error.response.data.errors) {
            Object.keys(error.response.data.errors).forEach(key => {
                form.errors[key] = error.response.data.errors[key];
            });
        } else {
            // Handle general errors
            console.error("An error occurred:", error);
        }
    }
};

</script>

<template>
    <Head title="Patient Enrollment" />

    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">PATIENT ENROLLMENT</h1>
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
                    autofocus
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

            <div v-if="!userId">
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

            <div v-if="!userId">
                <InputLabel for="password_confirmation">
                    Confirm Password <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    :disabled="userId"
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

            <!-- <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="ms-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div> -->

            <div class="flex items-center justify-center mt-4">
                <!-- <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link> -->

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submitOnly">
                    Submit
                </PrimaryButton>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submitAndDisease">
                    Submit & Add Disease Profile
                </PrimaryButton>
            </div>
        </form>
    </Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

</style>
