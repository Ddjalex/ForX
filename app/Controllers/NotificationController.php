<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\NotificationService;
use App\Services\Router;
use App\Middleware\AuthMiddleware;

class NotificationController
{
    public function getUnread(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $userId = Auth::id();
        $notifications = NotificationService::getUnreadNotifications($userId, 10);
        $unreadCount = NotificationService::getUnreadCount($userId);

        foreach ($notifications as &$n) {
            $n['time_ago'] = NotificationService::timeAgo($n['created_at']);
        }

        echo json_encode([
            'success' => true,
            'count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    public function getAll(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $userId = Auth::id();
        $notifications = NotificationService::getNotifications($userId, 20);

        foreach ($notifications as &$n) {
            $n['time_ago'] = NotificationService::timeAgo($n['created_at']);
        }

        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $notificationId = filter_input(INPUT_POST, 'notification_id', FILTER_VALIDATE_INT);

        if (!$notificationId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid notification ID']);
            return;
        }

        NotificationService::markAsRead($notificationId);

        echo json_encode([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllAsRead(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        NotificationService::markAllAsRead(Auth::id());

        echo json_encode([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    public function delete(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $notificationId = filter_input(INPUT_POST, 'notification_id', FILTER_VALIDATE_INT);

        if (!$notificationId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid notification ID']);
            return;
        }

        NotificationService::delete($notificationId);

        echo json_encode([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }

    public function deleteAll(): void
    {
        header('Content-Type: application/json');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        NotificationService::deleteAll(Auth::id());

        echo json_encode([
            'success' => true,
            'message' => 'All notifications deleted',
        ]);
    }
}
