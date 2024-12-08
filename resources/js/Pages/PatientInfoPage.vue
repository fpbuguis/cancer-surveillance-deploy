<script setup>
import { computed, onMounted, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    patient: {
        type: Object,
        required: true
    }
});

const selectedPatient = ref(0);

const patientId = props.patient?.patient_id

const fetchPatientInfo = async (patientId) => {
    try {
        console.log(patientId)
        let response = await axios.get(`/api/get-patient/${patientId}`);
        selectedPatient.value = response.data;
    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

onMounted(async () => {
    await fetchPatientInfo(patientId);
    console.log(props.patient?.patient_id)
    console.log(selectedPatient)
});

//capitalize first letter
const CF = (string) => {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};

const formattedContactNumber = computed(() => {
  const number = selectedPatient?.value.user?.contact_number;
  return number ? (number.startsWith('0') ? number : '0' + number) : '';
});

const formattedBirthday = computed(() => {
  const dateString = selectedPatient?.value.user?.birthday;
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
});

const EditInfo = () => {
    const data = {
        userId: props.patient?.user_id
    };
    Inertia.visit('/patient-details-update', { data }); 
}

</script>

<template>
    <div class="form-container">
        <div class="details-grid">
            <div class="label">Last Name:</div>
            <div class="value">{{ CF(selectedPatient?.user?.lastname ?? '') }}</div>

            <div class="label">First Name:</div>
            <div class="value">{{ CF(selectedPatient?.user?.firstname ?? '')}}</div>

            <div class="label">Middle Name:</div>
            <div class="value">{{ CF(selectedPatient?.user?.middlename ?? '') }}</div>

            <div class="label">Email:</div>
            <div class="value">{{ selectedPatient?.user?.email ?? ''}}</div>

            <div class="label">Contact Number:</div>
            <div class="value">{{ formattedContactNumber ?? ''}}</div>

            <div class="label">Birthday:</div>
            <div class="value">{{ formattedBirthday ?? '' }}</div>

            <div class="label">Birthplace:</div>
            <div class="value">{{ CF(selectedPatient?.user?.birthplace ?? '') }}</div>

            <div class="label">Gender:</div>
            <div class="value">{{ CF(selectedPatient?.user?.gender ?? '') }}</div>

            <div class="label">Marital Status:</div>
            <div class="value">{{ CF(selectedPatient?.user?.marital_status ?? '')}}</div>

            <div class="label">Region:</div>
            <div class="value">{{ selectedPatient?.user?.address?.region?.region_name ?? ''}}</div>

            <div class="label">Province:</div>
            <div class="value">{{ selectedPatient?.user?.address?.province?.province_name ?? ''}}</div>

            <div class="label">City:</div>
            <div class="value">{{ selectedPatient?.user?.address?.city?.city_name ?? ''}}</div>

            <div class="label">Municipality:</div>
            <div class="value">{{ selectedPatient?.user?.address?.municipality?.municipality_name ?? ''}}</div>

            <div class="label">Barangay:</div>
            <div class="value">{{ selectedPatient?.user?.address?.address_brgy ?? ''}}</div>

            <div class="label">Block/House Number:</div>
            <div class="value">{{ selectedPatient?.user?.address?.address_number ?? ''}}</div>

            <div class="label">Street:</div>
            <div class="value">{{ selectedPatient?.user?.address?.address_street ?? ''}}</div>

            <div class="label">Zipcode:</div>
            <div class="value">{{ selectedPatient?.user?.address?.address_zipcode ?? '' }}</div>
        </div>


        <div class="flex items-center justify-center mt-4">
                <button
                    :type="type"
                    class="inline-flex items-center px-4 py-2 bg-[#b04748] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#d3a5a3] focus:bg-[#d3a5a3] active:bg-[#9f2123] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    @click="EditInfo"
                >
                    Edit
                </button>
            </div>
    </div>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

.details-grid {
    display: grid;
    grid-template-columns: 190px auto;
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

</style>
