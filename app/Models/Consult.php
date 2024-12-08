<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consult extends Model
{
    use HasFactory;

    protected $table = 'consults';
    protected $primaryKey = 'consult_id';

    protected $fillable = [
        'patient_id',
        'consult_subjective',
        'consult_objective',
        'consult_surveillance_workup',
        'consult_assessment',
        'consult_plan',
        'consult_disease_status',
        'consult_patient_status',
    ];
}
