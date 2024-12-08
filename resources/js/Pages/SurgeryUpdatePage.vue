<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from './Layout.vue';
import Alert from '@/Components/Alert.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, onMounted  } from 'vue';
import { Inertia } from '@inertiajs/inertia';
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

const submit = async () => {
    try{
        alert.value.show = false;

        console.log(form);

        const response = await axios.put(`/api/treatment/update/surgery/${patientId}`, form);

        const receiver = await axios.get(`/api/get-patient/${patientId}`);

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: receiver.data.user.email,
            subject: 'Treatment Information Updated',
            message: `The patient's SURGERY treatment is updated`,
            senderId: userId,
            receiverId: receiver.data.user.user_id,
            notificationStatus: 1,
            notificationType: 5,
        });

        if (response.status === 200) {

            alert.value = {
                    show: true,
                    message: 'Success! Treatment surgery details updated.',
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
