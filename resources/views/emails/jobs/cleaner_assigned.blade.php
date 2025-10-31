<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cleaners Assigned to Your Service</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #007bff;">Cleaners Assigned to Your Service!</h2>
        
        <p>Dear {{ $job->customer->name }},</p>
        
        <p>Great news! We have assigned professional cleaners to your service. Here are the details:</p>
        
        <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #007bff;">
            <h3 style="margin-top: 0; color: #007bff;">The following cleaners will arrive for your service:</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($cleaners as $employee)
                    <li style="display: flex; align-items: center; margin-bottom: 15px;">
                        @if($employee->getPrimaryPhotoUrl())
                            <img src="{{ $employee->getPrimaryPhotoUrl() }}" alt="{{ $employee->name }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #007bff; margin-right: 15px;">
                        @endif
                        <div>
                            <strong>{{ $employee->name }}</strong><br>
                            <span style="font-size: 14px; color: #555;">Phone: {{ $employee->phone }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #007bff;">Service Details:</h3>
            <p><strong>Service:</strong> {{ $job->service->name }}</p>
            <p><strong>Scheduled Date:</strong> {{ $job->scheduled_date->format('M d, Y H:i') }}</p>
            <p><strong>Location:</strong> {{ $job->address ?? $job->customer->address }}</p>
            @if($job->special_instructions)
                <p><strong>Special Instructions:</strong> {{ $job->special_instructions }}</p>
            @endif
        </div>

        <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #007bff;">
            <h3 style="margin-top: 0; color: #007bff;">Assigned Cleaners:</h3>
            @foreach($cleaners as $employee)
                <div style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 5px; border: 1px solid #dee2e6;">
                    <div style="display: flex; align-items: start; gap: 15px;">
                        @if($employee->getPrimaryPhotoUrl())
                            <div style="flex-shrink: 0;">
                                <img src="{{ $employee->getPrimaryPhotoUrl() }}" 
                                     alt="{{ $employee->name }}" 
                                     style="width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 2px solid #007bff;">
                            </div>
                        @endif
                        <div style="flex-grow: 1;">
                            <h4 style="margin: 0 0 8px 0; color: #333;">{{ $employee->name }}</h4>
                            <p style="margin: 5px 0;"><strong>Phone:</strong> {{ $employee->phone }}</p>
                            <p style="margin: 5px 0;"><strong>Address:</strong> {{ $employee->address }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">What to Expect:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Our cleaners will arrive at the scheduled time</li>
                <li>They will introduce themselves and show their identification</li>
                <li>You can verify their identity using the photos provided above</li>
                <li>Feel free to communicate any specific requirements</li>
                <li>You can contact them directly if needed</li>
            </ul>
        </div>

        <div style="background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h4 style="margin-top: 0; color: #155724;">Security & Verification:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li>All our cleaners are trained professionals with verified photos</li>
                <li>They carry proper identification matching their photos</li>
                <li>Photos are regularly updated and approved by management</li>
                <li>You'll receive a notification when they start the job</li>
                <li>After completion, you'll be asked to rate our service</li>
            </ul>
        </div>

        <p>If you have any questions or need to make changes to your appointment, please contact us immediately.</p>
        
        <hr style="border: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #666;">
            Thank you for choosing our cleaning service. We appreciate your business!
        </p>
    </div>
</body>
</html> 