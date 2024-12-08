<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workup extends Model
{
    use HasFactory;

    protected $primaryKey = 'workup_id';

    public $timestamps = false; // No timestamps column in this table

    protected $fillable = [
        'workup_name',
    ];

    public function labsDownloads()
    {
        return $this->hasMany(LabsDownload::class, 'workup_id');
    }

    public function labsSubmitted()
    {
        return $this->hasMany(LabsSubmitted::class, 'workup_id');
    }

    public function labMonitors()
    {
        return $this->hasMany(LabMonitor::class, 'workup_id');
    }
}
