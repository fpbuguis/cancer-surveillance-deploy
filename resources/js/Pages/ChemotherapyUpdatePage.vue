<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from './Layout.vue';
import Alert from '@/Components/Alert.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
    chemo_type: '',
    chemotherapy_given: '',
    chemo_initialDate: '',
    chemo_endDate: '',
    chemo_cycleNumber: '',
    chemo_status: '',
    chemo_notes:'',
    chemo_isCompleted: Boolean,
    chemo_facility: '',
    chemo_doctor: '',
});

const currentStep = ref(0);
const doctors = ref([]);
const hospitals = ref([]);
const chemoprotocols = ref([]);
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

        form.chemo_type = data.chemotherapies[otherId].chemo_type
        form.chemotherapy_given = data.chemotherapies[otherId].chemo_protocol
        form.chemo_initialDate = data.chemotherapies[otherId].chemo_initial_date
        form.chemo_endDate = data.chemotherapies[otherId].chemo_end_date
        form.chemo_cycleNumber = data.chemotherapies[otherId].chemo_cycleNumGiven
        form.chemo_status= data.chemotherapies[otherId].chemo_status
        form.chemo_notes= data.chemotherapies[otherId].chemo_notes
        form.chemo_isCompleted = data.chemotherapies[otherId].chemo_isCompleted === 1 ? true : false
        form.chemo_facility = data.chemotherapies[otherId].chemo_facility
        form.chemo_doctor = data.chemotherapies[otherId].chemo_doctor

    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

onMounted(async () => {
    await fetchPatientInfo();
    await fetchPatientTreatmentInfo(patientId);

    try {
        const [doctorsResponse, hospsResponse, chemoprocResponse] = await Promise.all([
            axios.get('/api/all-doctors'),
            axios.get('/api/hospitals'),
            axios.get('/api/chemoprotocols')
        ]);

        console.log('Doctors:', doctorsResponse.data);
        console.log('Hospitals:', hospsResponse.data);
        console.log('Chemoprotocols:', chemoprocResponse.data);

        doctors.value = doctorsResponse.data;
        hospitals.value = hospsResponse.data;
        chemoprotocols.value = chemoprocResponse.data;
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
});


const submit = async () => {
    try{
        alert.value.show = false;

        console.log(form);

        const response = await axios.put(`/api/treatment/update/chemotherapy/${patientId}`, form);

        const receiver = await axios.get(`/api/get-patient/${patientId}`);

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: receiver.data.user.email,
            subject: 'Treatment Information Updated',
            message: `The patient's CHEMOTHERAPY treatment is updated`,
            senderId: userId,
            receiverId: receiver.data.user.user_id,
            notificationStatus: 1,
            notificationType: 5,
        });

        if (response.status === 200) {

            alert.value = {
                    show: true,
                    message: 'Success! Chemotherapy details updated.',
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
            <h1 class="main-heading">CHEMOTHERAPHY</h1>
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
        <InputLabel for="chemo_type" value="Chemotherapy Type" />
        <select id="chemo_type" v-model="form.chemo_type" class="mt-1 block w-full">
            <option value="" disabled>Select Chemotherapy Type</option>
            <option value="Neoadjuvant">Neoadjuvant</option>
            <option value="Adjuvant">Adjuvant</option>
            <option value="Palliative">Palliative</option>
        </select>
        <InputError :message="form.errors.chemo_type" />
    </div>

    <div>
        <InputLabel for="chemotherapy_given">
            Chemotherapy Given
        </InputLabel>
        <select id="chemotherapy_given" v-model="form.chemotherapy_given" class="mt-1 block w-full" required>
            <option value="" disabled>Select Chemotheraphy</option>
            <option v-for="chemoprotocol in chemoprotocols" :key="chemoprotocol.chemo_protocol_id" :value="chemoprotocol.chemo_protocol_id">
                {{ chemoprotocol.chemo_drugs }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.chemotherapy_given" />
    </div>

    <div>
        <InputLabel for="chemo_initialDate" value="Initial Chemotherapy Date" />
        <TextInput
            id="chemo_initialDate"
            v-model="form.chemo_initialDate"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.chemo_initialDate" />
    </div>

    <div>
        <InputLabel for="chemo_endDate" value="Last Chemotherapy Date" />
        <TextInput
            id="chemo_endDate"
            v-model="form.chemo_endDate"
            type="date"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.chemo_endDate" />
    </div>

    <div>
        <InputLabel for="chemo_cycleNumber" value="Chemotherapy No. of Cycles" />
        <TextInput
            id="chemo_cycleNumber"
            v-model="form.chemo_cycleNumber"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.chemo_cycleNumber" />
    </div>

    <div>
        <InputLabel for="chemo_status" value="Chemotherapy Status" />
        <select id="chemo_status" v-model="form.chemo_status" class="mt-1 block w-full">
            <option value="" disabled>Select Chemotherapy Status</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
            <option value="Not Completed">Not Completed</option>
        </select>
        <InputError class="mt-2" :message="form.errors.chemo_status" />
    </div>

    <div>
        <InputLabel for="chemo_notes" value="Chemotherapy Notes" />
        <TextInput
            id="chemo_notes"
            v-model="form.chemo_notes"
            type="text"
            class="mt-1 block w-full"
        />
        <InputError class="mt-2" :message="form.errors.chemo_notes" />
    </div>

    <div>
        <InputLabel for="chemo_isCompleted" value="Chemotheraphy Completed" />

        <div class="mt-1 flex flex-col gap-2">
            <label class="inline-flex items-center">
                <input
                    type="radio"
                    name="chemo_isCompleted"
                    :value="true"
                    v-model="form.chemo_isCompleted"
                    class="form-radio"
                />
                <span class="custom-radio"></span>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center">
                <input
                    type="radio"
                    name="chemo_isCompleted"
                    :value="false"
                    v-model="form.chemo_isCompleted"
                    class="form-radio"
                />
                <span class="custom-radio"></span>
                <span class="ml-2">No</span>
            </label>
        </div>
        <InputError class="mt-2" :message="form.errors.chemo_isCompleted" />
    </div>

    <div>
        <InputLabel for="chemo_facility">
            Chemotherapy Facility
        </InputLabel>
        <select id="chemo_facility" v-model="form.chemo_facility" class="mt-1 block w-full" required>
            <option value="" disabled>Select facility</option>
            <option v-for="hospital in hospitals" :key="hospital.hospital_id" :value="hospital.hospital_id">
                {{ hospital.hospital_name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.chemo_facility" />
    </div>

    <div>
        <InputLabel for="chemo_doctor">
            Chemotherapy Doctor
        </InputLabel>
        <select
            id="chemo_doctor"
            v-model="form.chemo_doctor"
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
        <InputError class="mt-2" :message="form.errors.chemo_doctor" />
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
