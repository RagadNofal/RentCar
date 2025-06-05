@extends('layouts.myapp')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow-sm p-4 w-100" style="max-width: 500px; margin-top: -100px;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <h3 class="text-center mb-4">Login</h3>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') ? old('email') : 'test_user@email.com' }}"
                       class="form-control"
                       placeholder="test_user@email.com">
                @error('email')
                    <div class="text-danger mt-1"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Your Password</label>
                <input type="password" id="password" name="password"
                       value="pass1234"
                       class="form-control"
                       placeholder="Demo for test: pass1234">
                @error('password')
                    <div class="text-danger mt-1"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember"
                       name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            {{-- Login Button --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary text-white">
                    Login
                </button>
            </div>

            {{-- Forgot Password --}}
            @if (Route::has('password.request'))
                <div class="mt-3 text-center">
                    <a class="text-decoration-none text-muted" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
            @endif

            {{-- Register Link --}}
            <div class="mt-3 text-center">
                <span>Don't have an account yet?</span>
                <a href="{{ route('register') }}" class="text-decoration-none fw-medium text-primary">
                    Register here
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

