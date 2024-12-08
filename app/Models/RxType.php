<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rxtype extends Model
{
    use HasFactory;

    protected $table = 'rxtypes';
    protected $primaryKey = 'rxtype_id';

    protected $fillable = [
        'patient_id',
        'rxtype_surgery',
        'rxtype_chemotherapy',
        'rxtype_radiotherapy',
        'rxtype_immunotherapy',
        'rxtype_hormonaltherapy',
        'rxtype_others',
        'rxtype_other_treatment',
        'rxtype_notes',
        'rxtype_encoder',
    ];

    // In Rxtype model
    public function treatment()
    {
        return $this->hasOne(Treatment::class, 'treatment_plan', 'rxtype_id');
    }

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    
    public function primary()
    {
        return $this->hasOne(Treatment::class, 'rxtype_id');
    }

    public function radiotherapy()
    {
        return $this->hasOne(Radiotherapy::class, 'rxtype_id');
    }

    public function surgery()
    {
        return $this->hasOne(Surgery::class, 'rxtype_id');
    }

    public function chemotherapy()
    {
        return $this->hasOne(Chemotherapy::class, 'rxtype_id');
    }

    public function immunotherapy()
    {
        return $this->hasOne(Immunotherapy::class, 'rxtype_id');
    }

    public function hormonal()
    {
        return $this->hasOne(Hormonal::class, 'rxtype_id');
    }


}
