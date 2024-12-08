<script setup>
import Layout from '@/Pages/Layout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import { ref, computed, onMounted } from 'vue';
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import { Inertia } from '@inertiajs/inertia';
import { Head, usePage  } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { url, props } = usePage();
const userId = props.auth.user.user_id;
const userLastname = props.auth.user.lastname;
const userEmail = props.auth.user.email;
const roleId = props.auth.user.role_id;
const patientId = ref(null)
const selectedPatient = ref(0);
const cancertypeId = ref(null);
const diseaseProfile = ref({ });
const messages = ref(null);
const currentNotifLogId = ref(null);
const surveyResponse = ref(null);
const documentsForCompletion = ref([]);
const laboratories = ref([]);
const submittedLabs = ref([]);
const initialTreatmentDate = ref(null)

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

const fetchPatientTreatmentInfo = async () => {
    try {
        let response = await axios.get(`/api/patient/${patientId.value}/treatments`);
        selectedPatient.value = response.data;
        initialTreatmentDate.value = selectedPatient.value.treatments[0].treatment_initialRxDate
        console.log("Treatment history:" , selectedPatient.value)
        console.log("Initial treatment date:" , initialTreatmentDate.value)
    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

// const fetchMessages = async (userId) => {
//         try {
//             let response = await axios.get(`/api/notifications/receiver/${userId}`);
//             messages.value = response.data;

//             messages.value = messages.value.filter(
//                 notif => notif.notification_status?.notifStatus_name !== 'Action Taken'
//             );

//             console.log(messages.value)
//         } catch (error) {
//             console.error('Failed to fetch messages:', error);
//         }
// };

const fetchMessages = async (userId) => {
    try {
        let response = await axios.get(`/api/notifications/receiver/${userId}`);
        messages.value = response.data;

        // Filter out the notifications with status 'Action Taken'
        messages.value = messages.value.filter(
            notif => notif.notification_status?.notifStatus_name !== 'Action Taken'
        );

        // Sort the messages by notiflog_id in descending order (latest first)
        messages.value = messages.value.sort((a, b) => b.notifLog_id - a.notifLog_id);

        console.log(messages.value);
    } catch (error) {
        console.error('Failed to fetch messages:', error);
    }
};

const fetchSurveyResponse = async () => {
    try {
        const response = await axios.get(`/api/patients/${patientId.value}/survey-responses`);
        surveyResponse.value = response.data;

        if (surveyResponse.value && surveyResponse.value.surveyResponse_date) {
            const responseDate = new Date(surveyResponse.value.surveyResponse_date);
            const currentDate = new Date();
            const threeMonthsLater = new Date(responseDate);
            threeMonthsLater.setMonth(threeMonthsLater.getMonth() + 3);

            console.log(responseDate)
            console.log(currentDate)
            console.log(threeMonthsLater)

            if (currentDate >= threeMonthsLater) {
                documentsForCompletion.value.push({
                    date: threeMonthsLater.toISOString().split('T')[0],
                    document: 'Symptom Survey',
                    documentType: 'Report Symptoms Form',
                });
            }
        }
    } catch (error) {
        console.error('Failed to fetch survey responses:', error);
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

const latestOnboard = ref(null);

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

const automatedCheckupSchedNotif = async (patientId) => {
    const currentDate = new Date();
    const initialDate = new Date(initialTreatmentDate.value);

    const diffInMonths = (currentDate.getFullYear() - initialDate.getFullYear()) * 12
                         + (currentDate.getMonth() - initialDate.getMonth());

    const doctor_id = await getLatestOnboard(patientId);
    let doctor = await axios.get(`api/get-doctor/${doctor_id}`);

    const notifLogs = [];
    let nextNotifDate = new Date(initialDate);

    if (diffInMonths >= 24) {
        nextNotifDate.setMonth(nextNotifDate.getMonth() + 24);
    }

    if (diffInMonths < 24) {
        nextNotifDate.setMonth(nextNotifDate.getMonth() + 3);
    } else {
        nextNotifDate.setMonth(nextNotifDate.getMonth() + 6);
    }

    while (nextNotifDate <= currentDate && diffInMonths >= 0) {

        const notificationExists = await axios.post(`/api/check-notification`, {
            patientId: userId,
            date: nextNotifDate.toDateString(),
            type: 4
        });

        if (!notificationExists.data.exists) {
            notifLogs.push({
                from: doctor.data.user.email,
                to: userEmail,
                subject: 'Checkup Schedule',
                message: `Reminder for follow-up checkup scheduled on ${nextNotifDate.toDateString()}.`,
                senderId: doctor.data.user.user_id,
                receiverId: userId,
                notificationStatus: 1,
                notificationType: 4,
            });
        }

        if (diffInMonths < 24) {
            nextNotifDate.setMonth(nextNotifDate.getMonth() + 3);
        } else {
            nextNotifDate.setMonth(nextNotifDate.getMonth() + 6);
        }
    }

    try {
        console.log(notifLogs);
        if (notifLogs.length > 0) {
            await Promise.all(
                notifLogs.map(notif => axios.post('/api/create-notification-log', notif))
            );
            console.log("Notifications successfully created.");
        } else {
            console.log("No new notifications to create.");
        }
    } catch (error) {
        console.error("Error creating notifications:", error);
    }
};

onMounted(async () => {
    await fetchPatientId();
    await fetchPatientDiseaseProfile();
    await fetchPatientTreatmentInfo();
    await fetchMessages(userId);
    await fetchSurveyResponse();
    await fetchLaboratoriesByPrimarySite();
    await fetchPatientSubmittedLabs();
    await automatedCheckupSchedNotif(patientId.value);

    console.log("latest initial treatment date", initialTreatmentDate.value)

    filteredLaboratories.value.forEach((lab) => {
        documentsForCompletion.value.push({
            date: initialTreatmentDate,
            document: 'Laboratories',
            documentType: lab.workup.workup_name,
        });
        // const latestSubmissionDate = getLatestSubmissionDate(lab.workup.workup_id);

        // if (latestSubmissionDate) {
        //     const labSubmissionDate = new Date(latestSubmissionDate);
        //     const workupFrequency = lab.workup_frequency;

        //     if (workupFrequency) {
        //         const nextDueDate = new Date(labSubmissionDate);
        //         nextDueDate.setMonth(labSubmissionDate.getMonth() + workupFrequency);

        //         console.log('Latest Submission Date:', labSubmissionDate);
        //         console.log('Next Due Date:', nextDueDate);

        //         documentsForCompletion.value.push({
        //             date: nextDueDate.toISOString().split('T')[0],
        //             document: 'Laboratories',
        //             documentType: lab.workup.workup_name,
        //         });
        //     }
        // } else {
        //     documentsForCompletion.value.push({
        //         date: 'N/A',
        //         document: 'Laboratories',
        //         documentType: lab.workup.workup_name,
        //     });
        // }
    });
});

// const unreadMessageCount = computed(() => messages.value.filter(message => message.notification_status.notifStatus_name === 'Unread').length);
const unreadMessageCount = computed(() => {
    if(messages.value !== null) {
        const unreadMessages = messages.value.filter(
            message => message.notification_status?.notifStatus_name === 'Unread'
        );
        return unreadMessages.length;
    } else {
        return 0;
    }

});

// const pendingSubmissionCount = computed(() => patientSubmissions.value.filter(submission => submission.workup_status === 'Pending').length);
const showDialog = ref(false);
const selectedMessage = ref(null);

const openMessagePopup = async (message, userId) => {
    if (message.notification_status.notifStatus_name === 'Unread') {
        console.log(message)
        await axios.put(`api/notification/${userId}/update-status`, {
            notifLog_id: message.notifLog_id,
            status: 2
        });
        message.notification_status.notifStatus_name = 'Viewed';
    }

    currentNotifLogId.value = message.notifLog_id
    selectedMessage.value = message;
    showDialog.value = true;
};

const replyToMessage = () => {
    // Inertia.visit('/message');
    Inertia.visit(`/message?notifLogId=${currentNotifLogId.value}`);
};

const getMsgRowClass = (rowData) => {
    console.log(rowData)
    return rowData.notification_status.notifStatus_name === 'Unread' ? 'unread-message' : 'viewed-message';
};

const getSubmissionRowClass = (rowData) => {
    return rowData.workup_status === 'Pending' ? 'pending-document' : 'submitted-document';
};

const redirectToPage = (event) => {
    const document = event.data.document;
    if (document === "Symptom Survey") {
        Inertia.visit('/symptomsreport');
    } else if (document === "Laboratories") {
        Inertia.visit('/laboratoryresult');
    }
};


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
                    :value="messages"
                    responsiveLayout="scroll"
                    @row-click="(e) => openMessagePopup(e.data, userId)" rowHover
                    :rowClass="getMsgRowClass"
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
                    <p>You have <span style="font-weight: bold;">{{ pendingSubmissionCount }}</span> pending laboratories. Please select <span style="font-weight: bold;">REQUEST DOCUMENTS</span> on the left side of the screen to download lab requests.</p>
                    <p>You have <span style="font-weight: bold;">{{ unreadMessageCount }}</span> unread messages from your doctor.</p>
                </div>

                <div class="table-container">
                    <div class="title-div"><h2 class="table-title">DOCUMENTS FOR COMPLETION</h2></div>
                    <DataTable
                    :value="documentsForCompletion"
                    responsiveLayout="scroll"
                    :rowClass="getSubmissionRowClass"
                    rowHover
                    :paginator="true"
                    :rows="3"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[3, 5, 10, 25]"
                    @row-click="redirectToPage"
                    >
                        <Column field="date" header="Date"></Column>
                        <Column field="document" header="Due Document"></Column>
                        <Column field="documentType" header="Document Type"></Column>
                    </DataTable>
                </div>

            </div>

            <Dialog v-model:visible="showDialog" header="Message Details" :closable="true" :modal="true" width="400px">
                <div v-if="selectedMessage" class="popup">
                    <p><strong>Date:</strong> {{ selectedMessage.notification_date }}</p>
                    <p><strong>Sender:</strong> {{ selectedMessage.sender.firstname + ' ' + selectedMessage.sender.lastname  }}</p>
                    <p><strong>Subject:</strong> {{ selectedMessage.notification_subject }}</p>
                    <p><strong>Message:</strong> {{ selectedMessage.notification_notes }}</p>
                    <div class="flex items-center justify-center mt-4">
                        <PrimaryButton @click="replyToMessage">Reply</PrimaryButton>
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

:deep(.unread-message) {
    font-weight: bold;
}

:deep(.pending-document) {
    font-weight: bold;
}


</style>
