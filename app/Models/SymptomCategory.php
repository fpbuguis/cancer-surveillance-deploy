<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'symptomCategory_id';

    public $timestamps = false; // No timestamps column in this table

    protected $fillable = [
        'symptom_category',
    ];

    public function symptomSurveys()
    {
        return $this->hasMany(SymptomSurvey::class, 'symptom_category', 'symptomCategory_id');
    }
}
