@extends('layouts.customer')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Rate Your Cleaning Service</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Job Details</h5>
                        <p><strong>Service:</strong> {{ $job->service->name }}</p>
                        <p><strong>Date:</strong> {{ $job->completed_at->format('M d, Y') }}</p>
                    </div>

                    <form action="{{ route('customer.jobs.submit-rating', $job->id) }}" method="POST">
                        @csrf
                        
                        @foreach($job->employees as $employee)
                        <div class="mb-4">
                            <h5>Rate {{ $employee->name }}</h5>
                            <div class="star-rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" 
                                           id="star{{ $employee->id }}_{{ $i }}" 
                                           name="ratings[{{ $employee->id }}]" 
                                           value="{{ $i }}" 
                                           class="d-none" 
                                           required>
                                    <label for="star{{ $employee->id }}_{{ $i }}" class="star-label" title="{{ $i }} stars">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            @error("ratings.{$employee->id}")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach

                        <div class="mb-4">
                            <label for="comments" class="form-label">Your Overall Feedback (Optional)</label>
                            <textarea class="form-control" id="comments" name="comments" rows="4" 
                                placeholder="Tell us about your experience...">{{ old('comments') }}</textarea>
                            @error('comments')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Ratings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        margin-bottom: 1rem;
    }
    
    .star-label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        padding: 0 0.2em;
    }
    
    .star-label:hover,
    .star-label:hover ~ .star-label,
    input:checked ~ .star-label {
        color: #ffc107;
    }
    
    .star-label:hover,
    .star-label:hover ~ .star-label {
        transform: scale(1.1);
    }
</style>
@endpush
@endsection 