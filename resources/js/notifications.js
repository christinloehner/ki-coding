/**
 * Alpine.js Notification System
 */

// Global notification data function
function notificationData() {
    return {
        open: false,
        unreadCount: 0,
        notifications: [],
        loading: false,

        init() {
            this.fetchNotifications();
            // Aktualisierung alle 30 Sekunden
            setInterval(() => {
                this.fetchNotifications();
            }, 30000);
        },

        async fetchNotifications() {
            if (this.loading) return;
            
            try {
                this.loading = true;
                const response = await fetch('/notifications');
                const data = await response.json();
                if (data.success) {
                    this.notifications = data.notifications || [];
                    this.unreadCount = data.unread_count || 0;
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async markAsRead(id) {
            try {
                const response = await fetch(`/notifications/${id}/read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    this.fetchNotifications();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            if (this.unreadCount === 0) return;
            
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    this.fetchNotifications();
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        },

        formatDate(dateString) {
            try {
                return new Date(dateString).toLocaleString('de-DE', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (error) {
                return dateString;
            }
        }
    };
}

// Make it globally available
window.notificationData = notificationData;