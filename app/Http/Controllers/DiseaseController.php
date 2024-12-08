<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Disease;
use App\Models\Patient;
use App\Models\BodySite;
use App\Models\Basis;
use App\Models\Laterality;
use App\Models\Histology;
use App\Models\DiseaseExtent;
use App\Models\MetastaticSite;
use App\Models\DiseaseStatus;
use App\Models\User;
use App\Models\OtherPrimary;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pathology;

class DiseaseController extends Controller
{

    public function getDiseaseProfile($patientId)
    {
        $disease = Disease::with([
            'patient.user',
            'diseasePrimarySite',
            'diseaseBasis',
            'diseaseLaterality',
            'diseaseHistology.pathology',
            'diseaseExtent',
            'diseaseMetastaticSite',
            'diseaseOtherPrimarySite',
            'diseaseStatus',
            'diseaseEncoder'
        ])
        ->where('patient_id', $patientId)
        ->first();

        if (!$disease) {
            return response()->json(['message' => 'Disease not found'], 404);
        }

        return response()->json($disease);
    }

    public function getLatestDiseaseProfile($patientId)
    {
        $disease = Disease::with([
            'patient.user',
            'diseasePrimarySite',
            'diseaseBasis',
            'diseaseLaterality',
            'diseaseHistology.pathology',
            'diseaseExtent',
            'diseaseMetastaticSite',
            'diseaseOtherPrimarySite',
            'diseaseStatus',
            'diseaseEncoder'
        ])
        ->where('patient_id', $patientId)
        ->orderBy('disease_id', 'desc')
        ->first();

        if (!$disease) {
            return response()->json(['message' => 'Latest disease not found'], 404);
        }

        return response()->json($disease);
    }

    public function getAllPathologies()
    {
        $pathologies = Pathology::all();

        return response()->json($pathologies, 200);
    }

    // GET OPTIONS FOR DISEASE PROFILE BUTTONS AND SELECTS
    public function getOptions()
    {
        return response()->json([
            'primarySites' => BodySite::all(['body_site_id', 'body_site_name']), // Fetching specific fields
            'bases' => Basis::all(),
            'lateralities' => Laterality::all(),
            'histologies' => Histology::all(),
            'diseaseExtents' => DiseaseExtent::all(),
            'metastaticSites' => MetastaticSite::all(),
            'diseaseStatuses' => DiseaseStatus::all(),
        ]);
    }

    public function getDiseaseStatuses()
    {
        return DiseaseStatus::all();
    }

