<?php $__env->startComponent('mail::message'); ?>
# Booking Status Update

Hi <?php echo new \Illuminate\Support\EncodedHtmlString($booking->customer->name); ?>,

Your booking for <?php echo new \Illuminate\Support\EncodedHtmlString($booking->service->name); ?> has been **<?php echo new \Illuminate\Support\EncodedHtmlString($booking->status); ?>**.

<?php if($booking->status === 'confirmed'): ?>
Your cleaning service is confirmed for:  
**<?php echo new \Illuminate\Support\EncodedHtmlString($booking->cleaning_date->format('l, F j')); ?> at <?php echo new \Illuminate\Support\EncodedHtmlString($booking->cleaning_date->format('g:i A')); ?>**

Please ensure someone will be available at the location during this time.

<?php elseif($booking->status === 'cancelled'): ?>
We're sorry to inform you that your booking has been cancelled. If you have any questions, please don't hesitate to contact us.

<?php elseif($booking->status === 'rescheduled' || ($booking->status === 'pending' && isset($booking->wasChanged) && $booking->wasChanged('cleaning_date'))): ?>
Your cleaning service has been rescheduled to:  
**<?php echo new \Illuminate\Support\EncodedHtmlString($booking->cleaning_date->format('l, F j')); ?> at <?php echo new \Illuminate\Support\EncodedHtmlString($booking->cleaning_date->format('g:i A')); ?>**

<?php if($rescheduleReason): ?>
**Reason for rescheduling:** <?php echo new \Illuminate\Support\EncodedHtmlString($rescheduleReason); ?>

<?php endif; ?>

If this new schedule doesn't work for you, please contact us as soon as possible.
<?php elseif($booking->status === 'pending'): ?>
Your booking is currently pending confirmation. We will notify you once it has been confirmed.
<?php endif; ?>

Thanks,  
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?><?php echo $__env->renderComponent(); ?>
<?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/emails/bookings/status_update.blade.php ENDPATH**/ ?>