<?php $__env->startComponent('mail::message'); ?>
# Your Cleaning Service Has Been Cancelled

Dear <?php echo new \Illuminate\Support\EncodedHtmlString($job->customer->name ?? 'Valued Customer'); ?>,

We regret to inform you that your scheduled cleaning service has been cancelled.

<?php $__env->startComponent('mail::panel'); ?>
### Booking Details
- **Service:** <?php echo new \Illuminate\Support\EncodedHtmlString($job->service->name ?? 'N/A'); ?>

- **Scheduled Date:** <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($job->scheduled_date)->format('F j, Y')); ?>

- **Scheduled Time:** <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($job->scheduled_date)->format('g:i A')); ?>

<?php if($job->employees->count() > 0): ?>
- **Assigned Cleaner(s):** <?php echo new \Illuminate\Support\EncodedHtmlString($job->employees->pluck('name')->implode(', ')); ?>

<?php endif; ?>
- **Cancellation Reason:** <?php echo new \Illuminate\Support\EncodedHtmlString($cancellationReason); ?>

<?php echo $__env->renderComponent(); ?>

If you have any questions or would like to reschedule, please don't hesitate to contact our customer service team.

We apologize for any inconvenience this may have caused and hope to serve you in the future.

Best regards,  
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?> Team

---
*This is an automated notification. Please do not reply to this email.*
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/emails/job-cancelled.blade.php ENDPATH**/ ?>