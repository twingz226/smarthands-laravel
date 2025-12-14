<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">Online Bookings
                <a href="#" class="btn btn-info pull-right" id="fullyBookedDatesButton">
                    <i class="entypo-calendar"></i> Fully Booked Dates
                </a>
            </h1>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade in">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Address</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($booking->user->name); ?></td>
                            <td><?php echo e($booking->service->name); ?></td>
                            <td><?php echo e($booking->customer_address); ?></td>
                            <td><?php echo e($booking->cleaning_date->format('M d, Y g:i A')); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($booking->status == 'confirmed' ? 'success' :
                                    ($booking->status == 'cancelled' ? 'danger' :
                                    ($booking->status == 'completed' ? 'primary' : 'warning'))); ?>">
                                    <?php echo e(ucfirst($booking->status)); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('bookings.show', $booking->id)); ?>"
                                       class="btn btn-info rounded-circle"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="View">
                                        <i class="entypo-eye"></i>
                                    </a>
                                    <?php if($booking->status == 'pending' || $booking->status == 'rescheduled'): ?>
                                        <form action="<?php echo e(route('bookings.confirm', $booking->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit"
                                                    class="btn btn-success rounded-circle"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Confirm"
                                                    onclick="console.log('Confirm button clicked for booking <?php echo e($booking->id); ?>')">
                                                <i class="entypo-thumbs-up"></i>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('bookings.admin.cancel', $booking->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="button"
                                                    class="btn btn-danger rounded-circle cancel-booking-btn"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Cancel"
                                                    data-booking-id="<?php echo e($booking->id); ?>">
                                                <i class="entypo-block"></i>
                                            </button>
                                        </form>
                                        <a href="<?php echo e(route('bookings.admin.reschedule', ['booking' => $booking->id])); ?>"
                                           class="btn btn-warning rounded-circle"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="Reschedule">
                                            <i class="entypo-back-in-time"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">No bookings found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.btn.rounded-circle {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    position: relative;
    border-radius: 50% !important;
    transition: all 0.3s ease;
}

.btn.rounded-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.btn.rounded-circle i {
    margin: 0;
    font-size: 14px;
}

