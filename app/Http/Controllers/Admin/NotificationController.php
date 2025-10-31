<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @method User user()
 * @property User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $unreadNotifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $readNotifications
 * @method int unreadNotificationsCount()
 * @method bool hasUnreadNotifications()
 * @method int markNotificationsAsRead()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Notification notifications()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Notification unreadNotifications()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Notification readNotifications()
 */
class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get unread notifications count for the badge
        $unreadCount = $user->unreadNotifications()->count();
        
        // Get all notifications
        $notifications = $user->notifications()
            ->latest()
            ->get();
        
        // If this is an AJAX request, return JSON with all notifications
        if ($request->ajax() || $request->wantsJson()) {
            $notificationData = $notifications->map(function ($notification) {
                $data = is_array($notification->data) ? $notification->data : [];
                
                // Convert full class name to simple type
                $simpleType = $this->getSimpleNotificationType($notification->type);
                
                return [
                    'id' => $notification->id,
                    'type' => $simpleType,
                    'message' => $data['message'] ?? 'New notification',
                    'link' => $data['link'] ?? null,
                    'created_at' => $notification->created_at,
                    'read_at' => $notification->read_at,
                    'time_ago' => $notification->created_at?->diffForHumans(),
                    'is_read' => !is_null($notification->read_at),
                    'booking_id' => $data['booking_id'] ?? null,
                    'customer_id' => $data['customer_id'] ?? null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $notificationData,
                'notifications' => $notificationData,
                'unread_count' => $unreadCount,
                'total' => $notifications->count()
            ]);
        }
            
        return view('admin.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Mark a notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Mark a notification as read.
     *
     * @param int $id Notification ID
     * @return JsonResponse
     */
    public function markAsRead(int $id): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'success' => true,
                'unread_count' => $user->unreadNotifications()->count()
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Notification not found'
        ], 404);
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Mark all notifications as read.
     *
     * @return JsonResponse
     */
    public function markAllAsRead(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->markNotificationsAsRead();
        
        return response()->json([
            'success' => true,
            'unread_count' => 0
        ]);
    }

    /**
     * Get unread notifications count.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Get the count of unread notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Get the count of unread notifications.
     *
     * @return JsonResponse
     */
    public function unreadCount(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();
        
        return response()->json([
            'unread_count' => $count
        ]);
    }

    /**
     * Get notifications for the dropdown.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Get notifications for dropdown display.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotifications(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                $data = is_array($notification->data) ? $notification->data : [];
                return [
                    'id' => $notification->id,
                    'type' => $this->getSimpleNotificationType($notification->type),
                    'message' => $data['message'] ?? null,
                    'link' => $data['link'] ?? null,
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at?->diffForHumans(),
                    'is_read' => !is_null($notification->read_at),
                    'booking_id' => $data['booking_id'] ?? null,
                    'customer_id' => $data['customer_id'] ?? null,
                ];
            });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Clear all notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Clear all notifications for the authenticated user.
     *
     * @return JsonResponse
     */
    public function clearAll(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->notifications()->delete();
        
        return response()->json([
            'success' => true,
            'unread_count' => 0
        ]);
    }

    /**
     * Delete a notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Delete a specific notification.
     *
     * @param int $id Notification ID
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json([
                'success' => true,
                'unread_count' => $user->unreadNotifications()->count()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }
    
    /**
     * Convert full notification class name to simple type string.
     *
     * @param string $fullType
     * @return string
     */
    private function getSimpleNotificationType(string $fullType): string
    {
        // Map full class names to simple types
        $typeMap = [
            'App\Notifications\NewBookingNotification' => 'booking_created',
            'App\Notifications\NewCustomerNotification' => 'new_customer',
            'booking_created' => 'booking_created',
            'booking_cancelled' => 'booking_cancelled',
            'booking_rescheduled' => 'booking_rescheduled',
            'new_customer' => 'new_customer',
        ];
        
        return $typeMap[$fullType] ?? 'default';
    }
}
