<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPrimary extends Model
{
    use HasFactory;

    protected $table = 'other_primaries';
    protected $primaryKey = 'op_id';

    protected $fillable = [
        'patient_id',
        'op_Colon',
        'op_Brain',
        'op_Liver',
        'op_UrinaryBladder',
        'op_GallBladder',
        'op_Thyroid',
        'op_UterineCervix',
        'op_CorpusUteri',
        'op_Breast',
        'op_Ovary',
        'op_Blood',
        'op_Lung',
        'op_Esophagus',
        'op_Kidney',
        'op_Pancreas',
        'op_OralCavity',
        'op_Stomach',
        'op_Skin',
        'op_Nasopharynx',
        'op_Testis',
        'op_Prostate',
        'op_Rectum',
        'op_Others',
        'op_Others_Specify'
    ];
}
