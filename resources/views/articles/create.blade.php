@extends('layouts.app')

@section('title', 'Submit Article')

@section('content')
<div class="container">
    <h1>Create New Article</h1>

    <form action="{{ route('articles.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                   id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="theme_id" class="form-label">Theme</label>
            <select class="form-select @error('theme_id') is-invalid @enderror" 
                    id="theme_id" name="theme_id" required>
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

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control @error('content') is-invalid @enderror" 
                      id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Article</button>
    </form>
</div>
@endsection
