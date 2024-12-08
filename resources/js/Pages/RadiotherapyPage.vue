<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Layout from './Layout.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const { url } = usePage();
const parts = url.split('/');
let patientId = parts[parts.length - 1]; // Get patientId from URL
patientId = parseInt(patientId, 10);
console.log('Patient ID:', patientId);

let otherId;
if (parts.length === 5) {
    otherId = parseInt(parts[3], 10);
    patientId = parseInt(parts[4], 10);
}

const form = useForm({
    lastname: '',
    email: '',
    radRx_type: '',
    radRx_initialDate: '',
    radRx_lastDate: '',
    radRx_dose:'',
    radRx_bodySite:'',
    radRx_status: '',
    radRx_isCompleted: Boolean,
    radRx_facility:'',
    radRx_doctor: '',
});

const currentStep = ref(0);
const radTypes = ref([]);
const doctors = ref([]);
const hospitals = ref([]);
const submittedOnly = ref(0);

    if (url) {
      const currentUrl = new URL(url, window.location.origin);
      currentStep.value = currentUrl.searchParams.get('currentStep');
      console.log(currentStep.value)
    } else {
      console.error('URL is undefined');
    }

    const goBack = () => {
    Inertia.visit(document.referrer);
};

const fetchPatientInfo = async () => {
    try {
        let response = await axios.get('/api/all-patients');
        const patients = response.data;

        // Find the patient with the matching patientId
        const patient = patients.find(p => p.patient_id === patientId);
        console.log('Found patient:', patient);

        if (patient) {
            const user = patient.user; // Extracting the user object
            form.lastname = user.lastname; // Set last name
            form.email = user.email; // Set email
        }
    } catch (error) {
        console.error('Failed to fetch patient info:', error);
    }
};

