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
import { ref, computed, onMounted } from 'vue';

const { props } = usePage();
const userId = props.auth.user.user_id;
const userFirstname = props.auth.user.firstname;
const userLastname = props.auth.user.lastname;
const userEmail = props.auth.user.email;
const localSymptoms = ref([]);
const systemicSymptoms = ref([]);
const qualitySymptoms = ref([]);
const sideEffectSymptoms = ref([]);
const patientId = ref(null);
const diseaseProfile = ref({ });
const cancertypeId = ref(null);
const latestOnboard = ref(null);
const currentUser = ref(null);
const draftId = ref(null);

const treatments = ref([]);
const treatmentCompletion = ref([]);

const surveyResponse = ref(null);

const alert = ref({
    show: false,
    message: '',
    type: '',
});

const fetchPatientId = async () => {
    try {
        const response = await axios.get('/api/find-patient', {
            params: { lastname: userLastname, email: userEmail },
        });
        patientId.value = response.data.patient_id || null;

        if (!patientId.value) {
            alert("Unable to find patient ID. Please verify your information.");
        }
    } catch (error) {
        console.error("Failed to fetch patient ID:", error);
        alert("An error occurred while fetching patient information.");
    }
};

const fetchPatientDiseaseProfile = async () => {
    try {
        if (!patientId.value) {
            console.warn("Skipping disease profile fetch as patient ID is missing.");
            return;
        }
        const response = await axios.get(`/api/disease/${patientId.value}/profile`);
        diseaseProfile.value = response.data || {};
        cancertypeId.value = diseaseProfile.value.disease_primarySite;
    } catch (error) {
        console.error("Failed to fetch disease profile:", error);
        alert("An error occurred while fetching the disease profile.");
    }
};

const fetchLocalSymptoms = async () => {
    try {
        const response = await axios.get(`/api/symptom-category/1`);
        localSymptoms.value = response.data;
        console.log("Local Symptoms: ", localSymptoms.value);
    } catch (error) {
        console.error('Error fetching symptoms:', error);
    }
};

const fetchSystemicSymptoms = async () => {
    try {
        const response = await axios.get(`/api/symptom-category/2`);
        systemicSymptoms.value = response.data;
        console.log("Systemic Symptoms: ", systemicSymptoms.value);
    } catch (error) {
        console.error('Error fetching symptoms:', error);
    }
};

const fetchQualitySymptoms = async () => {
    try {
        const response = await axios.get(`/api/symptom-category/3`);
        qualitySymptoms.value = response.data;
        console.log("Quality of Life Symptoms: ", qualitySymptoms.value);
    } catch (error) {
        console.error('Error fetching symptoms:', error);
    }
};

const fetchSideEffectSymptoms = async () => {
    try {
        const response = await axios.get(`/api/symptom-category/4`);
        sideEffectSymptoms.value = response.data;
        console.log("Treatment Side Effect Symptoms: ", sideEffectSymptoms.value);
    } catch (error) {
        console.error('Error fetching symptoms:', error);
    }
};

const fetchTreatments = async () => {
    try {
        const response = await axios.get(`/api/patient/${patientId.value}/treatments`);
        console.log("Treatment History: ", response.data)
        const rxtypes = response.data.rxtypes;
        const primarytreatment = response.data.treatments[0].treatment_primaryRxType;

        console.log(primarytreatment)
        if (rxtypes) {
            const treatmentMapping = {
                rxtype_chemotherapy: "Chemotherapy",
                rxtype_hormonaltherapy: "Hormonal Therapy",
                rxtype_immunotherapy: "Immunotherapy",
                rxtype_radiotherapy: "Radiotherapy",
                rxtype_surgery: "Surgery",
                rxtype_others: "Others",
            };

            treatments.value = Object.entries(treatmentMapping)
                .filter(([key, _]) => rxtypes[0][key] === 1)
                .map(([_, label]) => label);

            console.log(treatments.value)

            treatmentCompletion.value = [...treatments.value];
            if (primarytreatment) {
                treatmentCompletion.value.push(primarytreatment);
            }

            //treatmentCompletion.value = treatments.value;
            console.log("Treatment Completion: ", treatmentCompletion.value)
        }
    } catch (error) {
        console.error('Error fetching treatments:', error);
    }
};

