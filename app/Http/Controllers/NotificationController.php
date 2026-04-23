<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $query = AppNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $notifications = $query->paginate(20)->withQueryString();

        // Get unread count
        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        // Get recent notifications for sidebar
        $recentNotifications = $this->notificationService->getRecentNotifications($user->id, 5);

        return view('notifications.index', compact('notifications', 'unreadCount', 'recentNotifications'));
    }

    /**
     * Get notifications for AJAX requests (for real-time updates).
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $notifications = AppNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($request->get('limit', 10))
            ->get();

        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = auth()->user();
        
        $success = $this->notificationService->markAsRead($id, $user->id);
        
        if ($success) {
            $unreadCount = $this->notificationService->getUnreadCount($user->id);
            
            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $this->notificationService->markAllAsRead($user->id);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = auth()->user();
        
        $notification = AppNotification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($notification) {
            $notification->delete();
            
            $unreadCount = $this->notificationService->getUnreadCount($user->id);
            
            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }

    /**
     * Delete all read notifications for the authenticated user.
     */
    public function deleteRead(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $deletedCount = AppNotification::where('user_id', $user->id)
            ->whereNotNull('read_at')
            ->delete();

        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
            'message' => "{$deletedCount} notifications deleted"
        ]);
    }

    /**
     * Show notification settings.
     */
    public function settings(): View
    {
        $user = auth()->user();
        
        return view('notifications.settings', compact('user'));
    }

    /**
     * Update notification settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_types' => 'array',
            'notification_types.*' => 'string|in:success,error,warning,info,system,user,exploitant,foret,article',
        ]);

        // Update user notification preferences
        $user->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'push_notifications' => $request->boolean('push_notifications'),
            'notification_types' => $request->get('notification_types', []),
        ]);

        return redirect()->route('notifications.settings')
            ->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Get notification statistics for admin dashboard.
     */
    public function statistics(): JsonResponse
    {
        $user = auth()->user();
        
        // Check if user has admin permissions
        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = [
            'total_notifications' => AppNotification::count(),
            'unread_notifications' => AppNotification::whereNull('read_at')->count(),
            'notifications_today' => AppNotification::whereDate('created_at', today())->count(),
            'notifications_this_week' => AppNotification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'notifications_by_type' => AppNotification::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'notifications_by_priority' => AppNotification::selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
        ];

        return response()->json($stats);
    }

    /**
     * Send a test notification.
     */
    public function sendTest(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $request->validate([
            'type' => 'required|string|in:success,error,warning,info,system',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $notification = $this->notificationService->sendToUser(
            $user,
            $request->type,
            $request->title,
            $request->message,
            ['test' => true]
        );

        return response()->json([
            'success' => true,
            'notification' => $notification,
            'message' => 'Test notification sent successfully'
        ]);
    }
}
