@include('admin.partials.header')

<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <div class="container-fluid py-4 px-5">
                <h3>Welcome to <strong> Smarthands Cleaning Service Management System</strong></h3>
            </div>
        </div>
    </div>
</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h3>⭐ Feedback/Rating Report</h3>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Cleaner</th>
                        <th>Rating</th>
                        <th>Feedback</th>
                        <th>Date</th>
                        <th>Job Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ratings as $rating)
                        <tr>
                            <td>{{ $rating->customer->name ?? 'N/A' }}</td>
                            <td>{{ $rating->employee->name ?? 'N/A' }}</td>
                            <td>
                                <div class="star-rating" title="{{ $rating->rating }} out of 5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($rating->rating))
                                            <span class="star filled">★</span>
                                        @elseif($i == ceil($rating->rating) && ($rating->rating - floor($rating->rating)) >= 0.5)
                                            <span class="star half">★</span>
                                        @else
                                            <span class="star">☆</span>
                                        @endif
                                    @endfor
                                    <span class="rating-value">({{ number_format($rating->rating, 1) }})</span>
                                </div>
                            </td>
                            <td>{{ $rating->feedback ?? 'No feedback provided' }}</td>
                            <td>{{ $rating->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if($rating->job)
                                    {{ $rating->job->service_type ?? 'N/A' }} - 
                                    {{ $rating->job->created_at->format('M d, Y') }}
                                @else
                                    Job not found
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No feedback records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($ratings->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $ratings->links() }}
                </div>
            @endif
        </div>
    </div>


<style>
    .star-rating {
        display: inline-flex;
        align-items: center;
    }
    .star {
        color: #ddd;
        font-size: 1.2rem;
    }
    .star.filled {
        color: #ffc107;
    }
    .star.half {
        position: relative;
    }
    .star.half:before {
        position: absolute;
        content: '★';
        width: 50%;
        overflow: hidden;
        color: #ffc107;
    }
    .rating-value {
        margin-left: 5px;
        font-size: 0.9rem;
        color: #666;
    }
</style>

@include('admin.partials.scripts')