@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="container">
    <div class="dashboard-header">
        <h1>Welcome back, {{ auth()->user()->name }}!</h1>
    </div>

    <div class="dashboard-grid">
        <!-- Stats Cards -->
        <div class="stats-section">
            <div class="stat-card subscriptions">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['subscriptions'] }}</span>
                    <span class="stat-label">Subscriptions</span>
                </div>
            </div>

            <div class="stat-card articles">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['articles_read'] }}</span>
                    <span class="stat-label">Articles Read</span>
                </div>
            </div>

            <div class="stat-card interactions">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['comments_made'] }}</span>
                    <span class="stat-label">Comments</span>
                </div>
            </div>
        </div>

        <!-- Subscribed Themes -->
        <div class="content-section">
            <h2>Your Subscribed Themes</h2>
            <div class="themes-grid">
                @forelse($subscribedThemes as $theme)
                    <div class="theme-card">
                        <div class="theme-header">
                            <h3>{{ $theme->name }}</h3>
                            <span class="article-count">{{ $theme->articles->count() }} articles</span>
                            <form action="{{ route('themes.unsubscribe', $theme) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline">Unsubscribe</button>
                            </form>
                        </div>
                        
                    </div>
                @empty
                    <div class="empty-state">
                        <p>You haven't subscribed to any themes yet.</p>
                        <a href="{{ route('themes.index') }}" class="btn btn-primary">Browse Themes</a>
                    </div>
                @endforelse
            </div>
        </div>
        <a href="{{ route('subscriber.articles') }}" class="btn btn-primary">Voir mes articles</a>


        <!-- Recent Activity -->
        <div class="content-section">
            <!-- user/dashboard.blade.php -->
            <h2>Recently Viewed Articles</h2>
            <ul>
                @forelse($recentlyViewed as $history)
                    <li>
                        <a href="{{ route('articles.show', $history->article->id) }}">
                            {{ $history->article->title }}
                        </a>
                        <span>in {{ $history->article->theme->name }}</span>
                        <span class="viewed-date">{{ $history->last_visited_at->diffForHumans() }}</span>

                    </li>
                    @empty
                    <li>No recently viewed articles yet.</li>
                @endforelse
                <a href="{{ route('user.history') }}" class="btn btn-primary">Voir l'historique</a>
            </ul>
            
        </div>
    </div>
</div>

<style>
.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: #1f2937;
}

.dashboard-grid {
    display: grid;
    gap: 2rem;
}

.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    padding: 0.75rem;
    border-radius: 0.5rem;
    color: white;
}

.subscriptions .stat-icon { background: #3b82f6; }
.articles .stat-icon { background: #10b981; }
.interactions .stat-icon { background: #8b5cf6; }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    line-height: 1;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.content-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.content-section h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
}

.themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.theme-card {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
}

.theme-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.theme-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

.article-count {
    font-size: 0.875rem;
    color: #6b7280;
}

.article-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    color: #4b5563;
    text-decoration: none;
    border-bottom: 1px solid #f3f4f6;
}

.article-link:last-child {
    border-bottom: none;
}

.article-link:hover {
    color: #3b82f6;
}

.article-date {
    font-size: 0.75rem;
    color: #6b7280;
}

.activity-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.activity-card:last-child {
    border-bottom: none;
}

.activity-content {
    display: flex;
    flex-direction: column;
}

.activity-content a {
    color: #1f2937;
    text-decoration: none;
    font-weight: 500;
}

.activity-content a:hover {
    color: #3b82f6;
}

.activity-theme {
    font-size: 0.875rem;
    color: #6b7280;
}

.activity-date {
    font-size: 0.875rem;
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-state .btn {
    margin-top: 1rem;
}

@media (max-width: 640px) {
    .stats-section {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
