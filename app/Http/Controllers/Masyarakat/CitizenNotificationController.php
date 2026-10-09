<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CitizenNotificationController extends Controller
{
    /**
     * Display all notifications for authenticated citizen.
     */
    public function index(): View
    {
        $user = Auth::user();
        $desa = DesaProfile::current();

        $notifications = $user->notifications()->paginate(15);
        $unreadCount   = $user->unreadNotifications()->count();

        return view('masyarakat.notifikasi.index', compact('user', 'desa', 'notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read and redirect to its action URL.
     */
    public function read(string $id): RedirectResponse
    {
        $user         = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $actionUrl = $notification->data['action_url'] ?? route('masyarakat.notifikasi.index');

        return redirect($actionUrl);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
