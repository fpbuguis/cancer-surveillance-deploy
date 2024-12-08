<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Alert from '@/Components/Alert.vue';
import Layout from './Layout.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';
import axios from 'axios';

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
    radRx_type: '',
    radRx_initial_date: '',
    radRx_last_date: '',
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

        form.radRx_type = data.radiotherapies[otherId].radRx_type
        form.radRx_initial_date = data.radiotherapies[otherId].radRx_initial_date
        form.radRx_last_date = data.radiotherapies[otherId].radRx_last_date
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

const submit = async () => {
    try{
        alert.value.show = false;

        console.log(form);
        const response = await axios.put(`/api/treatment/update/radiotherapy/${patientId}`, form);

        const receiver = await axios.get(`/api/get-patient/${patientId}`);

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: receiver.data.user.email,
            subject: 'Treatment Information Updated',
            message: `The patient's RADIOTHERAPY treatment is updated`,
            senderId: userId,
            receiverId: receiver.data.user.user_id,
            notificationStatus: 1,
            notificationType: 5,
        });

        if (response.status === 200) {

            alert.value = {
                    show: true,
                    message: 'Success! Radiotherapy details updated.',
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
            v-model="form.radRx_initial_date"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.radRx_initialDate" />
    </div>

    <div>
        <InputLabel for="radRx_lastDate" value="Last Radiotherapy Date" />
        <TextInput
            id="radRx_lastDate"
            v-model="form.radRx_last_date"
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
        <PrimaryButton @click="nextStep" class="ms-4">
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
