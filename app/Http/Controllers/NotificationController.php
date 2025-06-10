<?php

namespace App\Http\Controllers;

// app/Http/Controllers/NotificationController.php

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications;
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
   
        $notification = auth()->user()->unreadNotifications->firstWhere('id', $id);

if (!$notification) {
    abort(404, 'Notification not found or already read.');
}

$notification->markAsRead();
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications->firstWhere('id', $id);

if (!$notification) {
    abort(404, 'Notification not found.');
}

$notification->delete();
        return redirect()->back()->with('success', 'Notification deleted.');
    }
}

