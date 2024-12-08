<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Pages/Layout.vue';
import { ref, onMounted, computed } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    patient: {
        type: Object,
        required: true
    }
});

const selectedPatient = ref(0);
const patientId = props.patient?.patient_id
const diseaseProfile = ref({}); 
const loading = ref(true);

const fetchPatientDiseaseProfile = async () => {
    try {
        let response = await axios.get(`/api/disease/${patientId}/profile`);
        diseaseProfile.value = response.data;
        console.log('Disease Profile:', diseaseProfile.value);

    } catch (error) {
        console.error('Failed to fetch patient disease profile:', error);
    } finally {
        loading.value = false; // Set loading to false after fetching completes
    }
};

onMounted(() => {
    fetchPatientDiseaseProfile();
});

const formattedAdditionalPrimarySites = computed(() => {
    const site = diseaseProfile.value.disease_other_primary_site;
    if (!site) return '';

    const sites = [];
    if (site.op_Blood) sites.push("Blood");
    if (site.op_Brain) sites.push("Brain");
    if (site.op_Breast) sites.push("Breast");
    if (site.op_Colon) sites.push("Colon");
    if (site.op_CorpusUteri) sites.push("Corpus Uteri");
    if (site.op_Esophagus) sites.push("Esophagus");
    if (site.op_GallBladder) sites.push("Gall Bladder");
    if (site.op_Kidney) sites.push("Kidney");
    if (site.op_Liver) sites.push("Liver");
    if (site.op_Lung) sites.push("Lung");
    if (site.op_Nasopharynx) sites.push("Nasopharynx");
    if (site.op_OralCavity) sites.push("Oral Cavity");
    if (site.op_Others) sites.push("Others");
    if (site.op_Ovary) sites.push("Ovary");
    if (site.op_Pancreas) sites.push("Pancreas");
    if (site.op_Prostate) sites.push("Prostate");
    if (site.op_Rectum) sites.push("Rectum");
    if (site.op_Skin) sites.push("Skin");
    if (site.op_Stomach) sites.push("Stomach");
    if (site.op_Testis) sites.push("Testis");
    if (site.op_Thyroid) sites.push("Thyroid");
    if (site.op_UrinaryBladder) sites.push("Urinary Bladder");
    if (site.op_UterineCervix) sites.push("Uterine Cervix");

    return sites.join(", ");
});

const formattedDiseaseStatus = computed(() => {
    const status = diseaseProfile.value.disease_status;
    if (!status) return '';

    const statuses = [];
    if (status.dxStatus_alive) statuses.push("Alive");
    if (status.dxStatus_symptoms) statuses.push("With symptoms");
    if (status.dxStatus_recurrence) statuses.push("Recurrent");
    if (status.dxStatus_metastatic) statuses.push("Metastatic");
    if (status.dxStatus_curative) statuses.push("Curative");

    return statuses.join(", ");
});

const metastaticSiteMapping = {
    mets_bone: "Bone",
    mets_brain: "Brain",
    mets_distantLN: "Distant Lymph Nodes",
    mets_intestine: "Intestine",
    mets_liver: "Liver",
    mets_lung: "Lung",
    mets_others: "Others",
    mets_ovary: "Ovary",
    mets_skin: "Skin",
    mets_unknown: "Unknown"
};

const selectedMetastaticSite = computed(() => {
    const metastaticData = diseaseProfile.value.disease_metastatic_site || {};
    const selectedKey = Object.keys(metastaticSiteMapping).find(
        (key) => metastaticData[key] === 1
    );
    return metastaticSiteMapping[selectedKey] || "None"; 
});

const EditInfo = () => {
    window.location.href = `/disease-profile-update/${patientId}`;
}

</script>

