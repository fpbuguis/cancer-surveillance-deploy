<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseStatus extends Model
{
    use HasFactory;

    protected $table = 'disease_statuses';
    protected $primaryKey = 'dxStatus_id';

    protected $fillable = [
        'patient_id',
        'dxStatus_alive',
        'dxStatus_symptoms',
        'dxStatus_recurrence',
        'dxStatus_metastatic',
        'dxStatus_curative'
    ];
}
