<nav class="topbar" id="admin-topbar">
    <!-- Left: dynamic page title from active sidebar menu -->
    <div class="topbar__start">
        <i class="topbar__brand-icon entypo-gauge"></i>
        <span class="topbar__brand" id="topbar-brand-text">Dashboard</span>
    </div>

    <!-- Right: icon actions + logout grouped together -->
    <div class="topbar__end">
        <!-- Messages -->
        <div class="topbar__item messages dropdown" id="topbar-messages">
            <a href="#" class="topbar__icon-btn dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="Messages">
                <i class="entypo-mail"></i>
                <span class="topbar__badge" id="message-count">0</span>
            </a>
            <ul class="dropdown-menu topbar__dropdown topbar__dropdown--notifications" style="color:#000 !important;">
                <li class="topbar__dropdown-header">
                    <p>You have <strong><span id="message-count-text">0</span> unread</strong> messages</p>
                </li>
                <li class="topbar__dropdown-footer">
                    <a href="{{ route('admin.contact_messages.index') }}">View all messages</a>
                </li>
            </ul>
        </div>

        <!-- Trash -->
        <a href="{{ route('admin.trash.index') }}" class="topbar__icon-btn" id="topbar-trash" title="Deleted Items">
            <i class="entypo-trash"></i>
        </a>

        <!-- Notifications -->
        <div class="topbar__item notifications dropdown" id="topbar-notifications">
            <a href="#" class="topbar__icon-btn dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="Notifications">
                <i class="entypo-bell"></i>
                <span class="topbar__badge" id="notification-count">0</span>
            </a>
            <ul class="dropdown-menu topbar__dropdown topbar__dropdown--notifications notifications-dropdown" style="color:#000 !important;">
                <li class="topbar__dropdown-header top">
                    <p class="small" style="color:#000 !important;">
                        You have <span class="bold"><span id="notification-count-text">0</span> unread</span> notifications
                    </p>
                </li>
                <li>
                    <ul class="dropdown-menu-list scroller" id="notification-list" style="max-height: 250px; overflow-y: auto; color:#000 !important;">
                        <li class="text-center">
                            <div class="alert alert-info" style="margin: 10px; color:#000 !important;">
                                <i class="entypo-hourglass"></i> Loading notifications...
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="topbar__dropdown-footer external">
                    <a href="{{ route('admin.notifications.page') }}" class="view-all-notifications" style="color:#000 !important;">View all notifications</a>
                </li>
            </ul>
        </div>

        <!-- Divider -->
        <span class="topbar__divider"></span>

        <!-- Logout -->
        <a href="#" class="topbar__logout-btn" data-toggle="modal" data-target="#logoutModal" id="topbar-logout">
            <span class="topbar__logout-label">Log Out</span>
            <i class="entypo-logout"></i>
        </a>
    </div>
</nav>

<!-- Logout form & modal — placed OUTSIDE the sticky topbar to avoid stacking-context issues -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ff9f1c; border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="logoutModalLabel" style="color:#000; margin:0; font-weight:600;">Confirm Logout</h4>
            </div>
            <div class="modal-body">
                <div class="media">
                    <div class="media-left" style="font-size: 26px; color: #f0ad4e;">
                        <i class="entypo-logout"></i>
                    </div>
                    <div class="media-body" style="padding-left: 10px;">
                        <p class="mb-1" style="margin-bottom: 6px;">Are you sure you want to log out?</p>
                        <small class="text-muted">You can always log back in to manage your bookings and profile.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmLogoutBtn" class="btn btn-success">Yes, Logout</button>
            </div>
        </div>
    </div>
</div>
<script>
    (function(){
        // Logout confirmation
        var btn = document.getElementById('confirmLogoutBtn');
        if (btn) {
            btn.addEventListener('click', function(){
                var form = document.getElementById('logout-form');
                if (form) form.submit();
            });
        }

        // Dynamic topbar brand — reads active sidebar item
        function updateTopbarBrand() {
            var brandText = document.getElementById('topbar-brand-text');
            var brandIcon = document.querySelector('.topbar__brand-icon');
            if (!brandText) return;

            var sidebar = document.getElementById('main-menu');
            if (!sidebar) return;

            // Try deepest active submenu item first
            var activeSubItem = sidebar.querySelector('li.has-sub.active > ul > li.active > a .title');
            var activeParent  = sidebar.querySelector('li.has-sub.active > a .title');
            var activeTopLevel = sidebar.querySelector(':scope > li.active > a .title');

            // Get the icon class from the active section
            var activeIcon = null;

            if (activeSubItem && activeParent) {
                // Show "Parent › Child"
                brandText.innerHTML = activeParent.textContent.trim() +
                    ' <span class="topbar__brand-sep">›</span> ' +
                    activeSubItem.textContent.trim();
                activeIcon = sidebar.querySelector('li.has-sub.active > a > i');
            } else if (activeParent) {
                brandText.textContent = activeParent.textContent.trim();
                activeIcon = sidebar.querySelector('li.has-sub.active > a > i');
            } else if (activeTopLevel) {
                brandText.textContent = activeTopLevel.textContent.trim();
                activeIcon = sidebar.querySelector(':scope > li.active > a > i');
            } else {
                brandText.textContent = 'Dashboard';
            }

            // Sync icon
            if (brandIcon && activeIcon) {
                var iconClass = activeIcon.className;
                brandIcon.className = 'topbar__brand-icon ' + iconClass;
            }
        }

        // Run on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateTopbarBrand);
        } else {
            updateTopbarBrand();
        }
    })();
</script>
