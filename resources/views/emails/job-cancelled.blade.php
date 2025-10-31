@component('mail::message')
# Your Cleaning Service Has Been Cancelled

Dear {{ $job->customer->name ?? 'Valued Customer' }},

We regret to inform you that your scheduled cleaning service has been cancelled.

@component('mail::panel')
### Booking Details
- **Service:** {{ $job->service->name ?? 'N/A' }}
- **Scheduled Date:** {{ \Carbon\Carbon::parse($job->scheduled_date)->format('F j, Y') }}
- **Scheduled Time:** {{ \Carbon\Carbon::parse($job->scheduled_date)->format('g:i A') }}
@if($job->employees->count() > 0)
- **Assigned Cleaner(s):** {{ $job->employees->pluck('name')->implode(', ') }}
@endif
- **Cancellation Reason:** {{ $cancellationReason }}
@endcomponent

If you have any questions or would like to reschedule, please don't hesitate to contact our customer service team.

We apologize for any inconvenience this may have caused and hope to serve you in the future.

Best regards,  
{{ config('app.name') }} Team

---
*This is an automated notification. Please do not reply to this email.*
@endcomponent
