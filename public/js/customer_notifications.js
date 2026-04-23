/**
 * Customer Notification System
 * Handles fetching, displaying, and managing customer notifications
 * via the bell icon dropdown on the homepage.
 */
(function () {
  'use strict';

  const NOTIF_POLL_INTERVAL = 30000; // Poll every 30 seconds
  const NOTIF_FETCH_URL = '/customer/notifications';
  const NOTIF_UNREAD_URL = '/customer/notifications/unread-count';
  const NOTIF_MARK_READ_URL = '/customer/notifications/{id}/read';
  const NOTIF_MARK_ALL_URL = '/customer/notifications/mark-all-read';

  let pollTimer = null;
  let csrfToken = '';

  /**
   * Get the notification icon class and Bootstrap icon based on type.
   */
  function getNotifMeta(type) {
    const map = {
      customer_booking_confirmed: { icon: 'bi-check-circle-fill', css: 'confirmed' },
      customer_booking_cancelled: { icon: 'bi-x-circle-fill', css: 'cancelled' },
      customer_booking_rescheduled: { icon: 'bi-calendar-event', css: 'rescheduled' },
      customer_cleaners_assigned: { icon: 'bi-people-fill', css: 'assigned' },
      customer_job_started: { icon: 'bi-play-circle-fill', css: 'started' },
      customer_job_completed: { icon: 'bi-trophy-fill', css: 'completed' },
      customer_price_set: { icon: 'bi-currency-exchange', css: 'price' },
    };
    return map[type] || { icon: 'bi-bell-fill', css: 'default' };
  }

  /**
   * Format a timestamp into a relative time string.
   */
  function timeAgo(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const now = new Date();
    const diffMs = now - date;
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHr = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHr / 24);

    if (diffSec < 60) return 'Just now';
    if (diffMin < 60) return diffMin + 'm ago';
    if (diffHr < 24) return diffHr + 'h ago';
    if (diffDay < 7) return diffDay + 'd ago';
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
  }

  /**
   * Render a single notification item.
   */
  function renderNotifItem(notif) {
    const data = notif.data || notif;
    const notifType = data.notification_type || notif.notification_type || 'default';
    const message = data.message || notif.message || 'New notification';
    const isRead = !!notif.read_at;
    const meta = getNotifMeta(notifType);
    const createdAt = notif.created_at || '';

    const div = document.createElement('div');
    div.className = 'customer-notif-item ' + (isRead ? 'read' : 'unread');
    div.setAttribute('data-notif-id', notif.id);

    div.innerHTML =
      '<div class="customer-notif-icon ' + meta.css + '">' +
      '  <i class="bi ' + meta.icon + '"></i>' +
      '</div>' +
      '<div class="customer-notif-body">' +
      '  <div class="customer-notif-message">' + escapeHtml(message) + '</div>' +
      '  <div class="customer-notif-time">' + timeAgo(createdAt) + '</div>' +
      '</div>' +
      (!isRead ? '<div class="customer-notif-dot"></div>' : '');

    // Click to mark as read
    if (!isRead) {
      div.addEventListener('click', function () {
        markAsRead(notif.id, div);
      });
    }

    return div;
  }

  /**
   * Escape HTML entities to prevent XSS.
   */
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
  }

  /**
   * Update the badge count.
   */
  function updateBadge(count) {
    const badge = document.getElementById('customerNotifBadge');
    const burgerBadge = document.getElementById('mobileBurgerNotifBadge');

    if (count > 0) {
      if (badge) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = '';
      }
      if (burgerBadge) {
        burgerBadge.style.display = '';
      }
      // Trigger bell ring animation
      const bell = document.getElementById('customerNotifBell');
      if (bell) {
        bell.classList.add('bell-ringing');
        setTimeout(function () {
          bell.classList.remove('bell-ringing');
        }, 1000);
      }
    } else {
      if (badge) badge.style.display = 'none';
      if (burgerBadge) burgerBadge.style.display = 'none';
    }
  }

  /**
   * Fetch and render notifications.
   */
  function fetchNotifications() {
    fetch(NOTIF_FETCH_URL + '?limit=20', {
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        const notifications = data.notifications || data.data || [];
        const unreadCount = data.unread_count || 0;

        updateBadge(unreadCount);
        renderNotifications(notifications);
      })
      .catch(function (err) {
        console.error('Failed to fetch customer notifications:', err);
      });
  }

  /**
   * Render the notification list.
   */
  function renderNotifications(notifications) {
    const list = document.getElementById('customerNotifList');
    const empty = document.getElementById('customerNotifEmpty');
    const footer = document.getElementById('customerNotifFooter');
    if (!list) return;

    // Clear existing items (but keep the empty placeholder)
    list.querySelectorAll('.customer-notif-item').forEach(function (el) {
      el.remove();
    });

    if (!notifications || notifications.length === 0) {
      if (empty) empty.style.display = '';
      if (footer) footer.style.display = 'none';
      return;
    }

    if (empty) empty.style.display = 'none';

    notifications.forEach(function (notif) {
      list.appendChild(renderNotifItem(notif));
    });

    // Show footer if all are read
    var hasUnread = notifications.some(function (n) {
      return !n.read_at;
    });
    if (footer) {
      footer.style.display = hasUnread ? 'none' : '';
    }
  }

  /**
   * Mark a single notification as read.
   */
  function markAsRead(notifId, element) {
    var url = NOTIF_MARK_READ_URL.replace('{id}', notifId);

    fetch(url, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken,
      },
      credentials: 'same-origin',
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.success !== false) {
          // Update the item visually
          if (element) {
            element.classList.remove('unread');
            element.classList.add('read');
            var dot = element.querySelector('.customer-notif-dot');
            if (dot) dot.remove();
          }
          // Refresh unread count
          fetchUnreadCount();
        }
      })
      .catch(function (err) {
        console.error('Failed to mark notification as read:', err);
      });
  }

  /**
   * Mark all notifications as read.
   */
  function markAllAsRead() {
    fetch(NOTIF_MARK_ALL_URL, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken,
      },
      credentials: 'same-origin',
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.success !== false) {
          // Update all items visually
          document.querySelectorAll('.customer-notif-item.unread').forEach(function (el) {
            el.classList.remove('unread');
            el.classList.add('read');
            var dot = el.querySelector('.customer-notif-dot');
            if (dot) dot.remove();
          });
          updateBadge(0);
          var footer = document.getElementById('customerNotifFooter');
          if (footer) footer.style.display = '';
        }
      })
      .catch(function (err) {
        console.error('Failed to mark all notifications as read:', err);
      });
  }

  /**
   * Fetch only the unread count (lightweight polling).
   */
  function fetchUnreadCount() {
    fetch(NOTIF_UNREAD_URL, {
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        updateBadge(data.unread_count || 0);
      })
      .catch(function () {
        // Silently fail
      });
  }

  /**
   * Initialize the notification system.
   */
  function init() {
    // Get CSRF token
    var meta = document.querySelector('meta[name="csrf-token"]');
    csrfToken = meta ? meta.getAttribute('content') : '';

    // Check if the bell exists (only for authenticated users)
    var bell = document.getElementById('customerNotifBell');
    if (!bell) return;

    // Fetch notifications on page load
    fetchNotifications();

    // Fetch full list when dropdown is opened
    var dropdown = document.getElementById('customerNotificationDropdown');
    if (dropdown) {
      dropdown.addEventListener('show.bs.dropdown', function () {
        fetchNotifications();
      });
    }

    // Mark all read button
    var markAllBtn = document.getElementById('customerMarkAllRead');
    if (markAllBtn) {
      markAllBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        markAllAsRead();
      });
    }

    // Poll for new notifications periodically
    pollTimer = setInterval(fetchUnreadCount, NOTIF_POLL_INTERVAL);

    // Pause polling when page is hidden
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        clearInterval(pollTimer);
      } else {
        fetchUnreadCount();
        pollTimer = setInterval(fetchUnreadCount, NOTIF_POLL_INTERVAL);
      }
    });
  }

  // Boot on DOMContentLoaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
