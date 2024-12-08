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
    hormonal_drug: '',
    hormonal_dose:'',
    hormonal_startDate: '',
    hormonal_endDate: '',
    hormonal_status: '',
    hormonal_notes:'',
    hormonal_doctor: '',
});

const currentStep = ref(0);
const doctors = ref([]);
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

const fetchPatientTreatmentInfo = async (patientId) => {
    try {
        let response = await axios.get(`/api/patient/${patientId}/treatments`);
        const data = response.data;
        console.log(data)

        form.hormonal_drug = data.hormonals[otherId].hormonal_drugs
        form.hormonal_dose = data.hormonals[otherId].hormonal_dose
        form.hormonal_startDate = data.hormonals[otherId].hormonal_initial_date
        form.hormonal_endDate = data.hormonals[otherId].hormonal_end_date
        form.hormonal_status= data.hormonals[otherId].hormonal_status
        form.hormonal_notes= data.hormonals[otherId].hormonal_notes
        form.hormonal_doctor = data.hormonals[otherId].hormonal_doctor

    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};


onMounted(async () => {
  await fetchPatientInfo();
  await fetchPatientTreatmentInfo(patientId);

  axios.get('/api/all-doctors')
    .then((response) => {
      doctors.value = response.data;
    })
    .catch((error) => {
      console.error(error);
    });
})

const submit = () => {
    console.log(form);

    form.post('/api/create-hormonal-record', {
        onSuccess: () => {
            if (submittedOnly.value === 1){
                Inertia.visit(`/treatment-history/hormonal/${patientId}`);
            } else {
                Inertia.visit(`/consult/${patientId}`);
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
            <h1 class="main-heading">HORMONAL</h1>
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
        <InputLabel for="hormonal_drug" value="Hormonal Drug" />
        <TextInput
            id="hormonal_drug"
            v-model="form.hormonal_drug"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError :message="form.errors.hormonal_drug" />
    </div>

    <div>
        <InputLabel for="hormonal_dose" value="Hormonal Dose" />
        <TextInput
            id="hormonal_dose"
            v-model="form.hormonal_dose"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.hormonal_dose" />
    </div>

    <div>
        <InputLabel for="hormonal_startDate" value="Initial Hormonal Date" />
        <TextInput
            id="hormonal_startDate"
            v-model="form.hormonal_startDate"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.hormonal_startDate" />
    </div>

    <div>
        <InputLabel for="hormonal_endDate" value="Last Hormonal Date" />
        <TextInput
            id="hormonal_endDate"
            v-model="form.hormonal_endDate"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.hormonal_endDate" />
    </div>

    <div>
        <InputLabel for="hormonal_status" value="Hormonal Status" />
        <select id="hormonal_status" v-model="form.hormonal_status" class="mt-1 block w-full">
            <option value="" disabled>Select Hormonal Status</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
            <option value="Not Completed">Not Completed</option>
        </select>
        <InputError class="mt-2" :message="form.errors.hormonal_status" />
    </div>

    <div>
        <InputLabel for="hormonal_notes" value="Hormonal Notes" />
        <TextInput
            id="hormonal_notes"
            v-model="form.hormonal_notes"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.hormonal_notes" />
    </div>

    <!-- <div>
        <InputLabel for="hormonal_doctor" value="Hormonal Doctor" />
        <TextInput
            id="hormonal_doctor"
            v-model="form.hormonal_doctor"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.hormonal_doctor" />
    </div> -->

    <div>
        <InputLabel for="hormonal_doctor">
            Hormonal Doctor
        </InputLabel>
        <select
            id="hormonal_doctor"
            v-model="form.hormonal_doctor"
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
        <InputError class="mt-2" :message="form.errors.hormonal_doctor" />
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
    </div>
    </form>
</Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

</style>
