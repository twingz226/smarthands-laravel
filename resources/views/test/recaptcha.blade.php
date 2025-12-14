<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container mt-5">
        <h1>reCAPTCHA Test</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Configuration Status</h3>
                <ul>
                    <li>Site Key Configured: {{ config('services.recaptcha.key') ? 'Yes' : 'No' }}</li>
                    <li>Secret Key Configured: {{ config('services.recaptcha.secret') ? 'Yes' : 'No' }}</li>
                    <li>Environment: {{ app()->environment() }}</li>
                </ul>
                
                <h3>Test Form</h3>
                <form method="POST" action="{{ route('contact.message.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Test</button>
                </form>
                
                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
