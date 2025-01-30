@extends('layouts.app')

@section('title', $theme->name)

@section('content')
<div class="container">
    <div class="theme-header">
        <div class="theme-info">
            <h1>{{ $theme->name }}</h1>
            <p class="theme-description">{{ $theme->description }}</p>
            <div class="theme-meta">
                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    {{ $theme->articles_count }} Articles
                </div>
                <div class="meta-item">
                    <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    {{ $theme->subscribers_count }} Subscribers
                </div>
            </div>
        </div>
        
        @auth
            <div class="theme-actions">
                @if(auth()->user()->isSubscribedTo($theme))
                    <form action="{{ route('themes.unsubscribe', $theme) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                            </svg>
                            Unsubscribe
                        </button>
                    </form>
                @else
                    <form action="{{ route('themes.subscribe', $theme) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <svg class="icon" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                            </svg>
                            Subscribe
                        </button>
                    </form>
                @endif
            </div>
        @endauth
    </div>

    <div class="articles-section">
        <div class="section-header">
            <h2>Latest Articles</h2>
            <div class="articles-filter">
                <select class="form-select" onchange="filterArticles(this.value)">
                    <option value="latest">Latest First</option>
                    <option value="popular">Most Popular</option>
                    <option value="rated">Highest Rated</option>
                </select>
            </div>
        </div>

        <div class="articles-grid">
            @forelse($articles as $article)
                <div class="article-card">
                    <div class="article-meta">
                        <span class="article-date">{{ $article->created_at->format('M d, Y') }}</span>
                        <span class="article-status {{ $article->status }}">{{ ucfirst($article->status) }}</span>
                    </div>
                    <h3 class="article-title">
                        <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                    </h3>
                    <p class="article-excerpt">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                    <div class="article-footer">
                        <div class="article-author">
                            By {{ $article->author->name }}
                        </div>
                        <div class="article-stats">
                            <span title="Comments" class="stat">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                {{ $article->comments_count ?? 0 }}
                            </span>
                            <span title="Average Rating" class="stat">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ number_format($article->average_rating ?? 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>No articles published yet.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination-container">
            {{ $articles->links() }}
        </div>
    </div>
</div>

<style>
.theme-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.theme-info h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
}

.theme-description {
    color: #4b5563;
    font-size: 1.125rem;
    line-height: 1.75;
    margin-bottom: 1.5rem;
    max-width: 600px;
}

.theme-meta {
    display: flex;
    gap: 2rem;
    color: #6b7280;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.theme-actions {
    padding-left: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
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
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.article-card:hover {
    transform: translateY(-2px);
}

.article-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.article-date {
    color: #6b7280;
}

.article-status {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.article-status.published { background: #dcfce7; color: #166534; }
.article-status.pending { background: #fef3c7; color: #92400e; }

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

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.article-author {
    color: #6b7280;
    font-size: 0.875rem;
}

.article-stats {
    display: flex;
    gap: 1rem;
}

.stat {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.icon {
    width: 1rem;
    height: 1rem;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    background: #f9fafb;
    border-radius: 0.75rem;
    color: #6b7280;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s;
}

.form-select {
    padding: 0.5rem 2rem 0.5rem 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
    background-color: white;
    color: #374151;
    font-size: 0.875rem;
}
</style>

@push('scripts')
<script>
function filterArticles(value) {
    // Add your filtering logic here
    console.log('Filtering by:', value);
}
</script>
@endpush
@endsection