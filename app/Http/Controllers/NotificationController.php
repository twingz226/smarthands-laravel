<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $limit = (int) $request->get('limit', 0);
        $perPage = (int) $request->get('per_page', 0);
        $page = (int) $request->get('page', 1);

        // Base query
        $query = $user->notifications()->orderBy('created_at', 'desc');

        // If pagination requested, use paginate; otherwise default to limit for bell dropdown
        if ($perPage > 0) {
            $paginator = $query->paginate($perPage, ['*'], 'page', max(1, $page));
            $items = $paginator->getCollection();
            $mapped = $items->map(function ($notification) {
                $data = is_array($notification->data) ? $notification->data : [];
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'message' => $data['message'] ?? null,
                    'link' => $data['link'] ?? null,
                    'booking_id' => $data['booking_id'] ?? null,
                    'customer_id' => $data['customer_id'] ?? null,
                    'created_at' => $notification->created_at,
                    'read_at' => $notification->read_at,
                ];
            })->values();

            // Dedupe by composite key (type | booking_id | message)
            $deduped = $mapped->unique(function ($n) {
                $type = $n['type'] ?? '';
                $bookingId = $n['booking_id'] ?? '';
                $customerId = $n['customer_id'] ?? '';
                $message = $n['message'] ?? '';

                // For new_customer notifications, use customer_id instead of booking_id
                if ($type === 'new_customer') {
                    return $type . '|' . $customerId . '|' . $message;
                }

                return $type . '|' . $bookingId . '|' . $message;
            })->values();

            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'notifications' => $deduped,
                // Duplicate under `data` for compatibility with some frontend code
                'data' => $deduped,
                'unread_count' => $unreadCount,
                'has_more' => $paginator->hasMorePages(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ]);
        }

        // Fallback: simple limit-based list (used by bell dropdown)
        $take = $limit > 0 ? $limit : 10;
        $mapped = $query->take($take)->get()->map(function ($notification) {
            $data = is_array($notification->data) ? $notification->data : [];
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $data['message'] ?? null,
                'link' => $data['link'] ?? null,
                'booking_id' => $data['booking_id'] ?? null,
                'customer_id' => $data['customer_id'] ?? null,
                'created_at' => $notification->created_at,
                'read_at' => $notification->read_at,
            ];
        })->values();

        // Dedupe by composite key (type | booking_id | message)
        $deduped = $mapped->unique(function ($n) {
            $type = $n['type'] ?? '';
            $bookingId = $n['booking_id'] ?? '';
            $customerId = $n['customer_id'] ?? '';
            $message = $n['message'] ?? '';

            // For new_customer notifications, use customer_id instead of booking_id
            if ($type === 'new_customer') {
                return $type . '|' . $customerId . '|' . $message;
            }

            return $type . '|' . $bookingId . '|' . $message;
        })->values();

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $deduped,
            'data' => $deduped,
            'unread_count' => $unreadCount,
            'has_more' => false,
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    }

    /**
     * Mark all notifications as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->markAllNotificationsAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.'
        ]);
    }

    /**
     * Get the count of unread notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCount(Request $request)
    {
        $count = $request->user()->unreadNotifications()->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }
}
