<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rate Our Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
            margin-bottom: 1rem;
        }
        
        .star-rating input[type="radio"] {
            display: none;
        }
        
        .star-rating label {
            font-size: 2.5rem;
            color: #ddd;
            cursor: pointer;
            padding: 0 0.2em;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Rate Our Service</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                                <p class="mt-3 mb-0">You can now close this window.</p>
                            </div>
                        @else
                            <div class="mb-4">
                                <h5>Job Details</h5>
                                <p><strong>Service:</strong> {{ $job->service->name }}</p>
                                <p><strong>Date:</strong> {{ $job->completed_at->format('M d, Y') }}</p>
                            </div>

                            <form action="{{ route('public.rating.submit', ['token' => $job->rating_token]) }}" method="POST">
                                @csrf
                                
                                @foreach($job->employees as $employee)
                                <div class="mb-4">
                                    <h5>Rate {{ $employee->name }}</h5>
                                    <div class="star-rating">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" 
                                                   id="star{{ $employee->id }}_{{ $i }}" 
                                                   name="ratings[{{ $employee->id }}]" 
                                                   value="{{ $i }}" 
                                                   required>
                                            <label for="star{{ $employee->id }}_{{ $i }}" title="{{ $i }} stars">
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 