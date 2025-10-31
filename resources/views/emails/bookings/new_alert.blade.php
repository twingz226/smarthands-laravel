@component('mail::message')
# New Booking Alert

A new booking has been received:

**Customer:** {{ $booking->customer->name }}
**Email:** {{ $booking->customer->email }}
**Contact:** {{ $booking->customer->contact }}
**Service:** {{ $booking->service->name }}
**Date:** {{ $booking->cleaning_date->format('l, F j') }} at {{ $booking->cleaning_date->format('g:i A') }}
**Address:** {{ $booking->customer->address }}

@if($booking->special_instructions)
**Special Instructions:**  
{{ $booking->special_instructions }}
@endif

@component('mail::button', ['url' => route('booking.view', $booking)])
View Booking Details
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent