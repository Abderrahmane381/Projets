@extends('layouts.app')

@section('title', 'Articles')

@section('content')
<div class="container">
    <div class="articles-header">
        <h1>Latest Articles</h1>
        @auth
            <a href="{{ route('articles.create') }}" class="btn btn-primary">Write Article</a>
        @endauth
    </div>

    <div class="articles-grid">
        @foreach($articles as $article)
            <div class="article-card">
                <div class="article-theme">{{ $article->theme->name }}</div>
                <h2 class="article-title">
                    <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                </h2>
                <div class="article-excerpt">
                    {{ Str::limit(strip_tags($article->content), 150) }}
                </div>
                <div class="article-meta">
                    <div class="article-author">
                        <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        {{ $article->author->name }}
                    </div>
                    <div class="article-date">
                        <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        {{ $article->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination-container">
        {{ $articles->links() }}
    </div>
</div>

<style>
.articles-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.article-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.article-card:hover {
    transform: translateY(-2px);
}

.article-theme {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 9999px;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

.article-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.article-title a {
    color: #1f2937;
    text-decoration: none;
}

.article-title a:hover {
    color: #3b82f6;
}

.article-excerpt {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.article-meta {
    display: flex;
    justify-content: space-between;
    color: #6b7280;
    font-size: 0.875rem;
}

.article-author, .article-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.icon {
    width: 1rem;
    height: 1rem;
}

.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}
</style>
@endsection