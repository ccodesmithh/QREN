<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications for the authenticated user.
     */
    public function unread()
    {
        $guru = Auth::guard('guru')->user();
        $notifications = Notification::where('guru_id', $guru->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? '',
                    'read' => $notification->read_at !== null,
                ];
            }),
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $guru = Auth::guard('guru')->user();
        $notification = Notification::where('guru_id', $guru->id)->where('id', $id)->firstOrFail();

        $notification->read_at = now();
        $notification->save();

        return response()->json(['success' => true]);
    }
}
