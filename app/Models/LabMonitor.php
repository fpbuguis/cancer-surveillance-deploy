<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabMonitor extends Model
{
    use HasFactory;

    protected $primaryKey = 'labMonitor_id';

    protected $fillable = [
        'cancer_type',
        'workup_id',
        'workup_frequency',
        'workupType_id',
        'workup_indication',
        'workup_duration',
        'workup_referral',
    ];

    public function cancerType()
    {
        return $this->belongsTo(BodySite::class, 'cancer_type', 'body_site_id');
    }

    public function workup()
    {
        return $this->belongsTo(Workup::class, 'workup_id');
    }

    public function workupType()
    {
        return $this->belongsTo(WorkupType::class, 'workupType_id');
    }
}
