<div class="row">
    <!-- Profile Info and Notifications -->
    <div class="col-md-6 col-sm-8 clearfix">
        <ul class="user-info pull-left pull-none-xsm">
            <!-- User info can be added here -->
        </ul>

        <!-- Notifications Dropdown -->
        <ul class="user-info pull-left pull-right xs-pull-left">
            <li class="messages dropdown" style="margin-right: 18px;">
                <a href="#" class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <i class="entypo-mail fs-4" style="font-size: 1.5rem;"></i>
                    <span class="badge badge-info" id="message-count">0</span>
                </a>
                <ul class="dropdown-menu notifications-dropdown" style="color:#000 !important;">
                    <li class="top">
                        <p class="small" style="color:#000 !important;">
                            You have <span class="bold"><span id="message-count-text">0</span> unread</span> messages
                        </p>
                    </li>
                    <li class="external">
                        <a href="<?php echo e(route('admin.contact_messages.index')); ?>" style="color:#000 !important;">View all messages</a>
                    </li>
                </ul>
            </li>
            <li class="trash-link" style="margin-right: 18px;">
                <a href="<?php echo e(route('admin.trash.index')); ?>" class="dropdown-toggle d-flex align-items-center" title="View Deleted Actions" style="color:#5d5d5d;">
                    <i class="entypo-trash fs-4" style="font-size: 1.5rem;"></i>
                </a>
            </li>
            <li class="notifications dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <i class="entypo-bell fs-4" style="font-size: 1.5rem;"></i>
                    <span class="badge badge-info" id="notification-count">0</span>
                </a>
                <ul class="dropdown-menu notifications-dropdown" style="color:#000 !important;">
                    <li class="top">
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
                    <li class="external">
                        <a href="<?php echo e(route('admin.notifications.page')); ?>" class="view-all-notifications" style="color:#000 !important;">View all notifications</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right">
            
            <li>
                <a href="#" 
                   data-toggle="modal" 
                   data-target="#logoutModal" 
                   style="color: #5d5d5d;">
                    Log Out <i class="entypo-logout right"></i>
                </a>
                
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>

                <!-- Logout Confirmation Modal -->
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
                        var btn = document.getElementById('confirmLogoutBtn');
                        if (btn) {
                            btn.addEventListener('click', function(){
                                var form = document.getElementById('logout-form');
                                if (form) form.submit();
                            });
                        }
                    })();
                </script>
            </li>
        </ul>
    </div>
</div>

<hr />
<?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/partials/topbar.blade.php ENDPATH**/ ?>