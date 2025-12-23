class NotificationManager {
    constructor() {
        this.pollInterval = 5000; // Poll every 5 seconds
        this.notificationBell = document.getElementById('notification-bell');
        this.notificationPanel = document.getElementById('notification-panel');
        this.notificationList = document.getElementById('notification-list');
        this.notificationBadge = document.getElementById('notification-badge');
        this.closeBtn = document.getElementById('close-notifications');
        this.clearBtn = document.getElementById('clear-notifications');
        this.deleteAllBtn = document.getElementById('delete-all-notifications');

        this.setupEventListeners();
        this.startPolling();
    }

    setupEventListeners() {
        this.notificationBell?.addEventListener('click', () => this.togglePanel());
        this.closeBtn?.addEventListener('click', () => this.closePanel());
        this.clearBtn?.addEventListener('click', () => this.markAllAsRead());
        this.deleteAllBtn?.addEventListener('click', () => this.deleteAll());

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notification-container')) {
                this.closePanel();
            }
        });
    }

    togglePanel() {
        if (this.notificationPanel.style.display === 'flex') {
            this.closePanel();
        } else {
            this.openPanel();
        }
    }

    openPanel() {
        this.notificationPanel.style.display = 'flex';
        this.loadNotifications();
    }

    closePanel() {
        this.notificationPanel.style.display = 'none';
    }

    startPolling() {
        this.loadUnreadCount();
        setInterval(() => this.loadUnreadCount(), this.pollInterval);
    }

    async loadUnreadCount() {
        try {
            const response = await fetch('/api/notifications/unread');
            const data = await response.json();

            if (data.success) {
                this.updateBadge(data.count);
            }
        } catch (error) {
            console.error('Error loading unread count:', error);
        }
    }

    async loadNotifications() {
        try {
            const response = await fetch('/api/notifications');
            const data = await response.json();

            if (data.success) {
                this.renderNotifications(data.notifications);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.notificationList.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">Error loading notifications</div>';
        }
    }

    renderNotifications(notifications) {
        if (!notifications || notifications.length === 0) {
            this.notificationList.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">No notifications yet</div>';
            return;
        }

        const html = notifications.map(notif => this.createNotificationHTML(notif)).join('');
        this.notificationList.innerHTML = html;

        // Add click handlers
        this.notificationList.querySelectorAll('.notification-item').forEach(item => {
            const notifId = item.dataset.id;
            item.addEventListener('click', () => {
                this.handleNotificationClick(notifId, item.dataset.url);
            });
        });

        // Add delete handlers
        this.notificationList.querySelectorAll('.notification-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.deleteNotification(btn.dataset.id);
            });
        });
    }

    createNotificationHTML(notif) {
        const isUnread = !notif.is_read;
        const time = this.formatTime(notif.created_at);
        const iconMap = {
            'check-circle': '✓',
            'x-circle': '✕',
            'gift': '🎁',
            'eye': '👁️',
            'arrow-down': '⬇️',
            'newspaper': '📰',
            'star': '⭐',
            'image': '🖼️',
            'credit-card': '💳',
            'alert-circle': '⚠️',
            'trending-up': '📈',
            'bell': '🔔'
        };
        const icon = iconMap[notif.icon] || '🔔';

        return `
            <div class="notification-item ${isUnread ? 'unread' : ''}" data-id="${notif.id}" data-url="${notif.action_url || '#'}">
                <div class="notification-item-header">
                    <span style="font-size: 1.2rem; margin-right: 8px;">${icon}</span>
                    <div style="flex: 1;">
                        <h5 class="notification-item-title">${this.escapeHtml(notif.title)}</h5>
                    </div>
                    <button type="button" class="notification-delete" data-id="${notif.id}" style="
                        background: none;
                        border: none;
                        color: #666;
                        cursor: pointer;
                        font-size: 1rem;
                    ">×</button>
                </div>
                <p class="notification-item-message">${this.escapeHtml(notif.message)}</p>
                <div class="notification-item-time">${time}</div>
            </div>
        `;
    }

    async handleNotificationClick(notifId, url) {
        await this.markAsRead(notifId);
        if (url && url !== '#') {
            window.location.href = url;
        }
    }

    async markAsRead(notifId) {
        try {
            await fetch('/api/notifications/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notification_id: notifId })
            });
            this.loadUnreadCount();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            await fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            this.loadNotifications();
            this.loadUnreadCount();
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }

    async deleteNotification(notifId) {
        try {
            await fetch('/api/notifications/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notification_id: notifId })
            });
            this.loadNotifications();
            this.loadUnreadCount();
        } catch (error) {
            console.error('Error deleting notification:', error);
        }
    }

    async deleteAll() {
        if (!confirm('Delete all notifications?')) return;

        try {
            await fetch('/api/notifications/delete-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            this.loadNotifications();
            this.loadUnreadCount();
        } catch (error) {
            console.error('Error deleting all notifications:', error);
        }
    }

    updateBadge(count) {
        if (count > 0) {
            this.notificationBadge.textContent = count > 9 ? '9+' : count;
            this.notificationBadge.style.display = 'flex';
        } else {
            this.notificationBadge.style.display = 'none';
        }
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;

        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;

        return date.toLocaleDateString();
    }

    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
}

// Initialize notification manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new NotificationManager();
});
