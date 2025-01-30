@extends('layouts.app')

@section('title', 'Review Article')

@section('content')
<div class="review-container">
    <header class="review-header">
        <h1>Review Article</h1>
        <span class="theme-tag">{{ $article->theme->name }}</span>
    </header>

    <div class="article-preview">
        <h2>{{ $article->title }}</h2>
        <div class="article-meta">
            <span>By {{ $article->author->name }}</span>
            <span>Submitted {{ $article->created_at->diffForHumans() }}</span>
        </div>

        @if($article->image)
            <div class="article-image">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
            </div>
        @endif

        <div class="article-content">
            {{ $article->content }}
        </div>
    </div>

    <form action="{{ route('theme-responsible.articles.update-status', $article) }}" method="POST" class="review-form">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label>Decision</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="status" value="published" required>
                    Published
                </label>
                <label class="radio-label">
                    <input type="radio" name="status" value="rejected" required>
                    Reject
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="feedback">Feedback</label>
            <textarea name="feedback" id="feedback" rows="5" required 
                placeholder="Provide detailed feedback for the author"></textarea>
            @error('feedback')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit Review</button>
            <a href="{{ route('theme-responsible.articles.index', $article->theme) }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
