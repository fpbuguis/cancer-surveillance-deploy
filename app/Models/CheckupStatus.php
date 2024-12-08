<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckupStatus extends Model
{
    use HasFactory;

    // Table name (optional if matches naming conventions)
    protected $table = 'checkup_statuses';

    // Primary key (optional if matches naming conventions)
    protected $primaryKey = 'checkupStatus_id';

    // Disable timestamps if not using them
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'notifStatus_name',
    ];

    // Relationships
    public function checkupSchedules()
    {
        return $this->hasMany(CheckupSchedule::class, 'checkupStatus_id', 'checkupStatus_id');
    }
}
