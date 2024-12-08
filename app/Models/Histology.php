<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histology extends Model
{
    use HasFactory;

    protected $table = 'histologies';
    protected $primaryKey = 'histology_id';

    protected $fillable = [
        'patient_id',
        'histo_pathology',
        'histo_tumorSize',
        'histo_tumorExtension',
        'histo_tumorGrade',
        'histo_nodePositive',
        'histo_nodeHarvest',
        'histo_negativeMargins',
        'histo_stage',
        'histo_encoder',
    ];

    // public function pathology()
    // {
    //     return $this->belongsTo(Pathology::class, 'histo_pathology', 'term'); // Make sure to relate by term
    // }

    // Accessor for dynamically retrieving histo_pathology based on term
    // public function getHistoPathologyAttribute()
    // {
    //     return $this->pathology ? $this->pathology->term : null;
    // }

    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'histo_pathology', 'pathology_id'); // Make sure to relate by term
    }
}