    // GET PATIENT ID FOR DISEASE PROFILE
    public function getPatientId(Request $request)
    {
        $user_id = User::where('lastname', $request->lastname)
                    ->where('email', $request->email)
                    ->first();

        $patient = Patient::where('user_id', $user_id->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        return response()->json(['patient_id' => $patient->patient_id]);
    }

    public function store(Request $request)
    {

        $user = User::where('lastname', $request->lastname)
                    ->where('email', $request->email);

        if (!$user->exists()) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $patient = Patient::where('user_id', $user->first()->user_id)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        if ($request['metastatic_site']=='None'){
            $metastaticSite_bool = false;
        } else {
            $metastaticSite_bool = true;
        }

        // Saving Metastatic Site Data
        $metastaticSite_mapping = [
            "Distant Lymph Nodes" =>'mets_distantLN',
            "Bone" => 'mets_bone',
            "Lung" => 'mets_lung',
            "Liver" => 'mets_liver',
            "Brain" => 'mets_brain',
            "Ovary" => 'mets_ovary',
            "Skin" => 'mets_skin',
            "Intestine" => 'mets_intestine',
            "Other" => 'mets_others',
            "Unknown" => 'mets_unknown'
        ];

        $metastaticSite_col = [
            'mets_distantLN' => false,
            'mets_bone' => false,
            'mets_liver' => false,
            'mets_lung' => false,
            'mets_brain' => false,
            'mets_ovary' => false,
            'mets_skin' => false,
            'mets_intestine' => false,
            'mets_others' => false,
            'mets_unknown' => false,
            'mets_others_site' => ''
        ];

        if($metastaticSite_bool == true){
            $metastaticSite = $request['metastatic_site'];
            if(isset($metastaticSite_mapping[$metastaticSite])){
                $metastaticSite_col[$metastaticSite_mapping[$metastaticSite]] = true;
            }

            $data = array_merge(
                ['patient_id' => $patient->patient_id],
                $metastaticSite_col,
            );

            // For other metastatic site
            if ($request->filled('other_metastatic_site')) {
                $data['mets_others_site'] = $request->other_metastatic_site;
            }

            // For notes
            if ($request->filled('metastatic_notes')) {
                $data['mets_notes'] = $request->metastatic_notes;
            }

            $mets = MetastaticSite::create($data);
            $metastaticSite_id = $mets->mets_id;
        }
        else {
            $metastaticSite_id = null;
        }

        // Save Disease Status
        $dxStatus_types = [
            'dxStatus_alive' => false,
            'dxStatus_symptoms' => false,
            'dxStatus_recurrence' => false,
            'dxStatus_metastatic' => false,
            'dxStatus_curative' => false
        ];

        $dxStatus_mapping = [
            "Alive" => 'dxStatus_alive',
            "Recurrent" => 'dxStatus_recurrence',
            "With symptoms" => 'dxStatus_symptoms',
            "Metastatic" => 'dxStatus_metastatic',
            "Curative" => 'dxStatus_curative'
        ];

        foreach ($request['disease_status'] as $status) {
            if (isset($dxStatus_mapping[$status])) {
                $dxStatus_types[$dxStatus_mapping[$status]] = true;
            }
        }

        $dxStatus_data = array_merge(
            ['patient_id' => $patient->patient_id],
            $dxStatus_types
        );

        $diseaseStatus = DiseaseStatus::create($dxStatus_data);

        // Save Other Primary Site
        $op_types = [
            'op_Colon' => false,
            'op_Brain' => false,
            'op_Liver' => false,
            'op_UrinaryBladder' => false,
            'op_GallBladder' => false,
            'op_Thyroid' => false,
            'op_UterineCervix' => false,
            'op_CorpusUteri' => false,
            'op_Breast' => false,
            'op_Ovary' => false,
            'op_Blood' => false,
            'op_Lung' => false,
            'op_Esophagus' => false,
            'op_Kidney' => false,
            'op_Pancreas' => false,
            'op_OralCavity' => false,
            'op_Stomach' => false,
            'op_Skin' => false,
            'op_Nasopharynx' => false,
            'op_Testis' => false,
            'op_Prostate' => false,
            'op_Rectum' => false ,
            'op_Others' => false,
            'op_Others_Specify' => ''
        ];

        $op_mapping = [
            'Colon' => 'op_Colon',
            'Brain' => 'op_Brain',
            'Urinary bladder' => 'op_UrinaryBladder',
            'Gall bladder' => 'op_GallBladder',
            'Thyroid' => 'op_Thyroid',
            'Uterine Cervix' => 'op_UterineCervix',
            'Liver' => 'op_Liver',
            'Corpus Uteri' => 'op_CorpusUteri',
            'Breast' => 'op_Breast',
            'Blood' => 'op_Blood',
            'Ovary' => 'op_Ovary',
            'Lung' => 'op_Lung',
            'Esophagus' => 'op_Esophagus',
            'Kidney' => 'op_Kidney',
            'Oral Cavity' => 'op_OralCavity',
            'Stomach' => 'op_Stomach',
            'Pancreas' => 'op_Pancreas',
            'Skin' => 'op_Skin',
            'Nasopharynx' => 'op_Nasopharynx',
            'Testis' => 'op_Testis',
            'Prostate' => 'op_Prostate',
            'Rectum' => 'op_Rectum',
            'Others' => 'op_Others'
        ];

        foreach($request['other_primary_sites'] as $opSite){
            if(isset($op_mapping[$opSite])){
                $op_types[$op_mapping[$opSite]] = true;
            }
        }

        if (in_array('Others', $request['other_primary_sites'])) {
            $op_types['op_Others_Specify'] = $request['additional_primary_site'];
        }

        $op_Data = array_merge(['patient_id'=> $patient->patient_id], $op_types);

        $otherPrimaries = OtherPrimary::create($op_Data);

        //Save Histology Data
        $histology = Histology::create([
            'patient_id' => $patient->patient_id,
            'histo_pathology' => $request['pathology'],
            'histo_tumorSize' => $request['tumor_size'],
            'histo_tumorExtension' => $request['hist_tumor_extension'],
            'histo_tumorGrade' => $request['hist_tumor_grade'],
            'histo_nodePositive' => $request['hist_nodepositive'],
            'histo_nodeHarvest' => $request['hist_nodeharvest'],
            'histo_negativeMargins' => $request['hist_negmargins'],
            'histo_stage' => $request['hist_stage'],
        ]);

        // Get Laterality ID (Laterality)
        $laterality_id = Laterality::where('laterality_name', $request['laterality'])->first()->laterality_id;

        // Get Extent ID (Extent of Disease)
        $extent_id = DiseaseExtent::where('extent_name', $request['extent_of_disease'])->first()->extent_id;

        // Get Basis ID (Diagnosis Basis)
        $basis_id = Basis::where('basis_name', $request['diagnosis_basis'])->first()->basis_id;

        // Get Body Site ID (Primary Site)
        $bodySite_id = BodySite::where('body_site_name', $request['primary_site'])->first()->body_site_id;

        // Save Disease Data
        $disease = Disease::create([
            'patient_id' => $patient->patient_id,
            'disease_primarySite' => $bodySite_id,
            'disease_otherPrimarySite' => $request['custom_primary_site'],
            'disease_diagnosisDate' => $request['diagnosis_date'],
            'disease_basis' => $basis_id,
            'disease_laterality' => $laterality_id,
            'disease_histology' => $histology->histology_id,
            'disease_extent' => $extent_id,
            'disease_status' => $diseaseStatus->dxStatus_id,
            'disease_tumorSize' => $request['tumor_size'],
            'disease_lymphNode' => $request['nodal_involvement'],
            'disease_metastatic' => $metastaticSite_bool,
            'disease_metastaticSite' => $metastaticSite_id,
            'disease_multiplePrimary' => $request['multiple_primaries'],
            'disease_otherSites' => $otherPrimaries->op_id,
            'disease_t_stage' => $request['t_stage'],
            'disease_n_stage' => $request['n_stage'],
            'disease_m_stage' => $request['m_stage'],
            'disease_g_stage' => $request['g_stage'],
            'disease_grade' => $request['grade'],
            'disease_stage' => $request['stage'],
            'disease_stageType' => $request['stage_type'],
            'disease_fullDiagnosis' => $request['full_diagnosis'],
        ]);


        return Inertia::render('DiseaseProfilePage', [
            'message' => 'Disease record created successfully.',
            'disease_id' => $disease->disease_id,
        ]);
    }

    public function updateDiseaseProfile(Request $request)
    {
        // Get Disease Profile
        $disease = Disease::find($request['disease_id']);

        if (!$disease) {
            return response()->json(['message' => 'Disease not found'], 404);
        }

        //Update non-FK fields
        $disease->update([
            'disease_diagnosisDate' => $request['diagnosis_date'],
            'disease_tumorSize' => $request['tumor_size'],
            'disease_lymphNode' => $request['nodal_involvement'],
            'disease_multiplePrimary' => $request['multiple_primaries'],
            'disease_t_stage' => $request['t_stage'],
            'disease_n_stage' => $request['n_stage'],
            'disease_m_stage' => $request['m_stage'],
            'disease_g_stage' => $request['g_stage'],
            'disease_grade' => $request['grade'],
            'disease_stage' => $request['stage'],
            'disease_stageType' => $request['stage_type'],
            'disease_fullDiagnosis' => $request['full_diagnosis'],
        ]);

        // Get Static Table IDs
        $laterality_id = Laterality::where('laterality_name', $request['laterality'])->first()->laterality_id;
        $bodySite_id = BodySite::where('body_site_name', $request['primary_site'])->first()->body_site_id;
        $extent_id = DiseaseExtent::where('extent_name', $request['extent_of_disease'])->first()->extent_id;
        $basis_id = Basis::where('basis_name', $request['diagnosis_basis'])->first()->basis_id;

        // Update FK static fields
        $disease->update([
            'disease_primarySite' => $bodySite_id,
            'disease_laterality' => $laterality_id,
            'disease_basis' => $basis_id,
            'disease_extent' => $extent_id,
        ]);


        // Update FK dynamic fields
        // 1. METASTATIC SITE
        // Set metatstatic site boolean value
        if ($request['metastatic_site']=='None'){
            $metastaticSite_bool = false;
        } else {
            $metastaticSite_bool = true;
        }

        // Updating Metastatic Site Data
        $metastaticSite_mapping = [
            "Distant Lymph Nodes" =>'mets_distantLN',
            "Bone" => 'mets_bone',
            "Lung" => 'mets_lung',
            "Liver" => 'mets_liver',
            "Brain" => 'mets_brain',
            "Ovary" => 'mets_ovary',
            "Skin" => 'mets_skin',
            "Intestine" => 'mets_intestine',
            "Other" => 'mets_others',
            "Unknown" => 'mets_unknown'
        ];

        $metastaticSite_col = [
            'mets_distantLN' => false,
            'mets_bone' => false,
            'mets_liver' => false,
            'mets_lung' => false,
            'mets_brain' => false,
            'mets_ovary' => false,
            'mets_skin' => false,
            'mets_intestine' => false,
            'mets_others' => false,
            'mets_unknown' => false,
            'mets_others_site' => ''
        ];

        // Get Metastatic Site Record
        $metastaticSite = MetastaticSite::find($disease->disease_metastaticSite);

        // Update Metastatic Site
        if ($metastaticSite_bool) {
            // Map the request's metastatic_site to the appropriate database column
            $metastaticSiteValue = $request['metastatic_site'];
            if (isset($metastaticSite_mapping[$metastaticSiteValue])) {
                $metastaticSite_col[$metastaticSite_mapping[$metastaticSiteValue]] = true;
            }
        
            // Add "Other" metastatic site details if applicable
            if ($request->filled('other_metastatic_sites')) {
                $metastaticSite_col['mets_others_site'] = $request['other_metastatic_sites'];
            }
        
            // Add notes if provided
            if ($request->filled('metastatic_notes')) {
                $metastaticSite_col['mets_notes'] = $request['metastatic_notes'];
            }

            $metastaticSite->update($metastaticSite_col);
        }

        // 2. DISEASE STATUS      
        // Updating Disease Status Data
        $dxStatus_types = [
            'dxStatus_alive' => false,
            'dxStatus_symptoms' => false,
            'dxStatus_recurrence' => false,
            'dxStatus_metastatic' => false,
            'dxStatus_curative' => false
        ];

        $dxStatus_mapping = [
            "Alive" => 'dxStatus_alive',
            "Recurrent" => 'dxStatus_recurrence',
            "With symptoms" => 'dxStatus_symptoms',
            "Metastatic" => 'dxStatus_metastatic',
            "Curative" => 'dxStatus_curative'
        ];

        //Get Disease Status Record
        $diseaseStatus = DiseaseStatus::find($disease->disease_status);

        // Update Disease Status
        foreach ($request['disease_status'] as $status) {
            if (isset($dxStatus_mapping[$status])) {
                $dxStatus_types[$dxStatus_mapping[$status]] = true;
            }
        }

        $diseaseStatus->update($dxStatus_types);


        // 3. OTHER PRIMARY SITE
        // Updating Other Primary Site Data
        $op_types = [
            'op_Colon' => false,
            'op_Brain' => false,
            'op_Liver' => false,
            'op_UrinaryBladder' => false,
            'op_GallBladder' => false,
            'op_Thyroid' => false,
            'op_UterineCervix' => false,
            'op_CorpusUteri' => false,
            'op_Breast' => false,
            'op_Ovary' => false,
            'op_Blood' => false,
            'op_Lung' => false,
            'op_Esophagus' => false,
            'op_Kidney' => false,
            'op_Pancreas' => false,
            'op_OralCavity' => false,
            'op_Stomach' => false,
            'op_Skin' => false,
            'op_Nasopharynx' => false,
            'op_Testis' => false,
            'op_Prostate' => false,
            'op_Rectum' => false ,
            'op_Others' => false,
            'op_Others_Specify' => ''
        ];

        $op_mapping = [
            'Colon' => 'op_Colon',
            'Brain' => 'op_Brain',
            'Urinary bladder' => 'op_UrinaryBladder',
            'Gall bladder' => 'op_GallBladder',
            'Thyroid' => 'op_Thyroid',
            'Uterine Cervix' => 'op_UterineCervix',
            'Liver' => 'op_Liver',
            'Corpus Uteri' => 'op_CorpusUteri',
            'Breast' => 'op_Breast',
            'Blood' => 'op_Blood',
            'Ovary' => 'op_Ovary',
            'Lung' => 'op_Lung',
            'Esophagus' => 'op_Esophagus',
            'Kidney' => 'op_Kidney',
            'Oral Cavity' => 'op_OralCavity',
            'Stomach' => 'op_Stomach',
            'Pancreas' => 'op_Pancreas',
            'Skin' => 'op_Skin',
            'Nasopharynx' => 'op_Nasopharynx',
            'Testis' => 'op_Testis',
            'Prostate' => 'op_Prostate',
            'Rectum' => 'op_Rectum',
            'Others' => 'op_Others'
        ];

        // Get Other Primary Site Record
        $otherPrimarySite = OtherPrimary::find($disease->disease_otherSites);

        // Update Other Primary Site
        foreach($request['other_primary_sites'] as $opSite){
            if(isset($op_mapping[$opSite])){
                $op_types[$op_mapping[$opSite]] = true;
            }
        }

        if (in_array('Others', $request['other_primary_sites'])) {
            $op_types['op_Others_Specify'] = $request['additional_primary_site'];
        }

        $otherPrimarySite->update($op_types);

        // 4. HISTOLOGY
        // Get Histology Record
        $histology = Histology::find($disease->disease_histology);

        // Update Histology
        $histology->update([
            'histo_pathology' => $request['pathology'],
            'histo_tumorSize' => $request['tumor_size'],
            'histo_tumorExtension' => $request['hist_tumor_extension'],
            'histo_tumorGrade' => $request['hist_tumor_grade'],
            'histo_nodePositive' => $request['hist_nodepositive'],
            'histo_nodeHarvest' => $request['hist_nodeharvest'],
            'histo_negativeMargins' => $request['hist_negmargins'],
            'histo_stage' => $request['hist_stage'],
        ]);

        return Inertia::render('DiseaseProfilePage', [
            'message' => 'Disease record updated successfully.',
            'disease_id' => $disease->disease_id,
        ]);
    }
}
