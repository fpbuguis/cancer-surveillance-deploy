<script setup>
import Layout from '@/Pages/Layout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import Listbox from 'primevue/listbox';
import { ref, computed, onMounted } from 'vue';
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import { Inertia } from '@inertiajs/inertia';
import { Head, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { url, props } = usePage();
const userId = props.auth.user.user_id;
const roleId = props.auth.user.role_id;
const msg_docurequest = ref(null);
const symptomsurveys_labsubmissions = ref(null);
const doctor = ref(null);

const fetchCurrentDoctor = async (userId) => {
        try {
            let response = await axios.get(`/api/doctor-profile/${userId}`);
            doctor.value = response.data;

            console.log("Doctor: ", doctor.value);
        } catch (error) {
            console.error(`Failed to fetch doctor with userId ${userId}:`, error);
        }
    };

const fetchMessages = async (userId) => {
        try {
            let response = await axios.get(`/api/notifications/receiver/${userId}`);
            //messages.value = response.data;

            msg_docurequest.value = response.data.filter(notif => notif.notification_type.notifType_id !== 2 && notif.notification_type.notifType_id !== 1 );
            symptomsurveys_labsubmissions.value = response.data.filter(notif => notif.notification_type.notifType_id == 1 || notif.notification_type.notifType_id == 2);

            //console.log("Messages: ", msg_docurequest.value)
            console.log("Symptom Surveys & Lab Submissions: ", symptomsurveys_labsubmissions.value)
            msg_docurequest.value = msg_docurequest.value.filter(
                notif => notif.notification_status?.notifStatus_name !== 'Action Taken'
            );

            symptomsurveys_labsubmissions.value = symptomsurveys_labsubmissions.value.filter(
                notif => notif.notification_status?.notifStatus_name !== 'Action Taken'
            );
        } catch (error) {
            console.error('Failed to fetch messages:', error);
        }
};

// const fetchPatientSubmissions = async (doctorId) => {
//         try {
//             let response = await axios.get(`/api/doctors/${doctorId}/labs-submitted`);
//             patientSubmissions.value = response.data;
//             console.log("Labs submitted:", patientSubmissions.value)
//         } catch (error) {
//             console.error('Failed to fetch patient submissions:', error);
//         }
//     };

// const combinedSubmissions = computed(() => {
//     // merge symptomsurveys and patientsubmissions
//     const merged = [
//         ...(symptomsurveys.value || []).map(survey => ({
//             type: 'Symptom Survey',
//             date: survey.notification_date,
//             patient: `${survey.sender.firstname} ${survey.sender.lastname}`,
//             details: survey.notification_subject,
//         })),
//         ...(patientSubmissions.value || []).map(submission => ({
//             type: 'Patient Submission',
//             date: submission.labSubmission_date,
//             patient: `${submission.patient.user.firstname} ${submission.patient.user.lastname}`,
//             details: submission.workup.workup_name,
//         }))
//     ];
//     return merged.sort((a, b) => new Date(b.date) - new Date(a.date));
// });

// const totalWorkupCount = computed(() =>
//     patientSubmissions.value.filter(submission => submission.workup_status === 'Submitted').length
// );
// const pendingWorkupCount = computed(() =>
//     patientSubmissions.value.length
// );

const showDialog = ref(false);
const selectedMessage = ref(null);
const currentNotifLogId = ref(null);

const openMessagePopup = async (message, userId) => {
    if (message.notification_status.notifStatus_name === 'Unread') {
        console.log(message)
        const response = await axios.put(`api/notification/${userId}/update-status`, {
            notifLog_id: message.notifLog_id,
            status: 2
        });
        //console.log(response)
        // currentNotifLogId.value = response.data[0].notifLog_id
        message.notification_status.notifStatus_name = 'Viewed';
    }

    currentNotifLogId.value = message.notifLog_id
    selectedMessage.value = message;

    if (selectedMessage.value.labSubmitted_id != null) {
        parseJson(selectedMessage.value.labs_submitted.labFileSubmissions);
    }

    showDialog.value = true;
};

const replyToMessage = () => {
    // Inertia.visit('/message');
    Inertia.visit(`/message?notifLogId=${currentNotifLogId.value}`);
};

const markAsDone = async () => {
    const response = await axios.put(`api/notification/${userId}/update-status`, {
        notifLog_id: currentNotifLogId.value,
        status: 3
    });

    console.log(response.data)
    Inertia.visit(`/doctornotification`);
};

const getMsgRowClass = (rowData) => {
    //console.log(rowData)
    return rowData.notification_status.notifStatus_name === "Unread" ? 'unread-message' : 'viewed-message';
};

const getWorkupRowClass = (rowData) => {
    return rowData.workup_status === 'Pending' ? 'pending-workup' : '';
};

const totalPatientSubmissions = computed(() => {
    return symptomsurveys_labsubmissions.value ? symptomsurveys_labsubmissions.value.length : 0;
});

const unreadMessageCount = computed(() => {
    if(msg_docurequest.value !== null) {
        const unreadMessages = msg_docurequest.value.filter(
            notif => notif.notification_status?.notifStatus_name === 'Unread'
        );
        return unreadMessages.length;
    } else {
        return 0;
    }

});

const jsonString = ref("");
const pdfUrl = ref(null);
const baseUrl = 'http://127.0.0.1:8000/storage/';

const parseJson = (jsonString) => {
    const filePath = JSON.parse(jsonString)[0];
    pdfUrl.value = `${baseUrl}${filePath}`;
    console.log(pdfUrl.value)
};

const openPdf = () => {
    if (pdfUrl.value) {
    window.open(pdfUrl.value, '_blank');
    }
};

const downloadPdf = (selectedMessage) => {
    console.log(selectedMessage)
    const link = document.createElement('a');
    link.href = pdfUrl.value;
    link.download = `${selectedMessage.sender.lastname}_${selectedMessage.labs_submitted.workup.workup_name}`;
    link.click();
};

onMounted(async () => {
    await fetchCurrentDoctor(userId);
    await fetchMessages(userId);
    console.log(selectedMessage)
    parseJson();
    //await fetchPatientSubmissions(doctor.value.doctor_id);
 });

</script>

<template>
    <Head title="Notification" />
        <Layout>

            <div class="page-heading">
                <h1 class="main-heading"> ALERTS </h1>
            </div>

            <div class = "table-section">

                <div class="table-container">
                    <div class="title-div"><h2 class="table-title">MESSAGES</h2></div>
                    <DataTable
                    :value="msg_docurequest"
                    responsiveLayout="scroll"
                    @row-click="(e) => openMessagePopup(e.data, userId)" rowHover :rowClass="getMsgRowClass"
                    :paginator="true"
                    :rows="3"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[3, 5, 10, 25]"
                >
                        <Column field="date" header="Date">
                            <template #body="slotProps">
                                {{ slotProps.data.notification_date }}
                            </template>
                        </Column>
                        <Column field="sender" header="Sender">
                            <template #body="slotProps">
                                {{ slotProps.data.sender.firstname + ' ' + slotProps.data.sender.lastname }}
                            </template>
                        </Column>
                        <Column field="subject" header="Subject">
                            <template #body="slotProps">
                                {{ slotProps.data.notification_subject }}
                            </template>
                        </Column>
                    </DataTable>
                </div>

                <div class="system-message">
                    <strong>SYSTEM MESSAGE:</strong>
                    <p>Number of unread messages: <span style="font-weight: bold;">{{ unreadMessageCount }}</span></p>
                    <p>Number of patient submissions: <span style="font-weight: bold;">{{ totalPatientSubmissions  }}</span></p>
                    <!-- <p>Number of patients pending for work-up submission: <span style="font-weight: bold;">{{ pendingWorkupCount }}</span></p> -->

                </div>

                <div class="table-container">
                    <div class="title-div"><h2 class="table-title">PATIENT SUBMISSION</h2></div>
                    <DataTable
                        :value="symptomsurveys_labsubmissions"
                        responsiveLayout="scroll"
                        @row-click="(e) => openMessagePopup(e.data, userId)" rowHover :rowClass="getMsgRowClass"
                        :paginator="true"
                        :rows="3"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[3, 5, 10, 25]"
                    >
                        <Column field="date" header="Date">
                                <template #body="slotProps">
                                    {{ slotProps.data.notification_date }}
                                </template>
                        </Column>
                        <Column field="patient" header="Patient">
                                <template #body="slotProps">
                                    {{ slotProps.data.sender.firstname + ' ' + slotProps.data.sender.lastname }}
                                </template>
                        </Column>
                        <Column field="documentType" header="Document Type">
                                <template #body="slotProps">
                                    {{ slotProps.data.labs_submitted?.workup?.workup_name || slotProps.data.notification_subject }}
                                </template>
                        </Column>
                    </DataTable>
                </div>

            </div>

            <Dialog v-model:visible="showDialog" header="Details" :closable="true" :modal="true" width="400px">
                <div v-if="selectedMessage" class="popup">
                    <!-- <div v-if="(selectedMessage.surveyResponse_id == null && selectedMessage.labSubmitted_id == null)"> -->
                    <p><strong>Date:</strong> {{ selectedMessage.notification_date }}</p>
                    <p><strong>Sender:</strong> {{ selectedMessage.sender.firstname + ' ' + selectedMessage.sender.lastname  }}</p>
                    <p><strong>Subject:</strong> {{ selectedMessage.notification_subject }}</p>
                    <p><strong>Message:</strong> {{ selectedMessage.notification_notes }}</p>



                    <div v-if="selectedMessage.surveyResponse_id != null">
                        <br><hr><br>
                        <p><strong>Completed Treatment/s:</strong> {{ selectedMessage.surveyResponse.treatment_completion}}</p>
                        <p><strong>Local Symptom:</strong> {{ selectedMessage.surveyResponse.symptom_surveys[0].symptom_name }}</p>
                        <p><strong>Systemic Symptom:</strong> {{ selectedMessage.surveyResponse.symptom_surveys[1].symptom_name  }}</p>
                        <p><strong>Quality of Life:</strong> {{ selectedMessage.surveyResponse.symptom_surveys[2].symptom_name }}</p>
                        <p><strong>Treatment Side Effects:</strong> {{ selectedMessage.surveyResponse.symptom_surveys[3].symptom_name }}</p>
                        <p><strong>Additional notes:</strong> {{ selectedMessage.surveyResponse.response_notes }}</p>

                    </div>

                    <div v-if="selectedMessage.labSubmitted_id != null">
                        <br><hr><br>
                        <div>
                            <iframe v-if="pdfUrl" :src="pdfUrl" width="100%" height="600"></iframe>

                            <div style="display: flex; justify-content: center; align-items: center;">
                                <button @click="openPdf" class="btn">Open PDF in New Tab</button>
                                <button @click="downloadPdf(selectedMessage)" class="btn">Download PDF</button>
                            </div>
                        </div>
                    </div>


                    <!-- <div class="symptoms">
                        <div v-if="selectedMessage.surveyResponse_id != null" class="details-grid">
                            <br><hr></hr><br>
                            <div class="label">Local Symptom:</div>
                            <div class="value">{{ selectedMessage.surveyResponse.symptom_surveys[0].symptom_name }}</div>

                            <div class="label">Systemic Symptom:</div>
                            <div class="value">{{ selectedMessage.surveyResponse.symptom_surveys[1].symptom_name }}</div>

                            <div class="label">Quality of Life:</div>
                            <div class="value">{{ selectedMessage.surveyResponse.symptom_surveys[2].symptom_name }}</div>

                            <div class="label">Treatment Side Effects:</div>
                            <div class="value">{{ selectedMessage.surveyResponse.symptom_surveys[3].symptom_name }}</div>

                            <div class="label">Additional Notes:</div>
                            <div class="value">{{ additionalNotes }}</div>

                            <div class="label">Completed Treatment/s:</div>
                            <div class="value">{{ completedTreatments }}</div>
                            </div>
                    </div> -->

                       <!-- <OrderList>
                            <li class="p-list-item">Local Symptom: {{ selectedMessage.surveyResponse.symptom_surveys[0].symptom_name }}</li>
                            <li class="p-list-item">Systemic Symptom: {{ selectedMessage.surveyResponse.symptom_surveys[1].symptom_name }}</li>
                            <li class="p-list-item">Quality of Life: {{ selectedMessage.surveyResponse.symptom_surveys[2].symptom_name }}</li>
                            <li class="p-list-item">Treatment Side Effects: {{ selectedMessage.surveyResponse.symptom_surveys[3].symptom_name }}</li>
                            <li class="p-list-item">Additional Notes: {{ additionalNotes }}</li>
                            <li class="p-list-item">Completed Treatment/s: {{ completedTreatments }}</li>
                        </OrderList> -->

                    <div class="flex items-center justify-center mt-4">
                        <PrimaryButton v-if="selectedMessage.notification_type.notifType_id === 4" @click="replyToMessage">Reply</PrimaryButton>
                        <PrimaryButton v-else  @click="markAsDone">Done</PrimaryButton>
                    </div>
                </div>
            </Dialog>

        </Layout>


</template>

<style scoped>

@import '../../css/GeneralPage.css';

.table-section {
    margin-top: 20px;
    padding: 0px 3%;
}

.table-container {
    margin: 20px 0;
}

.system-message {
    background-color: #FCB2B2;
    color: #FF2933;
    padding: 10px;
    border-radius: 5px;
    margin: 20px 0;
}

.system-message p {
    color: black;
    /* font-weight: bold; */
    font-size: 15px;
}

.table-title {
    font-size: 12px;
    font-weight: bold;
    color: #858585;
    margin-bottom: 10px;
}

.title-div {
    width: 100%;
    display: flex;
    justify-content: center;
}

.popup {
    width: 400px;
    max-height: 500px;
    overflow-y: auto;
}

.msg-row {
    background-color: red;
}

:deep(.unread-message) {
    font-weight: bold;
}

/* :deep(.pending-workup) {
    font-weight: bold;
} */

.symptoms {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.details-grid {
    display: grid;
    grid-template-columns: 200px auto;
    gap: 10px;
    align-items: center;
    padding: 10px 20px;
}

.label {
    padding-right: 15px;
    font-weight: bold;
}

.value {
    text-align: left;
}

.btn {
  margin: 5px;
  padding: 0.5rem 1.5rem;
  font-size: 14px;
  color: #fff;
  background-color: black;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

</style>
