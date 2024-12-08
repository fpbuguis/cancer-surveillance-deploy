<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Pages/Layout.vue';
import Alert from '@/Components/Alert.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';

const { url } = usePage();
const parts = url.split('/');
let patientId = parts[parts.length - 1]; 
patientId = parseInt(patientId, 10);
console.log('Patient ID:', patientId);

const primary_sites = ref([]);
const diagnosis_basis = ref([]);
const laterality = ref([]);
//const metastatic_site = ref([]);
const extent_of_disease = ref([]);
const pathologies = ref([]);
const alert = ref({
    show: false,
    message: '',
    type: '',
});

const form = useForm({
    lastname: '',
    email: '',
    primary_site: null,
    custom_primary_site: '',  
    diagnosis_date: '',
    diagnosis_basis: null,
    laterality: null,
    pathology: null,
    hist_tumor_extension: false,
    hist_tumor_grade: '',
    hist_nodepositive: '',
    hist_nodeharvest: '',
    hist_negmargins: false,
    hist_stage: '',
    extent_of_disease: null,
    tumor_size: '',
    nodal_involvement: '',
    metastatic_site: '',
    other_metastatic_site: '',
    metastatic_notes: '',
    multiple_primaries: '',
    other_primary_sites: [],
    additional_primary_site: '',
    t_stage: '',
    n_stage: '',
    m_stage: '', 
    g_stage: '',
    grade: '',
    stage: '',
    stage_type: '',
    full_diagnosis: '',
    disease_status: [],
    errors: {
        primary_site: null,
        multiple_primaries: null,
        other_primary_sites: null,
    },
});

const fetchBodySites = async () => {
    try {
        const response = await axios.get('/api/disease/options');
        primary_sites.value = response.data.primarySites;
    } catch (error) {
        console.error('Error fetching body sites:', error);
    }
};

const fetchDiagnosisBasis = async () => {
    try {
        const response = await axios.get('/api/disease/options');
        diagnosis_basis.value = response.data.bases;
    } catch (error) {
        console.error('Error fetching diagnosis basis:', error);
    }
};

const fetchLaterality = async () => {
    try {
        const response = await axios.get('/api/disease/options');
        laterality.value = response.data.lateralities;
    } catch (error) {
        console.error('Error fetching laterality:', error);
    }
};

const fetchExtentOfDisease = async () => {
    try {
        const response = await axios.get('/api/disease/options');
        extent_of_disease.value = response.data.diseaseExtents;
    } catch (error) {
        console.error('Error fetching extent of disease:', error);
    }
};

// change logic to retrieve from db
// const histology = [
//     "Invasive Ductal Carcinoma", "Adenocarcinoma", "Small Cell Carcinoma", "Papillary Thryoid",
//     "Squamous Cell Carcinoma" /* ... super marami to wahaha */
// ];

// searchable dropdown for histology
const histologySearch = ref('');
const selectedHistology = ref(null);
const showDropdown = ref(false);
const filteredHistology = computed(() => {
    return histology.filter(option =>
        option.toLowerCase().includes(histologySearch.value.toLowerCase())
    );
});

const selectHistology = (option) => {
    histologySearch.value = option;
    selectedHistology.value = option;
    form.histology_id = option;
    showDropdown.value = false;
};

const hideOptions = (event) => {
    if (!event.relatedTarget || !event.relatedTarget.classList.contains('dropdown-item')) {
        showDropdown.value = false;
    }
};


// change logic to retrieve from db
const metastatic_site = [
    "Distant Lymph Nodes", "Bone", "Liver", "Lung", "Brain", "Ovary",  "Skin", "Intestine", "Other", "Unknown" // ,"None"
];

const metastaticSiteMapping = {
    mets_bone: "Bone",
    mets_brain: "Brain",
    mets_distantLN: "Distant Lymph Nodes",
    mets_intestine: "Intestine",
    mets_liver: "Liver",
    mets_lung: "Lung",
    mets_others: "Other",
    mets_ovary: "Ovary",
    mets_skin: "Skin",
    mets_unknown: "Unknown",
};

