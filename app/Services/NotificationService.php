<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a notification to a specific user.
     */
    public function sendToUser(User $user, string $type, string $title, string $message, array $data = [], array $options = [])
    {
        $notification = AppNotification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'user_id' => $user->id,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'action_url' => $options['action_url'] ?? null,
            'icon' => $options['icon'] ?? null,
            'color' => $options['color'] ?? null,
            'priority' => $options['priority'] ?? 'medium',
        ]);

        // Log the notification
        Log::info('Notification sent', [
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
        ]);

        return $notification;
    }

    /**
     * Send a notification to multiple users.
     */
    public function sendToUsers(array $userIds, string $type, string $title, string $message, array $data = [], array $options = [])
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $notifications[] = $this->sendToUser($user, $type, $title, $message, $data, $options);
            }
        }

        return $notifications;
    }

    /**
     * Send a notification to all users.
     */
    public function sendToAllUsers(string $type, string $title, string $message, array $data = [], array $options = [])
    {
        $userIds = User::pluck('id')->toArray();
        return $this->sendToUsers($userIds, $type, $title, $message, $data, $options);
    }

    /**
     * Send a notification to users with specific roles.
     */
    public function sendToUsersWithRole(string $role, string $type, string $title, string $message, array $data = [], array $options = [])
    {
        $userIds = User::role($role)->pluck('id')->toArray();
        return $this->sendToUsers($userIds, $type, $title, $message, $data, $options);
    }

    /**
     * Send a system notification.
     */
    public function sendSystemNotification(string $title, string $message, array $data = [], array $options = [])
    {
        return $this->sendToAllUsers('system', $title, $message, $data, array_merge($options, [
            'priority' => 'high',
            'icon' => 'fas fa-cog',
            'color' => 'secondary'
        ]));
    }

    /**
     * Send a success notification.
     */
    public function sendSuccessNotification(User $user, string $title, string $message, array $data = [], array $options = [])
    {
        return $this->sendToUser($user, 'success', $title, $message, $data, array_merge($options, [
            'icon' => 'fas fa-check-circle',
            'color' => 'success'
        ]));
    }

    /**
     * Send an error notification.
     */
    public function sendErrorNotification(User $user, string $title, string $message, array $data = [], array $options = [])
    {
        return $this->sendToUser($user, 'error', $title, $message, $data, array_merge($options, [
            'priority' => 'high',
            'icon' => 'fas fa-exclamation-circle',
            'color' => 'danger'
        ]));
    }

    /**
     * Send a warning notification.
     */
    public function sendWarningNotification(User $user, string $title, string $message, array $data = [], array $options = [])
    {
        return $this->sendToUser($user, 'warning', $title, $message, $data, array_merge($options, [
            'icon' => 'fas fa-exclamation-triangle',
            'color' => 'warning'
        ]));
    }

    /**
     * Send an info notification.
     */
    public function sendInfoNotification(User $user, string $title, string $message, array $data = [], array $options = [])
    {
        return $this->sendToUser($user, 'info', $title, $message, $data, array_merge($options, [
            'icon' => 'fas fa-info-circle',
            'color' => 'info'
        ]));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(int $notificationId, int $userId)
    {
        $notification = AppNotification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(int $userId)
    {
        return AppNotification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Get unread notifications count for a user.
     */
    public function getUnreadCount(int $userId)
    {
        return AppNotification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get recent notifications for a user.
     */
    public function getRecentNotifications(int $userId, int $limit = 10)
    {
        return AppNotification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete old notifications (older than specified days).
     */
    public function deleteOldNotifications(int $days = 30)
    {
        $cutoffDate = now()->subDays($days);
        
        return AppNotification::where('created_at', '<', $cutoffDate)
            ->delete();
    }

    /**
     * Send exploitant-related notification.
     */
    public function sendExploitantNotification(User $user, string $action, $exploitant, array $options = [])
    {
        $title = match($action) {
            'created' => 'Nouvel Exploitant Créé',
            'updated' => 'Exploitant Modifié',
            'deleted' => 'Exploitant Supprimé',
            'excluded' => 'Exploitant Exclu',
            'reactivated' => 'Exploitant Réactivé',
            default => 'Action sur Exploitant'
        };

        $message = match($action) {
            'created' => "Un nouvel exploitant '{$exploitant->nom_complet}' a été créé.",
            'updated' => "L'exploitant '{$exploitant->nom_complet}' a été modifié.",
            'deleted' => "L'exploitant '{$exploitant->nom_complet}' a été supprimé.",
            'excluded' => "L'exploitant '{$exploitant->nom_complet}' a été exclu.",
            'reactivated' => "L'exploitant '{$exploitant->nom_complet}' a été réactivé.",
            default => "Une action a été effectuée sur l'exploitant '{$exploitant->nom_complet}'."
        };

        return $this->sendToUser($user, 'exploitant', $title, $message, [
            'exploitant_id' => $exploitant->id,
            'action' => $action
        ], array_merge($options, [
            'action_url' => route('settings.exploitants.show', $exploitant),
            'icon' => 'fas fa-user-tie',
            'color' => 'primary'
        ]));
    }

    /**
     * Send forest-related notification.
     */
    public function sendForetNotification(User $user, string $action, $foret, array $options = [])
    {
        $title = match($action) {
            'created' => 'Nouvelle Forêt Ajoutée',
            'updated' => 'Forêt Modifiée',
            'deleted' => 'Forêt Supprimée',
            default => 'Action sur Forêt'
        };

        $message = match($action) {
            'created' => "Une nouvelle forêt '{$foret->nom}' a été ajoutée.",
            'updated' => "La forêt '{$foret->nom}' a été modifiée.",
            'deleted' => "La forêt '{$foret->nom}' a été supprimée.",
            default => "Une action a été effectuée sur la forêt '{$foret->nom}'."
        };

        return $this->sendToUser($user, 'foret', $title, $message, [
            'foret_id' => $foret->id,
            'action' => $action
        ], array_merge($options, [
            'action_url' => route('settings.forets.show', $foret),
            'icon' => 'fas fa-tree',
            'color' => 'success'
        ]));
    }
}
