<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Pages/Layout.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import Alert from '@/Components/Alert.vue';

// const props1 = defineProps({
//     patient: {
//         type: Object,
//         required: true
//     }
// });

const { url, props } = usePage();
const parts = url.split('/');
let patientId = parts[parts.length - 1]; // http://127.0.0.1:8000/consult/patientId
patientId = parseInt(patientId, 10);
let userId;
//console.log('Patient ID:', patientId);

const patients = ref(null);
const patientData = ref({ }); // pwede iadd to lastname: '', email: ''
const diseaseProfile = ref({ });
const selectedPatient = ref(0);
const selectedCheckup = ref(0);
const labsSubmitted = ref(null);
const surveyResponse = ref(null);

const customAlert = ref({
    show: false,
    message: '',
    type: '',
});

const fetchPatientInfo = async () => {
        try {
            let response = await axios.get('/api/all-patients');
            patients.value = response.data;

            // Find the patient with the matching patientId
            const patient = patients.value.find(p => p.patient_id === patientId);
            //console.log('Found patient:', patient);

        if (patient) {
            const user = patient.user; // Extracting the user object
            console.log('Patient Details:', user);
            userId = user.user_id;

            patientData.value.lastname = user.lastname;
            patientData.value.firstname = user.firstname;
            patientData.value.middlename = user.middlename;
            patientData.value.birthday = user.birthday;
        }
        } catch (error) {
            console.error('Failed to fetch patient info:', error);
        }
};

let currentId = props.auth.user.user_id;
const currentUser = ref(null);
let doctorId = ref(null);

