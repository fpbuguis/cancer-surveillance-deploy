<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chemotherapy extends Model
{
    use HasFactory;

    protected $table = 'chemotherapies';
    protected $primaryKey = 'chemo_id';

    protected $fillable = [
        'patient_id',
        'chemo_type',
        'chemo_protocol',
        'chemo_initial_date',
        'chemo_end_date',
        'chemo_cycleNumGiven',
        'chemo_notes',
        'chemo_status',
        'chemo_isCompleted',
        'chemo_facility',
        'chemo_doctor',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'chemo_doctor', 'doctor_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'chemo_facility', 'hospital_id');
    }

    public function protocol()
    {
        return $this->belongsTo(Chemoprotocol::class, 'chemo_protocol', 'chemo_protocol_id');
    }
}
