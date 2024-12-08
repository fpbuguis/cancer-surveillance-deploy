<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Pages/Layout.vue';
import { ref, onMounted, watch, watchEffect } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';

const { url } = usePage();
const parts = url.split('/');
let patientId = parts[parts.length - 1];
let otherId;
patientId = parseInt(patientId, 10);

if (parts.length === 4) {
    otherId = parseInt(parts[2], 10);
    patientId = parseInt(parts[3], 10);
}

console.log('Patient ID:', patientId);

const form = useForm({
    lastname: '',
    email: '',
    treatment_purpose: '',
    other_treatment_purpose: '',
    primary_type: '',
    primary_treatment: '',
    initial_treatment_date: '',
    planned_additional_treatment: [],
    other_planned_additional_treatment: '',
    // surgical_operation: '',
    // operation_date: '',
});

const currentStep = ref(0);
const adjustedLength = ref(0);

// Watch for form changes
watch(form, (newVal) => {
    console.log("Updated form:", JSON.parse(JSON.stringify(newVal)));
}, { deep: true });

watch(
    () => [form.lastname, form.email],
    async (newVal, oldVal) => {
        if (form.lastname && form.email) {
            await fetchPatientId();
        }
    },
    { deep: true }
);

const patients = ref(null);
const patientData = ref({ lastname: '', email: '' });