const fetchSurveyResponse = async () => {
    try {
        const response = await axios.get(`/api/patients/${patientId.value}/survey-responses`);
        surveyResponse.value = response.data;

        if (surveyResponse.value && surveyResponse.value.surveyResponse_date) {
            console.log("survey response", surveyResponse)

            const responseDate = new Date(surveyResponse.value.surveyResponse_date);
            const currentDate = new Date();
            const threeMonthsLater = new Date(responseDate);
            threeMonthsLater.setMonth(threeMonthsLater.getMonth() + 3);

            console.log(responseDate)
            console.log(currentDate)
            console.log(threeMonthsLater)

            console.log("3 months later", threeMonthsLater)
            console.log("current date", currentDate)
            if (currentDate < threeMonthsLater) {
                alert.value = {
                    show: true,
                    message: 'Please answer this form after 3 months.',
                    type: 'warning',
                };
                disableForm();

            } else {
                alert.value = {
                    show: false,
                    message: '',
                    type: '',
                };
            }
        }
    } catch (error) {
        console.error('Failed to fetch survey responses:', error);
    }
};

const disableForm = () => {
    // Disable form by manipulating its elements or preventing submission
    form.disabled = true;
};


onMounted(async () => {
    try {
        await fetchPatientId();
        if (!patientId.value) {
            console.error("Patient ID not found. Other data will not be fetched.");
            return;
        }
        await fetchPatientDiseaseProfile();
        await fetchLocalSymptoms();
        await fetchSystemicSymptoms();
        await fetchQualitySymptoms();
        await fetchSideEffectSymptoms();
        await fetchTreatments();
        await fetchSurveyResponse();
    } catch (error) {
        console.error("Error during initialization:", error);
    }
});

const filteredLocalSymptoms = computed(() => {
    return localSymptoms.value.filter(
        (symptom) => symptom.cancer_type.body_site_id === cancertypeId.value ||
            symptom.cancer_type.body_site_name === 'Negative' ||
            symptom.cancer_type.body_site_name === 'All'
    );
});

const filteredSystemicSymptoms = computed(() => {
    return systemicSymptoms.value.filter(
        (symptom) => symptom.cancer_type.body_site_id === cancertypeId.value ||
            symptom.cancer_type.body_site_name === 'Negative' ||
            symptom.cancer_type.body_site_name === 'All'
    );
});

const filteredQualitySymptoms = computed(() => {
    return qualitySymptoms.value.filter(
        (symptom) => symptom.cancer_type.body_site_id === cancertypeId.value ||
            symptom.cancer_type.body_site_name === 'Negative' ||
            symptom.cancer_type.body_site_name === 'All'
    );
});

const filteredSideEffectSymptoms = computed(() => {
    return sideEffectSymptoms.value.filter(
        (symptom) => symptom.cancer_type.body_site_id === cancertypeId.value ||
            symptom.cancer_type.body_site_name === 'Negative' ||
            symptom.cancer_type.body_site_name === 'All'
    );
});

const form = useForm({
    patient_id: '',
    doctor_id: '',
    surveyResponse_date: '',
    symptom_surveys: [],
    selectedLocalSymptoms: '',
    selectedSystemicSymptoms: '',
    selectedQuality: '',
    selectedSideEffects: '',
    selectedTreatmentCompletion: [],
    additional_symptoms: '',
});

let currentId = props.auth.user.user_id;

