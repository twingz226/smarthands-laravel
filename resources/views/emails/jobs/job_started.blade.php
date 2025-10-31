<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Cleaning Service Has Started</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #28a745;">Your Cleaning Service Has Started! 🧹</h2>
        
        <p>Dear {{ $job->customer->name }},</p>
        
        <p>Great news! Our cleaning team has arrived and started working on your service.</p>
        
        <div style="background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h3 style="margin-top: 0; color: #155724;">Service Status Update:</h3>
            <p><strong>Service:</strong> {{ $job->service->name }}</p>
            <p><strong>Started At:</strong> {{ $job->started_at->format('M d, Y H:i') }}</p>
            <p><strong>Location:</strong> {{ $job->address ?? $job->customer->address }}</p>
            <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">IN PROGRESS</span></p>
        </div>

        <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #007bff;">
            <h3 style="margin-top: 0; color: #007bff;">Your Cleaning Team:</h3>
            @foreach($job->employees as $employee)
                <div style="margin-bottom: 10px; padding: 8px; background: white; border-radius: 3px;">
                    <strong>{{ $employee->name }}</strong> - {{ $employee->phone }}
                </div>
            @endforeach
        </div>

        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">What's Happening Now:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Our professional cleaners are working on your property</li>
                <li>They will complete all requested services thoroughly</li>
                <li>You'll receive another notification when the job is completed</li>
                <li>Feel free to communicate with the team if needed</li>
            </ul>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #6c757d;">
            <h4 style="margin-top: 0; color: #495057;">Important Reminders:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Our team carries proper identification</li>
                <li>They are trained professionals with experience</li>
                <li>Please provide access to all areas that need cleaning</li>
                <li>You'll be asked to rate our service after completion</li>
            </ul>
        </div>

        <div style="background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #383d41;">Need to Contact Us?</h4>
            <p style="margin: 5px 0;">If you have any questions or concerns during the service:</p>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Contact the cleaning team directly using the phone numbers above</li>
                <li>Call our support line for immediate assistance</li>
                <li>Send us an email for non-urgent matters</li>
            </ul>
        </div>

        <p>Thank you for choosing our cleaning service. We're working hard to make your space spotless!</p>
        
        <hr style="border: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #666;">
            This is an automated notification. Please do not reply to this email.
        </p>
    </div>
</body>
</html> 