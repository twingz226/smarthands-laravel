<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Notifications</h3>
                <div class="panel-options">
                    <a href="#" data-toggle="panel">
                        <span class="collapse-icon">&ndash;</span>
                        <span class="expand-icon">+</span>
                    </a>
                </div>
            </div>
            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="#" class="btn btn-primary btn-sm" id="mark-all-read">
                                <i class="entypo-check"></i> Mark All as Read
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="list-group" id="notifications-list">
<?php
    $typeMap = [
        'App\\Notifications\\NewBookingNotification' => 'booking_created',
        'App\\Notifications\\NewCustomerNotification' => 'new_customer',
        'booking_created' => 'booking_created',
        'booking_cancelled' => 'booking_cancelled',
        'booking_rescheduled' => 'booking_rescheduled',
        'new_customer' => 'new_customer',
    ];
    $iconMap = [
        'booking_created' => 'entypo-calendar',
        'booking_cancelled' => 'entypo-cancel',
        'booking_rescheduled' => 'entypo-calendar',
        'new_customer' => 'entypo-user-add',
        'default' => 'entypo-info'
    ];
?>
<?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php
    $data = is_array($notification->data) ? $notification->data : [];
    $simpleType = $typeMap[$notification->type] ?? 'default';
    $iconClass = $iconMap[$simpleType] ?? $iconMap['default'];
?>
                            <a href="<?php echo e($data['link'] ?? '#'); ?>" class="list-group-item notification-item" data-id="<?php echo e($notification->id); ?>">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="notification-icon <?php echo e($iconClass); ?>" style="font-size: 24px; color: #3498db;"></i>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading notification-message"><?php echo e($data['message'] ?? 'New notification'); ?></h4>
                                        <p class="notification-time text-muted"><?php echo e($notification->created_at?->diffForHumans()); ?></p>
                                    </div>
                                    <div class="media-right">
<?php if(is_null($notification->read_at)): ?>
                                        <span class="badge badge-info unread-badge">NEW</span>
<?php endif; ?>
                                    </div>
                                </div>
                            </a>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center">
                                <div class="alert alert-info">
                                    <i class="entypo-info"></i> No notifications found.
                                </div>
                            </div>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Item Template (Hidden) -->
<template id="notification-template">
    <a href="#" class="list-group-item notification-item" data-id="">
        <div class="media">
            <div class="media-left">
                <i class="notification-icon"></i>
            </div>
            <div class="media-body">
                <h4 class="media-heading notification-message"></h4>
                <p class="notification-time text-muted"></p>
            </div>
            <div class="media-right">
                <span class="badge badge-info unread-badge">NEW</span>
            </div>
        </div>
    </a>
