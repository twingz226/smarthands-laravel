<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Our Service - Job #{{ $job->id }}</title>
    <link rel="icon" href="{{ asset('images/Smarthands.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rating-stars {
            font-size: 2rem;
            color: #ffc107;
            cursor: pointer;
        }
        .rating-stars:hover {
            color: #ffdb4d;
        }
        .rating-stars.selected {
            color: #ffc107;
        }
        .rating-stars.unselected {
            color: #e4e5e9;
        }
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .service-details {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-star"></i> Rate Our Service
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Service Details -->
                        <div class="form-section service-details">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle"></i> Service Details
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Job ID:</strong> #{{ $job->id }}</p>
                                    <p><strong>Service:</strong> {{ $job->service->name }}</p>
                                    <p><strong>Date:</strong> {{ $job->completed_at->format('M d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Location:</strong> {{ $job->address ?? $job->customer->address }}</p>
                                    <p><strong>Cleaners:</strong></p>
                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        @foreach($job->employees as $employee)
                                            <div class="text-center">
                                                @if($employee->hasApprovedPhotos() && $employee->getPrimaryPhotoUrl())
                                                    <img src="{{ $employee->getPrimaryPhotoUrl() }}" alt="{{ $employee->name }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; margin-bottom: 8px;">
                                                @else
                                                    <div style="width: 120px; height: 120px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 2.5rem; border: 3px solid #ced4da; margin-bottom: 8px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div style="font-size: 1rem; color: #333; font-weight: 500;">{{ $employee->name }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('feedback.store', $job->rating_token) }}" method="POST">
                            @csrf
                            
                            <!-- Overall Rating -->
                            <div class="form-section">
                                <h5 class="mb-3">
                                    <i class="fas fa-star"></i> Overall Satisfaction
                                </h5>
                                <div class="rating-container mb-3">
                                    <div class="rating-stars" data-rating="1">★</div>
                                    <div class="rating-stars" data-rating="2">★</div>
                                    <div class="rating-stars" data-rating="3">★</div>
                                    <div class="rating-stars" data-rating="4">★</div>
                                    <div class="rating-stars" data-rating="5">★</div>
                                    <input type="hidden" name="overall_rating" id="overall_rating" required>
                                </div>
                                <small class="text-muted">Click on the stars to rate your overall satisfaction</small>
                                @error('overall_rating')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Detailed Ratings -->
                            <div class="form-section">
                                <h5 class="mb-3">
                                    <i class="fas fa-chart-bar"></i> Detailed Ratings (Optional)
                                </h5>
                                
                                <!-- Cleanliness -->
                                <div class="mb-4">
                                    <label class="form-label">Cleanliness Quality</label>
                                    <div class="rating-container">
                                        <div class="rating-stars" data-rating="1" data-field="cleanliness">★</div>
                                        <div class="rating-stars" data-rating="2" data-field="cleanliness">★</div>
                                        <div class="rating-stars" data-rating="3" data-field="cleanliness">★</div>
                                        <div class="rating-stars" data-rating="4" data-field="cleanliness">★</div>
                                        <div class="rating-stars" data-rating="5" data-field="cleanliness">★</div>
                                        <input type="hidden" name="cleanliness_rating" id="cleanliness_rating">
                                    </div>
                                </div>

                                <!-- Professionalism -->
                                <div class="mb-4">
                                    <label class="form-label">Professionalism</label>
                                    <div class="rating-container">
                                        <div class="rating-stars" data-rating="1" data-field="professionalism">★</div>
                                        <div class="rating-stars" data-rating="2" data-field="professionalism">★</div>
                                        <div class="rating-stars" data-rating="3" data-field="professionalism">★</div>
                                        <div class="rating-stars" data-rating="4" data-field="professionalism">★</div>
                                        <div class="rating-stars" data-rating="5" data-field="professionalism">★</div>
                                        <input type="hidden" name="professionalism_rating" id="professionalism_rating">
                                    </div>
                                </div>

                                <!-- Punctuality -->
                                <div class="mb-4">
                                    <label class="form-label">Punctuality</label>
                                    <div class="rating-container">
                                        <div class="rating-stars" data-rating="1" data-field="punctuality">★</div>
                                        <div class="rating-stars" data-rating="2" data-field="punctuality">★</div>
                                        <div class="rating-stars" data-rating="3" data-field="punctuality">★</div>
                                        <div class="rating-stars" data-rating="4" data-field="punctuality">★</div>
                                        <div class="rating-stars" data-rating="5" data-field="punctuality">★</div>
                                        <input type="hidden" name="punctuality_rating" id="punctuality_rating">
                                    </div>
                                </div>

                                <!-- Communication -->
                                <div class="mb-4">
                                    <label class="form-label">Communication</label>
                                    <div class="rating-container">
                                        <div class="rating-stars" data-rating="1" data-field="communication">★</div>
                                        <div class="rating-stars" data-rating="2" data-field="communication">★</div>
                                        <div class="rating-stars" data-rating="3" data-field="communication">★</div>
                                        <div class="rating-stars" data-rating="4" data-field="communication">★</div>
                                        <div class="rating-stars" data-rating="5" data-field="communication">★</div>
                                        <input type="hidden" name="communication_rating" id="communication_rating">
                                    </div>
                                </div>

                                <!-- Value for Money -->
                                <div class="mb-4">
                                    <label class="form-label">Value for Money</label>
                                    <div class="rating-container">
                                        <div class="rating-stars" data-rating="1" data-field="value">★</div>
                                        <div class="rating-stars" data-rating="2" data-field="value">★</div>
                                        <div class="rating-stars" data-rating="3" data-field="value">★</div>
                                        <div class="rating-stars" data-rating="4" data-field="value">★</div>
                                        <div class="rating-stars" data-rating="5" data-field="value">★</div>
                                        <input type="hidden" name="value_rating" id="value_rating">
                                    </div>
                                </div>
                            </div>

                            <!-- Comments -->
                            <div class="form-section">
                                <h5 class="mb-3">
                                    <i class="fas fa-comment"></i> Additional Comments
                                </h5>
                                <div class="mb-3">
                                    <textarea class="form-control" name="comments" rows="4" 
                                              placeholder="Tell us about your experience, suggestions for improvement, or any other comments..."></textarea>
                                </div>
                            </div>

                            <!-- Anonymous Option -->
                            <div class="form-section">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous" value="1">
                                    <label class="form-check-label" for="is_anonymous">
                                        Submit feedback anonymously
                                    </label>
                                </div>
                                <small class="text-muted">Your feedback will be used to improve our services</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> Submit Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const ratingContainers = document.querySelectorAll('.rating-container');
            
            ratingContainers.forEach(container => {
                const stars = container.querySelectorAll('.rating-stars');
                const hiddenInput = container.querySelector('input[type="hidden"]');
                
                stars.forEach((star, index) => {
                    star.addEventListener('click', function() {
                        const rating = this.getAttribute('data-rating');
                        hiddenInput.value = rating;
                        
                        // Update star display
                        stars.forEach((s, i) => {
                            if (i < rating) {
                                s.classList.add('selected');
                                s.classList.remove('unselected');
                            } else {
                                s.classList.add('unselected');
                                s.classList.remove('selected');
                            }
                        });
                    });
                    
                    star.addEventListener('mouseenter', function() {
                        const rating = this.getAttribute('data-rating');
                        stars.forEach((s, i) => {
                            if (i < rating) {
                                s.style.color = '#ffdb4d';
                            } else {
                                s.style.color = '#e4e5e9';
                            }
                        });
                    });
                    
                    star.addEventListener('mouseleave', function() {
                        const selectedRating = hiddenInput.value;
                        stars.forEach((s, i) => {
                            if (selectedRating && i < selectedRating) {
                                s.style.color = '#ffc107';
                            } else if (selectedRating) {
                                s.style.color = '#e4e5e9';
                            } else {
                                s.style.color = '#ffc107';
                            }
                        });
                    });
                });
            });
        });
    </script>
</body>
</html> 