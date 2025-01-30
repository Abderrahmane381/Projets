@extends('layouts.app')

@section('title', $issue->title)

@section('content')
<div class="container">
    <div class="issue-container">
        <div class="issue-header">
            <div class="issue-status {{ $issue->is_public ? 'status-public' : 'status-private' }}">
                {{ $issue->is_public ? 'Public Issue' : 'Private Issue' }}
            </div>
            <h1>{{ $issue->title }}</h1>
            <div class="issue-meta">
                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    {{ $issue->articles->count() }} Articles
                </div>
                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    Published {{ $issue->created_at->format('M d, Y') }}
                </div>
            </div>
            <p class="issue-description">{{ $issue->description }}</p>
        </div>

        <div class="articles-section">
            <h2>Articles in this Issue</h2>
            <div class="articles-list">
                @foreach($issue->articles as $article)
                    <div class="article-card">
                        <div class="article-theme">{{ $article->theme->name }}</div>
                        <h3 class="article-title">
                            <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                        </h3>
                        <div class="article-meta">
                            <span class="author">By {{ $article->author->name }}</span>
                            <span class="reading-time">5 min read</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.issue-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 0;
}

.issue-header {
    margin-bottom: 3rem;
    text-align: center;
}

.issue-status {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.status-public {
    background-color: #dcfce7;
    color: #166534;
}

.status-private {
    background-color: #f3f4f6;
    color: #374151;
}

.issue-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.issue-meta {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.issue-description {
    color: #4b5563;
    font-size: 1.125rem;
    line-height: 1.75;
    max-width: 600px;
    margin: 0 auto;
}

.articles-section {
    border-top: 1px solid #e5e7eb;
    padding-top: 2rem;
}

.articles-section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.articles-list {
    display: grid;
    gap: 1.5rem;
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
    font-size: 0.75rem;
    margin-bottom: 0.75rem;
}

.article-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.article-title a {
    color: #1f2937;
    text-decoration: none;
}

.article-title a:hover {
    color: #3b82f6;
}

.article-meta {
    display: flex;
    justify-content: space-between;
    color: #6b7280;
    font-size: 0.875rem;
}

.icon {
    width: 1rem;
    height: 1rem;
}
</style>
@endsection