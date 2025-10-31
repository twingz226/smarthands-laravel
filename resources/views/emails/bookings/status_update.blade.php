@component('mail::message')
# Booking Status Update

Hi {{ $booking->customer->name }},

Your booking for {{ $booking->service->name }} has been **{{ $booking->status }}**.

@if($booking->status === 'confirmed')
Your cleaning service is confirmed for:  
**{{ $booking->cleaning_date->format('l, F j') }} at {{ $booking->cleaning_date->format('g:i A') }}**

Please ensure someone will be available at the location during this time.

@elseif($booking->status === 'cancelled')
We're sorry to inform you that your booking has been cancelled. If you have any questions, please don't hesitate to contact us.

@elseif($booking->status === 'rescheduled' || ($booking->status === 'pending' && isset($booking->wasChanged) && $booking->wasChanged('cleaning_date')))
Your cleaning service has been rescheduled to:  
**{{ $booking->cleaning_date->format('l, F j') }} at {{ $booking->cleaning_date->format('g:i A') }}**

If this new schedule doesn't work for you, please contact us as soon as possible.
@elseif($booking->status === 'pending')
Your booking is currently pending confirmation. We will notify you once it has been confirmed.
@endif

Thanks,  
{{ config('app.name') }}@endcomponent
