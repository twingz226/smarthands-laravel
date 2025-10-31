/**
 * Notification System for Admin Panel
 */
class NotificationSystem {
    constructor() {
        this.pollingInterval = 60000; // 1 minute
        this.notificationSound = new Audio('/sounds/notification.mp3');
        this.initialize();
    }

    initialize() {
        this.loadNotifications();
        this.setupEventListeners();
        this.startPolling();
    }

    setupEventListeners() {
        // Mark all as read
        $(document).on('click', '.mark-all-read', (e) => {
            e.preventDefault();
            this.markAllAsRead();
        });

        // Mark single notification as read
        $(document).on('click', '.notification-item', (e) => {
            const $target = $(e.currentTarget);
            const notificationId = $target.data('id');
            
            if (!notificationId) return;
            
            this.markAsRead(notificationId);
            
            // If there's a link, follow it after a short delay
            const link = $target.data('link');
            if (link) {
                setTimeout(() => {
                    window.location.href = link;
                }, 100);
            }
        });

        // Prevent dropdown from closing when clicking inside
        $(document).on('click', '.dropdown-menu', (e) => {
            e.stopPropagation();
        });

        // Reset badge when "View All Notifications" link is clicked
        $(document).on('click', '.view-all-notifications', (e) => {
            console.log('View All Notifications link clicked'); // Debug log
            e.preventDefault(); // Prevent immediate navigation
            const $link = $(e.target);
            const href = $link.attr('href');
            console.log('Link href:', href); // Debug log

            // Mark all notifications as read
            $.post('/admin/notifications/mark-all-read', {
                _token: window.Laravel.csrfToken
            }).done(() => {
                console.log('Notifications marked as read successfully'); // Debug log
                // Reset badge immediately
                this.updateNotificationCount(0);
                // Now navigate to the page
                window.location.href = href;
            }).fail((error) => {
                console.error('Error marking all notifications as read:', error);
                // Still navigate even if AJAX fails
                window.location.href = href;
            });
        });
    }

    async loadNotifications() {
        try {
            // Load notification list (use admin web endpoint so session auth works)
            const response = await $.get('/admin/notifications', { limit: 10 });
            this.updateNotificationCount(response.unread_count);
            this.renderNotifications(response.notifications);
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.showError('Failed to load notifications');
        }
    }

    async markAsRead(notificationId) {
        try {
            await $.post(`/admin/notifications/${notificationId}/read`, {
                _token: window.Laravel.csrfToken
            });
            
            // Update UI
            $(`.notification-item[data-id="${notificationId}"]`).removeClass('unread');
            this.updateNotificationCount(Math.max(0, parseInt($('#notification-count').text()) - 1));
            
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            await $.post('/admin/notifications/mark-all-read', {
                _token: window.Laravel.csrfToken
            });
            
            // Update UI
            $('.notification-item').removeClass('unread');
            this.updateNotificationCount(0);
            
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            this.showError('Failed to mark all as read');
        }
    }

    updateNotificationCount(count) {
        const $badge = $('#notification-count');
        const $text = $('#notification-count-text');
        
        $badge.text(count);
        $text.text(count);
        
        if (count > 0) {
            $badge.show();
            this.playNotificationSound();
        } else {
            $badge.hide();
        }
    }

    renderNotifications(notifications) {
        const $container = $('#notification-list');
        
        if (!notifications || notifications.length === 0) {
            $container.html(`
                <li class="text-center">
                    <div class="alert alert-info" style="margin: 10px;">
                        <i class="entypo-info"></i> No notifications found
                    </div>
                </li>
            `);
            return;
        }
        
        const items = notifications.map(notification => {
            const isUnread = !notification.read_at;
            const timeAgo = this.getTimeAgo(notification.created_at);
            
            return `
                <li class="notification-item ${isUnread ? 'unread' : ''}" 
                    data-id="${notification.id}" 
                    data-link="${notification.link || '#'}">
                    <a href="#" style="color:#000 !important; text-decoration:none;">
                        <div class="notification-icon" style="color:#000 !important;">
                            <i class="${this.getNotificationIcon(notification.type)}" style="color:#000 !important;"></i>
                        </div>
                        <div class="notification-content">
                            <span class="notification-message" style="color:#000 !important;">${notification.message}</span>
                            <span class="notification-time" style="color:#000 !important;">${timeAgo}</span>
                        </div>
                    </a>
                </li>
            `;
        }).join('');
        
        $container.html(items);
    }

    getNotificationIcon(type) {
        const icons = {
            'booking_created': 'entypo-calendar',
            'booking_cancelled': 'entypo-cancel',
            'booking_rescheduled': 'entypo-calendar',
            'new_customer': 'entypo-user-add',
            'default': 'entypo-info'
        };
        
        return icons[type] || icons.default;
    }

    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const seconds = Math.floor((new Date() - date) / 1000);
        
        const intervals = {
            year: 31536000,
            month: 2592000,
            week: 604800,
            day: 86400,
            hour: 3600,
            minute: 60,
            second: 1
        };
        
        for (const [unit, secondsInUnit] of Object.entries(intervals)) {
            const interval = Math.floor(seconds / secondsInUnit);
            if (interval >= 1) {
                return interval === 1 ? `1 ${unit} ago` : `${interval} ${unit}s ago`;
            }
        }
        
        return 'just now';
    }

    playNotificationSound() {
        try {
            this.notificationSound.play().catch(e => console.error('Error playing sound:', e));
        } catch (e) {
            console.error('Error with notification sound:', e);
        }
    }

    showError(message) {
        const $container = $('#notification-list');
        $container.html(`
            <li class="text-center">
                <div class="alert alert-danger" style="margin: 10px;">
                    <i class="entypo-warning"></i> ${message}
                </div>
            </li>
        `);
    }
    stopPolling() {
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer);
        }
    }
    
    startPolling() {
        // Update notifications periodically
        this.pollingTimer = setInterval(() => this.loadNotifications(), this.pollingInterval);
    }
}

// Initialize when document is ready
$(document).ready(() => {
    if ($('#notification-list').length) {
        window.notificationSystem = new NotificationSystem();
        window.notificationSystem.startPolling();
    }
});
