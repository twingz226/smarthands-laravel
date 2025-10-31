


@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
<div class="container" style="height: auto; padding: 40px;">
    <h1 class="login-title">{{ __('Set New Password') }}</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <section class="input-box">
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                            <i class='bx bxs-user'></i>
                            @error('email')
                                <span class="error-message" role="alert" style="position: absolute; top: 100%; left: 0; width: 100%;">{{ $message }}</span>
                            @enderror
                        </section>

                        <section class="input-box">
                            <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                            <i class='bx bxs-lock-alt'></i>
                            @error('password')
                                <span class="error-message" role="alert" style="position: absolute; top: 100%; left: 0; width: 100%;">{{ $message }}</span>
                            @enderror
                        </section>

                        <section class="input-box">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            <i class='bx bxs-lock-alt'></i>
                        </section>

                        <button type="submit" class="login-button" style="margin-top: 20px;">
                            {{ __('Set New Password') }}
                        </button>
                    </form>
</div>
@endsection