const fetchPatientTreatmentInfo = async (patientId) => {
    try {
        let response = await axios.get(`/api/patient/${patientId}/treatments`);
        const data = response.data;
        console.log(data)

        form.radRx_type = data.radiotherapies[otherId].radRx_type
        form.radRx_initialDate = data.radiotherapies[otherId].radRx_initialDate
        form.radRx_lastDate = data.radiotherapies[otherId].radRx_lastDate
        form.radRx_dose = data.radiotherapies[otherId].radRx_dose
        form.radRx_bodySite = data.radiotherapies[otherId].radRx_bodySite
        form.radRx_status = data.radiotherapies[otherId].radRx_status
        form.radRx_isCompleted = data.radiotherapies[otherId].radRx_isCompleted === 1 ? true : false
        form.radRx_facility = data.radiotherapies[otherId].radRx_facility
        form.radRx_doctor = data.radiotherapies[otherId].radRx_doctor

    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

onMounted(async () => {
    await fetchPatientInfo();
    await fetchPatientTreatmentInfo(patientId);

    try {
        const [doctorsResponse,hospsResponse, radDetailsResponse] = await Promise.all([
            axios.get('/api/all-doctors'),
            axios.get('/api/hospitals'),
            axios.get('/api/rad-details')
        ]);

        doctors.value = doctorsResponse.data;
        hospitals.value = hospsResponse.data;
        radTypes.value = radDetailsResponse.data;
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
});

const submit = () => {
   console.log(form);

    form.post('/api/create-radiotherapy-record', {
        onSuccess: () => {
            //  window.location.href = `/treatment-history/chemotherapy/${patientId}`;
            // Reset the form or redirect to a success page
            // console.log("Success");
            console.log(submittedOnly)
            if (submittedOnly.value === 1){
                Inertia.visit(`/treatment-history/radiotherapy/${patientId}`);
            } else if (currentStep.value) {
                Inertia.visit(`/consult/${patientId}`);
            } else {
                window.location.href = `/treatment-history/chemotherapy/${patientId}`;
            }
        },
        onError: (errors) => {
            if (errors.patient_id) {
                error.value = errors.patient_id[0];
            } else {
                console.log(errors);
            }
        },
    });
};

</script>

<template>
    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">RADIOTHERAPHY</h1>
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
            autofocus
            autocomplete="lastname"

        />
        <InputError class="mt-2" :message="form.errors.lastname" />
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
            autocomplete="username"

        />
        <InputError class="mt-2" :message="form.errors.email" />
    </div>

    <!-- <div>
        <InputLabel for="radRx_type" value="Radiotherapy Type" />
        <TextInput
            id="radRx_type"
            v-model="form.radRx_type"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError :message="form.errors.radRx_type" />
    </div> -->

    <div>
        <InputLabel for="radRx_type">
            Radiotherapy Type
        </InputLabel>
        <select id="radRx_type" v-model="form.radRx_type" class="mt-1 block w-full" required>
            <option value="" disabled>Select Radiotherapy Type</option>
            <option v-for="radType in radTypes" :key="radType.radDetails_id" :value="radType.radDetails_id">
                {{ radType.radDetails_name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.radRx_type" />
    </div>

    <div>
        <InputLabel for="radRx_initialDate" value="Initial Radiotherapy Date" />
        <TextInput
            id="radRx_initialDate"
            v-model="form.radRx_initialDate"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.radRx_initialDate" />
    </div>

    <div>
        <InputLabel for="radRx_lastDate" value="Last Radiotherapy Date" />
        <TextInput
            id="radRx_lastDate"
            v-model="form.radRx_lastDate"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.radRx_lastDate" />
    </div>

    <div>
        <InputLabel for="radRx_dose" value="Radiotherapy Dose" />
        <TextInput
            id="radRx_dose"
            v-model="form.radRx_dose"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.radRx_dose" />
    </div>

    <div>
        <InputLabel for="radRx_bodySite" value="Radiotherapy Body Site" />
        <TextInput
            id="radRx_bodySite"
            v-model="form.radRx_bodySite"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.radRx_bodySite" />
    </div>

    <div>
        <InputLabel for="radRx_status" value="Radiotherapy Status" />
        <select id="radRx_status" v-model="form.radRx_status" class="mt-1 block w-full">
            <option value="" disabled>Select Radiotherapy Status</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
            <option value="Not Completed">Not Completed</option>
        </select>
        <InputError class="mt-2" :message="form.errors.radRx_status" />
    </div>

    <div>
        <InputLabel for="radRx_isCompleted" value="Radiotheraphy Completed" />

        <div class="mt-1 flex flex-col gap-2">
            <label class="inline-flex items-center">
                <input
                    type="radio"
                    name="radRx_isCompleted"
                    :value="true"
                    v-model="form.radRx_isCompleted"
                    class="form-radio"
                />
                <span class="custom-radio"></span>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center">
                <input
                    type="radio"
                    name="radRx_isCompleted"
                    :value="false"
                    v-model="form.radRx_isCompleted"
                    class="form-radio"
                />
                <span class="custom-radio"></span>
                <span class="ml-2">No</span>
            </label>
        </div>
        <InputError class="mt-2" :message="form.errors.radRx_isCompleted" />
    </div>

    <!-- <div>
        <InputLabel for="radRx_facility" value="Radiotherapy Facility" />
        <TextInput
            id="radRx_facility"
            v-model="form.radRx_facility"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.radRx_facility" />
    </div> -->

    <div>
        <InputLabel for="radRx_facility">
            Radiotherapy Facility
        </InputLabel>
        <select id="radRx_facility" v-model="form.radRx_facility" class="mt-1 block w-full" required>
            <option value="" disabled>Select facility</option>
            <option v-for="hospital in hospitals" :key="hospital.hospital_id" :value="hospital.hospital_id">
                {{ hospital.hospital_name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.radRx_facility" />
    </div>

    <!-- <div>
        <InputLabel for="radRx_doctor" value="Radiotherapy Doctor" />
        <TextInput
            id="radRx_doctor"
            v-model="form.radRx_doctor"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.radRx_doctor" />
    </div> -->

    <div>
        <InputLabel for="radRx_doctor">
            Radiotherapy Doctor
        </InputLabel>
        <select
            id="radRx_doctor"
            v-model="form.radRx_doctor"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
            :disabled="isLoading"
        >
            <option value="" disabled selected>
                {{ isLoading ? 'Loading doctors...' : 'Select a Doctor' }}
            </option>
            <option
                v-for="doctor in doctors"
                :key="doctor.doctor_id"
                :value="doctor.doctor_id"
            >
                {{ `Dr. ` +`${doctor.user.firstname} ${doctor.user.middlename} ${doctor.user.lastname}`.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ') }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.radRx_doctor" />
        <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>

    <div class="flex items-center justify-center mt-4">
        <PrimaryButton @click="goBack" class="ms-4" v-if="currentStep">
            Back
        </PrimaryButton>

        <PrimaryButton @click="submittedOnly = 1" class="ms-4" v-if="!currentStep">
            Submit
        </PrimaryButton>

        <PrimaryButton @click="nextStep" class="ms-4">
            Submit and Proceed
        </PrimaryButton>

        <!-- <PrimaryButton @click="submit" class="ms-4">
            Submit
        </PrimaryButton> -->
    </div>
    </form>
</Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

</style>