</template>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    // Mark notification as read
    $(document).on('click', '.notification-item', function(e) {
        e.preventDefault();
        
        const $item = $(this);
        const notificationId = $item.data('id');
        const link = $item.attr('href');
        
        // Mark as read
        if ($item.find('.unread-badge').length > 0) {
            $.post(`/admin/notifications/${notificationId}/read`, { _token: csrf })
                .done(function() {
                    $item.find('.unread-badge').remove();
                    updateUnreadCount();
                });
        }
        
        // Navigate to the link
        if (link && link !== '#') {
            window.location.href = link;
        }
    });
    
    // Mark all as read
    $('#mark-all-read').on('click', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="entypo-arrows-ccw"></i> Processing...');
        
        $.post('/admin/notifications/mark-all-read', { _token: csrf })
            .done(function() {
                $('.notification-item .unread-badge').remove();
                updateUnreadCount();
            })
            .always(function() {
                $btn.prop('disabled', false).html(originalText);
            });
    });
    
    // Helper function to get notification icon
    function getNotificationIcon(type) {
        const icons = {
            'booking_created': 'entypo-calendar',
            'booking_cancelled': 'entypo-cancel',
            'booking_rescheduled': 'entypo-calendar',
            'new_customer': 'entypo-user-add',
            'default': 'entypo-info'
        };
        
        return icons[type] || icons.default;
    }
    
    // Helper function to format time ago
    function formatTimeAgo(dateString) {
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
    
    // Helper function to update unread count
    function updateUnreadCount() {
        $.get('/admin/notifications/unread-count')
            .done(function(response) {
                const count = response.unread_count || 0;
                
                // Update the notification badge in the header
                const $badge = $('.notifications .badge');
                if (count > 0) {
                    $badge.text(count).show();
                } else {
                    $badge.hide();
                }
                
                // Update the count in the page title
                document.title = document.title.replace(/^\(\d+\)\s*/, '');
                if (count > 0) {
                    document.title = `(${count}) ${document.title}`;
                }
            });
    }
    
    // Initial load
    loadNotifications();

    // Refresh notifications every 60 seconds
    setInterval(updateUnreadCount, 10000);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('styles'); ?>
<style>
.notification-item {
    border-left: 4px solid transparent !important;
    transition: all 0.2s ease !important;
    padding: 15px 20px !important;
    border-bottom: 1px solid #f0f0f0 !important;
    background-color: #fff !important;
    margin: 0 !important;
}

.notification-item:hover {
    background-color: #f8fbfe !important;
    border-left-color: #3498db !important;
    transform: translateX(2px) !important;
    z-index: 5 !important;
}

.notification-item .media-left {
    padding-right: 18px !important;
    min-width: 40px !important;
}

.notification-item .media-body {
    vertical-align: middle !important;
    padding-right: 15px !important;
    width: auto !important;
}

.notification-item .media-heading {
    font-size: 15px !important;
    margin-bottom: 5px !important;
    font-weight: 500 !important;
    line-height: 1.4 !important;
    color: #2c3e50 !important;
    display: block !important;
}

.notification-item .notification-message {
    font-size: 14px !important;
    color: #555 !important;
    margin-bottom: 3px !important;
    line-height: 1.5 !important;
    display: block !important;
}

.notification-item .notification-time {
    font-size: 12px !important;
    color: #7f8c8d !important;
    margin-bottom: 0 !important;
    display: flex !important;
    align-items: center !important;
    line-height: 1.2 !important;
}

.notification-item .notification-time i {
    margin-right: 5px !important;
    font-size: 11px !important;
    display: inline-flex !important;
    align-items: center !important;
}

.notification-item .unread-badge {
    margin-top: 8px !important;
    background-color: #e74c3c !important;
    font-size: 10px !important;
    font-weight: 600 !important;
    padding: 3px 8px !important;
    border-radius: 10px !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1) !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border: none !important;
}

.notification-item.read {
    background-color: #f9f9f9 !important;
}

.notification-item.read .media-heading,
.notification-item.read .notification-message {
    color: #7f8c8d !important;
}

.notification-item.read .notification-time {
    color: #95a5a6 !important;
}

.list-group-item:first-child {
    border-top-left-radius: 4px !important;
    border-top-right-radius: 4px !important;
}

.list-group-item:last-child {
    border-bottom-left-radius: 4px !important;
    border-bottom-right-radius: 4px !important;
    border-bottom: none !important;
}

#notifications-list {
    border: 1px solid #e0e0e0 !important;
    border-radius: 4px !important;
    background: #fff !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
}

#load-more {
    margin: 20px auto !important;
    padding: 8px 25px !important;
    border: 1px solid #e0e0e0 !important;
    background: #f8f9fa !important;
    transition: all 0.2s !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    border-radius: 4px !important;
    color: #333 !important;
    cursor: pointer !important;
    display: block !important;
}

#load-more:hover {
    background: #e9ecef !important;
    border-color: #d6d8db !important;
    color: #000 !important;
}

/* Notification icons */
.notification-icon {
    font-size: 20px !important;
    width: 40px !important;
    height: 40px !important;
    line-height: 40px !important;
    text-align: center !important;
    border-radius: 50% !important;
    background: #f1f5f9 !important;
    color: #4a6cf7 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.notification-booking {
    background: #e3f2fd !important;
    color: #1976d2 !important;
}

.notification-payment {
    background: #e8f5e9 !important;
    color: #388e3c !important;
}

.notification-alert {
    background: #fff3e0 !important;
    color: #f57c00 !important;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/notifications/index.blade.php ENDPATH**/ ?>