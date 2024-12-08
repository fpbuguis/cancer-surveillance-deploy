<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $table = 'notification_logs';

    protected $primaryKey = 'notifLog_id';

    protected $fillable = [
        'notification_date',
        'notification_type',
        'notification_status',
        'notification_sender',
        'notification_receiver',
        'notification_notes',
        'notification_subject',
        'surveyResponse_id',
        'labSubmitted_id',
    ];

    // Relationships
    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class, 'notification_type', 'notifType_id');
    }

    public function notificationStatus()
    {
        return $this->belongsTo(NotificationStatus::class, 'notification_status', 'notifStatus_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'notification_sender', 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'notification_receiver', 'user_id');
    }

    public function surveyResponse()
    {
        return $this->belongsTo(SurveyResponse::class, 'surveyResponse_id', 'surveyResponse_id');
    }

    public function labsSubmitted()
    {
        return $this->belongsTo(LabsSubmitted::class, 'labSubmitted_id', 'labSubmitted_id');
    }

}
