<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    use HasFactory;

    protected $table = 'surgeries';
    protected $primaryKey = 'surgery_id';

    protected $fillable = [
        'patient_id',
        'surgery_operation',
        'surgery_date',
        'surgery_findings',
        'surgery_intent',
        'surgery_otherIntent',
        'surgery_surgeon',
        'surgery_hospital',
        'surgery_encoder'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'surgery_surgeon', 'doctor_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'surgery_hospital', 'hospital_id');
    }
}
