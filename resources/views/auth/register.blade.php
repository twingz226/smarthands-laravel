<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cleaning Service</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            --text-dark: #2d3748;
            --text-muted: #718096;
            --border-color: #e2e8f0;
            --error-color: #e53e3e;
            --success-color: #38a169;
            --warning-color: #dd6b20;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
            padding: 2rem;
        }

        .auth-card {
            width: 100%;
            max-width: 28rem;
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .auth-card:hover {
            transform: translateY(-0.25rem);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .auth-header {
            padding: 2rem 2rem 1rem;
            text-align: center;
            background: white;
        }

        .auth-title {
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-body {
            padding: 1.5rem 2rem 2rem;
        }

        .form-floating {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: 2rem;
            font-size: 0.9375rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }

        .form-label {
            position: absolute;
            top: 0.75rem;
            left: 1.25rem;
            color: var(--text-muted);
            font-size: 0.875rem;
            transition: var(--transition);
            pointer-events: none;
            background: white;
            padding: 0 0.25rem;
            transform-origin: left center;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #667eea;
        }

        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--text-dark);
        }

        .auth-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 2rem;
            background: var(--secondary-gradient);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: var(--transition);
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .social-divider {
            position: relative;
            margin: 1.5rem 0;
            text-align: center;
        }

        .social-divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
            z-index: 1;
        }

        .social-divider-text {
            position: relative;
            display: inline-block;
            background: white;
            padding: 0 1rem;
            z-index: 2;
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            transition: var(--transition);
        }

        .social-btn:hover {
            background: #f7fafc;
            transform: translateY(-2px);
        }

        .invalid-feedback {
            display: block;
            color: var(--error-color);
            font-size: 0.75rem;
            margin-top: 0.25rem;
            padding-left: 1.25rem;
        }

        .is-invalid {
            border-color: var(--error-color);
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.25);
        }

        .password-strength {
            margin-top: 0.5rem;
            padding: 0 1.25rem;
        }

        .progress {
            height: 4px;
            background-color: #edf2f7;
            border-radius: 2px;
        }

        .progress-bar {
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .bg-danger { background-color: var(--error-color); }
        .bg-warning { background-color: var(--warning-color); }
        .bg-success { background-color: var(--success-color); }

        @media (max-width: 576px) {
            .auth-container {
                padding: 1rem;
            }
            .auth-card {
                border-radius: 0.75rem;
            }
            .auth-body {
                padding: 1.5rem 1.25rem 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Create Account</h1>
                <p class="text-muted">Join our community today</p>
            </div>

            <div class="auth-body">
                <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                    @csrf

                    <!-- Name Field -->
                    <div class="form-floating">
                        <input id="name" type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" 
                               required autocomplete="name" autofocus
                               placeholder="Full Name">
                        <label for="name">{{ __('Full Name') }}</label>
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-floating">
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                               required autocomplete="email"
                               placeholder="Email Address">
                        <label for="email">{{ __('Email Address') }}</label>
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-floating">
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password"
                               placeholder="Password">
                        <label for="password">{{ __('Password') }}</label>
                        <span class="password-toggle">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                        <div class="password-strength">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-floating">
                        <input id="password-confirm" type="password" 
                               class="form-control" 
                               name="password_confirmation" required 
                               autocomplete="new-password"
                               placeholder="Confirm Password">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <span class="password-toggle">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="auth-btn">
                        {{ __('Create Account') }}
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                    <!-- Divider -->
                    <div class="social-divider">
                        <span class="social-divider-text">OR</span>
                    </div>

                    <!-- Social Login -->
                    <div class="text-center mb-4">
                        <p class="text-muted">Sign up with</p>
                        <div class="social-buttons">
                            <a href="#" class="social-btn">
                                <i class="bi bi-google"></i>
                            </a>
                            <a href="#" class="social-btn">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="social-btn">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">Already have an account? 
                            <a href="{{ route('login') }}" class="text-primary fw-bold">Sign In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.password-toggle').forEach(function(el) {
            el.addEventListener('click', function() {
                const input = this.closest('.form-floating').querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                }
            });
        });

        // Password strength meter
        document.getElementById('password').addEventListener('input', function() {
            const strengthMeter = this.closest('.form-floating').querySelector('.progress-bar');
            const strength = calculatePasswordStrength(this.value);
            strengthMeter.style.width = strength + '%';
            strengthMeter.classList.remove('bg-danger', 'bg-warning', 'bg-success');
            
            if (strength < 30) {
                strengthMeter.classList.add('bg-danger');
            } else if (strength < 70) {
                strengthMeter.classList.add('bg-warning');
            } else {
                strengthMeter.classList.add('bg-success');
            }
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length > 0) strength += 10;
            if (password.length >= 8) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 30;
            return Math.min(strength, 100);
        }
    </script>
</body>
</html>