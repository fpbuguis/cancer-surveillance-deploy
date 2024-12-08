<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetastaticSite extends Model
{
    use HasFactory;

    protected $table = 'metastatic_sites';
    protected $primaryKey = 'mets_id';

    protected $fillable = [
        'patient_id',
        'mets_distantLN',
        'mets_bone',
        'mets_liver',
        'mets_lung',
        'mets_brain',
        'mets_ovary',
        'mets_skin',
        'mets_intestine',
        'mets_others',
        'mets_others_site',
        'mets_unknown',
        'mets_notes'
    ];
}