<template>
    <div v-if="loading" class="loading-message">Loading...</div>
    <div v-else class="form-container">
        <div class="details-grid">
                <div class="label">Last Name:</div>
                <div class="value"  v-if="diseaseProfile">{{ diseaseProfile.patient.user.lastname }}</div><div v-else></div>

                <div class="label">Email Address:</div>
                <div class="value"  v-if="diseaseProfile">{{ diseaseProfile.patient.user.email }}</div><div v-else></div>

                <div class="label">Primary Site:</div>
                <div class="value" v-if="diseaseProfile">
                    <div class="value" v-if="diseaseProfile.disease_primary_site.body_site_name === 'Others'">{{ diseaseProfile.disease_otherPrimarySite }}</div>
                    <div class="value" v-if="diseaseProfile.disease_primary_site.body_site_name !== 'Others'">{{ diseaseProfile.disease_primary_site.body_site_name }}</div>
                </div>
                <div v-else></div>
                

                <div class="label">Date of Diagnosis:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_diagnosisDate }}</div><div v-else></div>

                <div class="label">Basis of Diagnosis:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_basis.basis_category }}</div><div v-else></div>

                <div class="label">Laterality:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_laterality.laterality_name }}</div><div v-else></div>

                <div class="label">Histology:</div>
                <div class="value" v-if=" diseaseProfile.disease_histology">{{ diseaseProfile.disease_histology.pathology.term }}</div><div v-else></div>

                <div class="label">Tumor Extension:</div>
                <div class="value" v-if=" diseaseProfile.disease_histology">{{ diseaseProfile.disease_histology.histo_tumorExtension  === 1 ? 'Yes' : 'No' }}</div><div v-else></div>

                <div class="label">Tumor Grade:</div>
                <div class="value" v-if=" diseaseProfile.disease_histology">{{ diseaseProfile.disease_histology.histo_tumorGrade }}</div><div v-else></div>

                <div class="label">Node Positive:</div>
                <div class="value" v-if=" diseaseProfile.disease_histology">{{ diseaseProfile.disease_histology.histo_nodePositive }}</div><div v-else></div>

                <div class="label">Node Harvest:</div>
                <div class="value" v-if=" diseaseProfile.disease_histology">{{ diseaseProfile.disease_histology.histo_nodeHarvest }}</div><div v-else></div>

                <div class="label">Negative Margins:</div>
                <div class="value" v-if=" diseaseProfile.disease_histology"> {{ diseaseProfile.disease_histology.histo_negativeMargins === 1 ? 'Yes' : 'No' }}</div><div v-else></div>

                <div class="label">Histology Stage:</div>
                <div class="value" v-if=" diseaseProfile.disease_histology">{{ diseaseProfile.disease_histology.histo_stage }}</div><div v-else></div>

                <div class="label">Extent of Disease:</div>
                <div class="value" v-if=" diseaseProfile">{{ diseaseProfile.disease_extent.extent_name }}</div><div v-else></div>

                <div class="label">Tumor Size:</div>
                <div class="value" v-if=" diseaseProfile">{{ diseaseProfile.disease_tumorSize }}</div><div v-else></div>

                <div class="label">Nodal Involvement:</div>
                <div class="value" v-if=" diseaseProfile">{{ diseaseProfile.disease_lymphNode }}</div><div v-else></div>

                <!-- <div class="label">Metastasis:</div>
                <div class="value">{{ form.metastasis ? 'Yes' : 'No' }}</div> -->

                <div class="label">Metastatic Site:</div>
                <div class="value" v-if="selectedMetastaticSite">
                    <div class="value" v-if="selectedMetastaticSite !== 'Others'">{{ selectedMetastaticSite }}</div>
                    <div class="value" v-if="selectedMetastaticSite === 'Others'">{{ diseaseProfile.disease_metastatic_site.mets_others_site }}</div>
                </div>
                <div v-else></div>
                

                <div class="label">Metastatic Notes:</div>
                <div class="value" v-if="diseaseProfile.disease_metastatic_site">{{diseaseProfile.disease_metastatic_site.mets_notes }}</div><div v-else></div>

                <div class="label">Multiple Primaries:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_multiplePrimary }}</div><div v-else></div>

                <div class="label">Other Primary Site/s:</div>
                <div class="value" v-if="formattedAdditionalPrimarySites && formattedAdditionalPrimarySites.length > 0">{{ formattedAdditionalPrimarySites }}</div><div v-else></div>

                <div class="label" v-if="formattedAdditionalPrimarySites && formattedAdditionalPrimarySites.includes('Others')">Specified Primary Site:</div>
                <div class="value" v-if="formattedAdditionalPrimarySites && formattedAdditionalPrimarySites.includes('Others')">{{ diseaseProfile.disease_other_primary_site.op_Others_Specify }}</div>
            
                <div class="label">T Stage:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_t_stage }}</div><div v-else></div>

                <div class="label">N Stage:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_n_stage }}</div><div v-else></div>

                <div class="label">M Stage:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_m_stage }}</div><div v-else></div>

                <div class="label">G Stage:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_g_stage }}</div><div v-else></div>

                <div class="label">Disease Grade:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_grade }}</div><div v-else></div>

                <div class="label">Stage:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_stage }}</div><div v-else></div>

                <div class="label">Stage Type:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_stageType }}</div><div v-else></div>

                <div class="label">Full Diagnosis:</div>
                <div class="value" v-if="diseaseProfile">{{ diseaseProfile.disease_fullDiagnosis }}</div><div v-else></div>

                <div class="label">Disease Status:</div>
                <div class="value" v-if="diseaseProfile">{{ formattedDiseaseStatus }}</div><div v-else></div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <!-- <PrimaryButton class="ms-4" @click="EditInfo">
                Edit
            </PrimaryButton> -->

            <button class="button-custom1" @click="EditInfo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z" fill="#ffffff"/>
                </svg>
            </button>

            
        </div>

    </div>


</template>

<style scoped>

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

.loading-message {
    text-align: center;
    font-size: 1.2rem;
    padding: 20px;
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

@import '../../css/GeneralPage.css';
</style>
