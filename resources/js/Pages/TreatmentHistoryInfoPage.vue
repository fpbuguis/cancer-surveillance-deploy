<script setup>
import { computed, onMounted, ref, watchEffect } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    patient: {
        type: Object,
        required: true
    }
});

const selectedPatient = ref(0);
const patientId = props.patient?.patient_id
const currentPageIndex = ref(0);
const availablePages = ref([]);
const otherId = ref(null);

const fetchPatientTreatmentInfo = async (patientId) => {
    try {
        // console.log(patientId)
        let response = await axios.get(`/api/patient/${patientId}/treatments`);
        selectedPatient.value = response.data;
    } catch (error) {
        console.error('Failed to fetch patient:', error);
    }
};

onMounted(async () => {
    await fetchPatientTreatmentInfo(patientId);
    setAvailablePages();

    // console.log(props.patient?.patient_id)
    // console.log(selectedPatient)
});

//capitalize first letter
const CF = (string) => {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};

const formattedDate = (dateString) => {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const EditInfo = () => {
    // const data = {
    //     userId: props.patient?.user_id
    // };

    if (currentPage.value === 1) {
        Inertia.visit(`/treatment-history-update/${otherId.value}/${patientId}`);
    } else if (currentPage.value === 2) {
        Inertia.visit(`/treatment-history-update-surgery/${otherId.value}/${patientId}`);
    } else if (currentPage.value === 3) {
        Inertia.visit(`/treatment-history-update-radiotherapy/${otherId.value}/${patientId}`);
    } else if (currentPage.value === 4) {
        Inertia.visit(`/treatment-history-update-chemotherapy/${otherId.value}/${patientId}`);
    } else if (currentPage.value === 5) {
        Inertia.visit(`/treatment-history-update-immunotherapy/${otherId.value}/${patientId}`);
    } else if (currentPage.value === 6) {
        Inertia.visit(`/treatment-history-update-hormonal/${otherId.value}/${patientId}`);
    }
    // Inertia.visit('/patient-enrollment', { data });
}

let treatments;
const selectedTreatments = (index) => {
    const rxtypes = selectedPatient.value?.rxtypes[index];
    if (!rxtypes) return "";

    const treatmentMapping = {
        rxtype_chemotherapy: "Chemotherapy",
        rxtype_hormonaltherapy: "Hormonal Therapy",
        rxtype_immunotherapy: "Immunotherapy",
        rxtype_radiotherapy: "Radiotherapy",
        rxtype_surgery: "Surgery",
        rxtype_others: "Others",
    };

    treatments = Object.entries(treatmentMapping)
        .filter(([key, _]) => rxtypes[key] === 1)
        .map(([_, label]) => label);

    return treatments.join(", ");
};

// Pagination navigation

const currentPage = computed(() => availablePages.value[currentPageIndex.value]);

const setAvailablePages = () => {
    availablePages.value = [];

    if (selectedPatient.value) {
        availablePages.value.push(1);
        if (selectedPatient.value.surgeries && selectedPatient.value.surgeries.length > 0) {
            availablePages.value.push(2);
        }
        if (selectedPatient.value.radiotherapies && selectedPatient.value.radiotherapies.length > 0) {
            availablePages.value.push(3);
        }
        if (selectedPatient.value.chemotherapies && selectedPatient.value.chemotherapies.length > 0) {
            availablePages.value.push(4);
        }
        if (selectedPatient.value.immunotherapies && selectedPatient.value.immunotherapies.length > 0) {
            availablePages.value.push(5);
        }
        if (selectedPatient.value.hormonals && selectedPatient.value.hormonals.length > 0) {
            availablePages.value.push(6);
        }
    }
};

const goToNextPage = () => {
    if (currentPageIndex.value < availablePages.value.length - 1) {
        currentPageIndex.value++;
    }
};

const goToPreviousPage = () => {
    if (currentPageIndex.value > 0) {
        currentPageIndex.value--;
    }
};

watchEffect(() => {
    console.log( selectedPatient)
    // console.log( availablePages)
});

</script>

<template>
    <div class="form-container">
        <div v-if="currentPage === 1">
            <p>PRIMARY</p>
            <div class="details-grid">
                <div class="label">Last Name:</div>
                <div class="value">{{ selectedPatient?.user?.lastname }}</div>

                <div class="label">Email Address:</div>
                <div class="value">{{ selectedPatient?.user?.email }}</div>

            </div>
            <div class="details-grid" v-for="(treatment, index) in selectedPatient?.treatments" :key="treatment.treatment_id">
                <div class="label">Treatment Purposes:</div>
                <div class="value">{{ treatment.treatment_purpose }}</div>

                <div class="label" v-if="treatment.treatment_purpose === 'Others'">Other Treatment Purpose:</div>
                <div class="value" v-if="treatment.treatment_purpose === 'Others'">
                    {{ treatment.treatment_other_purpose }}
                </div>

                <div class="label">Primary Type:</div>
                <div class="value">{{ treatment.treatment_primaryRxType }}</div>

                <div class="label">Primary Treatment:</div>
                <div class="value">{{ treatment.treatment_primaryRxName }}</div>

                <div class="label">Initial Treatment Date:</div>
                <div class="value">{{ formattedDate(treatment.treatment_initialRxDate) }}</div>

                <div class="label">Planned Additional Treatments:</div>
                <div class="value">{{ selectedTreatments(index) }}</div>

                <div class="label" v-if="treatments.includes('Others')">Other Planned Additional Treatment:</div>
                <div class="value" v-if="treatments.includes('Others')">
                    {{ selectedPatient.rxtypes[index].rxtype_other_treatment }}
                </div>

                <div></div>
                <div class="button-container">
                    <button
                        class="button-custom1"
                        @click="() => { otherId = index; EditInfo(); }"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z" fill="#ffffff"/>
                    </svg>
                    </button>
                </div>

                <hr>
            </div>
        </div>

        <div v-if="currentPage === 2 && selectedPatient?.surgeries">
            <p>SURGERY</p>
            <div class="details-grid" v-for="(surgery,index) in selectedPatient?.surgeries" :key="surgery.surgery_id">
                <div class="label">Surgery Operation:</div>
                <div class="value">{{ surgery.surgery_operation }}</div>

                <div class="label">Surgery Date:</div>
                <div class="value">{{ formattedDate(surgery.surgery_date) }}</div>

                <div class="label">Surgery Findings:</div>
                <div class="value">{{ surgery.surgery_findings }}</div>

                <div class="label">Surgery Intent:</div>
                <div class="value">{{ surgery.surgery_intent }}</div>

                <div class="label" v-if="surgery.surgery_intent === 'Others'">Other Surgery Intent:</div>
                <div class="value" v-if="surgery.surgery_intent === 'Others'">{{ surgery.surgery_otherIntent }}</div>

                <div class="label">Surgery Doctor:</div>
                <div class="value">Dr. {{ CF(surgery.doctor.user.firstname) }} {{ CF(surgery.doctor.user.lastname) }}</div>

                <div class="label">Surgery Hospital:</div>
                <div class="value">{{ surgery.hospital.hospital_name }}</div>

                <div></div>
                <div class="button-container">
                    <button
                        class="button-custom1"
                        @click="() => { otherId = index; EditInfo(); }"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z" fill="#ffffff"/>
                    </svg>
                    </button>
                </div>

                <hr>
            </div>
        </div>

        <div v-if="currentPage === 3 && selectedPatient?.radiotherapies">
            <p>RADIOTHERAPY</p>
            <div class="details-grid" v-for="(radRx,index) in selectedPatient?.radiotherapies" :key="radRx.radRx_id">
                <div class="label">Radiotherapy Type:</div>
                <div class="value">{{ radRx.type.radDetails_name }}</div>

                <div class="label">Initial Radiotherapy Date:</div>
                <div class="value">{{ formattedDate(radRx.radRx_initial_date) }}</div>

                <div class="label">Last Radiotherapy Date:</div>
                <div class="value">{{ formattedDate(radRx.radRx_last_date) }}</div>

                <div class="label">Radiotherapy Dose:</div>
                <div class="value">{{ radRx.radRx_dose }}</div>

                <div class="label">Radiotherapy Body Site:</div>
                <div class="value">{{ radRx.radRx_bodySite}}</div>

                <div class="label">Radiotherapy Status:</div>
                <div class="value">{{ radRx.radRx_status }}</div>

                <div class="label">Radiotheraphy Completed:</div>
                <div class="value">{{ radRx.radRx_isCompleted === 1 ? 'Yes' : 'No' }}</div>

                <div class="label">Radiotherapy Facility:</div>
                <div class="value">{{ radRx.hospital.hospital_name }}</div>

                <div class="label">Radiotherapy Doctor:</div>
                <div class="value">Dr. {{ CF(radRx.doctor.user.firstname) }} {{ CF(radRx.doctor.user.lastname) }}</div>

                <div></div>
                <div class="button-container">
                    <button
                        class="button-custom1"
                        @click="() => { otherId = index; EditInfo(); }"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z" fill="#ffffff"/>
                    </svg>
                    </button>
                </div>

                <hr>
            </div>

        </div>

        <div v-if="currentPage === 4 && selectedPatient?.chemotherapies">
            <p>CHEMOTHERAPY</p>
            <div class="details-grid" v-for="(chemo,index) in selectedPatient?.chemotherapies" :key="chemo.chemo_id">
                <div class="label">Chemotherapy Type:</div>
                <div class="value">{{ chemo.chemo_type }}</div>

                <div class="label">Chemotherapy Given:</div>
                <div class="value">{{ chemo.protocol.chemo_drugs }}</div>

                <div class="label">Initial Chemotherapy Date:</div>
                <div class="value">{{ formattedDate(chemo.chemo_initial_date) }}</div>

                <div class="label">Last Chemotherapy Date:</div>
                <div class="value">{{ formattedDate(chemo.chemo_end_date) }}</div>

                <div class="label">Chemotherapy No. of Cycles:</div>
                <div class="value">{{ chemo.chemo_cycleNumGiven }}</div>

                <div class="label">Chemotherapy Status:</div>
                <div class="value">{{ chemo.chemo_status }}</div>

                <div class="label">Chemotherapy Notes:</div>
                <div class="value">{{ chemo.chemo_notes }}</div>

                <div class="label">Chemotheraphy Completed:</div>
                <div class="value">{{ chemo.chemo_isCompleted === 1 ? 'Yes' : 'No'  }}</div>

                <div class="label">Chemotherapy Facility:</div>
                <div class="value">{{ chemo.hospital.hospital_name }}</div>

                <div class="label">Chemotherapy Doctor:</div>
                <div class="value">Dr. {{ CF(chemo.doctor.user.firstname) }} {{ CF(chemo.doctor.user.lastname) }}</div>

                <div></div>
                <div class="button-container">
                    <button
                        class="button-custom1"
                        @click="() => { otherId = index; EditInfo(); }"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z" fill="#ffffff"/>
                    </svg>
                    </button>
                </div>

                <hr>
            </div>
        </div>

        <div v-if="currentPage === 5 && selectedPatient?.immunotherapies">
            <p>IMMUNOTHERAPY</p>
            <div class="details-grid" v-for="(immunoRx,index) in selectedPatient?.immunotherapies" :key="immunoRx.immunoRx_id">
                <div class="label">Immunotherapy Drug:</div>
                <div class="value">{{ immunoRx.immunoRx_drugs }}</div>

                <div class="label">Initial Immunotherapy Date:</div>
                <div class="value">{{ formattedDate(immunoRx.immunoRx_initial_date) }}</div>

                <div class="label">Last Immunotherapy Date:</div>
                <div class="value">{{ formattedDate(immunoRx.immunoRx_end_date) }}</div>

                <div class="label">Immunotherapy Status:</div>
                <div class="value">{{ immunoRx.immunoRx_status }}</div>

                <div class="label">Immunotherapy Notes:</div>
                <div class="value">{{ immunoRx.immunoRx_notes || "-:-" }}</div>

                <div class="label">Immunotherapy Completed:</div>
                <div class="value">{{ immunoRx.immunoRx_isCompleted === 1 ? 'Yes' : 'No' }}</div>

                <div class="label">Immunotherapy Facility:</div>
                <div class="value">{{ immunoRx.hospital.hospital_name }}</div>

                <div class="label">Immunotherapy Doctor:</div>
                <div class="value">Dr. {{ CF(immunoRx.doctor.user.firstname) }} {{ CF(immunoRx.doctor.user.lastname) }}</div>

                <div></div>
                <div class="button-container">
                    <button
                        class="button-custom1"
                        @click="() => { otherId = index; EditInfo(); }"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z" fill="#ffffff"/>
                    </svg>
                    </button>
                </div>

                <hr>
            </div>
        </div>

        <div v-if="currentPage === 6 && selectedPatient?.hormonals">
            <p>HORMONAL</p>
            <div class="details-grid" v-for="(hormonal,index) in selectedPatient?.hormonals" :key="hormonal.hormonal_id">
                <div class="label">Hormonal Drug:</div>
                <div class="value">{{ hormonal.hormonal_drugs }}</div>

                <div class="label">Hormonal Dose:</div>
                <div class="value">{{ hormonal.hormonal_dose }}</div>

                <div class="label">Initial Hormonal Date:</div>
                <div class="value">{{ formattedDate(hormonal.hormonal_initial_date) }}</div>

                <div class="label">Last Hormonal Date:</div>
                <div class="value">{{ formattedDate(hormonal.hormonal_end_date) }}</div>

                <div class="label">Hormonal Status:</div>
                <div class="value">{{ hormonal.hormonal_status }}</div>

                <div class="label">Hormonal Notes:</div>
                <div class="value">{{ hormonal.hormonal_notes }}</div>

                <div class="label">Hormonal Doctor:</div>
                <div class="value">Dr. {{ CF(hormonal.doctor.user.firstname) }} {{ CF(hormonal.doctor.user.lastname) }}</div>

                <div></div>
                <div class="button-container">
                    <button
                        class="button-custom1"
                        @click="() => { otherId = index; EditInfo(); }"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z" fill="#ffffff"/>
                    </svg>
                    </button>
                </div>

                <hr>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div class="pagination-controls">
            <button class="button-custom" @click="goToPreviousPage" :disabled="currentPageIndex === 0">&lt;</button>
            <button class="button-custom" @click="goToNextPage" :disabled="currentPageIndex === availablePages.length - 1">&gt;</button>
        </div>
    </div>
</template>

<style scoped>

.details-grid {
    display: grid;
    grid-template-columns: 250px auto;
    gap: 10px;
    align-items: center;
    padding: 10px 20px;
}

.button-custom1 {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px; /* Adjust size as needed */
    height: 40px;
    padding: 11px;
    border: none;
    border-radius: 50%;
    background-color: #b04748; /* Button color */
    color: white;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
}

.button-custom1:hover {
    background-color:#d3a5a3; /* Hover color */
}

.button-container {
    display: flex;
    justify-content: flex-end;
    width: 100%;
}

.label {
    padding-right: 15px;
    font-weight: bold;
}

.value {
    text-align: left;
}

p {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f4e3e2;
    font-weight: bold
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.pagination-controls button {
    margin: 0 10px;
    padding: 5px 15px;
    font-weight: bold;
}

.button-custom {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem; /* px-4 py-2 */
  background-color: #b04748;
  border: 1px solid transparent;
  border-radius: 0.375rem; /* rounded-md */
  font-weight: 600; /* font-semibold */
  font-size: 0.75rem; /* text-xs */
  color: white;
  text-transform: uppercase;
  letter-spacing: 0.05em; /* tracking-widest */
  transition: background-color 150ms ease-in-out;
  outline: none;
}

.button-custom:hover {
  background-color: #d3a5a3;
}

.button-custom:focus,
.button-custom:active {
  background-color: #d3a5a3;
}

.button-custom:active {
  background-color: #9f2123;
}

.button-custom:focus {
  box-shadow: 0 0 0 2px #9f2123, 0 0 0 4px rgba(0, 0, 0, 0.5); /* focus:ring-indigo-500 with focus:ring-offset */
}

/* hr {
    border: none;
    border-top: 2px solid maroon;
    margin: 10px 0;
} */

.details-grid hr {
    grid-column: 1 / -1;
    width: 100%;
    border: none;
    border-top: 1.5px solid maroon;
    margin: 10px 0;
}

@import '../../css/GeneralPage.css';
</style>
