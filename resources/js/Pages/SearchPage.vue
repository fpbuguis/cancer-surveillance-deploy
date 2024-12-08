<script setup>
import Layout from '@/Pages/Layout.vue';
import { ref, computed, onMounted, watchEffect } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Head } from '@inertiajs/inertia-vue3';
import { Link, usePage } from '@inertiajs/vue3';
import PatientInfoPage from './PatientInfoPage.vue';
import DiseaseInfoPage from './DiseaseInfoPage.vue';
import TreatmentHistoryInfoPage from './TreatmentHistoryInfoPage.vue';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

const patients = ref([]);
const selectedPatient = ref(null);
const currentPage = ref(null);
const searchTerm = ref('');

const { url} = usePage();

if (url) {
    const currentUrl = new URL(url, window.location.origin);
    searchTerm.value = currentUrl.searchParams.get('name');
} else {
    console.error('URL is undefined');
}

const { props } = usePage();
const userId = props.auth.user.user_id;
const doctor = ref(null);

const fetchCurrentDoctor = async (userId) => {
    try {
        let response = await axios.get(`/api/doctor-profile/${userId}`);
        doctor.value = response.data;
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${userId}:`, error);
    }
};

const fetchPatients = async (doctorId) => {
    try {
        let response = await axios.get(`/api/doctors/${doctorId}/patients`);
        patients.value = response.data;
    } catch (error) {
        console.error('Failed to fetch patients:', error);
    }
};

const fetchPatientDiseaseAndLatestEncounter = async (reactiveWorkup) => {
    try {
        for (let i = 0; i < reactiveWorkup.value.length; i++) {
            const workup = reactiveWorkup.value[i];
            if (workup.patient_id) {

                const fetchWithFallback = async (url, fallback = '') => {
                    try {
                        const response = await axios.get(url);
                        return response.data;
                    } catch (error) {
                        if (error.response && error.response.status === 404) {
                            console.warn(`404 Not Found: ${url}`);
                            return fallback;
                        }
                        throw error;
                    }
                };

                const diseaseResponse = await fetchWithFallback(`/api/latestdisease/${workup.patient_id}/profile`);
                const checkupResponse = await fetchWithFallback(`/api/checkups/${workup.patient_id}`);
                const surveyResponses = await fetchWithFallback(`/api/patients/${workup.patient_id}/survey-responses`);
                const labsSubmitted = await fetchWithFallback(`/api/patients/${workup.patient_id}/latest-labs-submitted`);

                const latestEntry = ref(null);

                if (checkupResponse.checkup && surveyResponses && labsSubmitted) {
                    const entries = [];

                    entries.push({
                        date: new Date(checkupResponse.checkup.checkup_date),
                        type: "Consult"
                    });

                    entries.push({
                        date: new Date(surveyResponses.surveyResponse_date),
                        type: "Survey Response"
                    });

                    entries.push({
                        date: new Date(labsSubmitted.labSubmission_date),
                        type: "Laboratory"
                    });

                    const latestDate = entries.reduce((latest, current) => {
                        return current.date > latest ? current.date : latest;
                    }, new Date(0));

                    const latestEntries = entries.filter(entry => entry.date.getTime() === latestDate.getTime());

                    latestEntry.value = {
                        date: latestDate,
                        types: latestEntries.map(entry => entry.type).join(', ')
                    };

                }

                reactiveWorkup.value[i] = {
                    ...workup,
                    disease: diseaseResponse || '',
                    latestEncounter: latestEntry.value || '',
                };
            }
        }
    } catch (error) {
        console.error('Failed to fetch checkups or disease profiles:', error);
    }
};

onMounted(async () => {
    await fetchCurrentDoctor(userId);
    await fetchPatients(doctor?.value.doctor_id);

    await fetchPatientDiseaseAndLatestEncounter(filteredWorkups);
});



const openPage = (page) => {
    if (selectedPatient.value) {
        currentPage.value = page;
    } else {
        alert('Please select a patient first.');
    }
};

const filteredWorkups = computed(() => {
    if (!searchTerm.value) return patients.value;
    return patients.value?.filter((workup) => {
        const firstName = workup.user?.firstname?.toLowerCase() || '';
        const lastName = workup.user?.lastname?.toLowerCase() || '';
        const searchValue = searchTerm.value.toLowerCase();
        return firstName.includes(searchValue) || lastName.includes(searchValue);
    });
});

const schedConsult = () => {
    if (selectedPatient.value) {
        Inertia.visit(`/schedconsult/${selectedPatient.value.patient_id}`);
    } else {
        alert('Please select a patient first.');
    }
}

const consultPage = () => {
    if (selectedPatient.value) {
        Inertia.visit(`/consult/${selectedPatient.value.patient_id}`);
    } else {
        alert('Please select a patient first.');
    }
}

const messagePage = () => {
    if (selectedPatient.value) {
        Inertia.visit(`/message/${selectedPatient.value.patient_id}`);
    } else {
        alert('Please select a patient first.');
    }
}

watchEffect(async () => {
    console.log(selectedPatient.value.patient_id)
});

function formatWord(word) {
    if (!word) return '';
    return word
        .split(' ')
        .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
        .join(' ');
}

const formatDate = (date) => {
  const parsedDate = new Date(date);
  if (isNaN(parsedDate)) {
    return "";
  }
  return parsedDate.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

</script>

<template>
    <Head title="Search" />

    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">{{ currentPage ? currentPage.toUpperCase() : "SEARCH" }}</h1>
        </div>
        <div v-if="!currentPage">

            <DataTable
                v-if="filteredWorkups?.length"
                :value="filteredWorkups"
                responsiveLayout="scroll"
                :paginator="true"
                :rows="3"
                :rowsPerPageOptions="[3, 5, 10, 25]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                selectionMode="single"
                v-model:selection="selectedPatient"
            >
                <template #header>
                    <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                        <h2 class="table-title m-0">Submitted Workup</h2>
                    </div>
                </template>
                <Column field="user.firstname" header="Name" :sortable="true">
                    <template #body="slotProps">
                        {{
                            formatWord(slotProps.data.user.firstname) + ' ' +  formatWord(slotProps.data.user.lastname)
                        }}
                    </template>
                </Column>
                <Column field="diagnosis" header="Diagnosis" :sortable="true">
                <template #body="slotProps">
                    {{ slotProps.data.disease?.disease_fullDiagnosis || '' }}
                </template>
                </Column>
                <Column field="last_encounter_type" header="Last Encounter Type" :sortable="true">
                <template #body="slotProps">
                    {{ slotProps.data.latestEncounter?.types || '' }}
                </template>
                </Column>
                <Column field="last_encounter_date" header="Last Encounter Date" :sortable="true">
                <template #body="slotProps">
                    {{ formatDate(slotProps.data.latestEncounter?.date) || '' }}
                </template>
                </Column>
            </DataTable>

            <!-- Buttons to Navigate to Other Pages -->
            <div class="buttons-section">
                <div class="column">
                    <button class="bigger-btn" @click="openPage('Patient Information')">PATIENT INFO</button>
                    <button class="bigger-btn" @click="openPage('Disease Profile')">DISEASE PROFILE</button>
                    <button class="bigger-btn" @click="schedConsult()">SCHED CONSULT</button>
                </div>
                <div class="column">
                    <button class="bigger-btn" @click="consultPage()">CONSULT</button>
                    <button class="bigger-btn" @click="openPage('Treatment History')">TREATMENT HISTORY</button>
                    <button class="bigger-btn" @click="messagePage()">MESSAGE</button>
                </div>
            </div>
        </div>

        <!-- Conditional Rendering of Pages -->
        <div v-if="currentPage === 'Patient Information'">
            <PatientInfoPage :patient="selectedPatient" />
        </div>
        <div v-if="currentPage === 'Disease Profile'">
            <DiseaseInfoPage :patient="selectedPatient" />
        </div>
        <div v-if="currentPage === 'Treatment History'">
            <TreatmentHistoryInfoPage :patient="selectedPatient" />
        </div>
    </Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

.table-section {
    padding: 0px 3%;
}

.buttons-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    width: 50%;
    margin: 0 auto;
}

.buttons-section .column {
    display: flex;
    flex-direction: column;
}

.bigger-btn {
    margin: 10px 0;
}

:deep(.p-highlight) {
    background-color: #f2e7e6 !important;
}

</style>
