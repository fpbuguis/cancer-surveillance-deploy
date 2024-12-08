<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $table = 'treatments';
    protected $primaryKey = 'treatment_id';

    protected $fillable = [
        'patient_id',
        'treatment_primaryRxType',
        'treatment_primaryRxName',
        'treatment_initialRxDate',
        'treatment_purpose',
        'treatment_plan',
        'treatment_encoder',
        'treatment_other_purpose',
        'treatment_plan'
    ];

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Relationship with RxType
    public function plan()
    {
        return $this->belongsTo(RxType::class, 'treatment_plan');
    }

    // Relationship with User (encoder)
    // public function encoder()
    // {
    //     return $this->belongsTo(User::class, 'treatment_encoder');
    // }

    public function rxtype()
    {
        return $this->belongsTo(Rxtype::class, 'treatment_plan', 'rxtype_id');
    }
}
