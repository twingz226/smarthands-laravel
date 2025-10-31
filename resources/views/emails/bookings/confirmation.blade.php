@component('mail::message')
# Booking Received!

Hi {{ $booking->customer->name }},

Your {{ $booking->service->name }} is scheduled for:  
**{{ $booking->cleaning_date->format('l, F j') }} at {{ $booking->cleaning_date->format('g:i A') }}**

@component('mail::button', ['url' => route('booking.view', $booking)])
View Booking Details
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent