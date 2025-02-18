@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container">
    <div class="article-container">
        <div class="article-header">
            <div class="article-theme">{{ $article->theme->name }}</div>
            <h1>{{ $article->title }}</h1>
            <div class="article-meta">
                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    {{ $article->author->name }}
                </div>
                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    {{ $article->created_at->format('M d, Y') }}
                </div>
                @auth
                    <div class="meta-actions">
                    <a href="{{ route('comments.afficher',$article) }}" class="btn">View Comments</a>
                        @can('update', $article)
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                        @endcan
                        @can('delete', $article)
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endcan
                    </div>
                @endauth
            </div>
        </div>

        <div class="article-content">
            {!! $article->content !!}
        </div>

        @if(auth()->check())
            <div class="article-actions">
                <div class="rating-section">
                    <form action="{{ route('articles.rate', $article) }}" method="POST" class="rating-form">
                        @csrf
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="submit" name="rating" value="{{ $i }}" class="star-button">
                                    <svg class="star {{ $i <= ($userRating ?? 0) ? 'active' : '' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.article-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 0;
}

.article-header {
    margin-bottom: 2rem;
}

.article-theme {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 9999px;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.article-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.article-meta {
    display: flex;
    align-items: center;
    gap: 2rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.icon {
    width: 1rem;
    height: 1rem;
}

.article-content {
    font-size: 1.125rem;
    line-height: 1.75;
    color: #374151;
}

.article-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.rating-stars {
    display: flex;
    gap: 0.25rem;
}

.star-button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.star {
    width: 1.5rem;
    height: 1.5rem;
    fill: #e5e7eb;
    transition: fill 0.2s ease;
}

.star.active {
    fill: #fbbf24;
}

.star-button:hover .star {
    fill: #fbbf24;
}

.meta-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-outline-primary {
    border: 1px solid #3b82f6;
    color: #3b82f6;
    background: transparent;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: #3b82f6;
    color: white;
}
</style>
@endsection
