<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Feedback</title>
    <link rel="icon" href="{{ asset('images/Smarthands.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .thank-you-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 4rem;
            color: #28a745;
        }
        .rating-display {
            font-size: 2rem;
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card text-center">
                        <div class="card-body p-5">
                            <div class="success-icon mb-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            
                            <h2 class="card-title text-primary mb-4">Thank You!</h2>
                            
                            <p class="card-text lead mb-4">
                                Your feedback has been submitted successfully. We appreciate you taking the time to share your experience with us.
                            </p>

                            @if($feedback->overall_rating)
                            <div class="mb-4">
                                <p class="text-muted mb-2">Your Overall Rating:</p>
                                <div class="rating-display">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->overall_rating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-muted">{{ $feedback->overall_rating }}/5 stars</p>
                            </div>
                            @endif

                            @if($feedback->comments)
                            <div class="mb-4">
                                <p class="text-muted mb-2">Your Comments:</p>
                                <div class="bg-light p-3 rounded">
                                    <em>"{{ $feedback->comments }}"</em>
                                </div>
                            </div>
                            @endif

                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> What happens next?</h6>
                                <ul class="list-unstyled mb-0 text-start">
                                    <li>✓ Your feedback will be reviewed by our team</li>
                                    <li>✓ We'll use your input to improve our services</li>
                                    <li>✓ If you provided contact information, we may follow up</li>
                                </ul>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="fas fa-home"></i> Return to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 