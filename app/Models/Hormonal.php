<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hormonal extends Model
{
    use HasFactory;

    protected $table = 'hormonals';
    protected $primaryKey = 'hormonal_id';

    protected $fillable = [
        'patient_id',
        'hormonal_drugs',
        'hormonal_dose',
        'hormonal_initial_date',
        'hormonal_end_date',
        'hormonal_status',
        'hormonal_notes',
        'hormonal_doctor'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'hormonal_doctor', 'doctor_id');
    }
}
