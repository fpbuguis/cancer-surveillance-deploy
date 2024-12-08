<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationStatus extends Model
{
    use HasFactory;

    protected $table = 'notification_statuses';

    protected $primaryKey = 'notifStatus_id';

    public $timestamps = false;

    protected $fillable = [
        'notifStatus_name',
    ];

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class, 'notification_status', 'notifStatus_id');
    }
}
