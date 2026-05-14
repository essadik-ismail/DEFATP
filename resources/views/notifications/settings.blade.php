@extends('layouts.app')

@section('title', 'Paramètres des Notifications - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('notifications.index') }}">Notifications</a></li>
<li class="bc-item active">Paramètres</li>
@endsection

@section('content')
<div class="main-content">
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-cog mr-2 text-primary"></i>Paramètres des Notifications
                </h1>
                <p class="text-muted mb-0">Configurez vos préférences de notification</p>
            </div>
            <a href="{{ route('notifications.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux Notifications
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Notification Preferences -->
        <div class="w-2/3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell mr-2"></i>Préférences de Notification
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('notifications.update-settings') }}">
                        @csrf
                        @method('PUT')

                        <!-- Email Notifications -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_notifications" 
                                       name="email_notifications" value="1" 
                                       {{ $user->email_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    <strong>Notifications par Email</strong>
                                    <p class="text-muted mb-0">Recevoir des notifications importantes par email</p>
                                </label>
                            </div>
                        </div>

                        <!-- Push Notifications -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="push_notifications" 
                                       name="push_notifications" value="1" 
                                       {{ $user->push_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_notifications">
                                    <strong>Notifications Push</strong>
                                    <p class="text-muted mb-0">Recevoir des notifications push dans le navigateur</p>
                                </label>
                            </div>
                        </div>

                        <!-- Notification Types -->
                        <div class="mb-4">
                            <h6 class="mb-3">Types de Notifications</h6>
                            <div class="row">
                                <div class="w-1/2">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="type_success" 
                                               name="notification_types[]" value="success"
                                               {{ in_array('success', $user->notification_types ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_success">
                                            <i class="fas fa-check-circle text-success mr-2"></i>Succès
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="type_error" 
                                               name="notification_types[]" value="error"
                                               {{ in_array('error', $user->notification_types ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_error">
                                            <i class="fas fa-exclamation-circle text-danger mr-2"></i>Erreurs
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="type_warning" 
                                               name="notification_types[]" value="warning"
                                               {{ in_array('warning', $user->notification_types ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_warning">
                                            <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Avertissements
                                        </label>
                                    </div>
                                </div>
                                <div class="w-1/2">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="type_info" 
                                               name="notification_types[]" value="info"
                                               {{ in_array('info', $user->notification_types ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_info">
                                            <i class="fas fa-info-circle text-info mr-2"></i>Informations
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="type_system" 
                                               name="notification_types[]" value="system"
                                               {{ in_array('system', $user->notification_types ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_system">
                                            <i class="fas fa-cog text-secondary mr-2"></i>Système
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="type_exploitant" 
                                               name="notification_types[]" value="exploitant"
                                               {{ in_array('exploitant', $user->notification_types ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_exploitant">
                                            <i class="fas fa-user-tie text-primary mr-2"></i>Exploitants
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-content-end gap-2">
                            <a href="{{ route('notifications.index') }}" class="btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Test Notifications -->
        <div class="w-1/3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-flask mr-2"></i>Test des Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <form id="testNotificationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="test_type" class="form-label">Type de Notification</label>
                            <select class="form-select" id="test_type" name="type" required>
                                <option value="success">Succès</option>
                                <option value="error">Erreur</option>
                                <option value="warning">Avertissement</option>
                                <option value="info">Information</option>
                                <option value="system">Système</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="test_title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="test_title" name="title" 
                                   placeholder="Titre de la notification" required>
                        </div>
                        <div class="mb-3">
                            <label for="test_message" class="form-label">Message</label>
                            <textarea class="form-control" id="test_message" name="message" rows="3" 
                                      placeholder="Message de la notification" required></textarea>
                        </div>
                        <button type="submit" class="btn-outline w-100">
                            <i class="fas fa-paper-plane mr-2"></i>Envoyer Test
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notification Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar mr-2"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-label">Notifications totales</div>
                        <div class="stat-value">{{ $user->notifications()->count() }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Non lues</div>
                        <div class="stat-value text-warning">{{ $user->notifications()->whereNull('read_at')->count() }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Lues</div>
                        <div class="stat-value text-success">{{ $user->notifications()->whereNotNull('read_at')->count() }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Aujourd'hui</div>
                        <div class="stat-value text-info">{{ $user->notifications()->whereDate('created_at', today())->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt mr-2"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success" onclick="markAllAsRead()">
                            <i class="fas fa-check-double mr-2"></i>Marquer tout comme lu
                        </button>
                        <button type="button" class="btn-danger" onclick="deleteReadNotifications()">
                            <i class="fas fa-trash mr-2"></i>Supprimer les lues
                        </button>
                        <a href="{{ route('notifications.index') }}" class="btn-outline">
                            <i class="fas fa-list mr-2"></i>Voir toutes les notifications
                        </a>
                    </div>
                </div>
            </div>
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

    .form-check-label {
        font-weight: 500;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .stat-value {
        font-weight: 600;
        font-size: 1.1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Test notification form
    document.getElementById('testNotificationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/notifications/send-test', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notification de test envoyée avec succès !');
                this.reset();
            } else {
                alert('Erreur lors de l\'envoi de la notification de test');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'envoi de la notification de test');
        });
    });

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
                    alert('Toutes les notifications ont été marquées comme lues');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour des notifications');
            });
        }
    }

    function deleteReadNotifications() {
        if (confirm('Supprimer toutes les notifications lues ? Cette action est irréversible.')) {
            fetch('/notifications/delete-read', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`${data.deleted_count} notifications ont été supprimées`);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la suppression des notifications');
            });
        }
    }
</script>
@endpush
