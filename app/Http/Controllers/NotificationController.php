<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::where('recipient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = Notification::findOrFail($id);

        // Ensure user can only mark their own notifications as read
        if ($notification->recipient_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json($notification);
    }

    public function clearAll(Request $request)
    {
        $user = $request->user();

        Notification::where('recipient_id', $user->id)->delete();

        return response()->json(['message' => 'All notifications cleared']);
    }
}
