<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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

// fetch from db
// const laboratories = [
//     'mammogram', 'bone scan', 'chest CT with contrast', 'creatinine'
// ];

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

// fetch from db
// const referrals = [
//     'medical oncology', 'nutritionist'
// ];

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


const form = useForm({
    selectedLaboratory: '',
    selectedReferral: '',
});

const selectedLaboratoryWorkupReferral = computed(() => {
    const selectedLab = laboratories.value.find(lab => lab.workup.workup_name === form.selectedLaboratory);
    return selectedLab ? selectedLab.workup_referral : null;
});

watch(() => form.selectedLaboratory, () => {
    form.selectedReferral = '';
});

const getLatestOnboard = async (patientId) => {
    try {
            let response = await axios.get(`/api/latestonboards/${patientId}`);
            console.log(response.data.doctor_id)
            return response.data.doctor_id;
    } catch (error) {
        console.error(`Failed to fetch doctor:`, error);
    }
};

const submit = async () => {
    try {
        const response = await axios.post('/api/create-lab-download', {
            patient_id: patientId.value,
            workup_id: form.selectedLaboratory
        }, { responseType: 'blob' });

        const blob = new Blob([response.data], { type: 'application/pdf' });
        const downloadUrl = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = downloadUrl;
        a.download = 'lab_results.pdf';
        document.body.appendChild(a);
        a.click();

        document.body.removeChild(a);
        URL.revokeObjectURL(downloadUrl);

        let doctorId = await getLatestOnboard(patientId.value);
        const receiver = await axios.get(`/api/get-doctor/${doctorId}`);
        const workup = await axios.get(`/api/workupById/${form.selectedLaboratory}`);

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: receiver.data.user.email,
            subject: 'Document Requested',
            message: `The document ${workup.data.workup_name} is requested`,
            senderId: userId,
            receiverId: receiver.data.user.user_id,
            notificationStatus: 1,
            notificationType: 3,
        });

        alert('Document requested!');
        form.reset(); 
    } catch (error) {
        console.error('Error submitting lab results:', error);
        alert(`Failed to submit form: ${error.response?.data?.message || error.message}`);
    }
};

const isLaboratorySelected = computed(() => form.selectedLaboratory !== '');
const isReferralSelected = computed(() => form.selectedReferral !== '');

</script>

<template>
    <Head title="Laboratory Request" />

    <Layout>

        <div class="page-heading">
            <h1 class="main-heading">REQUEST DOCUMENTS</h1>
        </div>


        <form @submit.prevent="submit" class="form-container" style="width: 700px;">
        <div>
            <div class="lab-list">
                <h2>Laboratory:</h2>
                <InputError v-if="!isLaboratorySelected" message="Please select one." />
                <!-- <div class="mt-1 mb-2 ml-5 grid grid-cols-1 ">
                    <label
                        v-for="(lab, index) in laboratories"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                type="radio"
                                :value="lab.workup.workup_id"
                                v-model="form.selectedLaboratory"
                                class="form-radio"
                            />
                            <span class="custom-radio"></span>
                            <span class="ml-2">{{ lab.workup.workup_name }}</span>
                    </label>
                </div> -->
                <div class="mt-1 mb-2 ml-5 grid grid-cols-1 ">
                    <label
                        v-for="(lab, index) in filteredLaboratories"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                type="radio"
                                :value="lab.workup.workup_id"
                                v-model="form.selectedLaboratory"
                                class="form-radio"
                            />
                            <span class="custom-radio"></span>
                            <span class="ml-2">{{ lab.workup.workup_name }}</span>
                    </label>
                </div>


                <!-- <h2>Please click the download button below to get your request.</h2> -->

                <!-- <h2>Referral:</h2>
                <InputError v-if="!isReferralSelected" message="Please select one." />
                <div class="mt-1 mb-2 ml-5 grid grid-cols-1 ">
                    <label
                        v-for="(ref, index) in referrals"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                type="radio"
                                :value="ref"
                                v-model="form.selectedReferral"
                                class="form-radio"
                            />
                            <span class="custom-radio"></span>
                            <span class="ml-2">{{ ref }}</span>
                    </label>
                </div> -->

                <h2>Referral:</h2>
                    <!-- <InputError v-if="!isReferralSelected" message="Please select one." /> -->
                    <div class="mt-1 mb-2 ml-5 grid grid-cols-1 ">
                        <template v-if="selectedLaboratoryWorkupReferral">
                            <label class="inline-flex items-center">
                                <input
                                    type="radio"
                                    :value="selectedLaboratoryWorkupReferral"
                                    v-model="form.selectedReferral"
                                    class="form-radio"
                                />
                                <span class="custom-radio"></span>
                                <span class="ml-2">{{ selectedLaboratoryWorkupReferral }}</span>
                            </label>
                        </template>

                        <!-- If there is no workup referral, show a message -->
                        <template v-else>
                            <InputError  message="There is no referral available for this laboratory."></InputError>
                        </template>
                    </div>

            </div>

            <div class="flex items-center justify-center mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !isLaboratorySelected"  type="submit">
                    DOWNLOAD
                </PrimaryButton>
            </div>


        </div>
        </form>

    </Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';


</style>
