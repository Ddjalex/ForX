document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    const notificationClose = document.getElementById('notificationClose');
    const notificationBackdrop = document.getElementById('notificationBackdrop');
    const notificationList = document.getElementById('notificationList');
    const notificationBadge = document.getElementById('notificationBadge');
    const markAllReadBtn = document.getElementById('markAllReadBtn');

    if (!notificationBtn) return;

    function togglePanel(e) {
        if (e) e.stopPropagation();
        const isVisible = notificationPanel.style.display === 'block';
        notificationPanel.style.display = isVisible ? 'none' : 'block';
        
        if (!isVisible) {
            // Position panel under the button
            const rect = notificationBtn.getBoundingClientRect();
            notificationPanel.style.top = (rect.bottom + window.scrollY + 5) + 'px';
            notificationPanel.style.right = (window.innerWidth - rect.right) + 'px';
            notificationPanel.style.left = 'auto';
            notificationPanel.style.position = 'fixed';
            fetchNotifications();
        }
    }

    notificationBtn.addEventListener('click', togglePanel);
    notificationClose?.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationPanel.style.display = 'none';
    });

    document.addEventListener('click', (e) => {
        if (!notificationPanel.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationPanel.style.display = 'none';
        }
    });

    function updateClock() {
        const headerClock = document.getElementById('headerClock');
        if (!headerClock) return;
        const now = new Date();
        const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
        const dateStr = now.toLocaleDateString([], { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        headerClock.innerHTML = `<div style="text-align: right;">${timeStr}</div><div style="font-size: 10px; opacity: 0.8; font-weight: 400;">${dateStr}</div>`;
    }

    updateClock();
    setInterval(updateClock, 1000);

    async function fetchNotifications() {
        try {
            const response = await fetch('/api/notifications');
            const data = await response.json();
            if (data.success) {
                renderNotifications(data.notifications);
                updateBadge(data.notifications.filter(n => !n.is_read).length);
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
            notificationList.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">Error loading notifications</div>';
        }
    }

    function renderNotifications(notifications) {
        if (!notifications || notifications.length === 0) {
            notificationList.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">No notifications yet</div>';
            return;
        }

        notificationList.innerHTML = notifications.map(n => `
            <div class="notification-item ${!n.is_read ? 'unread' : ''}" onclick="markAsRead(${n.id}, '${n.action_url || '#'}')">
                <div class="notification-item-icon ${n.type}">
                    ${getIcon(n.icon)}
                </div>
                <div class="notification-item-content">
                    <div class="notification-item-title">${n.title}</div>
                    <div class="notification-item-message">${n.message}</div>
                    <div class="notification-item-time">${n.time_ago}</div>
                </div>
            </div>
        `).join('');
    }

    function getIcon(iconName) {
        // Simple icon mapper
        const icons = {
            'check-circle': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
            'x-circle': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
            'newspaper': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path></svg>',
            'trending-up': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>'
        };
        return icons[iconName] || '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path></svg>';
    }

    window.markAsRead = async function(id, url) {
        try {
            await fetch('/api/notifications/read', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `notification_id=${id}`
            });
            if (url && url !== '#') window.location.href = url;
            else fetchNotifications();
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    };

    markAllReadBtn?.addEventListener('click', async () => {
        try {
            await fetch('/api/notifications/read-all', { method: 'POST' });
            fetchNotifications();
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    });

    function updateBadge(count) {
        if (count > 0) {
            notificationBadge.textContent = count;
            notificationBadge.style.display = 'block';
        } else {
            notificationBadge.style.display = 'none';
        }
    }

    // Initial unread count poll
    async function pollUnread() {
        try {
            const response = await fetch('/api/notifications/unread');
            const data = await response.json();
            if (data.success) {
                updateBadge(data.count);
            }
        } catch (e) {}
    }
    
    pollUnread();
    setInterval(pollUnread, 30000);
});