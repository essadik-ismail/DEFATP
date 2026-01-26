@extends('layouts.app')

@section('title', 'Notifications - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="main-content">
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-bell me-2 text-primary"></i>Notifications
                </h1>
                <p class="text-muted mb-0">Gérez vos notifications et restez informé des dernières activités</p>
            </div>
            <div class="d-flex gap-2">
                @if($unreadCount > 0)
                    <button type="button" class="btn btn-outline-success" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-2"></i>Marquer tout comme lu
                    </button>
                @endif
                <a href="{{ route('notifications.settings') }}" class="btn btn-outline-primary">
                    <i class="fas fa-cog me-2"></i>Paramètres
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $notifications->total() }}</h3>
                    <p>Total Notifications</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $unreadCount }}</h3>
                    <p>Non lues</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $notifications->where('read_at', '!=', null)->count() }}</h3>
                    <p>Lues</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $notifications->where('created_at', '>=', today())->count() }}</h3>
                    <p>Aujourd'hui</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('notifications.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" name="type" id="type">
                        <option value="">Tous les types</option>
                        <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Succès</option>
                        <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Erreur</option>
                        <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Avertissement</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Information</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>Système</option>
                        <option value="exploitant" {{ request('type') == 'exploitant' ? 'selected' : '' }}>Exploitant</option>
                        <option value="foret" {{ request('type') == 'foret' ? 'selected' : '' }}>Forêt</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">Tous les statuts</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Non lues</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lues</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="priority" class="form-label">Priorité</label>
                    <select class="form-select" name="priority" id="priority">
                        <option value="">Toutes les priorités</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Urgent</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Important</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Effacer
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Liste des Notifications
            </h5>
        </div>
        <div class="card-body p-0">
            @if($notifications->count() > 0)
                <div class="notifications-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item {{ $notification->isUnread() ? 'unread' : 'read' }}" 
                             data-notification-id="{{ $notification->id }}">
                            <div class="notification-icon">
                                <i class="{{ $notification->icon }} text-{{ $notification->color }}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-header">
                                    <h6 class="notification-title">{{ $notification->title }}</h6>
                                    <div class="notification-actions">
                                        @if($notification->isUnread())
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="markAsRead('{{ $notification->id }}')"
                                                    title="Marquer comme lu">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteNotification('{{ $notification->id }}')"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="notification-message">{{ $notification->message }}</p>
                                <div class="notification-meta">
                                    <span class="notification-time">
                                        <i class="fas fa-clock me-1"></i>{{ $notification->time_ago }}
                                    </span>
                                    @if($notification->priority === 'high')
                                        <span class="badge bg-danger ms-2">
                                            <i class="fas fa-exclamation me-1"></i>Urgent
                                        </span>
                                    @elseif($notification->priority === 'medium')
                                        <span class="badge bg-warning ms-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Important
                                        </span>
                                    @endif
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-external-link-alt me-1"></i>Voir
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-section p-3">
                    {{ $notifications->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <i class="fas fa-bell-slash text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">Aucune notification</h5>
                    <p class="text-muted">Vous n'avez aucune notification pour le moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4a7c59 0%, #3d6b4a 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .stat-content h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
    }

    .stat-content p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .notifications-list {
        max-height: 600px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.3s ease;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item.unread {
        background-color: #f0f8ff;
        border-left: 4px solid #4a7c59;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .notification-title {
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
    }

    .notification-message {
        color: #6c757d;
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .notification-time {
        display: flex;
        align-items: center;
    }

    .empty-state {
        padding: 3rem;
    }

    .pagination-section {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
</style>
@endpush

@push('scripts')
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
            alert('Erreur lors de la mise à jour de la notification');
        });
    }

    function markAllAsRead() {
        if (confirm('Marquer toutes les notifications comme lues ?')) {
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
                alert('Erreur lors de la mise à jour des notifications');
            });
        }
    }

    function deleteNotification(notificationId) {
        if (confirm('Supprimer cette notification ?')) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
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
                        notificationItem.remove();
                    }
                    
                    // Update unread count
                    updateUnreadCount(data.unread_count);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la suppression de la notification');
            });
        }
    }

    function updateUnreadCount(count) {
        // Update unread count in the page
        const unreadElements = document.querySelectorAll('.unread-count');
        unreadElements.forEach(element => {
            element.textContent = count;
        });
        
        // Update the statistics card
        const unreadStatCard = document.querySelector('.stat-card:nth-child(2) h3');
        if (unreadStatCard) {
            unreadStatCard.textContent = count;
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
@endpush
