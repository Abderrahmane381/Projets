@extends('layouts.app')

@section('title', "Manage Articles - {$theme->name}")

@section('content')
@include('editor.shared.form-styles')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $theme->name }} - Articles</h1>
        <a href="{{ route('theme-responsible.articles.create', $theme) }}" class="btn btn-primary">Add New Article</a>
    </div>

    <div class="filters mb-4">
        <select class="form-select" onchange="filterArticles(this.value)">
            <option value="all">All Articles</option>
            <option value="published">Published</option>
            <option value="pending">Pending Review</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <div class="articles-grid">
        @forelse($articles as $article)
            <div class="article-card">
                <div class="article-status {{ $article->status }}">{{ ucfirst($article->status) }}</div>
                <h3 class="article-title">{{ $article->title }}</h3>
                <div class="article-meta">
                    <span>By {{ $article->author->name }}</span>
                    <span>{{ $article->created_at->format('M d, Y') }}</span>
                </div>
                <div class="article-stats">
                    <span title="Comments">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        {{ $article->comments_count }}
                    </span>
                    <span title="Average Rating">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        {{ number_format($article->average_rating, 1) }}
                    </span>
                </div>
                <div class="article-actions">
                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">View</a>
                    {{-- Bouton de modification du statut --}}
                    <form action="{{ route('theme-responsible.articles.updateStatus', [$theme, $article]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="pending_review" {{ $article->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Publish</option>
                            <option value="rejected" {{ $article->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                        </select>
                    </form>

                    {{-- Bouton de suppression --}}
                    <form action="{{ route('theme-responsible.articles.destroy', [$theme, $article]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                            onclick="return confirm('Are you sure you want to delete this article?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>No articles found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>

<style>
.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.article-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: relative;
}

.article-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.article-status.published { background: #dcfce7; color: #166534; }
.article-status.pending { background: #fef3c7; color: #92400e; }
.article-status.rejected { background: #fee2e2; color: #991b1b; }

.article-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 1rem 0;
}

.article-meta {
    display: flex;
    justify-content: space-between;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.article-stats {
    display: flex;
    gap: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.article-stats span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.icon {
    width: 1rem;
    height: 1rem;
}

.article-actions {
    display: flex;
    gap: 0.5rem;
}

.filters {
    max-width: 200px;
}
.article-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-top: 1rem;
}

.article-actions form {
    display: inline-block;
}

.article-actions .form-select-sm {
    width: auto;
    padding: 0.25rem 1.5rem 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    cursor: pointer;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
}
</style>
@endsection