const fetchByUserId = async (roleId,currentId) => {
    try {
        let response;
        if (roleId === 2) {
            response = await axios.get(`/api/doctor-profile/${currentId}`);
            currentUser.value = response.data;
            draftId.value = currentUser.value.doctor_id;
        } else {
            response = await axios.get(`/api/patient-profile/${currentId}`);
            currentUser.value = response.data;
            draftId.value = currentUser.value.patient_id;
        }
        console.log(draftId.value)
        return draftId.value;
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${currentId}:`, error);
    }
};

const getLatestOnboard = async (patientId) => {
    try {
            let response = await axios.get(`/api/latestonboards/${patientId}`);
            // console.log(response.data)
            latestOnboard.value = response.data.doctor_id;
        return latestOnboard.value;
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${currentId}:`, error);
    }
};

const resetForm = () => {
    form.patient_id = '';
    form.doctor_id = '';
    form.surveyResponse_date = '';
    form.selectedLocalSymptoms = [];
    form.selectedSystemicSymptoms = [];
    form.selectedQuality = [];
    form.selectedSideEffects = [];
    form.additional_symptoms = '';
    form.selectedTreatmentCompletion = [];
};

function formatWord(word) {
    if (!word) return '';
    return word
        .split(' ')
        .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
        .join(' ');
}

const submit = async () => {
    alert.value.show = false;

    if (!patientId.value) {
        alert("Patient ID is missing. Unable to submit the report.");
        return;
    }

    if (!form.selectedLocalSymptoms && !form.selectedSystemicSymptoms && !form.selectedQuality && !form.selectedSideEffects) {
        alert("Please select at least one symptom category.");
        return;
    }

    form.patient_id = await fetchByUserId(1, currentId)
    form.doctor_id = await getLatestOnboard(form.patient_id)
    form.surveyResponse_date = new Date().toISOString();
    form.symptom_surveys = [
        form.selectedLocalSymptoms,
        form.selectedSystemicSymptoms,
        form.selectedQuality,
        form.selectedSideEffects
    ];

    try {
        const response = await axios.post('/api/create-symptoms-report', {
            patient_id: form.patient_id,
            doctor_id: form.doctor_id,
            surveyResponse_date: new Date(form.surveyResponse_date).toISOString().split('T')[0],
            symptom_surveys: form.symptom_surveys,
            response_notes: form.additional_symptoms,
            treatment_completion: form.selectedTreatmentCompletion.join(", "),
        });

        let doctor = await axios.get(`api/get-doctor/${form.doctor_id}`)

        await axios.post('/api/create-notification-log', {
            from: userEmail,
            to: doctor.data.user.email,
            subject: 'Symptom Survey Submission',
            message: 'Submitted my symptom report.',
            senderId: currentId,
            receiverId: doctor.data.user.user_id,
            notificationStatus: 1,
            notificationType: 1,
            surveyResponseId: response.data.surveyResponse_id
        });


        //alert("Symptoms report submitted successfully!");
        alert.value = {
            show: true,
            message: 'Success! Symptoms report submitted successfully.',
            type: 'success',
        };

        resetForm();
    } catch (error) {
        console.error("Submission errors:", error);
        alert("Failed to submit the symptoms report. Please try again.");
    }
};

const isLSSelected = computed(() => form.selectedLocalSymptoms !== '');
const isSSSelected = computed(() => form.selectedSystemicSymptoms !== '');
const isQSelected = computed(() => form.selectedQuality !== '');
const isSESelected = computed(() => form.selectedSideEffects !== '');
// const isTCSelected = computed(() => form.selectedTreatmentCompletion !== '');

</script>

