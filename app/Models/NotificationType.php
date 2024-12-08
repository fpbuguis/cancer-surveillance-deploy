<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    use HasFactory;

    protected $table = 'notification_types';

    protected $primaryKey = 'notifType_id';

    public $timestamps = false;

    protected $fillable = [
        'notificationType_name',
    ];

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class, 'notification_type', 'notifType_id');
    }
}
