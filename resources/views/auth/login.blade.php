<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <title>Welcome Admin</title>
    <style>
        .error-message {
            color: #ff5555;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            text-align: center;
            border: 1px solid #ff5555;
            border-radius: 5px;
            background-color: whitesmoke;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('login') }}" class="container">
        @csrf
        <h1 class="login-title">Login</h1>

        <!-- Display general errors -->
        @if($errors->any())
            <div class="error-message">
                @if($errors->has('email'))
                    {{ $errors->first('email') }}
                @elseif($errors->has('password'))
                    {{ $errors->first('password') }}
                @else
                    Invalid credentials. Please try again.
                @endif
            </div>
        @endif

        <section class="input-box">
            <input type="text" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus />
            <i class='bx bxs-user'></i>
        </section>

        <section class="input-box">
            <input type="password" name="password" id="password" placeholder="Password" required />
            <i class='bx bx-show' id="togglePassword" style="right: 14px;"></i>
            <i class='bx bxs-lock-alt' style="left: 14px;"></i>
        </section>

        <section class="remember-forgot-box">
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                <label for="remember"><h5>Remember me</h5></label>
            </div>
            <a class="forgot-password" href="{{ route('password.request') }}"><h5>Forgot password?</h5></a>
        </section>

        <button class="login-button" type="submit">Login</button>
        
        <div class="mt-3 text-center">
            <p class="mb-0">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary">
                    Register
                </a>
            </p>
        </div>
    </form>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            togglePassword.classList.toggle('bx-show');
            togglePassword.classList.toggle('bx-hide');
        });
    </script>
</body>
</html>