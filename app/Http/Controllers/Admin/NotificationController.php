<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            "notifications" => auth_user("web")->notifications()->paginate(10)
        ]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        $notification->markAsRead();

        return response()->json([
            "markedAsRead" => true
        ]);
    }

    public function markAllAsRead()
    {
        auth_user("web")->unreadNotifications->markAsRead();

        return response()->json([
            "markedAsRead" => true
        ]);
    }
}
