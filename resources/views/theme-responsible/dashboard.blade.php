@extends('layouts.app')

@section('title', 'Theme Manager Dashboard')

@section('content')
<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Theme Manager Dashboard</h1>
    </header>

    <div class="stats-overview">
        <div class="stat-card">
            <h3>Total Subscribers</h3>
            <p class="stat-number">{{ $stats['total_subscribers'] ?? 0 }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Articles</h3>
            <p class="stat-number">{{ $stats['total_articles'] }}</p>
        </div>
        <div class="stat-card">
            <h3>Pending Reviews</h3>
            <p class="stat-number">{{ $pending_reviews ?? 0 }}</p>
        </div>
        <div class="stat-card">
            <h3>Managed Themes</h3>
            <p class="stat-number">{{ $stats['themes_count'] }}</p>
        </div>
    </div>

    <section class="managed-themes">
        <h2>Your Themes</h2>
        <div class="themes-grid">
            @forelse($themes as $theme)
                <div class="theme-management-card">
                    <div class="theme-header">
                        <h3>{{ $theme->name }}</h3>
                        <span class="subscriber-count">{{ $theme->subscribers_count }} Subscribers</span>
                    </div>
                    <div class="theme-actions">
                    <a href="{{ route('theme-responsible.subscriptions.index', $theme) }}" class="btn btn-primary"><i class="fas fa-users"></i> Manage Subscribers</a>
                        <a href="{{ route('theme-responsible.articles.index', $theme) }}" class="btn">Manage Articles</a>
                        <a href="{{ route('theme-responsible.comments.moderate', $theme) }}" class="btn">Moderate Comments</a>
                        <a href="{{ route('theme-responsible.statistics', $theme) }}" class="btn">View Statistics</a>
                    </div>
                </div>
            @empty
                <p>No themes assigned yet.</p>
            @endforelse
        </div>
    </section>

    <section class="pending-articles">
        <h2>Articles Pending Review</h2>
        <div class="articles-list">
            @forelse($pendingArticles as $article)
                <div class="article-review-card">
                    <div class="article-info">
                        <h3>{{ $article->title }}</h3>
                        <p>By {{ $article->author->name }}</p>
                        <span class="theme-tag">{{ $article->theme->name }}</span>
                    </div>
                    <div class="article-actions">
                        <a href="{{ route('theme-responsible.articles.review', $article) }}" class="btn">Review Article</a>
                    </div>
                </div>
            @empty
                <p class="empty-state">No articles pending review</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
