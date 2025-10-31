<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rate Our Service</title>
    <link rel="icon" href="{{ asset('images/Smarthand.png') }}" />
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
                        @elseif(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @elseif(isset($alreadyRated) && $alreadyRated)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> This job has already been rated. Thank you for your feedback!
                                <p class="mt-3 mb-0">You can now close this window.</p>
                            </div>
                        @else
                            <div class="mb-4">
                                <h5>Job Details</h5>
                                <p><strong>Service:</strong> {{ $job->service->name }}</p>
                                <p><strong>Date:</strong> {{ $job->completed_at->format('M d, Y') }}</p>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('public.rating.submit', ['ratingToken' => $job->rating_token]) }}" method="POST" id="ratingForm">
                                @csrf
                                
                                @foreach($job->employees as $employee)
                                <div class="mb-4 text-center">
                                    @php
                                        $photoUrl = $employee->getPrimaryPhotoUrl();
                                    @endphp
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $employee->name }}" class="img-thumbnail mb-2" style="max-height: 140px; max-width: 140px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                    @else
                                        <img src="{{ asset('images/default-avatar.png') }}" alt="No Photo" class="img-thumbnail mb-2" style="max-height: 140px; max-width: 140px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                    @endif
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

                                <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                                    <i class="fas fa-paper-plane"></i> Submit Ratings
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ratingForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Check if at least one rating is selected for each employee
                    const employees = document.querySelectorAll('[name^="ratings["]');
                    const employeeIds = new Set();
                    
                    employees.forEach(input => {
                        const match = input.name.match(/ratings\[(\d+)\]/);
                        if (match) {
                            employeeIds.add(match[1]);
                        }
                    });
                    
                    let allRated = true;
                    employeeIds.forEach(employeeId => {
                        const selected = document.querySelector(`input[name="ratings[${employeeId}]"]:checked`);
                        if (!selected) {
                            allRated = false;
                        }
                    });
                    
                    if (!allRated) {
                        e.preventDefault();
                        alert('Please rate all cleaners before submitting.');
                        return false;
                    }
                    
                    // Disable button and show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                });
            }
        });
    </script>
</body>
</html> 