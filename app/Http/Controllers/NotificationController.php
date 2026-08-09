<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()
                ->unreadNotifications()
                ->count(),
        ]);
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }
}