<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabsDownload extends Model
{
    use HasFactory;

    protected $primaryKey = 'labReq_id';

    protected $fillable = [
        'patient_id',
        'workup_id',
        'labReq_date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function workup()
    {
        return $this->belongsTo(Workup::class, 'workup_id');
    }
}
