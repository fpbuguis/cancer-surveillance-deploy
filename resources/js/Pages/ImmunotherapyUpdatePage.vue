<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Layout from './Layout.vue';
import Alert from '@/Components/Alert.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';
import axios from 'axios'

const { url, props } = usePage();
const parts = url.split('/');
let patientId = parts[parts.length - 1];
patientId = parseInt(patientId, 10);
console.log('Patient ID:', patientId);

let otherId;
if (parts.length === 4) {
    otherId = parseInt(parts[2], 10);
    patientId = parseInt(parts[3], 10);
}

const userId = props.auth.user.user_id;
const userEmail = props.auth.user.email;

const alert = ref({
    show: false,
    message: '',
    type: '',
});

const form = useForm({
    lastname: '',
    email: '',
    immunoRx_drugs:'',
    immunoRx_initial_date: '',
    immunoRx_end_date: '',
    immunoRx_status: '',
    immunoRx_notes:'',
    immunoRx_isCompleted: Boolean,
    immunoRx_facility: '',
    immunoRx_doctor: '',
});

const currentStep = ref(0);
const doctors = ref([]);
const hospitals = ref([]);
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
        const patient = patients.find(p => p.patient_id === patientId);
        console.log('Found patient:', patient);

        if (patient) {
            const user = patient.user;
            form.lastname = user.lastname;
            form.email = user.email;
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

        form.immunoRx_drugs = data.immunotherapies[otherId].immunoRx_drugs
        form.immunoRx_initial_date = data.immunotherapies[otherId].immunoRx_initial_date
        form.immunoRx_end_date = data.immunotherapies[otherId].immunoRx_end_date
        form.immunoRx_status= data.immunotherapies[otherId].immunoRx_status
        form.immunoRx_notes= data.immunotherapies[otherId].immunoRx_notes
        form.immunoRx_isCompleted = data.immunotherapies[otherId].immunoRx_isCompleted === 1 ? true : false
        form.immunoRx_facility = data.immunotherapies[otherId].immunoRx_facility
        form.immunoRx_doctor = data.immunotherapies[otherId].immunoRx_doctor

    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

onMounted(async () => {
    await fetchPatientInfo();
    await fetchPatientTreatmentInfo(patientId);

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

const submit = async () => {
    try{
        alert.value.show = false;

        console.log(form);

        const response = await axios.put(`/api/treatment/update/immunotherapy/${patientId}`, form);

        const receiver = await axios.get(`/api/get-patient/${patientId}`);

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: receiver.data.user.email,
            subject: 'Treatment Information Updated',
            message: `The patient's IMMUNOTHERAPY treatment is updated`,
            senderId: userId,
            receiverId: receiver.data.user.user_id,
            notificationStatus: 1,
            notificationType: 5,
        });

        if (response.status === 200) {

            alert.value = {
                    show: true,
                    message: 'Success! Immunotherapy details updated.',
                    type: 'success',
                };

            Inertia.get('/search');
        }
        else{
            console.error('Error updating details:', response.status);
        }

    }
    catch (error){
        console.error('Error updating details:', error);
    }
};

</script>

<template>
    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">IMMUNOTHERAPHY</h1>
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
            disabled
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
            disabled
        />
        <InputError class="mt-2" :message="form.errors.email" />
    </div>

    <div>
        <InputLabel for="immunoRx_drugs" value="Immunotherapy Drug" />
        <TextInput
            id="immunoRx_drugs"
            v-model="form.immunoRx_drugs"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError :message="form.errors.immunoRx_drugs" />
    </div>

    <div>
        <InputLabel for="immunoRx_initialDate" value="Initial Immunotherapy Date" />
        <TextInput
            id="immunoRx_initial_date"
            v-model="form.immunoRx_initial_date"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.immunoRx_initial_date" />
    </div>

    <div>
        <InputLabel for="immunoRx_end_date" value="Last Immunotherapy Date" />
        <TextInput
            id="immunoRx_end_date"
            v-model="form.immunoRx_end_date"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.immunoRx_end_date" />
    </div>

    <div>
        <InputLabel for="immunoRx_status" value="Immunotherapy Status" />
        <select id="immunoRx_status" v-model="form.immunoRx_status" class="mt-1 block w-full">
            <option value="" disabled>Select Immunotherapy Status</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
            <option value="Not Completed">Not Completed</option>
        </select>
        <InputError class="mt-2" :message="form.errors.immunoRx_status" />
    </div>

    <div>
        <InputLabel for="immunoRx_notes" value="Immunotherapy Notes" />
        <TextInput
            id="immunoRx_notes"
            v-model="form.immunoRx_notes"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.immunoRx_notes" />
    </div>

    <div>
        <InputLabel for="immunoRx_isCompleted" value="Immunotheraphy Completed" />

        <div class="mt-1 flex flex-col gap-2">
            <label class="inline-flex items-center">
                <input
                    type="radio"
                    name="immunoRx_isCompleted"
                    :value="true"
                    v-model="form.immunoRx_isCompleted"
                    class="form-radio"
                />
                <span class="custom-radio"></span>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center">
                <input
                    type="radio"
                    name="immunoRx_isCompleted"
                    :value="false"
                    v-model="form.immunoRx_isCompleted"
                    class="form-radio"
                />
                <span class="custom-radio"></span>
                <span class="ml-2">No</span>
            </label>
        </div>
        <InputError class="mt-2" :message="form.errors.immunoRx_isCompleted" />
    </div>

    <div>
        <InputLabel for="immunoRx_facility">
            Immunotherapy Facility
        </InputLabel>
        <select id="immunoRx_facility" v-model="form.immunoRx_facility" class="mt-1 block w-full" required>
            <option value="" disabled>Select facility</option>
            <option v-for="hospital in hospitals" :key="hospital.hospital_id" :value="hospital.hospital_id">
                {{ hospital.hospital_name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.immunoRx_facility" />
    </div>

    <div>
        <InputLabel for="immunoRx_doctor">
            Immunotherapy Doctor
        </InputLabel>
        <select
            id="immunoRx_doctor"
            v-model="form.immunoRx_doctor"
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
        <InputError class="mt-2" :message="form.errors.immunoRx_doctor" />
        <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>

    <div class="flex items-center justify-center mt-4">
        <PrimaryButton @click="submittedOnly = 1" class="ms-4">
            Update Details
        </PrimaryButton>
    </div>
    </form>
    <Alert v-if="alert.show" :message="alert.message" :type="alert.type" />
</Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

</style>
