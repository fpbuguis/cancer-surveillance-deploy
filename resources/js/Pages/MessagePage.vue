<script setup>
import { Head } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Layout from '@/Pages/Layout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';
import Alert from '@/Components/Alert.vue';
import { watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const form = ref({
    to: '',
    subject: '',
    message: '',
});

const { url,props } = usePage();
const userEmail = props.auth.user.email;
let userId = props.auth.user.user_id;
const roleId = props.auth.user.role_id;

const params = new URLSearchParams(window.location.search);
const notifLogId = params.get('notifLogId');
const notifLog = ref(null);

const user = ref(null);
const alert = ref({
    show: false,
    message: '',
    type: '',
});

const parts = url.split('/');
let patientId = parts[parts.length - 1];
patientId = parseInt(patientId, 10);

const recipients = ref([]);
let customEmail = ref('');
let customSubject = ref('');

const subjectOptions = ref([
    "Cancer Diagnosis",
    "Treatment Follow-up",
    "Checkup Schedule",
    "Patient Outcome",
    "Data Collection"
]);

const fetchCurrentDoctor = async (userId) => {
    try {
        let response = await axios.get(`/api/doctor-profile/${userId}`);
        user.value = response.data;
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${userId}:`, error);
    }
};

const fetchPatients = async (doctorId) => {
    try {
        let response = await axios.get(`/api/doctors/${doctorId}/patients`);
        recipients.value = response.data;
    } catch (error) {
        console.error('Failed to fetch patients:', error);
    }
};

const fetchDoctors = async (patientId) => {
    try {
        let response = await axios.get(`/api/all-doctors`);
        recipients.value = response.data;
    } catch (error) {
        console.error('Failed to fetch patients:', error);
    }
};

const fetchUser = async (userId) => {
    try {
        let response = await axios.get(`/api/get-user/${userId}`);
        user.value = response.data;
    } catch (error) {
        console.error(`Failed to fetch user with userId ${userId}:`, error);
    }
};

watch(() => form.value.to, () => {
      customEmail.value = '';
    });

// watch(() => form.value.subject, () => {
//     customSubject.value = '';
// });

function formatWord(word) {
    if (!word) return '';
    return word
        .split(' ')
        .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
        .join(' ');
}

let currentId = props.auth.user.user_id;
const currentUser = ref(null);
let draftId = ref(0);

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

const selectedRecipientId = ref(null);
const receiverEmail = ref('');

const updateReceiverEmail = () => {
    const selectedRecipient = recipients.value.find(
    recipient => recipient.user.user_id === form.value.to
    );
    receiverEmail.value = selectedRecipient?.user?.email || '';
};

const fetchNotifInfo = async () => {
    try {
        let response = await axios.get(`/api/notifications/${notifLogId}`);
        notifLog.value = response.data

        console.log(notifLog.value)
        form.value.to = notifLog.value.notification_sender;
        // form.value.subject = notifLog.value.notification_subject;

        console.log(notifLog.value.notification_subject)
        if (subjectOptions.value.includes(notifLog.value.notification_subject)) {
            form.value.subject = notifLog.value.notification_subject;
        } else {
            form.value.subject = "Others";
            customSubject.value = notifLog.value.notification_subject;
            console.log(notifLog.value.notification_subject)
        }
    } catch (error) {
        console.error(`Failed to fetch doctor with userId ${currentId}:`, error);
    }
};

const sendEmail = async () => {
    alert.value.show = false;
    try {
        if(notifLog.value) {
            receiverEmail.value = notifLog.value.sender.email;
        }

        if (roleId === 2) {
            await axios.post('/api/send-email', {
                doctor: 'Dr. ' + formatWord(user?.value.user_details?.firstname) + ' ' + formatWord(user?.value.user_details?.lastname),
                hospital: user?.value.hospital_details?.hospital_name,
                from: userEmail,
                to: form.value.to === 'others' ? customEmail.value : receiverEmail.value ,
                subject: form.value.subject === 'Others' ? customSubject.value : form.value.subject,
                message: form.value.message,
                senderId: currentId,
                receiverId: form.value.to,
                notificationStatus: 1,
                notificationType: 4
            });

            if(notifLog.value) {
                const response = await axios.put(`api/notification/${userId}/update-status`, {
                    notifLog_id: notifLogId,
                    status: 3
                });
            }

        } else {
            await axios.post('/api/send-email', {
                patient: formatWord(user?.value.firstname) + ' ' + formatWord(user?.value.lastname),
                from: userEmail,
                to: form.value.to === 'others' ? customEmail.value : receiverEmail.value,
                subject: form.value.subject === 'Others' ? customSubject.value : form.value.subject,
                message: form.value.message,
                senderId: currentId,
                receiverId: form.value.to,
                notificationStatus: 1,
                notificationType: 4
            });

            if(notifLog.value) {
                const response = await axios.put(`api/notification/${userId}/update-status`, {
                    notifLog_id: notifLogId,
                    status: 3
                });
            }
        }

        alert.value = {
            show: true,
            message: 'Success! Your email was sent successfully.',
            type: 'success',
        };

        Inertia.visit(`/message`);

    } catch (error) {
        console.error('Failed to send email:', error);
        alert.value = {
            show: true,
            message: 'Failed to send email. Please ensure you are logged in.',
            type: 'error',
        };
    }
    form.value.to = '';
    form.value.subject = '';
    form.value.message = '';
};

onMounted(async () => {
    if (roleId === 2) {
        await fetchCurrentDoctor(userId);
        await fetchPatients(user?.value.doctor_id);
    } else {
        await fetchUser(userId);
        await fetchDoctors();
    }

    if (notifLogId) {
        await fetchNotifInfo(notifLogId)
    }

    console.log(customSubject.value)
    // console.log(props)
    console.log(notifLogId)
});

</script>

<template>
    <Head title="Message" />

    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">MESSAGE</h1>
        </div>

        <form @submit.prevent="sendEmail" class="form-container">
            <div class="form-group">
                <InputLabel for="to" value="To:" />
                    <select
                        v-model="form.to"
                        @change="updateReceiverEmail"
                        class="input-field"
                        required
                        :disabled="notifLog"
                    >
                    <option disabled value="">Select an email</option>
                    <option v-for="recipient in recipients" :key="recipient.id" :value="recipient.user.user_id">
                    {{ formatWord(recipient?.user?.firstname) + ' ' + formatWord(recipient?.user?.lastname) }}
                </option>
                <option value="others">Others</option>
                </select>

                <input
                v-if="form.to === 'others'"
                type="email"
                v-model="customEmail"
                placeholder="Please Specify"
                class="form-group"
                required
                :disabled="notifLog"
                />
            </div>

            <div class="form-group">
                <InputLabel for="subject" value="Subject:" />
                <select v-model="form.subject" class="input-field" required :disabled="notifLog">
                    <option disabled value="">Select a subject</option>
                    <option v-for="option in subjectOptions" :key="option" :value="option">
                        {{ option }}
                    </option>
                    <option value="Others">Others</option>
                </select>

                <div v-if="form.subject === 'Others'" class="form-group">
                    <input
                        type="text"
                        v-model="customSubject"
                        class="input-field"
                        placeholder="Please specify"
                        required
                        :disabled="notifLog"
                    />
                </div>
            </div>

            <div class="form-group">
                <InputLabel for="message" value="Message:" />
                <textarea
                    v-model="form.message"
                    class="message-textarea"
                    required
                ></textarea>
            </div>
            <div class="flex items-center justify-center">
                <PrimaryButton class="ms-4">
                    Send
                </PrimaryButton>
            </div>
        </form>

        <Alert v-if="alert.show" :message="alert.message" :type="alert.type" />
    </Layout>
</template>

<style scoped>
@import '../../css/GeneralPage.css';

.input-field {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-top: 8px;
}

.message-textarea {
    width: 100%;
    height: 300px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-top: 8px;
}
</style>
