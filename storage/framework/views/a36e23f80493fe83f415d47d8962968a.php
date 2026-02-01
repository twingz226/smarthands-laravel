<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Completed - Please Rate Our Service</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #28a745;">Thank You for Choosing Our Service!</h2>
        
        <p>Dear <?php echo e($job->customer->name); ?>,</p>
        
        <p>We have completed your cleaning service on <?php echo e($job->completed_at->format('M d, Y')); ?>.</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Job Details:</h3>
            <p><strong>Service:</strong> <?php echo e($job->service->name); ?></p>
            <p><strong>Date:</strong> <?php echo e($job->completed_at->format('M d, Y')); ?></p>
            <p><strong>Location:</strong> <?php echo e($job->customer->address); ?></p>
        </div>

        <p>We value your feedback! Please take a moment to rate our service by clicking the button below:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="<?php echo e(route('public.rating.form', ['ratingToken' => $job->rating_token])); ?>" 
               style="background: #ffc107; color: #000; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Rate Our Service
            </a>
        </div>

        <p>Your feedback helps us improve our service quality and better serve you in the future.</p>
        
        <hr style="border: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #666;">
            If you have any questions or concerns, please don't hesitate to contact us.
        </p>
    </div>
</body>
</html> <?php /**PATH /opt/lampp/htdocs/cleaning_service_management_system/resources/views/emails/jobs/completed.blade.php ENDPATH**/ ?>