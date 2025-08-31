<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->systemNotifications->sortByDesc('created_at')->take(5);
        return view('notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $notification = $user->systemNotifications->findOrFail($id);

        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }

        return view('notifications.show', compact('notification'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->systemNotifications->findOrFail($id);

        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->systemNotifications
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back();
    }
}
