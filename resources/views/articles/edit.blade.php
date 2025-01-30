@extends('layouts.app')

@section('title', 'Edit Article')

@section('content')
<div class="container">
    <h1>Edit Article</h1>

    <form action="{{ route('articles.update', $article) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                   id="title" name="title" value="{{ old('title', $article->title) }}" required>
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
                    <option value="{{ $theme->id }}" 
                        {{ old('theme_id', $article->theme_id) == $theme->id ? 'selected' : '' }}>
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
                      id="content" name="content" rows="10" required>{{ old('content', $article->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Article</button>
        <a href="{{ route('articles.show', $article) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
