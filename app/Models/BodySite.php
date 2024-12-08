<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodySite extends Model
{
    use HasFactory;

    protected $table = 'body_sites';
    protected $primaryKey = 'body_site_id';

    public $timestamps = false;

    protected $fillable = [
        'body_site_name'
    ];

    // survey response
    
    public function symptomSurveys()
    {
        return $this->hasMany(SymptomSurvey::class, 'cancer_type', 'body_site_id');
    }

}
