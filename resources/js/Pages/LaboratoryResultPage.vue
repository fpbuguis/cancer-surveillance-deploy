<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Alert from '@/Components/Alert.vue';
import Layout from '@/Pages/Layout.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';

const { props } = usePage();
const userId = props.auth.user.user_id;
const userLastname = props.auth.user.lastname;
const userEmail = props.auth.user.email;
const patientId = ref(null)
const diseaseProfile = ref({ });
const cancertypeId = ref(null);
const laboratories = ref([]);
const submittedLabs = ref([]);

const alert = ref({
    show: false,
    message: '',
    type: '',
});

const fetchPatientId = async () => {
    try {
        const response = await axios.get('/api/find-patient', {
            params: {
                lastname: userLastname,
                email: userEmail
            }
        });

        patientId.value = response.data.patient_id;

    } catch (error) {
        console.log('Failed to fetch patient ID:', error);
    }
};

const fetchPatientDiseaseProfile = async () => {
    try {
        let response = await axios.get(`/api/disease/${patientId.value}/profile`);
        diseaseProfile.value = response.data;
        console.log('Disease Profile:', diseaseProfile.value);

        cancertypeId.value = diseaseProfile.value.disease_primarySite;

    } catch (error) {
        console.error('Failed to fetch patient disease profile:', error);
    }
};

const fetchLaboratoriesByPrimarySite = async () => {
    try {
        const response = await axios.get(`/api/labMonitors/primary-site/${cancertypeId.value}`);
        laboratories.value = response.data;
        console.log("Laboratories: ", laboratories.value);
    } catch (error) {
        console.error('Error fetching laboratories:', error);
    }
};

const fetchPatientSubmittedLabs = async () => {   
    try { 
        const response = await axios.get(`/api/patients/${patientId.value}/labs-submitted`);
        submittedLabs.value = response.data;
        console.log("Submitted Laboratories: ", submittedLabs.value);
    } catch (error) {
        console.error('Error fetching submitted laboratories:', error);
    }
};

const getLatestOnboard = async (patientId) => {
    try {
            let response = await axios.get(`/api/latestonboards/${patientId}`);
            console.log(response.data.doctor_id)
            return response.data.doctor_id;
    } catch (error) {
        console.error(`Failed to fetch doctor:`, error);
    }
};

onMounted(async () => {
    await fetchPatientId();
    await fetchPatientDiseaseProfile();
    await fetchLaboratoriesByPrimarySite();
    await fetchPatientSubmittedLabs();
});

const getLatestSubmissionDate = (workupId) => {
    const labSubmissions = submittedLabs.value.filter(submitted => submitted.workup_id === workupId);
    if (labSubmissions.length === 0) return null; 
    const latestSubmission = labSubmissions.sort((a, b) => new Date(b.labSubmission_date) - new Date(a.labSubmission_date))[0];
    return latestSubmission.labSubmission_date;
};


const isWithinFrequency = (submittedDate, frequency) => {
    const now = new Date();
    const submissionDate = new Date(submittedDate);
    const nextDueDate = new Date(submissionDate);
    nextDueDate.setMonth(nextDueDate.getMonth() + frequency);
    return now <= nextDueDate;
};

const filteredLaboratories = computed(() => {
    if (!laboratories.value.length || !submittedLabs.value.length) {
        return laboratories.value;
    }

    return laboratories.value.filter((lab) => {
        const latestSubmissionDate = getLatestSubmissionDate(lab.workup.workup_id);

        if (!latestSubmissionDate) {  // labs not submitted yet, display as radio buttons
            return true;
        }
        
        const workup_frequency = lab.workup_frequency;
        if (!workup_frequency) { // no frequency defined, exclude if already submitted
            return false;
        }

        // check if the lab is still within the frequency window based on the latest submission
        return !isWithinFrequency(latestSubmissionDate, workup_frequency);
    });
});

// fetch from db
// const workups = [
//     'Mammogram', 'Colonoscopy', 'Chest Imaging', 'Abdominal Imaging', 'Tumor Marker', 'Bone Scan',
// ];

const form = useForm({
    patient_id: '',
    doctor_id: '',
    workupSubmitted: '',
    other_workups: '',
    labResults: [], // array for multiple files
});


const submit = async () => {
    alert.value.show = false;

    if (!isWorkupSelected.value) {
        alert('Please select a workup.');
        return;
    }

    if (form.labResults.length === 0) {
        alert('Please attach at least one lab result.');
        return;
    }

    form.patient_id = patientId.value;
    form.doctor_id = await getLatestOnboard(patientId.value);

    const formData = new FormData();

    // Append workup selection and other_workups
    formData.append('patient_id', form.patient_id);
    formData.append('doctor_id', form.doctor_id);
    formData.append('workupSubmitted', form.workupSubmitted);
    formData.append('other_workups', form.other_workups);

    // Append selected files
    form.labResults.forEach(file => {
        formData.append('labResults[]', file);
    });

    console.log(formData)

    try {
        const response = await axios.post('/api/labs/submit', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        let doctor = await axios.get(`api/get-doctor/${form.doctor_id}`)

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: doctor.data.user.email,
            subject: 'Laboratory Result Submission',
            message: `Submitted my laboratory result.`,
            senderId: userId,
            receiverId: doctor.data.user.user_id,
            notificationStatus: 1,
            notificationType: 2,
            labSubmittedId: response.data.labSubmission.labSubmitted_id
        });

        alert.value = {
            show: true,
            message: 'Success! Laboratory results submitted successfully.',
            type: 'success',
        };

        window.location.reload();

    } catch (error) {
        console.error('Error submitting lab results:', error);
        alert(`Failed to submit form: ${error.response?.data?.message || error.message}`);
    }

    console.log(formData);
};

const handleFileUpload = (event) => {
  form.labResults = Array.from(event.target.files); // convert file list to array
};

// const isWorkupSelected = computed(() => form.workupSubmitted.length > 0);
// const isWorkupSelected = computed(() => form.workupSubmitted && form.workupSubmitted !== '');
const isWorkupSelected = computed(() => !!form.workupSubmitted); // checks if the workup is selected

</script>

<template>
    <Head title="Laboratory Result" />

    <Layout>

        <div class="page-heading">
            <h1 class="main-heading">LABORATORY RESULT</h1>
        </div>

        <form @submit.prevent="submit" class="form-container" style="width: 700px;">

            <div class="form-section">
                <h2>Surveillance Workup</h2>

                <div class="mt-1 mb-2 grid grid-cols-1 gap-2">
                    <InputError v-if="!isWorkupSelected" message="Please select one." />
                    <label
                    v-for="(lab, index) in filteredLaboratories"
                    :key="index"
                    class="inline-flex items-center">
                        <input
                            type="radio"
                            name="labSubmitted"
                            :value="lab.workup.workup_name"
                            v-model="form.workupSubmitted"
                            class="form-radio"
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">{{ lab.workup.workup_name }}</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            value="Other"
                            v-model="form.workupSubmitted"
                            class="form-radio"
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">Other</span>
                    </label>
                </div>

                <div v-if="form.workupSubmitted === 'Other'">
                    <InputLabel for="other_workups">
                       Please specify workup <span class="red-asterisk">*</span>
                    </InputLabel>
                    <TextInput
                        id="other_workups"
                        v-model="form.other_workups"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                </div>

            </div>

            <div class="form-section">
                <h2>Laboratory Result</h2>
                <input type="file" accept=".jpg,.jpeg,.pdf" multiple @change="handleFileUpload" required/>
            </div>
            <div class="flex items-center justify-center mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !isWorkupSelected"  type="submit">
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
