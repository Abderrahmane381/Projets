@extends('layouts.app')

@section('title', 'Create Theme')

@section('content')
@include('editor.shared.form-styles')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1>Create New Theme</h1>
        <a href="{{ route('editor.themes.index') }}" class="btn btn-outline">Back to Themes</a>
    </div>

    <div class="form-container">
        <form action="{{ route('editor.themes.store') }}" method="POST" class="form-custom">
            @csrf
            <div class="form-section">
                <div class="form-group">
                    <label for="name">Theme Name</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" 
                        class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="theme_responsible_id">Theme Responsible</label>
                    <select name="theme_responsible_id" id="theme_responsible_id" 
                        class="form-control @error('theme_responsible_id') is-invalid @enderror" required>
                        <option value="">Select Theme Responsible</option>
                        @foreach($themeResponsibles as $responsible)
                            <option value="{{ $responsible->id }}" 
                                {{ old('theme_responsible_id') == $responsible->id ? 'selected' : '' }}>
                                {{ $responsible->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('theme_responsible_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Theme</button>
                <a href="{{ route('editor.themes.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-container {
    max-width: 600px;
    margin: 2rem auto;
}

.form-section {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-control {
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 0.75rem;
    width: 100%;
    transition: border-color 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.custom-switch {
    padding-left: 2.5rem;
}
</style>
@endsection
