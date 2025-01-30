@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Create an Account</h2>
            <p>Join Tech Horizons and start exploring technology articles</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
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
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>

            <div class="auth-links">
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
