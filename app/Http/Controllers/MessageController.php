<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function sendEmail(Request $request)
    {
        // Validate the request, including additional fields

        // dd($request);
        $validated = $request->validate([
            'doctor' => 'nullable|string',
            'hospital' => 'nullable|string',
            'patient' => 'nullable|string',
            'from' => 'required|email',
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'senderId' => 'nullable|integer',
            'receiverId' => 'nullable|integer',
            'notificationStatus' => 'nullable|integer',
            'notificationType' => 'nullable|integer',
        ]);

        // Construct the signature
        $signature = "<br><br>--<br>";
        if (isset($validated['doctor'])) {
            $signature .= $validated['doctor'] . "<br>" . $validated['hospital'];
        } elseif (isset($validated['patient'])) {
            $signature .= $validated['patient'];
        }

        $notificationNote = "<br><br><span style='color: gray;'>This is an automated notification. Please do not reply to this email.</span>";

        $fullMessage = $validated['message'] . $signature . $notificationNote;

        try {
            // Send the email
            Mail::html($fullMessage, function ($mail) use ($validated) {
                $mail->to($validated['to'])
                    ->from($validated['from'])
                    ->subject($validated['subject']);
            });

            // Insert a record into the notification_logs table
            DB::table('notification_logs')->insert([
                'notification_date' => now(),
                'notification_type' => $validated['notificationType'],
                'notification_status' => $validated['notificationStatus'],
                'notification_receiver' => $validated['receiverId'],
                'notification_sender' => $validated['senderId'],
                'notification_notes' => $validated['message'],
                'notification_subject' => $validated['subject'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'Email sent created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Email could not be sent: ' . $e->getMessage()], 500);
        }
    }

}
