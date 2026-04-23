<!-- Customer Notification Bell & Dropdown -->
<li class="nav-item dropdown" id="customerNotificationDropdown">
  <a class="nav-link position-relative" href="#" id="customerNotifBell" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Notifications">
    <i class="bi bi-bell fs-5" aria-hidden="true"></i>
    <span id="customerNotifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; display: none;">
      0
      <span class="visually-hidden">unread notifications</span>
    </span>
  </a>
  <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0 customer-notif-dropdown" aria-labelledby="customerNotifBell">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center px-3 py-2" style="background: linear-gradient(135deg, #ff9f1c, #ff6b35); color: white;">
      <h6 class="mb-0 fw-bold"><i class="bi bi-bell me-1"></i> Notifications</h6>
      <button type="button" class="btn btn-sm btn-link text-white text-decoration-none p-0" id="customerMarkAllRead" title="Mark all as read" style="font-size: 0.8rem;">
        <i class="bi bi-check2-all me-1"></i>Mark all read
      </button>
    </div>
    <!-- Notification List -->
    <div id="customerNotifList" style="max-height: 380px; overflow-y: auto;">
      <div class="text-center py-4 text-muted" id="customerNotifEmpty">
        <i class="bi bi-bell-slash" style="font-size: 2rem; opacity: 0.4;"></i>
        <p class="mb-0 mt-2 small">No notifications yet</p>
      </div>
    </div>
    <!-- Footer -->
    <div class="text-center border-top py-2" id="customerNotifFooter" style="display: none;">
      <small class="text-muted">You're all caught up!</small>
    </div>
  </div>
</li>

<style>
  /* Customer Notification Dropdown Styles */
  .customer-notif-dropdown {
    width: 380px;
    max-height: 480px;
    border-radius: 12px;
    overflow: hidden;
    animation: notifSlideDown 0.2s ease-out;
  }

  @media (max-width: 576px) {
    .customer-notif-dropdown {
      width: 94vw !important;
      max-width: none !important;
      position: fixed !important;
      left: 3vw !important;
      right: 3vw !important;
      top: 80px !important;
      margin: 0 auto !important;
      z-index: 1080 !important;
      transform: none !important;
    }
  }

  #customerNotificationDropdown .dropdown-menu {
    animation: notifSlideDown 0.2s ease-out;
  }

  @keyframes notifSlideDown {
    from {
      opacity: 0;
      transform: translateY(-8px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .customer-notif-item {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .customer-notif-item:hover {
    background-color: rgba(255, 159, 28, 0.06);
  }

  .customer-notif-item.unread {
    background-color: rgba(255, 159, 28, 0.08);
    border-left: 3px solid #ff9f1c;
  }

  .customer-notif-item.read {
    opacity: 0.7;
  }

  .customer-notif-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.9rem;
  }

  .customer-notif-icon.confirmed { background: rgba(40, 167, 69, 0.15); color: #28a745; }
  .customer-notif-icon.cancelled { background: rgba(220, 53, 69, 0.15); color: #dc3545; }
  .customer-notif-icon.rescheduled { background: rgba(0, 123, 255, 0.15); color: #007bff; }
  .customer-notif-icon.assigned { background: rgba(23, 162, 184, 0.15); color: #17a2b8; }
  .customer-notif-icon.started { background: rgba(255, 193, 7, 0.15); color: #ffc107; }
  .customer-notif-icon.completed { background: rgba(40, 167, 69, 0.15); color: #28a745; }
  .customer-notif-icon.price { background: rgba(111, 66, 193, 0.15); color: #6f42c1; }
  .customer-notif-icon.default { background: rgba(108, 117, 125, 0.15); color: #6c757d; }

  .customer-notif-body {
    flex: 1;
    min-width: 0;
  }

  .customer-notif-message {
    font-size: 0.85rem;
    line-height: 1.4;
    color: #2c3e50;
    margin-bottom: 4px;
    word-break: break-word;
  }

  .customer-notif-time {
    font-size: 0.72rem;
    color: #6c757d;
  }

  .customer-notif-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #ff9f1c;
    flex-shrink: 0;
    margin-top: 6px;
  }

  #customerNotifBell .bi-bell {
    transition: transform 0.3s ease;
  }

  #customerNotifBell:hover .bi-bell {
    transform: rotate(15deg);
  }

  @keyframes bellRing {
    0%, 100% { transform: rotate(0); }
    15% { transform: rotate(15deg); }
    30% { transform: rotate(-15deg); }
    45% { transform: rotate(10deg); }
    60% { transform: rotate(-10deg); }
    75% { transform: rotate(5deg); }
  }

  .bell-ringing .bi-bell {
    animation: bellRing 0.8s ease;
  }
</style>
