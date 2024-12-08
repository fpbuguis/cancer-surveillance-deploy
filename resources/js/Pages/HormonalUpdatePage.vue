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
    hormonal_drugs: '',
    hormonal_dose:'',
    hormonal_initial_date: '',
    hormonal_end_date: '',
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

        form.hormonal_drugs = data.hormonals[otherId].hormonal_drugs
        form.hormonal_dose = data.hormonals[otherId].hormonal_dose
        form.hormonal_initial_date = data.hormonals[otherId].hormonal_initial_date
        form.hormonal_end_date = data.hormonals[otherId].hormonal_end_date
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

const submit = async () => {
    try{
        alert.value.show = false;

        console.log(form);

        const response = await axios.put(`/api/treatment/update/hormonal/${patientId}`, form);

        const receiver = await axios.get(`/api/get-patient/${patientId}`);

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: receiver.data.user.email,
            subject: 'Treatment Information Updated',
            message: `The patient's HORMONAL treatment is updated`,
            senderId: userId,
            receiverId: receiver.data.user.user_id,
            notificationStatus: 1,
            notificationType: 5,
        });

        if (response.status === 200) {

            alert.value = {
                    show: true,
                    message: 'Success! Hormonal therapy details updated.',
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
        <InputLabel for="hormonal_drugs" value="Hormonal Drug" />
        <TextInput
            id="hormonal_drugs"
            v-model="form.hormonal_drugs"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError :message="form.errors.hormonal_drugs" />
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
        <InputLabel for="hormonal_initial_date" value="Initial Hormonal Date" />
        <TextInput
            id="hormonal_initial_date"
            v-model="form.hormonal_initial_date"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.hormonal_initial_date" />
    </div>

    <div>
        <InputLabel for="hormonal_end_date" value="Last Hormonal Date" />
        <TextInput
            id="hormonal_end_date"
            v-model="form.hormonal_end_date"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.hormonal_end_date" />
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