/* Button colors */
.btn-info.rounded-circle {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-success.rounded-circle {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-danger.rounded-circle {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-warning.rounded-circle {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-primary.rounded-circle {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Cancel Booking Confirmation Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelBookingModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h4>
            </div>
            <div class="modal-body">
                <p id="cancelBookingMessage">Are you sure you want to cancel this booking?</p>
                <p class="text-muted" style="margin-top:10px;">
                    This action cannot be undone. The customer will be notified about the cancellation.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Keep Booking</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="entypo-block"></i> Yes, Cancel Booking
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('admin.bookings.partials.fully_booked_dates_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<input type="hidden" id="fullyBookedDatesUrl" value="<?php echo e(route('bookings.fully.booked.dates', [], false)); ?>">

<script>
    $(document).ready(function() {
        // Initialize tooltips with Bootstrap 3
        $('[data-toggle="tooltip"]').tooltip({
            delay: { show: 0, hide: 0 }, // Instant show/hide
            animation: false, // Disable fade animation for instant display
            trigger: 'hover', // Only trigger on hover
            container: 'body' // Improve rendering performance
        });

        // Debug: Check if forms are properly set up
        console.log('Booking action buttons initialized');
        $('form[action*="bookings"]').each(function() {
            console.log('Form found:', $(this).attr('action'));
        });

        // Add form submission debugging
        $('form[action*="bookings"]').on('submit', function(e) {
            console.log('Form submitted:', $(this).attr('action'));
            console.log('Form method:', $(this).attr('method'));
            console.log('Form data:', $(this).serialize());

            // Add a small delay to ensure the form is processed
            setTimeout(function() {
                console.log('Form submission completed');
            }, 100);
        });

        // Add button click debugging
        $('.btn-group .btn').on('click', function(e) {
            console.log('Button clicked:', $(this).attr('class'));
            console.log('Button type:', $(this).attr('type'));
            console.log('Button parent form:', $(this).closest('form').attr('action'));
            console.log('Event target:', e.target);
            console.log('Event type:', e.type);
        });

        // Check for any JavaScript errors
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            console.error('JavaScript error:', msg, 'at', url, 'line', lineNo);
            return false;
        };

        // Test if jQuery is working
        console.log('jQuery version:', $.fn.jquery);
        console.log('Bootstrap version:', typeof $.fn.modal !== 'undefined' ? 'Bootstrap 3' : 'Bootstrap not found');

        // Cancel Booking: modal confirmation flow
        var cancelTargetForm = null;
        $(document).on('click', '.cancel-booking-btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            cancelTargetForm = $btn.closest('form');
            var bookingId = $btn.data('booking-id');

            // Get the table row containing the button to extract customer and service details
            var $row = $btn.closest('tr');
            var customerName = $row.find('td:nth-child(1)').text().trim();
            var serviceName = $row.find('td:nth-child(2)').text().trim();

            var message = 'Are you sure you want to cancel the booking for ' + customerName + ' (' + serviceName + ')?';
            $('#cancelBookingMessage').text(message);
            $('#confirmCancelBtn').prop('disabled', false).removeClass('disabled');
            $('#cancelBookingModal').modal('show');
        });

        $('#confirmCancelBtn').on('click', function() {
            if (!cancelTargetForm) return;
            var $btn = $(this);
            $btn.prop('disabled', true).addClass('disabled');
            // Optional: show quick feedback
            $btn.html('<i class="entypo-hourglass"></i> Cancelling...');
            // Submit the stored form
            cancelTargetForm.trigger('submit');
            // Close the modal shortly after to provide UX feedback
            setTimeout(function(){
                $('#cancelBookingModal').modal('hide');
                // Reset button text after hide for next time
                $btn.html('<i class="entypo-block"></i> Yes, Cancel Booking');
            }, 200);
        });

        // Fully Booked Dates: helpers and auto-refresh
        var fullyBookedDatesUrl = $('#fullyBookedDatesUrl').val();
        var fallbackFullyBookedDatesUrl = "<?php echo e(route('fully.booked.dates')); ?>?context=reschedule";
        var fullyBookedInterval = null;
        function loadFullyBookedDates(useFallback) {
            console.log('Loading fully booked dates via AJAX...');
            $.ajax({
                url: useFallback ? fallbackFullyBookedDatesUrl : fullyBookedDatesUrl,
                method: 'GET',
                cache: false,
                success: function(response, status, xhr) {
                    console.log('AJAX success, content loaded.');
                    var contentType = xhr.getResponseHeader('Content-Type') || '';
                    try {
                        // If JSON, render table dynamically
                        var data = response;
                        if (typeof response === 'string') {
                            var trimmed = response.trim();
                            if (contentType.indexOf('application/json') !== -1 || trimmed.startsWith('{') || trimmed.startsWith('[')) {
                                data = JSON.parse(trimmed);
                            }
                        }

                        if (data && typeof data === 'object' && data.fullyBookedTimes) {
                            var timesByDate = data.fullyBookedTimes;
                            var rows = '';
                            var hasAny = false;
                            Object.keys(timesByDate).forEach(function(dateStr){
                                var times = timesByDate[dateStr] || [];
                                var count = Array.isArray(times) ? times.length : 0;
                                if (count > 0) {
                                    hasAny = true;
                                    rows += '<tr>' +
                                        '<td>' + dateStr + '</td>' +
                                        '<td>' + count + '</td>' +
                                    '</tr>';
                                }
                            });

                            if (!hasAny) {
                                $('#fullyBookedDatesContent').html('<p>No fully booked dates found.</p>');
                                return;
                            }

                            var html = '' +
                                '<table class="table table-bordered">' +
                                '  <thead>' +
                                '    <tr>' +
                                '      <th>Date</th>' +
                                '      <th>Number of Fully Booked Time Slots</th>' +
                                '    </tr>' +
                                '  </thead>' +
                                '  <tbody>' + rows + '</tbody>' +
                                '</table>';
                            $('#fullyBookedDatesContent').html(html);
                            return;
                        }
                    } catch (e) {
                        console.warn('Response not JSON or parse failed, falling back to HTML injection.');
                    }

                    // Fallback: assume HTML
                    $('#fullyBookedDatesContent').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    if (!useFallback && xhr && xhr.status === 404) {
                        console.warn('Admin endpoint returned 404. Retrying with public endpoint...');
                        return loadFullyBookedDates(true);
                    }
                    $('#fullyBookedDatesContent').html('<p class="text-danger">Error loading dates: ' + (xhr && xhr.statusText ? xhr.statusText : error) + '</p>');
                }
            });
        }

        // Fully Booked Dates Button Click Handler
        $('#fullyBookedDatesButton').on('click', function(e) {
            e.preventDefault();
            $('#fullyBookedDatesModal').modal('show');
        });

        // When modal is shown, load immediately and start polling
        $('#fullyBookedDatesModal').on('show.bs.modal', function () {
            loadFullyBookedDates(false);
            if (fullyBookedInterval) {
                clearInterval(fullyBookedInterval);
            }
            fullyBookedInterval = setInterval(function() {
                if ($('#fullyBookedDatesModal').is(':visible')) {
                    loadFullyBookedDates(false);
                }
            }, 15000); // refresh every 15 seconds while open
        });

        // When modal is hidden, stop polling
        $('#fullyBookedDatesModal').on('hidden.bs.modal', function () {
            if (fullyBookedInterval) {
                clearInterval(fullyBookedInterval);
                fullyBookedInterval = null;
            }
        });

        // Refresh when window gets focus or visibility changes, if modal is open
        $(window).on('focus', function() {
            if ($('#fullyBookedDatesModal').is(':visible')) {
                loadFullyBookedDates(false);
            }
        });
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible' && $('#fullyBookedDatesModal').is(':visible')) {
                loadFullyBookedDates(false);
            }
        });

        // If there is a success flash (e.g., after cancel or reschedule), refresh data once immediately
        var hasSuccessFlash = $('.alert.alert-success').length > 0;
        if (hasSuccessFlash) {
            // Preload the modal content so it's up to date even before opening
            loadFullyBookedDates(false);
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/admin/bookings/index.blade.php ENDPATH**/ ?>