const fetchCurrentDoctor = async (currentId) => {
    //console.log(currentId)
    try {
        let response = await axios.get(`/api/doctor-profile/${currentId}`);
        currentUser.value = response.data;
        doctorId.value = currentUser.value.doctor_id;
        //console.log(doctorId.value)
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${currentId}:`, error);
    }
};

const getPatientAge = (birthday) => {
    const today = new Date();
    const birthDate = new Date(birthday); // Convert birthday string to Date object
    let age = today.getFullYear() - birthDate.getFullYear(); // Calculate difference
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
};

const fetchPatientDiseaseProfile = async () => {
    try {
        let response = await axios.get(`/api/disease/${patientId}/profile`);
        diseaseProfile.value = response.data;
        console.log('Disease Profile:', diseaseProfile.value);

    } catch (error) {
        console.error('Failed to fetch patient disease profile:', error);
    }
};

const fetchPatientTreatmentInfo = async () => {
    try {
        let response = await axios.get(`/api/patient/${patientId}/treatments`);
        selectedPatient.value = response.data;
        console.log("Treatment Details: ", selectedPatient.value);


    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

const fetchPatientCheckup = async () => {
    try {
        let response = await axios.get(`/api/checkups/${patientId}`);
        selectedCheckup.value = response.data;
        console.log("Checkup Details: ", selectedCheckup.value);


    } catch (error) {
        console.error('Failed to fetch checkup:', error);
    }
};

const fetchLabsSubmitted = async () => {
    try {
        let response = await axios.get(`/api/patients/${patientId}/latest-labs-submitted`);
        labsSubmitted.value = response.data;
        console.log("Lab Submitted:", labsSubmitted.value)
    }
    catch (error) {
        console.error('Failed to fetch labs submitted:', error);
    }
};

const fetchSurveyResponse = async () => {
    try {
        let response = await axios.get(`/api/patients/${patientId}/survey-responses`);
        surveyResponse.value = response.data;
        console.log("Survey Response:", surveyResponse.value)
    }
    catch (error) {
        console.error('Failed to fetch survey responses:', error);
     }
};

const latest_Sched = ref(null);
const isValidSched = ref(true);

const showAlert = (message, type) => {
    customAlert.value = {
        show: true,
        message,
        type,
    };

    // Automatically hide the alert after 3 seconds
    setTimeout(() => {
        customAlert.value.show = false;
    }, 3000);
};

const checkValidCheckupSched = async () => {
    try {
        let response = await axios.get(`/api/patient/${patientId}/latest-schedule`);
        latest_Sched.value = response.data;

        console.log("Latest Sched:", latest_Sched);

        if (latest_Sched.value && latest_Sched.value.checkupConfirmed_date) {
            const checkupDate = new Date(latest_Sched.value.checkupConfirmed_date);
            const today = new Date();

            today.setHours(0, 0, 0, 0);
            checkupDate.setHours(0, 0, 0, 0);

            if (checkupDate < today) {
                latest_Sched.value.checkupStatus_id = 4;
                isValidSched.value = false;

                await axios.put(`/api/patient/${patientId}/update-schedule`, {
                    checkupStatus_id: latest_Sched.value.checkupStatus_id
                });
                showAlert('Checkup Schedule was Cancelled!', 'warning');
            } else if (latest_Sched.value.checkupStatus_id === 3) {
                isValidSched.value = false;
                showAlert('Checkup Schedule already Done!', 'warning');
            } else {
                isValidSched.value = true;
            }
            console.log("Updated Schedule:", latest_Sched.value.checkupStatus_id);
        } else {
            isValidSched.value = false;
            showAlert('Checkup Schedule is not confirmed yet!', 'warning');
        }
    } catch (error) {
        console.error("Error fetching or updating schedule:", error);
    }
}

watch(
    () => customAlert.value,
    (newAlert, oldAlert) => {
        console.log('Alert changed:', newAlert, oldAlert);
        // Add any additional logic here if needed
    },
    { deep: true } // Watch for deep changes within the alert object
);

onMounted(async () => {
    await fetchPatientInfo();
    await fetchPatientDiseaseProfile();
    await fetchPatientTreatmentInfo();
    await fetchCurrentDoctor(currentId);
    await fetchPatientCheckup();
    await fetchLabsSubmitted();
    await fetchSurveyResponse();
    await checkValidCheckupSched();
    console.log("Latest Schedule:", latest_Sched);
    console.log(isValidSched.value)
    console.log(customAlert)
});

onMounted(() => {
    checkValidCheckupSched();
});


const EditInfo = () => {
    const data = {
        userId: userId
    };
    Inertia.visit('/patient-enrollment', { data });
}

const form = useForm({
    subjective: '',
    objective: '',
    surveillance_workup: '',
    assessment: '',
    plan: '',
    disease_status: [],
    patient_status: '',
    patient_id: patientId,
    doctor_id: computed(() => doctorId.value)
});

const disease_statuses = [
    "Alive", "Recurrent", "With symptoms", "Curative", "Metastatic"
];

const patient_statuses = [
    "Recovered", "Improved", "Unimproved", "Died"
];

const submit = () => {
    console.log(form);
    console.log(isValidSched.value)

    if (isValidSched.value === true) {
        form.post('/api/create-checkup', {
            onSuccess: () => {
                Inertia.visit(`/consult/${patientId}`);
                showAlert('Checkup Done!', 'success');
            },
            onError: (errors) => {
                if (errors.patient_id) {
                    error.value = errors.patient_id[0];
                } else {
                    console.log(errors);
                }
            },
        });
    } else {
        if (latest_Sched.value.checkupStatus_id === 3) {
            alert("Consultation Schedule Added!")
        } else {
            alert("Checkup Schedule was Cancelled!")
        }
    }

};

// consult buttons
const searchTerm = ref('');

const search = () => {
    if(patientData) {
        searchTerm.value = patientData.value.firstname
    }
    Inertia.get('/search', { name: searchTerm.value }, {
        preserveState: true
    });
};

const handleClick = (href) => {
    Inertia.visit(href);
};

</script>

<template>
    <Head title="Consult" />

    <Layout>

        <div class="page-heading">
            <h1 class="main-heading">CONSULT</h1>
        </div>

        <div class="wider-page-container">

            <div class="two-section-container">
                <div class="info-left">
                    <p><span>Name: </span><span class="display" v-if="patientId">{{ patientData.lastname }}, {{ patientData.firstname }} {{ patientData.middlename }}</span></p>
                    <p><span>Age: </span><span class="display" v-if="patientId">{{ getPatientAge(patientData.birthday) }}</span></p>
                    <p><span>Diagnosis: </span>
                        <span class="display" v-if="patientId && diseaseProfile.disease_stage">{{ "Stage " + diseaseProfile.disease_stage + ", " }}</span>
                        <span class="display" v-if="patientId && diseaseProfile.disease_laterality">{{ diseaseProfile.disease_laterality?.laterality_name + " laterality" || '' }}</span>
                    </p>

                    <p>
                        <span>Operation: </span>
                        <span class="display" v-if="patientId && selectedPatient?.surgeries?.length">{{  selectedPatient.surgeries[selectedPatient.surgeries.length - 1].surgery_operation }}, {{ selectedPatient.surgeries[selectedPatient.surgeries.length - 1].surgery_date  }}</span>
                    </p>

                    <p>
                        <span>Chemotherapy: </span>
                        <span class="display" v-if="patientId  && selectedPatient?.chemotherapies?.length">{{  selectedPatient.chemotherapies.length > 0 ? 'Yes' : 'None' }}, {{ selectedPatient.chemotherapies[selectedPatient.chemotherapies.length - 1].chemo_status  }}</span>
                    </p>

                    <p>
                        <span>Radiotherapy: </span>
                        <span class="display" v-if="patientId  && selectedPatient?.radiotherapies?.length">{{  selectedPatient.radiotherapies.length > 0 ? 'Yes' : 'None' }}, {{ selectedPatient.radiotherapies[selectedPatient.radiotherapies.length - 1].radRx_status  }}</span>
                    </p>

                    <p>
                        <span>Hormonal Therapy: </span>
                        <span class="display" v-if="patientId  && selectedPatient?.hormonals?.length">{{  selectedPatient.hormonals.length > 0 ? 'Yes' : 'None' }}, {{ selectedPatient.hormonals[selectedPatient.hormonals.length - 1].hormonal_status  }}</span>
                    </p>

                </div>



                <div class="info-right">
                    <p>
                        <span>Patient Status: </span>
                        <span class="display" v-if="patientId  && selectedCheckup">{{  selectedCheckup.checkup.checkup_patientStatus }}</span><span class="display" v-else></span>
                    </p>

                    <p>
                        <span>Latest Consult Date: </span>
                        <span class="display" v-if="patientId  && selectedCheckup ">{{  selectedCheckup.checkup.checkup_date }}</span><span class="display" v-else></span>
                    </p>

                    <p>
                        <span>Latest Labs Submitted: </span>
                        <span class="display" v-if="patientId  && labsSubmitted ">{{  labsSubmitted.workup.workup_name }}</span><span class="display" v-else></span>
                    </p>

                    <p>
                        <span>Submission Date: </span>
                        <span class="display" v-if="patientId  && labsSubmitted ">{{  labsSubmitted.labSubmission_date }}</span><span class="display" v-else></span>
                    </p>

                    <p>
                        <span>Patient S/Sx Report: </span>
                        <span class="display" v-if="patientId && surveyResponse">
                            {{ surveyResponse.symptom_surveys.map(survey => survey.symptom_name).join(', ') }}
                        </span>
                        <span class="display" v-else></span>
                    </p>

                    <p>
                        <span>Patient Report Date: </span>
                        <span class="display" v-if="patientId  && surveyResponse ">{{ surveyResponse.surveyResponse_date   }}</span><span class="display" v-else></span>
                    </p>
                </div>
            </div>

            <div class="two-section-container">
                <!-- <div class="side-form-section">
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <InputLabel for="subjective" value="Subjective" />
                            <textarea v-model="form.subjective"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="objective" value="Objective" />
                            <textarea v-model="form.objective" class="obj-textarea"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="surveillance_workup" value="Surveillance Workup" />
                            <textarea v-model="form.surveillance_workup" class="sw-textarea"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="assessment" value="Assessment" />
                            <textarea v-model="form.assessment"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="plan" value="Plan" />
                            <textarea v-model="form.plan" class="sw-textarea"></textarea>
                        </div>

                        <div class="form-group">
                            <InputLabel for="patient_status" value="Patient Status" />
                            <select v-model="form.patient_status" class="mt-1 block w-full">
                                <option value="" disabled>Select Patient Status</option>
                                <option v-for="(ps, index) in patient_statuses" :key="index" :value="ps">{{ ps }}</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-center">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Submit
                            </PrimaryButton>
                        </div>
                    </form>
                </div> -->

                <div class="side-form-section">
                    <p>{{  }}</p>
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <InputLabel for="subjective" value="Subjective" />
                            <textarea v-model="form.subjective" :disabled="!isValidSched"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="objective" value="Objective" />
                            <textarea v-model="form.objective" class="obj-textarea" :disabled="!isValidSched"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="surveillance_workup" value="Surveillance Workup" />
                            <textarea v-model="form.surveillance_workup" class="sw-textarea" :disabled="!isValidSched"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="assessment" value="Assessment" />
                            <textarea v-model="form.assessment" :disabled="!isValidSched"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="plan" value="Plan" />
                            <textarea v-model="form.plan" class="sw-textarea" :disabled="!isValidSched"></textarea>
                        </div>
                        <div class="form-group">
                            <InputLabel for="patient_status" value="Patient Status" />
                            <select v-model="form.patient_status" class="mt-1 block w-full" :disabled="!isValidSched">
                                <option value="" disabled>Select Patient Status</option>
                                <option v-for="(ps, index) in patient_statuses" :key="index" :value="ps">{{ ps }}</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-center">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing || !isValidSched }"
                                        :disabled="form.processing || !isValidSched">
                                Submit
                            </PrimaryButton>
                        </div>
                    </form>
                </div>

                <div class="buttons-section">
                    <button class="bigger-btn" @click="search">UPDATE PATIENT INFO</button>
                    <button class="bigger-btn" @click="handleClick(`/prescription`)">PRESCRIPTION</button>
                    <button class="bigger-btn" @click="handleClick(`/labrequest`)">LAB REQUEST</button>
                    <button class="bigger-btn" @click="handleClick(`/clinicalabstract`)">CLINICAL ABSTRACT</button>
                    <button class="bigger-btn" @click="handleClick(`/medcertification`)">MED CERTIFICATION</button>
                    <button class="bigger-btn" @click="handleClick(`/referralform`)">REFERRAL FORM</button>
                </div>
            </div>
       </div>

       <Alert v-if="customAlert.show" :message="customAlert.message" :type="customAlert.type" />

    </Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

.obj-textarea{
    height: 150px;
}

.sw-textarea{
    height: 80px;
}

.display{
    font-weight: normal;
    font-size: 15px;
}
</style>
