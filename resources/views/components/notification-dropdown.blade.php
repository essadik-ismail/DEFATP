@php
    $user = auth()->user();
    $recentNotifications = $user->notifications()->orderBy('created_at', 'desc')->limit(5)->get();
    $unreadCount = $user->notifications()->whereNull('read_at')->count();
@endphp

<div class="notification-dropdown">
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle notification-toggle" type="button" id="notificationDropdown" 
                data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            @if($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </button>
        
        <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown">
            <div class="notification-header">
                <h6 class="mb-0">Notifications</h6>
                @if($unreadCount > 0)
                    <small class="text-muted">{{ $unreadCount }} non lue(s)</small>
                @endif
            </div>
            
            <div class="notification-list">
                @forelse($recentNotifications as $notification)
                    <div class="notification-item {{ $notification->isUnread() ? 'unread' : 'read' }}" 
                         data-notification-id="{{ $notification->id }}">
                        <div class="notification-icon">
                            <i class="{{ $notification->icon }} text-{{ $notification->color }}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">{{ $notification->title }}</div>
                            <div class="notification-message">{{ Str::limit($notification->message, 50) }}</div>
                            <div class="notification-time">{{ $notification->time_ago }}</div>
                        </div>
                        @if($notification->isUnread())
                            <div class="notification-actions">
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="markAsRead('{{ $notification->id }}')"
                                        title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash text-muted"></i>
                        <p class="text-muted mb-0">Aucune notification</p>
                    </div>
                @endforelse
            </div>
            
            <div class="notification-footer">
                <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm">
                    Voir toutes les notifications
                </a>
                @if($unreadCount > 0)
                    <button class="btn btn-outline-success btn-sm" onclick="markAllAsRead()">
                        Marquer tout comme lu
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.notification-dropdown {
    position: relative;
}

.notification-toggle {
    position: relative;
    color: #6c757d;
    text-decoration: none;
    border: none;
    background: none;
    font-size: 1.2rem;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.notification-toggle:hover {
    color: #4a7c59;
    background-color: rgba(74, 124, 89, 0.1);
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.notification-menu {
    width: 350px;
    max-height: 500px;
    overflow-y: auto;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border-radius: 1rem;
    padding: 0;
}

.notification-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    background-color: #f8f9fa;
    border-radius: 1rem 1rem 0 0;
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.3s ease;
    position: relative;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #f0f8ff;
    border-left: 3px solid #4a7c59;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    flex-shrink: 0;
    font-size: 0.9rem;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    font-size: 0.9rem;
    color: #2c3e50;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.notification-message {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.notification-time {
    font-size: 0.75rem;
    color: #adb5bd;
}

.notification-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
}

.notification-empty {
    text-align: center;
    padding: 2rem 1.5rem;
}

.notification-empty i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.notification-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background-color: #f8f9fa;
    border-radius: 0 0 1rem 1rem;
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.notification-footer .btn {
    flex: 1;
    font-size: 0.8rem;
}

/* Custom scrollbar */
.notification-list::-webkit-scrollbar {
    width: 4px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');
                
                // Remove the mark as read button
                const markAsReadBtn = notificationItem.querySelector('button[onclick*="markAsRead"]');
                if (markAsReadBtn) {
                    markAsReadBtn.remove();
                }
            }
            
            // Update unread count
            updateUnreadCount(data.unread_count);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to update all notifications
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateUnreadCount(count) {
    const badge = document.querySelector('.notification-badge');
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            // Create badge if it doesn't exist
            const toggle = document.querySelector('.notification-toggle');
            const newBadge = document.createElement('span');
            newBadge.className = 'notification-badge';
            newBadge.textContent = count;
            toggle.appendChild(newBadge);
        }
    } else {
        if (badge) {
            badge.remove();
        }
    }
}

// Auto-refresh notifications every 30 seconds
setInterval(function() {
    fetch('/notifications/get?limit=5')
        .then(response => response.json())
        .then(data => {
            updateUnreadCount(data.unread_count);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}, 30000);
</script>