// change logic to retrieve from db
const disease_statuses = [
    "Alive", "Recurrent", "With symptoms", "Curative", "Metastatic"
];

const patients = ref(null);
const patientData = ref({ lastname: '', email: '' });
const diseaseProfile = ref({ });

const fetchPatientInfo = async () => {
        try {
            let response = await axios.get('/api/all-patients');
            patients.value = response.data;

            // Find the patient with the matching patientId
            const patient = patients.value.find(p => p.patient_id === patientId);
            console.log('Found patient:', patient);

        if (patient) {
            const user = patient.user; // Extracting the user object

            patientData.value.lastname = user.lastname;
            patientData.value.email = user.email;

            form.lastname = patientData.value.lastname;
            form.email = patientData.value.email;
        }
        } catch (error) {
            console.error('Failed to fetch patient info:', error);
        }
};

const fetchPatientDiseaseProfile = async () => {
    try {
        let response = await axios.get(`/api/disease/${patientId}/profile`);
        diseaseProfile.value = response.data;
        console.log('Disease Profile:', diseaseProfile.value);

        form.primary_site = diseaseProfile.value.disease_primary_site.body_site_name;
        form.custom_primary_site = diseaseProfile.value.disease_otherPrimarySite;
        form.diagnosis_date = diseaseProfile.value.disease_diagnosisDate;
        form.diagnosis_basis = diseaseProfile.value.disease_basis.basis_name;
        form.laterality = diseaseProfile.value.disease_laterality.laterality_name;
        form.pathology = diseaseProfile.value.disease_histology.pathology.pathology_id;
        form.hist_tumor_extension = diseaseProfile.value.disease_histology.histo_tumorExtension === 1 ;
        form.hist_tumor_grade = diseaseProfile.value.disease_histology.histo_tumorGrade;
        form.hist_nodepositive = diseaseProfile.value.disease_histology.histo_nodePositive;
        form.hist_nodeharvest = diseaseProfile.value.disease_histology.histo_nodeHarvest;
        form.hist_negmargins = diseaseProfile.value.disease_histology.histo_negativeMargins === 1 
        form.hist_stage = diseaseProfile.value.disease_histology.histo_stage;
        form.extent_of_disease = diseaseProfile.value.disease_extent.extent_name;
        form.tumor_size = diseaseProfile.value.disease_tumorSize;
        form.nodal_involvement = diseaseProfile.value.disease_lymphNode;

        const selectedSite = Object.keys(metastaticSiteMapping).find(
            (key) => diseaseProfile.value.disease_metastatic_site[key] === 1
        );

        if (selectedSite) {
            form.metastatic_site = metastaticSiteMapping[selectedSite];
        }

        form.other_metastatic_site = diseaseProfile.value.disease_metastatic_site.mets_others_site;
        form.metastatic_notes = diseaseProfile.value.disease_metastatic_site.mets_notes;
        form.multiple_primaries = diseaseProfile.value.disease_multiplePrimary;

        form.other_primary_sites = [];
        const primarySitesMap = {
            'Blood': diseaseProfile.value.disease_other_primary_site.op_Blood,
            'Brain': diseaseProfile.value.disease_other_primary_site.op_Brain,
            'Breast': diseaseProfile.value.disease_other_primary_site.op_Breast,
            'Colon': diseaseProfile.value.disease_other_primary_site.op_Colon,
            'Corpus Uteri': diseaseProfile.value.disease_other_primary_site.op_CorpusUteri,
            'Esophagus': diseaseProfile.value.disease_other_primary_site.op_Esophagus,
            'Gall Bladder': diseaseProfile.value.disease_other_primary_site.op_GallBladder,
            'Kidney': diseaseProfile.value.disease_other_primary_site.op_Kidney,
            'Liver': diseaseProfile.value.disease_other_primary_site.op_Liver,
            'Lung': diseaseProfile.value.disease_other_primary_site.op_Lung,
            'Nasopharynx': diseaseProfile.value.disease_other_primary_site.op_Nasopharynx,
            'Oral Cavity': diseaseProfile.value.disease_other_primary_site.op_OralCavity,
            'Others': diseaseProfile.value.disease_other_primary_site.op_Others,
            'Ovary': diseaseProfile.value.disease_other_primary_site.op_Ovary,
            'Pancreas': diseaseProfile.value.disease_other_primary_site.op_Pancreas,
            'Prostate': diseaseProfile.value.disease_other_primary_site.op_Prostate,
            'Rectum': diseaseProfile.value.disease_other_primary_site.op_Rectum,
            'Skin': diseaseProfile.value.disease_other_primary_site.op_Skin,
            'Stomach': diseaseProfile.value.disease_other_primary_site.op_Stomach,
            'Testis': diseaseProfile.value.disease_other_primary_site.op_Testis,
            'Thyroid': diseaseProfile.value.disease_other_primary_site.op_Thyroid,
            'Urinary Bladder': diseaseProfile.value.disease_other_primary_site.op_UrinaryBladder,
            'Uterine Cervix': diseaseProfile.value.disease_other_primary_site.op_UterineCervix,
        };

        for (const [site, value] of Object.entries(primarySitesMap)) {
            if (value === 1) {
                form.other_primary_sites.push(site);
            }
        }
            
        form.additional_primary_site = diseaseProfile.value.disease_other_primary_site.op_Others_Specify;
        form.t_stage = diseaseProfile.value.disease_t_stage;
        form.n_stage = diseaseProfile.value.disease_n_stage;
        form.m_stage = diseaseProfile.value.disease_m_stage;
        form.g_stage = diseaseProfile.value.disease_g_stage;
        form.grade = diseaseProfile.value.disease_grade;
        form.stage = diseaseProfile.value.disease_stage;
        form.stage_type = diseaseProfile.value.disease_stageType;
        form.full_diagnosis = diseaseProfile.value.disease_fullDiagnosis;
        form.disease_status = [];
        const diseaseStatusMap = {
            'Alive': diseaseProfile.value.disease_status.dxStatus_alive,
            'Curative': diseaseProfile.value.disease_status.dxStatus_curative,
            'Metastatic': diseaseProfile.value.disease_status.dxStatus_metastatic,
            'Recurrent': diseaseProfile.value.disease_status.dxStatus_recurrence,
            'With symptoms': diseaseProfile.value.disease_status.dxStatus_symptoms,
        };

        for (const [status, value] of Object.entries(diseaseStatusMap)) {
            if (value === 1) {
                form.disease_status.push(status);
            }
        }
    
    } catch (error) {
        console.error('Failed to fetch patient disease profile:', error);
    }
};

