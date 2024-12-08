<script setup>
    import Layout from '@/Pages/Layout.vue';
    import DataTable from 'primevue/datatable';
    import Column from 'primevue/column';
    import { ref, computed, onMounted } from 'vue';
    import VueCal from 'vue-cal';
    import 'vue-cal/dist/vuecal.css';
    import { Link, usePage } from '@inertiajs/vue3';
    import { Inertia } from '@inertiajs/inertia';

    const patients = ref(null);
    const today = new Date().toDateString()
    const { props } = usePage();
    const userId = props.auth.user.user_id;
    const doctor = ref(null);
    const selectedDate = ref(null);
    const showModal = ref(false);

    const checkupScheds = ref(null);
    const labsSubmitted = ref(null);
    const surveyResponses = ref(null);
    const reactiveWorkup = ref([]);

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
            console.log(patients.value)
        } catch (error) {
            console.error('Failed to fetch patients:', error);
        }
    };

    const fetchCheckupSchedules = async (doctorId) => {
        try {
            let response = await axios.get(`/api/doctors/${doctorId}/checkup-schedules`);
            checkupScheds.value = response.data;
            console.log(checkupScheds.value)
        } catch (error) {
            console.error('Failed to fetch checkup schedules:', error);
        }
    };

    const fetchLabsSubmitted = async (doctorId) => {
        try {
            console.log(doctorId)
            let response = await axios.get(`/api/doctors/${doctorId}/labs-submitted`);

            console.log(response.data)
            labsSubmitted.value = response.data;
            console.log(labsSubmitted.value)
        } catch (error) {
            console.error('Failed to fetch checkup schedules:', error);
        }
    };

    const fetchSurveyResponses = async (doctorId) => {
        try {
            let response = await axios.get(`/api/doctors/${doctorId}/survey-responses`);
            surveyResponses.value = response.data;
            console.log(surveyResponses.value)
        } catch (error) {
            console.error('Failed to fetch checkup schedules:', error);
        }
    };

    let submittedWorkup = computed(() => {
        if (!labsSubmitted.value || !surveyResponses.value) return [];
        return [
            ...labsSubmitted.value.map(item => ({ ...item, type: 'lab' })),
            ...surveyResponses.value.map(item => ({ ...item, type: 'survey' })),
        ];
    });

    const fetchPatientCheckupsAndDiseaseForSubmittedWorkups = async (reactiveWorkup) => {
        try {
            for (let i = 0; i < reactiveWorkup.value.length; i++) {
                const workup = reactiveWorkup.value[i];
                if (workup.patient_id) {
                    let checkupResponse = await axios.get(`/api/checkups/${workup.patient_id}`);
                    let diseaseResponse = await axios.get(`/api/latestdisease/${workup.patient_id}/profile`);

                    reactiveWorkup.value[i] = {
                        ...workup,
                        checkup: checkupResponse.data.checkup,
                        disease: diseaseResponse.data,
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
        await fetchCheckupSchedules(doctor?.value.doctor_id);
        await fetchLabsSubmitted(doctor?.value.doctor_id);
        await fetchSurveyResponses(doctor?.value.doctor_id);

        reactiveWorkup.value = submittedWorkup.value;

        console.log( reactiveWorkup.value)
        await fetchPatientCheckupsAndDiseaseForSubmittedWorkups(reactiveWorkup);
        console.log(reactiveWorkup)
    });

    const checkupSchedsToday = computed(() =>
        checkupScheds.value?.filter(checkupSched =>
            new Date(checkupSched.checkupConfirmed_date).toDateString() === today
        )
    );

    const getCheckupSchedsForDay = (date) => {
        return checkupScheds.value?.filter(checkupSched =>
            new Date(checkupSched.checkupConfirmed_date).toDateString() === new Date(date).toDateString()
        )
    };

    const onDayClick = (date) => {
        selectedDate.value = date;
        showModal.value = true;
    };

    const events = computed(() => {
        const counts = {};
        checkupScheds.value?.forEach(checkupSched => {
            const date = new Date(checkupSched.checkupConfirmed_date).toISOString().split('T')[0];
            counts[date] = (counts[date] || 0) + 1;
        });

        return Object.entries(counts).map(([date, count]) => ({
            start: date,
            end: date,
            title: `${count} patient${count > 1 ? 's' : ''}`,
        }));
    });

    const formatDate = (date) => {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Intl.DateTimeFormat('en-US', options).format(new Date(date));
    };

    const formatDateWithoutDay = (date) => {
        if (!date || date.trim() === '') {
            return 'Loading...';
        }
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Intl.DateTimeFormat('en-US', options).format(new Date(date));
    };

    const formatTime = (time) => {
        const [hours, minutes, seconds] = time.split(":").map(Number);
        const amPm = hours >= 12 ? "PM" : "AM";
        const formattedHours = hours % 12 || 12; // Convert 0 to 12 for 12-hour clock
        return `${formattedHours}:${String(minutes).padStart(2, "0")} ${amPm}`;
    };

    function formatWord(word) {
        if (!word) return '';
        return word
            .split(' ')
            .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
            .join(' ');
    }

    const handleLinkClick = (href) => {
        Inertia.visit(href);
    };

</script>

<template>
        <Layout>
            <div class="page-heading">
                <h1 class="main-heading"> CANCER SURVEILLANCE SYSTEM </h1>
            </div>

            <div class="container">
                <div class="left-panel">
                    <div class="patient_list">
                        <h2>Patients List</h2>
                    </div>
                    <DataTable
                        v-if="checkupSchedsToday && checkupSchedsToday.length"
                        :value="checkupSchedsToday"
                        responsiveLayout="scroll"
                        :paginator="true"
                        :rows="5"
                        class="modal-table"
                    >
                        <Column field="patient.user.firstname" header="Patient Name" class="patient-name" :sortable="true">
                            <template #body="slotProps">
                                <Link href="/consult" @click="handleLinkClick(`/consult/${slotProps.data.patient_id}`)">
                                    {{ formatWord(slotProps.data.patient.user.firstname) + ' ' + formatWord(slotProps.data.patient.user.lastname) }}
                                </Link>
                            </template>
                        </Column>
                        <Column field="checkup_startTime" header="Appointment Time" :sortable="true">
                            <template #body="slotProps">
                                {{ formatTime(slotProps.data.checkup_startTime) }}
                            </template>
                        </Column>
                    </DataTable>
                    <h1 class="center" v-else>No Patients Today</h1>
                </div>
                <div class="right-panel">
                    <vue-cal
                        class="custom-vuecal"
                        hide-view-selector
                        startWeekOnSunday
                        full
                        :time-from="10 * 60"
                        :disable-views="['years', 'year', 'week', 'day']"
                        active-view="month"
                        @cell-click="onDayClick"
                        :events="events"
                    >
                    </vue-cal>
                </div>
            </div>

            <div class="table-section">
                <DataTable
                v-if="reactiveWorkup && reactiveWorkup.length"
                :value="reactiveWorkup"
                responsiveLayout="scroll"
                :paginator="true"
                :rows="3"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[3, 5, 10, 25]"
                >
                <template #header>
                    <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                        <h2 class="table-title m-0">Submitted Workup</h2>
                    </div>
                </template>

                <Column field="patient.user.firstname" header="Name" :sortable="true" >
                    <template #body="slotProps">
                        {{ formatWord(slotProps.data.patient.user.firstname) +' '+ formatWord(slotProps.data.patient.user.lastname)}}
                        <!-- {{ slotProps.data.patient.user.firstname }} -->
                    </template>
                </Column>
                <Column field="diagnosis" header="Diagnosis" :sortable="true">
                    <template #body="slotProps">
                        {{ slotProps.data.disease?.disease_fullDiagnosis || '' }}
                    </template>
                </Column>
                <Column field="submission_date" header="Submission Date" :sortable="true">
                    <template #body="slotProps">
                        {{ formatDateWithoutDay(slotProps.data.labSubmission_date || slotProps.data.surveyResponse_date) }}
                    </template>
                </Column>
                <Column field="laboratory" header="Laboratory" :sortable="true" >
                    <template #body="slotProps">
                        {{ slotProps.data.workup?.workup_name ? slotProps.data.workup?.workup_name : 'Symptom Survey Report' }}
                    </template>
                </Column>
                <Column field="last_encounter_date" header="Last Encounter Date" :sortable="true" >
                    <template #body="slotProps">
                        {{ formatDateWithoutDay(slotProps.data.checkup?.checkup_date || ' ') }}
                    </template>
                </Column>

                </DataTable>
                <h1 class="center" v-else>No Submitted Workup</h1>
            </div>

            <div v-if="showModal" class="modal-backdrop"></div>
            <div v-if="showModal" class="patient_list modal">
                <button @click="showModal = false" class="close-button">X</button>
                <h2>Patients List for {{ formatDate(selectedDate) }}</h2>

                <div v-if="getCheckupSchedsForDay(selectedDate) && getCheckupSchedsForDay(selectedDate).length">
                    <DataTable
                    :value="getCheckupSchedsForDay(selectedDate)"
                    responsiveLayout="scroll"
                    :paginator="true"
                    :rows="5"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[ 5, 10, 25]"
                    class="modal-table"
                    >
                    <Column field="patient.user.firstname" header="Patient Name" class="patient-name" :sortable="true">
                        <template #body="slotProps">
                            <Link href="/consult" @click="handleLinkClick(`/consult/${slotProps.data.patient_id}`)">
                                {{ formatWord(slotProps.data.patient.user.firstname) + ' ' +formatWord(slotProps.data.patient.user.lastname) }}
                            </Link>
                        </template>
                    </Column>
                    <Column field="user.created_at" header="Appointment Time" :sortable="true">
                        <template #body="slotProps">
                            {{ formatTime(slotProps.data.checkup_startTime) }}
                        </template>
                    </Column>
                    </DataTable>
                </div>
                <h1 class="center" v-else>No Patients</h1>
            </div>
        </Layout>
</template>

<style scoped>

    @import '../../css/GeneralPage.css';

    .container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        height: 40%;
        justify-content: center
    }

    .left-panel {
        width: 40%;
        padding: 10px;
        border: 1px solid #ccc;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .patient_list h2{
        font-weight: bold;
        background: #efeeee;
        border-bottom: 1.5px solid #a02123;;
        width: 100%;
        font-size: 20px;
        justify-content: center;
        display: flex;
        padding: 10px;
    }

    .table-title {
        font-size: 20px;
        font-weight: bold;
    }

    .table-section {
        margin-top: 20px;
        padding: 0px 3%;
    }

    .modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50%;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1001;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .close-button {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 40px;
        background: #a02123;
        border-radius: 50%;
        border: none;
        font-size: 20px;
        cursor: pointer;
        padding: 5px;
        color: #ffffff;
    }

    .center {
        display: flex;
        justify-content: center;
    }
    /* calendar */

    :deep(.vuecal__cell-events-count) {
        width: 18px;
        height: 2px;
        color: transparent;
        background: #a02123;
        margin-top: 6px;
    }

    :deep(.vuecal__cell--has-events) {
        background-color: #f2e7e6;
    }

    :deep(.vuecal__flex.weekday-label) {
        color: white;
        background: #a02123;
    }

    :deep(.vuecal__title-bar) {
        background: #f5f5f5;
        font-weight: bold;
    }

    .patient-name a {
    text-decoration: none;
    color: inherit;
    }

    .patient-name a:hover {
        text-decoration: underline;
        color: #a02123;
    }

    :deep(.p-sortable-column.p-highlight) {
        color: #a02123;
    }

    :deep(.p-sortable-column.p-highlight .p-sortable-column-icon) {
        color: #a02123;
    }

</style>
