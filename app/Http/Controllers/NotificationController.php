<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate($request->get('per_page', 20));

        return response()->json($notifications);
    }

    public function unread(): JsonResponse
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->count(),
        ]);
    }

    public function markAsRead($id): JsonResponse
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);
        
        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification,
        ]);
    }

    public function markAllAsRead(): JsonResponse
    {
        Auth::user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);
        
        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully',
        ]);
    }

    public function destroyAll(): JsonResponse
    {
        Auth::user()->notifications()->delete();

        return response()->json([
            'message' => 'All notifications deleted successfully',
        ]);
    }

    public function getStats(): JsonResponse
    {
        $user = Auth::user();
        
        $totalCount = $user->notifications()->count();
        $unreadCount = $user->unreadNotifications()->count();
        $readCount = $totalCount - $unreadCount;

        return response()->json([
            'total' => $totalCount,
            'unread' => $unreadCount,
            'read' => $readCount,
        ]);
    }
}