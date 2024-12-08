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
import { watchEffect } from 'vue';
import { watch } from 'vue';
import { computed } from 'vue';
import Alert from '@/Components/Alert.vue';


// const patientId = ref(0);
const { url, props} = usePage();
const parts = url.split('/');
let patientId = parts[parts.length - 1];
patientId = parseInt(patientId, 10);
let currentId = props.auth.user.user_id;
const currentUser = ref(null);
let doctorId = ref(null);
const selectedPatient = ref(null);

const alert = ref({
    show: false,
    message: '',
    type: '',
});

const form = useForm({
    lastname: '',
    email: '',
    checkupRequest_date: '',
    checkupConfirmed_date: '',
    checkup_startTime: '',
    checkup_endTime: '',
    checkup_status:'',
    patient_id: patientId,
    doctor_id: computed(() => doctorId.value)
});

const fetchPatientInfo = async (patientId) => {
    try {
        console.log(patientId)
        let response = await axios.get(`/api/get-patient/${patientId}`);
        selectedPatient.value = response.data;

        form.lastname = selectedPatient.value.user.lastname;
        form.email = selectedPatient.value.user.email;
    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

const fetchCurrentDoctor = async (currentId) => {
    console.log(currentId)
    try {
        let response = await axios.get(`/api/doctor-profile/${currentId}`);
        currentUser.value = response.data;
        doctorId.value = currentUser.value.doctor_id;
        console.log(doctorId.value)
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${currentId}:`, error);
    }
};

const CF = (string) => {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};

// watch(selectedPatient, (newPatient) => {
//     if (newPatient) {
//         form.lastname = newPatient.user.lastname || '';
//         form.email = newPatient.user.email || '';
//     }
// }, { immediate: true });

onMounted(async () => {
    await fetchPatientInfo(patientId)
    await fetchCurrentDoctor(currentId);
});


watchEffect(async () => {
    console.log(patientId)
    console.log(selectedPatient)
    console.log(form)
});

const submit = () => {
    console.log(form);

    form.post('/api/create-schedule-checkup', {
        onSuccess: () => {
            alert.value = {
                show: true,
                message: 'Consultation Schedule Added!',
                type: 'success',
            };
            Inertia.visit(`/schedconsult/${patientId}`);
        },
        onError: (errors) => {
            console.log("WAHHHHHHHHHHHHH")
            alert.value = {
                show: true,
                message: 'Consultation Schedule Failed!',
                type: 'error',
            };

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
            <h1 class="main-heading">SCHEDULE CONSULTATION</h1>
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
                :disabled="patientId"
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
                :disabled="patientId"
            />
            <InputError class="mt-2" :message="form.errors.email" />
        </div>
        <div>
            <InputLabel for="checkupRequest_date" value="Checkup Request Date" />
            <TextInput
                id="checkupRequest_date"
                v-model="form.checkupRequest_date"
                type="date"
                class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="form.errors.checkupRequest_date" />
        </div>

        <div>
            <InputLabel for="checkupConfirmed_date" value="Checkup Confirmed Date" />
            <TextInput
                id="checkupConfirmed_date"
                v-model="form.checkupConfirmed_date"
                type="date"
                class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="form.errors.checkupConfirmed_date" />
        </div>

        <div>
            <InputLabel for="checkup_startTime" value="Checkup Start Time" />
            <TextInput
                id="checkup_startTime"
                v-model="form.checkup_startTime"
                type="time"
                class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="form.errors.checkup_startTime" />
        </div>

        <div>
            <InputLabel for="checkup_endTime" value="Checkup End Time" />
            <TextInput
                id="checkup_endTime"
                v-model="form.checkup_endTime"
                type="time"
                class="mt-1 block w-full"
            />
            <InputError class="mt-2" :message="form.errors.checkup_endTime" />
        </div>

        <!-- <div>
            <InputLabel for="checkup_status" value="Checkup Status" />
            <select
                id="checkup_status"
                v-model="form.checkup_status"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            >
                <option value="requested">Requested</option>
                <option value="confirmed">Confirmed</option>
                <option value="done">Done</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <InputError class="mt-2" :message="form.errors.checkup_status" />
        </div> -->

        <div class="mt-1 flex flex-col gap-2">
        <InputLabel for="checkup_status" value="Checkup Status" />
        <label class="inline-flex items-center">
            <input
                type="radio"
                name="checkup_status"
                value="request"
                v-model="form.checkup_status"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Requested</span>
        </label>
        <label class="inline-flex items-center mt-1">
            <input
                type="radio"
                name="checkup_status"
                value="confirmed"
                v-model="form.checkup_status"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Confirmed</span>
        </label>
        <label class="inline-flex items-center mt-1">
            <input
                type="radio"
                name="checkup_status"
                value="done"
                v-model="form.checkup_status"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Done</span>
        </label>
        <label class="inline-flex items-center mt-1">
            <input
                type="radio"
                name="checkup_status"
                value="cancelled"
                v-model="form.checkup_status"
                class="form-radio"
            />
            <span class="custom-radio"></span>
            <span class="ml-2">Cancelled</span>
        </label>
        </div>

    <div class="flex items-center justify-center mt-4">
        <!-- <PrimaryButton @click="submit" class="ms-4">
            Submit
        </PrimaryButton> -->
        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Submit
        </PrimaryButton>
    </div>
    </form>

    <Alert v-if="alert.show" :message="alert.message" :type="alert.type" />
</Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

</style>
