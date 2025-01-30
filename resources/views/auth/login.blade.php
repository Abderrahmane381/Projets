@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Login to Tech Horizons</h2>
            <p>Enter your credentials to access your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <div class="auth-links">
                <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
