<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\NotificationLog;
use App\Models\SurveyResponse;

class NotificationController extends Controller
{
    public function getNotificationsByReceiver($userId)
    {
        $notifications = NotificationLog::where('notification_receiver', $userId)
            ->with([
                'notificationType',
                'notificationStatus',
                'sender',
                'surveyResponse',
                'labsSubmitted.workup'
            ])
            ->orderBy('notification_date', 'desc')
            ->get();

        foreach ($notifications as $notification) {
            if ($notification->surveyResponse) {
                $survey_response = SurveyResponse::where('patient_id', $notification->surveyResponse->patient_id)
                    ->with([
                        'patient.user',
                        'symptomSurveys'
                    ])
                    ->orderBy('surveyResponse_id', 'desc')
                    ->first();

                $notification->surveyResponse = $survey_response;
            }
        }

        return response()->json($notifications);
    }

    public function getNotificationById($notifLogId)
    {
        $notification = NotificationLog::where('notifLog_id', $notifLogId)
            ->with([
                'notificationType',
                'notificationStatus',
                'sender',
                'surveyResponse',
                'labsSubmitted.workup',
            ])
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notification log not found'], 404);
        }

        if ($notification->surveyResponse) {
            $survey_response = SurveyResponse::where('patient_id', $notification->surveyResponse->patient_id)
                ->with([
                    'patient.user',
                    'symptomSurveys',
                ])
                ->orderBy('surveyResponse_id', 'desc')
                ->first();

            $notification->surveyResponse = $survey_response;
        }

        return response()->json($notification);
    }

    public function updateNotificationStatus(Request $request, $userId)
    {
        // Validate the request
        $request->validate([
            'notifLog_id' => 'required|integer|exists:notification_logs,notifLog_id',
            'status' => 'required|integer' // Accept only 2 or 3
        ]);

        // Find the notification log
        $notificationLog = DB::table('notification_logs')
            ->where('notifLog_id', $request->input('notifLog_id'))
            ->where('notification_receiver', $userId)
            ->first();

        if (!$notificationLog) {
            return response()->json(['error' => 'Notification log not found or does not belong to the user'], 404);
        }

        // Update the notification status
        DB::table('notification_logs')
            ->where('notifLog_id', $request->input('notifLog_id'))
            ->update(['notification_status' => $request->input('status')]);

        $updatedNotificationLog = DB::table('notification_logs')
        ->where('notifLog_id', $request->input('notifLog_id'))
        ->first();

        return response()->json([$updatedNotificationLog], 200);
    }

    public function checkNotification(Request $request)
    {
        $data = $request->validate([
            'patientId' => 'required|integer',
            'date' => 'required|string',
            'type' => 'required|integer',
        ]);

        $patientId = $data['patientId'];
        $formattedDate = $data['date'];
        $type = $data['type'];

        $notificationExists = NotificationLog::where('notification_receiver', $patientId)
            ->where('notification_type', $type)
            ->where('notification_notes', 'LIKE', "%$formattedDate%")
            ->exists();

        return response()->json(['exists' => $notificationExists]);
    }

    public function createNotificationLog(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|email',
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'senderId' => 'nullable|integer',
            'receiverId' => 'nullable|integer',
            'notificationStatus' => 'nullable|integer',
            'notificationType' => 'nullable|integer',
            'surveyResponseId' => 'nullable|integer',
            'labSubmittedId' => 'nullable|integer',
        ]);

        try {
            DB::table('notification_logs')->insert([
                'notification_date' => now(),
                'notification_type' => $validated['notificationType'] ?? null,
                'notification_status' => $validated['notificationStatus'] ?? null,
                'notification_receiver' => $validated['receiverId'] ?? null,
                'notification_sender' => $validated['senderId'] ?? null,
                'notification_notes' => $validated['message'],
                'notification_subject' => $validated['subject'],
                'surveyResponse_id' => $validated['surveyResponseId'] ?? null,
                'labSubmitted_id' => $validated['labSubmittedId'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'Notification log created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Email could not be sent: ' . $e->getMessage()], 500);
        }
    }

}
