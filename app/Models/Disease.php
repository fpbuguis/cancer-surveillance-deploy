<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Disease extends Model
{
    use HasFactory;

    protected $table = 'diseases';
    protected $primaryKey = 'disease_id';
    
    protected $fillable = [
        'patient_id',
        'disease_primarySite',
        'disease_otherPrimarySite',
        'disease_diagnosisDate',
        'disease_basis',
        'disease_laterality',
        'disease_histology',
        'disease_extent',
        'disease_tumorSize',
        'disease_lymphNode',
        'disease_metastatic',
        'disease_metastaticSite',
        'disease_multiplePrimary',
        'disease_otherSites',
        'disease_otherMultiPrimary', // wala naman yata nito ueue
        'disease_t_stage',
        'disease_n_stage',
        'disease_m_stage',
        'disease_g_stage',
        'disease_grade',
        'disease_stage',
        'disease_stageType',
        'disease_fullDiagnosis',
        'disease_status',
        'disease_encoder',
    ];

    
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function diseasePrimarySite()
    {
        return $this->belongsTo(BodySite::class, 'disease_primarySite', 'body_site_id');
    }

    public function diseaseBasis()
    {
        return $this->belongsTo(Basis::class, 'disease_basis', 'basis_id');
    }

    public function diseaseLaterality()
    {
        return $this->belongsTo(Laterality::class, 'disease_laterality', 'laterality_id');
    }

    public function diseaseHistology()
    {
        return $this->belongsTo(Histology::class, 'disease_histology', 'histology_id');
    }

    public function diseaseExtent()
    {
        return $this->belongsTo(DiseaseExtent::class, 'disease_extent', 'extent_id');
    }

    public function diseaseMetastaticSite()
    {
        return $this->belongsTo(MetastaticSite::class, 'disease_metastaticSite', 'mets_id');
    }

    public function diseaseOtherPrimarySite()
    {
        return $this->belongsTo(OtherPrimary::class, 'disease_otherSites', 'op_id');
    }

    public function diseaseStatus()
    {
        return $this->belongsTo(DiseaseStatus::class, 'disease_status', 'dxStatus_id');
    }

    public function diseaseEncoder()
    {
        return $this->belongsTo(User::class, 'disease_encoder', 'user_id'); // Correct relationship definition
    }
}
