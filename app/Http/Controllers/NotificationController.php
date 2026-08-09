<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = Auth::user()
            ->unreadNotifications()
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()
                ->unreadNotifications()
                ->count(),
        ]);
    }

    public function markAsRead(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead()
    {
        Auth::user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }
}