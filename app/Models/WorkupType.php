<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkupType extends Model
{
    use HasFactory;

    protected $primaryKey = 'workupType_id';

    public $timestamps = false; // No timestamps column in this table

    protected $fillable = [
        'workupType_name',
    ];

    public function labMonitors()
    {
        return $this->hasMany(LabMonitor::class, 'workupType_id');
    }
}