<template>
    <Head title="Symptoms Report" />

    <Layout>

        <div class="page-heading">
            <h1 class="main-heading">SYMPTOMS REPORT</h1>
        </div>

        <div
            v-if="alert.show && alert.type === 'warning'"
            class="alert-warning"
            style="margin: 0 auto; margin-bottom: 20px; padding: 10px; border-radius: 4px; border: 1px solid; background-color: #fffbe6; color: #856404; width: 45%;">
            {{ alert.message }}
        </div>

        <form @submit.prevent="submit" class="form-container " style="width: 700px;">

            <div class="lab-list">
                <h2>Local Symptoms:</h2>
                <!-- <p class="smallnote">*Please select symptom/s you are experiencing</p> -->
                <InputError v-if="!isLSSelected" message="Please select at least one." class="smallnote"/>
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2">
                    <label
                        v-for="(ls, index) in filteredLocalSymptoms"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                name="local_symptom"
                                type="radio"
                                :value="ls.symptomSurvey_id"
                                v-model="form.selectedLocalSymptoms"
                                class="form-radio"
                            />
                            <span class="custom-radio"></span>
                            <span class="ml-2">{{ ls.symptom_name }}</span>
                    </label>
                </div>
            </div>

            <div class="lab-list">
                <h2>Systemic Symptoms:</h2>
                <InputError v-if="!isSSSelected" message="Please select at least one." class="smallnote"/>
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2">
                    <label
                        v-for="(ss, index) in filteredSystemicSymptoms"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                type="radio"
                                :value="ss.symptomSurvey_id"
                                v-model="form.selectedSystemicSymptoms"
                                class="form-radio"
                            />
                            <span class="custom-radio"></span>
                            <span class="ml-2">{{ ss.symptom_name }}</span>
                    </label>
                </div>
            </div>

            <div class="lab-list">
                <h2>Quality of Life:</h2>
                <InputError v-if="!isQSelected" message="Please select at least one." class="smallnote" />
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2">
                    <label
                        v-for="(qual, index) in filteredQualitySymptoms"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                type="radio"
                                :value="qual.symptomSurvey_id"
                                v-model="form.selectedQuality"
                                class="form-radio"
                            />
                            <span class="custom-radio"></span>
                            <span class="ml-2">{{ qual.symptom_name }}</span>
                    </label>
                </div>
            </div>

            <div class="lab-list">
                <h2>Treatment Side Effects:</h2>
                <InputError v-if="!isSESelected" message="Please select at least one." class="smallnote"/>
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2">
                    <label
                        v-for="(se, index) in filteredSideEffectSymptoms"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                type="radio"
                                :value="se.symptomSurvey_id"
                                v-model="form.selectedSideEffects"
                                class="form-radio"
                            />
                            <span class="custom-radio"></span>
                            <span class="ml-2">{{ se.symptom_name }}</span>
                    </label>
                </div>
            </div>

            <div class="lab-list">
                <h2>Please specify other symptom/s you are experiencing:</h2>
                <TextInput
                        id="additional_symptoms"
                        v-model="form.additional_symptoms"
                        type="text"
                        class="mt-1 block w-full"
                />
            </div>

            <div class="lab-list">
                <h2>Treatment Completion:</h2>
                <p class="smallnote">*Please select treatment/s you already received</p>
                <!-- <InputError v-if="!isTCSelected" message="Please select at least one." /> -->
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2">
                    <label
                        v-for="(tc, index) in treatmentCompletion"
                        :key="index"
                        class="inline-flex items-center">
                            <input
                                name="treatmentcompletion"
                                type="checkbox"
                                :value="tc"
                                v-model="form.selectedTreatmentCompletion"
                                class="form-checkbox"
                            />
                            <span class="ml-2">{{ tc }}</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-center mt-4">
                <!-- <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !isLSSelected || !isSSSelected || !isQSelected || !isSESelected || !isTCSelected" > -->
                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing || alert.show || !isLSSelected || !isSSSelected || !isQSelected || !isSESelected">
                        Submit
                    </PrimaryButton>
            </div>
        </form>

        <Alert v-if="alert.show && alert.type !== 'warning'" :message="alert.message" :type="alert.type" />
    </Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

.smallnote {
    font-size: smaller;
    color: rgb(90, 90, 90);
    margin-top: -10px;
}

</style>
