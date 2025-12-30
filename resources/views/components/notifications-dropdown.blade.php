@php
$notifications = auth()->check() ? auth()->user()->unreadNotifications()->limit(5)->get() : collect();
$unreadCount = $notifications->count();
@endphp

<div class="relative" x-data="{ isOpen: false, isLoading: false, notifications: {{ $notifications->toJson() }}, unreadCount: {{ $unreadCount }} }" @click.outside="isOpen = false">
    <!-- Notification Bell -->
    <button @click="isOpen = !isOpen; if(isOpen && unreadCount > 0) { markAllAsRead(); }" 
            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md relative">
        <i class="fas fa-bell text-lg sm:text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            <span class="sr-only">
                {{ $unreadCount }} {{ $unreadCount === 1 ? 'notification non lue' : 'notifications non lues' }}
            </span>
        @else
            <span class="sr-only">Aucune notification</span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50 border border-gray-200"
         style="display: none;">
        <div class="bg-white">
            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">
                    Notifications
                    <span x-show="unreadCount > 0" x-text="'(' + unreadCount + ')'" class="text-xs text-gray-500"></span>
                </h3>
                <div class="flex space-x-2">
                    <button @click="markAllAsRead()" 
                            class="text-xs text-blue-600 hover:text-blue-800"
                            x-bind:disabled="unreadCount === 0 || isLoading">
                        <span x-show="!isLoading">Tout marquer comme lu</span>
                        <span x-show="isLoading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    <a href="{{ route('notifications.index') }}" class="text-xs text-gray-600 hover:text-gray-800">
                        Voir tout
                    </a>
                </div>
            </div>
            
            <!-- Notifications list -->
            <div class="max-h-96 overflow-y-auto">
                <template x-if="notifications.length === 0">
                    <div class="p-4 text-center text-sm text-gray-500">
                        Aucune notification
                    </div>
                </template>
                
                <template x-for="(notification, index) in notifications" :key="notification.id">
                    <a :href="notification.data.action_url || '#'" 
                       class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0"
                       :class="{ 'bg-blue-50': notification.is_unread }"
                       @click="markAsRead(notification.id, $event)">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5">
                                <i :class="notification.data.icon || 'fas fa-bell'" 
                                   class="h-5 w-5" 
                                   :class="{
                                       'text-green-500': notification.type === 'success',
                                       'text-red-500': notification.type === 'error',
                                       'text-yellow-500': notification.type === 'warning',
                                       'text-blue-500': notification.type === 'info',
                                       'text-gray-500': !['success', 'error', 'warning', 'info'].includes(notification.type)
                                   }">
                                </i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900" x-text="notification.data.title || 'Notification'"></p>
                                <p class="mt-1 text-sm text-gray-500" x-text="notification.data.message || notification.message"></p>
                                <p class="mt-1 text-xs text-gray-400" x-text="formatTimeAgo(notification.created_at)"></p>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
            
            <!-- Footer -->
            <div class="px-4 py-2 bg-gray-50 text-center border-t border-gray-200">
                <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                    Voir toutes les notifications
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notifications', () => ({
        init() {
            // Set up event listeners for real-time updates
            this.setupEventListeners();
            
            // Mark notifications as read when dropdown is opened
            this.$watch('isOpen', value => {
                if (value && this.unreadCount > 0) {
                    this.markAllAsRead();
                }
            });
        },
        
        formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) return `il y a ${interval} an${interval > 1 ? 's' : ''}`;
            
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) return `il y a ${interval} mois`;
            
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) return `il y a ${interval} jour${interval > 1 ? 's' : ''}`;
            
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) return `il y a ${interval} heure${interval > 1 ? 's' : ''}`;
            
            interval = Math.floor(seconds / 60);
            if (interval >= 1) return `il y a ${interval} minute${interval > 1 ? 's' : ''}`;
            
            return 'à l\'instant';
        },
        
        async markAsRead(notificationId, event) {
            if (event) {
                event.preventDefault();
                const url = event.currentTarget.href;
                
                try {
                    const response = await fetch(`/notifications/${notificationId}/read`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        // Update UI
                        this.notifications = this.notifications.filter(n => n.id !== notificationId);
                        this.unreadCount--;
                        
                        // Navigate to the URL after a short delay
                        if (url && url !== '#') {
                            setTimeout(() => window.location.href = url, 100);
                        }
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                    // Still navigate to the URL even if there's an error
                    if (url && url !== '#') {
                        window.location.href = url;
                    }
                }
            }
        },
        
        async markAllAsRead() {
            if (this.unreadCount === 0) return;
            
            this.isLoading = true;
            
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    // Update all notifications to be marked as read in the UI
                    this.notifications = this.notifications.map(notification => ({
                        ...notification,
                        read_at: new Date().toISOString()
                    }));
                    
                    // Reset unread count
                    this.unreadCount = 0;
                    
                    // Update the notification bell
                    this.updateNotificationBell(0);
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        updateNotificationBell(count) {
            // Update the notification bell in the navigation
            const bellBadge = document.querySelector('.notification-badge');
            if (bellBadge) {
                if (count > 0) {
                    bellBadge.textContent = count > 99 ? '99+' : count;
                    bellBadge.classList.remove('hidden');
                } else {
                    bellBadge.classList.add('hidden');
                }
            }
        },
        
        setupEventListeners() {
            // Listen for new notifications (you'll need to implement this with Laravel Echo)
            window.Echo.private(`user.${{{ auth()->id() }}}`)
                .listen('.notification.created', (data) => {
                    // Add new notification to the top of the list
                    this.notifications.unshift(data.notification);
                    this.unreadCount++;
                    
                    // Show a toast notification
                    UXUtils.showToast(
                        data.notification.data.message || 'Nouvelle notification',
                        data.notification.type || 'info',
                        {
                            title: data.notification.data.title || 'Notification',
                            action: {
                                text: 'Voir',
                                callback: () => {
                                    if (data.notification.data.action_url) {
                                        window.location.href = data.notification.data.action_url;
                                    }
                                }
                            }
                        }
                    );
                    
                    // Play sound if enabled
                    if (window.localStorage.getItem('notification_sound') !== 'off') {
                        UXUtils.playNotificationSound(data.notification.type || 'info');
                    }
                });
        }
    }));
});
</script>
@endpush
