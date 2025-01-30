@extends('layouts.app')

@section('title', 'Create Article')

@section('content')
@include('editor.shared.form-styles')
<div class="container">
    <header class="page-header">
        <h1>Create New Article</h1>
    </header>

    <form action="{{ route('editor.articles.store') }}" method="POST" class="article-form">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="theme_id">Theme</label>
            <select name="theme_id" id="theme_id" class="form-control @error('theme_id') is-invalid @enderror" required>
                <option value="">Select Theme</option>
                @foreach($themes as $theme)
                    <option value="{{ $theme->id }}" {{ old('theme_id') == $theme->id ? 'selected' : '' }}>
                        {{ $theme->name }}
                    </option>
                @endforeach
            </select>
            @error('theme_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" rows="10" 
                class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Article</button>
            <a href="{{ route('editor.articles.review') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.article-form {
    max-width: 800px;
    margin: 0 auto;
}
.form-group {
    margin-bottom: 1rem;
}
.form-actions {
    margin-top: 2rem;
}
</style>
@endsection
