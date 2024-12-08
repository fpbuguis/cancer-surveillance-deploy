<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabsSubmitted extends Model
{
    use HasFactory;

    protected $primaryKey = 'labSubmitted_id';

    protected $table = 'labs_submitted';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'labSubmission_date',
        'workup_id',
        'labFileSubmissions',
        'labSubmission_notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function workup()
    {
        return $this->belongsTo(Workup::class, 'workup_id');
    }
}