// Fetch patient info when component mounts
const fetchPatientInfo = async () => {
    try {
        const response = await axios.get('/api/all-patients');
        const patient = response.data.find(p => p.patient_id === patientId);
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

const fetchPatientId = async () => {
    try {
        const response = await axios.get('/api/find-patient', {
            params: {
                lastname: form.lastname,
                email: form.email
            }
        });

        const patientid = response.data;

        if (patientid) {
            patientId = patientid.patient_id;
            console.log(patientId)
            console.log(otherId)
        } else {
            console.log('Failed to fetch patient ID');
        }
    } catch (error) {
        console.log('Failed to fetch patient ID:', error);
    }
};

// const treatments = [];
const selectedTreatments = (data,index) => {
    // const treatments = [];
    console.log(data)
    const rxtypes = data.rxtypes[index];

    if (rxtypes) {
    if (rxtypes.rxtype_chemotherapy === 1) form.planned_additional_treatment.push("Chemotherapy");
    if (rxtypes.rxtype_hormonaltherapy === 1) form.planned_additional_treatment.push("Hormonal Therapy");
    if (rxtypes.rxtype_immunotherapy === 1) form.planned_additional_treatment.push("Immunotherapy/Cryotherapy");
    if (rxtypes.rxtype_radiotherapy === 1) form.planned_additional_treatment.push("Radiotherapy");
    if (rxtypes.rxtype_surgery === 1) form.planned_additional_treatment.push("Surgery");
    if (rxtypes.rxtype_others === 1) form.planned_additional_treatment.push("Others");
    }

    // return treatments.filter((treatment) => treatment !== "Others").join(", ");
};

const fetchPatientTreatmentInfo = async (patientId) => {
    try {
        // console.log(patientId)
        let response = await axios.get(`/api/patient/${patientId}/treatments`);
        const data = response.data;

        form.treatment_purpose = data.treatments[otherId].treatment_purpose

        console.log("Treatment Info:", data);

        if (form.treatment_purpose === "Others") {
            form.other_treatment_purpose = data.treatments[otherId].treatment_other_purpose
        }

        form.primary_type = data.treatments[otherId].treatment_primaryRxType;
        form.primary_treatment = data.treatments[otherId].treatment_primaryRxName;
        form.initial_treatment_date = data.treatments[otherId].treatment_initialRxDate;
        selectedTreatments(data,otherId)

        if(form.planned_additional_treatment.includes('Others')) {
            form.other_planned_additional_treatment = data.rxtypes[otherId].rxtype_other_treatment
        }

        console.log(data)
    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

// onMounted(() => {
//     fetchPatientInfo();
//     fetchPatientTreatmentInfo();
// });

onMounted(async () => {
    await fetchPatientInfo();
    await fetchPatientTreatmentInfo(patientId);
    selectedTreatments(otherId)
    // setAvailablePages();
});

const submittedOnly = ref(0);

const submit = () => {
    console.log(form);

    form.post('/api/create-treatment-record', {
        onSuccess: () => {
            // Reset the form or redirect to a success page
            console.log("Success");

            if (submittedOnly.value === 1){
                Inertia.visit('/treatment-history');
            } else if (form.primary_type) {
                console.log(patientId)
                nextStep();
            } else {
                // window.location.href = `/treatment-history/surgery/${patientId}`;
                Inertia.visit('/consult');
            }
        },
        onError: (errors) => {
            if (errors.patient_id) {
                error.value = errors.patient_id[0];
            } else {
                console.log(errors);
            }
        },
    });

};

const nextStep = () => {
    if (currentStep.value === 0 && form.primary_type) {
        currentStep.value++;

        const data = {
            currentStep: 1,
            treatment: form.primary_type,
        };

        console.log(form.primary_type)
        console.log(patientId)

        switch (form.primary_type) {
            case 'Radiotherapy':
                Inertia.visit(`/treatment-history/radiotherapy/${patientId}`, {data});
                break;
            case 'Chemotherapy':
                Inertia.visit(`/treatment-history/chemotherapy/${patientId}`,{data});
                break;
            case 'Surgery':
                Inertia.visit(`/treatment-history/surgery/${patientId}`,{data});
                break;
            case 'Immunotherapy/Cryotherapy':
                Inertia.visit(`/treatment-history/immunotherapy/${patientId}`,{data});
                break;
            case 'Hormonal':
                Inertia.visit(`/treatment-history/hormonal/${patientId}`,{data});
                break;
            default:
                break;
        }
    }
};

const previousStep = () => {
    if (currentStep.value > 0) {
        currentStep.value--;
    }
};

watch(form, (newVal) => {
    console.log("Updated form:", JSON.parse(JSON.stringify(newVal)));
}, { deep: true });

// watch(patientId, (newVal) => {
//     console.log("Patient Id:", newVal);
// }, { deep: true });

</script>


<template>
    <Head title="Treatment History" />

    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">TREATMENT HISTORY</h1>
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

                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="treatment_purpose" value="Treatment Purpose" />

                    <div class="mt-1 flex flex-col gap-2">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="treatment_purpose"
                            value="Curative-complete"
                            v-model="form.treatment_purpose"
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
                            v-model="form.treatment_purpose"
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
                            v-model="form.treatment_purpose"
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
                            v-model="form.treatment_purpose"
                            class="form-radio"
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">Others</span>
                    </label>
                    </div>

                    <div v-if="form.treatment_purpose.includes('Others')" class="mt-3">
                        <InputLabel for="other_treatment_purpose" value="Specify" />
                        <input
                            id="other_treatment_purpose"
                            v-model="form.other_treatment_purpose"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Specify treatment purpose"
                        />
                    </div>

                    <InputError class="mt-2" :message="form.errors.treatment_purpose" />

                </div>

                <div>
                    <InputLabel for="primary_type" value="Primary Type" />
                        <select id="primary_type" v-model="form.primary_type" class="mt-1 block w-full">
                            <option value="" disabled>Select Primary Type</option>
                            <option value="Surgery">Surgery</option>
                            <option value="Radiotherapy">Radiotherapy</option>
                            <option value="Chemotherapy">Chemotherapy</option>
                            <option value="Immunotherapy/Cryotherapy">Immunotherapy/Cryotherapy</option>
                            <option value="Hormonal Therapy">Hormonal Therapy</option>
                            <option value="Unknown">Unknown</option>
                        </select>

                    <InputError :message="form.errors.primary_type" />
                </div>

                <div>
                    <InputLabel for="primary_treatment">
                        Primary Treatment
                    </InputLabel>
                    <TextInput
                        id="primary_treatment"
                        v-model="form.primary_treatment"
                        type="primary_treatment"
                        class="mt-1 block w-full"

                    />
                    <InputError class="mt-2" :message="form.errors.primary_treatment" />
                </div>

                <div>
                    <InputLabel for="initial_treatment_date" value="Initial Treatment Date" />
                    <TextInput
                        id="initial_treatment_date"
                        v-model="form.initial_treatment_date"
                        type="date"
                        class="mt-1 block w-full"
                    />
                    <InputError class="mt-2" :message="form.errors.initial_treatment_date" />
                </div>

                <!-- Planned Additional Treatment -->
                <div>
                    <InputLabel for="planned_additional_treatment" value="Planned Additional Treatment" />

                    <div class="mt-1 flex flex-col gap-2">
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                value="Surgery"
                                v-model="form.planned_additional_treatment"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Surgery</span>
                        </label>
                        <label class="inline-flex items-center mt-1">
                            <input
                                type="checkbox"
                                value="Radiotherapy"
                                v-model="form.planned_additional_treatment"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Radiotherapy</span>
                        </label>
                        <label class="inline-flex items-center mt-1">
                            <input
                                type="checkbox"
                                value="Chemotherapy"
                                v-model="form.planned_additional_treatment"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Chemotherapy</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                value="Immunotherapy/Cryotherapy"
                                v-model="form.planned_additional_treatment"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Immunotherapy/Cryotherapy</span>
                        </label>
                        <label class="inline-flex items-center mt-1">
                            <input
                                type="checkbox"
                                value="Hormonal Therapy"
                                v-model="form.planned_additional_treatment"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Hormonal Therapy</span>
                        </label>
                        <label class="inline-flex items-center mt-1">
                            <input
                                type="checkbox"
                                value="Unknown"
                                v-model="form.planned_additional_treatment"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Unknown</span>
                        </label>
                        <label class="inline-flex items-center mt-1">
                            <input
                                type="checkbox"
                                value="Others"
                                v-model="form.planned_additional_treatment"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Others</span>
                        </label>
                    </div>

                    <div v-if="form.planned_additional_treatment.includes('Others')" class="mt-3">
                        <InputLabel for="other_planned_additional_treatment" value="Specify" />
                        <input
                            id="other_planned_additional_treatment"
                            v-model="form.other_planned_additional_treatment"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Specify other planned additional treatment"
                        />
                    </div>

                    <InputError class="mt-2" :message="form.errors.planned_additional_treatment" />
                </div>

            <div class="flex items-center justify-center mt-4">
                <PrimaryButton @click="submittedOnly = 1" class="ms-4">
                    Submit
                </PrimaryButton>

                <PrimaryButton @click="nextStep" class="ms-4">
                    Submit and Proceed
                </PrimaryButton>
            </div>
        </form>
    </Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

</style>
