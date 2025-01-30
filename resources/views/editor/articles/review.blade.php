@extends('layouts.app')

@section('title', 'Review Articles')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <h1>Review Articles</h1>
    </div>

    <div class="articles-list">
        @forelse($articles as $article)
            <div class="article-card">
                <div class="article-main">
                    <div class="article-info">
                        <h3 class="article-title">{{ $article->title }}</h3>
                        <div class="article-meta">
                            <span class="author">
                                <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                {{ $article->author->name }}
                            </span>
                            <span class="theme">
                                <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                                {{ $article->theme->name }}
                            </span>
                            <span class="date">
                                <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                {{ $article->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="article-actions">
                        <form action="{{ route('editor.articles.update-status', $article) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select status-select" onchange="this.form.submit()">
                                <option value="submitted" {{ $article->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="approved" {{ $article->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $article->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>No articles pending review.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>

<style>
.article-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.article-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.article-info {
    flex: 1;
}

.article-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.article-meta {
    display: flex;
    gap: 1.5rem;
    color: #64748b;
    font-size: 0.875rem;
}

.article-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.icon {
    width: 1rem;
    height: 1rem;
}

.status-select {
    min-width: 140px;
    padding: 0.5rem;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    background: #f8fafc;
    border-radius: 8px;
    color: #64748b;
}
</style>
@endsection