onMounted(async () => {
    await fetchPatientInfo();
    await fetchPatientDiseaseProfile();
    await fetchBodySites();
    await fetchDiagnosisBasis();
    await fetchLaterality();
    //fetchMetastaticSite();
    await fetchExtentOfDisease();

    try {
        const [pathosResponse] = await Promise.all([
            axios.get('/api/pathologies'),
            
        ]);

        pathologies.value = pathosResponse.data;
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
});

const submittedOnly = ref(0);
const isPrimarySiteSelected = computed(() => form.primary_site !== null && form.primary_site !== '');
const isBasisDiagnosisSelected = computed(() => form.diagnosis_basis !== null && form.diagnosis_basis !== '');
const isLateralitySelected = computed(() => form.laterality !== null && form.laterality !== '');
const isLMetSiteSelected = computed(() => form.metastatic_site !== null && form.metastatic_site !== '');
const isDiseaseStatusSelected = computed(() => form.disease_status.length > 0);

const validateForm = () => {
    let isValid = true;

    if(!isPrimarySiteSelected.value || !isBasisDiagnosisSelected.value || !isLateralitySelected.value || !isLMetSiteSelected.value || !isDiseaseStatusSelected.value){
        isValid = false;
    }

    for (const error in form.errors) {
        if (form.errors[error]) {
            isValid = false;
            break;
        }
    }

    return isValid;
};

const submit = async () => {
    alert.value.show = false;

    if (!validateForm()) {
        return; 
    }

    try{
        await form.post('/api/create-disease-record', {
        onSuccess: () => {

            if (submittedOnly.value === 1){
                // submit only
                alert.value = {
                    show: true,
                    message: 'Success! Disease profile submitted.',
                    type: 'success',
                };

                form.reset();
                //console.log("Form submitted successfully.");
                //window.location.href = `/display-profile`;
            } 
            else {
                // submit then treatment
                alert.value = {
                    show: true,
                    message: 'Success! Disease profile submitted.',
                    type: 'success',
                };

                window.location.href = `/treatment-history/${patientId}`;
                console.log("Form submitted successfully.");
            }
        },
        onError: (errors) => {

            alert.value = {
                show: true,
                message: 'Error! Please try sbumitting again.',
                type: 'error',
            };

            if (errors.patient_id) {
                error.value = errors.patient_id[0];
            } else {
                console.log(errors);
            }
        },
    });
    }
    catch(error){
        console.error("Submission failed:", error);

    }
}
watch(
    () => [form.other_primary_sites, form.primary_site],
    ([newOtherPrimarySites, newPrimarySite]) => {
        if (form.multiple_primaries === '1' && newOtherPrimarySites.length !== 1) {
            form.errors.other_primary_sites = "Select only one.";
        } else if (form.multiple_primaries === '2' && newOtherPrimarySites.length !== 2) {
            form.errors.other_primary_sites = "Select only two.";
        } else {
            form.errors.other_primary_sites = null; 
        }

    }
);


</script>

<template>
    <Head title="Disease Profile" />

    <Layout>
        <div class="page-heading">
            <h1 class="main-heading">DISEASE PROFILE</h1>
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
                    required
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
                    required
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="primary_site">
                    Primary Site <span class="red-asterisk">*</span>
                </InputLabel>
                <InputError v-if="!isPrimarySiteSelected" message="Please select one." />
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <label
                    v-for="(primary_site, index) in primary_sites.filter(
                    (site) => site.body_site_name !== 'All' && site.body_site_name !== 'Negative')"
                    :key="index"
                    class="inline-flex items-center">
                        <input
                            type="radio"
                            name="primary_site"
                            :value="primary_site.body_site_name"
                            v-model="form.primary_site"
                            class="form-radio"
                            required
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">{{ primary_site.body_site_name }}</span>
                    </label>

                    <!-- <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="primary_site"
                            value="Other"
                            v-model="form.primary_site"
                            class="form-radio"
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">Other</span>
                    </label> -->
                </div>
                <InputError :message="form.errors.primary_site" />
            </div>

            <div v-if="form.primary_site === 'Others'">
                <InputLabel for="custom_primary_site">
                    Specify Primary Site <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="custom_primary_site"
                    v-model="form.custom_primary_site"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.custom_primary_site" />
            </div>

            <div>
                <InputLabel for="diagnosis_date">
                    Date of Diagnosis <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="diagnosis_date"
                    v-model="form.diagnosis_date"
                    type="date"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.diagnosis_date" />
            </div>

            <div>
                <InputLabel for="diagnosis_basis">
                    Basis of Diagnosis <span class="red-asterisk">*</span>
                </InputLabel>
                <InputError v-if="!isBasisDiagnosisSelected" message="Please select one." />
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <label
                    v-for="(basis, index) in diagnosis_basis"
                    :key="index"
                    class="inline-flex items-center">
                        <input
                            type="radio"
                            name="diagnosis_basis"
                            :value="basis.basis_name"
                            v-model="form.diagnosis_basis"
                            class="form-radio"
                            required
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">{{ basis.basis_name }}</span>
                    </label>
                </div>
                <InputError :message="form.errors.diagnosis_basis" />
            </div>

            <div>
                <InputLabel for="laterality">
                    Laterality <span class="red-asterisk">*</span>
                </InputLabel>
                <InputError v-if="!isLateralitySelected" message="Please select one." />
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <label
                    v-for="(lat, index) in laterality"
                    :key="index"
                    class="inline-flex items-center">
                        <input
                            type="radio"
                            name="laterality"
                            :value="lat.laterality_name"
                            v-model="form.laterality"
                            class="form-radio"
                            required
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">{{ lat.laterality_name }}</span>
                    </label>
                </div>
                <InputError class="mt-2" :message="form.errors.laterality" />
            </div>

            <div>
            
                <InputLabel for="histo_pathology">
                Histology <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="pathology" v-model="form.pathology" class="mt-1 block w-full" required>
                    <option value="" disabled>Select pathology</option>
                    <option v-for="pathology in pathologies" :key="pathology.pathology_id" :value="pathology.pathology_id">
                        {{ pathology.term }}
                    </option>
                </select>

                <ul v-if="showDropdown && filteredHistology.length > 0" class="dropdown-list">
                    <li v-for="(option, index) in filteredHistology"
                        :key="index"
                        @mousedown.prevent="selectHistology(option)"
                        class="dropdown-item">
                        {{ option }}
                    </li>
                </ul>

                <InputError class="mt-2" :message="form.errors.histo_pathology" />
            </div>

            <div>
                <InputLabel for="hist_tumor_extension">
                    Tumor Extension
                </InputLabel>
                    <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label
                            class="inline-flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.hist_tumor_extension"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Yes</span>
                        </label>
                    </div>
                <InputError :message="form.errors.hist_tumor_extension" />
            </div>

            <div>
                <InputLabel for="hist_tumor_grade">
                    Tumor Grade <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="hist_tumor_grade"
                    v-model="form.hist_tumor_grade"
                    type="number"
                    step="1"
                    min="0"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.hist_tumor_grade" />
            </div>

            <div>
                <InputLabel for="hist_nodepositive">
                    Node Positive <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="hist_nodepositive"
                    v-model="form.hist_nodepositive"
                    type="number"
                    step="1"
                    min="0"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.hist_nodepositive" />
            </div>

            <div>
                <InputLabel for="hist_nodeharvest">
                    Node Harvest <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="hist_nodeharvest"
                    v-model="form.hist_nodeharvest"
                    type="number"
                    step="1"
                    min="0"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.hist_nodeharvest" />
            </div>

            <div>
                <InputLabel for="hist_negmargins" value="Negative Margins" />
                    <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label
                            class="inline-flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.hist_negmargins"
                                class="form-checkbox"
                            />
                            <span class="ml-2">Yes</span>
                        </label>
                    </div>
                <InputError :message="form.errors.hist_negmargins" />
            </div>

            <div>
                <InputLabel for="hist_stage">
                    Histology Stage <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="hist_stage" v-model="form.hist_stage" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Stage</option>
                    <option value="I">Stage I</option>
                    <option value="II">Stage II</option>
                    <option value="III">Stage III</option>
                    <option value="IV">Stage IV</option>
                </select>

                <InputError :message="form.errors.hist_stage" />
            </div>

            <div>
                <InputLabel for="extent_of_disease">
                    Extent of Disease <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="extent_of_disease" v-model="form.extent_of_disease" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Extent of Disease</option>
                    <option v-for="(extent, index) in extent_of_disease" :key="index" :value="extent.extent_name">{{ extent.extent_name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.extent_of_disease" />
            </div>

            <div>
                <InputLabel for="tumor_size">
                    Tumor Size <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="tumor_size"
                    v-model="form.tumor_size"
                    type="number"
                    step="0.01"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.tumor_size" />
            </div>

            <div>
                <InputLabel for="nodal_involvement">
                    Nodal Involvement <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="nodal_involvement"
                    v-model="form.nodal_involvement"
                    type="number"
                    step="1"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.nodal_involvement" />
            </div>

            <div>
                <InputLabel for="metastatic_site">
                    Metastatic Site <span class="red-asterisk">*</span>
                </InputLabel>
                <InputError v-if="!isLMetSiteSelected" message="Please select one." />
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <label
                        v-for="(site, index) in metastatic_site"
                        :key="index"
                        class="inline-flex items-center">
                        <input
                            type="radio"
                            name="metastatic_site"
                            :value="site"
                            v-model="form.metastatic_site"
                            class="form-radio"
                            required
                        />
                        <span class="custom-radio"></span>
                        <span class="ml-2">{{ site }}</span>
                    </label>
                </div>
                <InputError :message="form.errors.metastatic_site" />  
            </div>

            <div v-if="form.metastatic_site.includes('Other')">
                <InputLabel for="other_metastatic_site">
                    Specify Other Metastatic Site <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="other_metastatic_site"
                    v-model="form.other_metastatic_site"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.other_metastatic_site" />
            </div>

            <div>
                <InputLabel for="metastatic_notes" value="Metastatic Notes" />
                <textarea
                    id="metastatic_notes"
                    v-model="form.metastatic_notes"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.metastatic_notes" />
            </div>

            <div>
                <InputLabel for="multiple_primaries">
                    Multiple Primaries <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="multiple_primaries" v-model="form.multiple_primaries" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Multiple Primaries</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>

                <InputError :message="form.errors.multiple_primaries" />
            </div>

            <div v-if="form.multiple_primaries == '1' || form.multiple_primaries == '2'">
                <InputLabel for="other_primary_sites" value="Other Primary Sites" />
                <div class="mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <label
                    v-for="(primary_site, index) in primary_sites.filter(
                    (site) => site.body_site_name !== 'All' && site.body_site_name !== 'Negative')"
                    :key="index"
                    class="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="other_primary_sites"
                            :value="primary_site.body_site_name"
                            v-model="form.other_primary_sites"
                            class="form-checkbox"
                        />
                        <span class="ml-2">{{ primary_site.body_site_name }}</span>
                    </label>
                </div>
                <InputError :message="form.errors.other_primary_sites" />

                <div v-if="form.other_primary_sites.includes('Others')">
                    <InputLabel for="additional_primary_site" value="Specify Other Primary Site" />
                    <TextInput
                        id="additional_primary_site"
                        v-model="form.additional_primary_site"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.additional_primary_site" />
                </div>

            </div>

            <!-- <div>
                <InputLabel for="t_stage" value="T Stage" />
                <select id="t_stage" v-model="form.t_stage" class="mt-1 block w-full">
                    <option value="" disabled>Select T Stage</option>
                    <option value="0">Stage 0</option>
                    <option value="1">Stage 1</option>
                    <option value="2">Stage 2</option>
                    <option value="3">Stage 3</option>
                </select>
                <InputError :message="form.errors.t_stage" />
            </div>

            <div>
                <InputLabel for="n_stage" value="N Stage" />
                <select id="n_stage" v-model="form.n_stage" class="mt-1 block w-full">
                    <option value="" disabled>Select N Stage</option>
                    <option value="0">Stage 0</option>
                    <option value="1">Stage 1</option>
                    <option value="2">Stage 2</option>
                    <option value="3">Stage 3</option>
                </select>
                <InputError :message="form.errors.n_stage" />
            </div>

            <div>
                <InputLabel for="m_stage" value="M Stage" />
                <select id="m_stage" v-model="form.m_stage" class="mt-1 block w-full">
                    <option value="" disabled>Select M Stage</option>
                    <option value="0">Stage 0</option>
                    <option value="1">Stage 1</option>
                    <option value="2">Stage 2</option>
                    <option value="3">Stage 3</option>
                </select>
                <InputError :message="form.errors.m_stage" />
            </div> 
            
            <div>
                <InputLabel for="g_stage" value="G Stage" />
                <select id="g_stage" v-model="form.g_stage" class="mt-1 block w-full">
                    <option value="" disabled>Select G Stage</option>
                    <option value="0">Stage 0</option>
                    <option value="1">Stage 1</option>
                    <option value="2">Stage 2</option>
                    <option value="3">Stage 3</option>
                </select>
                <InputError :message="form.errors.g_stage" />
            </div> -->

            <div>
                <InputLabel for="t_stage">
                    T Stage <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="t_stage"
                    v-model="form.t_stage"
                    type="number"
                    min="0"
                    step="1"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.t_stage" />
            </div>

            <div>
                <InputLabel for="n_stage">
                    N Stage <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="n_stage"
                    v-model="form.n_stage"
                    type="number"
                    min="0"
                    step="1"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.n_stage" />
            </div>

            <div>
                <InputLabel for="m_stage">
                    M Stage <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="m_stage"
                    v-model="form.m_stage"
                    type="number"
                    min="0"
                    step="1"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.m_stage" />
            </div>

            <div>
                <InputLabel for="g_stage">
                    G Stage <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="g_stage"
                    v-model="form.g_stage"
                    type="number"
                    min="0"
                    step="1"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.g_stage" />
            </div>

            <div>
                <InputLabel for="grade">
                    Disease Grade <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="grade" v-model="form.grade" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Disease Grade</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <InputError :message="form.errors.grade" />
            </div>

            <div>
                <InputLabel for="stage">
                    Disease Stage <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="stage" v-model="form.stage" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Stage</option>
                    <option value="I">Stage I</option>
                    <option value="II">Stage II</option>
                    <option value="III">Stage III</option>
                    <option value="IV">Stage IV</option>
                </select>

                <InputError :message="form.errors.stage" />
            </div>

            <div>
                <InputLabel for="stage_type">
                    Disease Stage Type <span class="red-asterisk">*</span>
                </InputLabel>
                <select id="stage_type" v-model="form.stage_type" class="mt-1 block w-full" required>
                    <option value="" disabled>Select Stage Type</option>
                    <option value="clinical">Clinical</option>
                    <option value="pathologic">Pathologic</option>
                </select>
                <InputError class="mt-2" :message="form.errors.stage_type" />
            </div>

            <div>
                <InputLabel for="full_diagnosis">
                    Full Diagnosis <span class="red-asterisk">*</span>
                </InputLabel>
                <TextInput
                    id="full_diagnosis"
                    v-model="form.full_diagnosis"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Enter Primary Site + Histology + Stage + Laterality"
                    required
                />
                <InputError :message="form.errors.full_diagnosis" />
            </div>

            <div>
                <InputLabel for="disease_status">
                    Disease Status <span class="red-asterisk">*</span>
                </InputLabel>
                <InputError v-if="!isDiseaseStatusSelected" message="Please select at least one." />
                    <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label
                            v-for="(ds, index) in disease_statuses"
                            :key="index"
                            class="inline-flex items-center">
                            <input
                                type="checkbox"
                                name="disease_status"
                                :value="ds"
                                v-model="form.disease_status"
                                class="form-checkbox"
                            />
                            <span class="ml-2">{{ ds }}</span>
                        </label>
                    </div>

                <InputError :message="form.errors.disease_status" />
            </div>

            <div class="flex items-center justify-center mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submittedOnly = 1">
                    Submit
                </PrimaryButton>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submittedOnly = 2">
                    Submit & Add Treatment History
                </PrimaryButton>
            </div>
        </form>
        <Alert v-if="alert.show" :message="alert.message" :type="alert.type" />
    </Layout>
</template>

<style scoped>

@import '../../css/GeneralPage.css';

.dropdown-list {
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 4px;
    z-index: 10;
    max-height: 200px;
    overflow-y: auto;
    width: 575px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease-in-out;
    margin-top: -12px;
}

.dropdown-item {
    padding: 2px 10px;
    cursor: pointer;

}

.dropdown-item:hover {
    background-color: #1967D2; /* f1f1f1 */
    color: white;
}

</style>
