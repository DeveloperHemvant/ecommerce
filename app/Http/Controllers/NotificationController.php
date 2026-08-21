<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Full paginated notification history for the account area.
     */
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->paginate(20);

        return view('account.notifications', compact('notifications'));
    }

    /**
     * Latest notifications for the header bell dropdown, as JSON.
     */
    public function recent(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $user->notifications()->latest()->limit(8)->get()->map(fn ($n) => [
                'id' => $n->id,
                'read' => $n->read_at !== null,
                'created_at' => $n->created_at->diffForHumans(),
                ...$n->data,
            ]),
        ]);
    }

    /**
     * Mark a single notification as read and send the customer to its target.
     */
    public function markRead(Request $request, string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('account.notifications'));
    }

    /**
     * Mark every notification as read.
     */
    public function markAllRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
