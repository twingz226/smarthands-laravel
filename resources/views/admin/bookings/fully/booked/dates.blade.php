@if ($fullyBookedDates->isEmpty())
    <p>No fully booked dates found.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Number of Bookings</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fullyBookedDates as $date)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date->booking_date)->format('F d, Y') }}</td>
                    <td>{{ $date->booking_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
