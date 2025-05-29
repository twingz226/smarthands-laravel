@component('mail::message')
# Booking Received!

Hi {{ $booking->customer->name }},

Your {{ $booking->service->name }} is scheduled for:  
**{{ $booking->service_date->format('l, F j') }} at {{ $booking->service_time }}**

@component('mail::button', ['url' => route('booking.view', $booking)])
View Booking Details
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent