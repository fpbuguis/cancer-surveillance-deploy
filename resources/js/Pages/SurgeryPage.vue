<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from './Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, onMounted  } from 'vue';
import { Inertia } from '@inertiajs/inertia';
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
    surgery_operation: '',
    surgery_date: '',
    surgery_findings:'',
    surgery_intent:'',
    surgery_otherIntent: '',
    surgery_surgeon:'',
    surgery_hospital:'',
    // surgery_encoder: '',
});

const currentStep = ref(0);
const doctors = ref([]);
const hospitals = ref([]);
const error = ref(null);
const submittedOnly = ref(0);


if (url) {
    const currentUrl = new URL(url, window.location.origin);
    currentStep.value = currentUrl.searchParams.get('currentStep');
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

    // const nextStep = () => {
    //     if (!currentStep) {
    //         Inertia.visit('/treatment-history/radiotherapy');
    //     }
    // }

const fetchPatientTreatmentInfo = async (patientId) => {
    try {
        let response = await axios.get(`/api/patient/${patientId}/treatments`);
        const data = response.data;
        console.log(data)

        form.surgery_operation = data.surgeries[otherId].surgery_operation
        form.surgery_date = data.surgeries[otherId].surgery_date
        form.surgery_findings = data.surgeries[otherId].surgery_findings
        form.surgery_intent = data.surgeries[otherId].surgery_intent

        if (form.surgery_intent === "Others") {
            form.surgery_otherIntent = data.treatments[otherId].surgery_otherIntent
        }

        form.surgery_surgeon = data.surgeries[otherId].surgery_surgeon
        form.surgery_hospital = data.surgeries[otherId].surgery_hospital

    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

onMounted(async () => {
    // Fetch patient info when component mounts
    await fetchPatientInfo();
    await fetchPatientTreatmentInfo(patientId);
    console.log(patientId)
    console.log(otherId)

    try {
        const [doctorsResponse,hospsResponse] = await Promise.all([
            axios.get('/api/all-doctors'),
            axios.get('/api/hospitals'),
        ]);

        doctors.value = doctorsResponse.data;
        hospitals.value = hospsResponse.data;
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
});

const submit = () => {
    form.post('/api/create-surgery-record', {
        onSuccess: () => {
            if (submittedOnly.value === 1){
                Inertia.visit(`/treatment-history/surgery/${patientId}`);
            } else if (currentStep.value) {
                Inertia.visit(`/consult/${patientId}`);
            } else {
                window.location.href = `/treatment-history/radiotherapy/${patientId}`;
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
            <h1 class="main-heading">SURGERY</h1>
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

    <div>
        <InputLabel for="surgery_operation" value="Surgery Operation" />
        <TextInput
            id="surgery_operation"
            v-model="form.surgery_operation"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError :message="form.errors.surgery_operation" />
    </div>

    <div>
        <InputLabel for="surgery_date" value="Surgery Date" />
        <TextInput
            id="surgery_date"
            v-model="form.surgery_date"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.surgery_date" />
    </div>

    <div>
        <InputLabel for="surgery_findings" value="Surgery Findings" />
        <TextInput
            id="surgery_findings"
            v-model="form.surgery_findings"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.surgery_findings" />
    </div>

    <div>
        <InputLabel for="surgery_intent" value="Surgery Intent" />
        <!-- <TextInput
            id="surgery_intent"
            v-model="form.surgery_intent"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.surgery_intent" /> -->

        <div class="mt-1 flex flex-col gap-2">
        <label class="inline-flex items-center">
            <input
                type="radio"
                name="treatment_purpose"
                value="Curative-complete"
                v-model="form.surgery_intent"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Curative-complete</span>
        </label>
        <label class="inline-flex items-center mt-1">
            <input
                type="radio"
                name="treatment_purpose"
                value="Curative-incomplete"
                v-model="form.surgery_intent"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Curative-incomplete</span>
        </label>
        <label class="inline-flex items-center mt-1">
            <input
                type="radio"
                name="treatment_purpose"
                value="Palliative only"
                v-model="form.surgery_intent"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Palliative only</span>
        </label>
        <label class="inline-flex items-center mt-1">
            <input
                type="radio"
                name="treatment_purpose"
                value="Others"
                v-model="form.surgery_intent"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Others</span>
        </label>
        </div>

        <div v-if="form.surgery_intent === 'Others'" class="mt-3">
            <InputLabel for="surgery_otherIntent" value="Specify" />
            <input
                id="surgery_otherIntent"
                v-model="form.surgery_otherIntent"
                type="text"
                class="mt-1 block w-full"
                placeholder="Specify surgery intent"
            />
        </div>

        <InputError class="mt-2" :message="form.errors.treatment_purpose" />
    </div>

    <div>
        <InputLabel for="surgery_surgeon">
            Surgery Doctor <span class="red-asterisk">*</span>
        </InputLabel>
        <select
            id="surgery_surgeon"
            v-model="form.surgery_surgeon"
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
        <InputError class="mt-2" :message="form.errors.surgery_surgeon" />
        <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>
<!--
    <div>
        <InputLabel for="surgery_hospital" value="Surgery Hospital" />
        <TextInput
            id="surgery_hospital"
            v-model="form.surgery_hospital"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.surgery_hospital" />
    </div> -->

    <div>
        <InputLabel for="surgery_hospital">
           Surgery Hospital <span class="red-asterisk">*</span>
        </InputLabel>
        <select id="surgery_hospital" v-model="form.surgery_hospital" class="mt-1 block w-full" required>
            <option value="" disabled>Select hospital</option>
            <option v-for="hospital in hospitals" :key="hospital.hospital_id" :value="hospital.hospital_id">
                {{ hospital.hospital_name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.surgery_hospital" />
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
