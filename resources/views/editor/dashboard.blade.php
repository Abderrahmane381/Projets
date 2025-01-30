@extends('layouts.app')

@section('title', 'Editor Dashboard')

@section('content')
<div class="container">
    <div class="dashboard-header">
        <h1>Editor Dashboard</h1>
        <div class="management-buttons">
            <a href="{{ route('editor.users.index') }}" class="btn btn-outline">Manage Users</a>
            <a href="{{ route('editor.themes.index') }}" class="btn btn-outline">Manage Themes</a>
            <a href="{{ route('editor.articles.index') }}" class="btn btn-outline">Manage Articles</a>
            <a href="{{ route('editor.articles.review') }}" class="btn btn-outline">Review Articles</a>
            <a href="{{ route('editor.issues.index') }}" class="btn btn-outline">Manage Issues</a>
        </div>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-header users">Users</div>
            <div class="stat-value">{{ $stats['users']['total'] }}</div>
            <div class="stat-details">
                <span>Subscribers: {{ $stats['users']['subscribers'] }}</span>
                <span>Theme Managers: {{ $stats['users']['theme_responsibles'] }}</span>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-header themes">Themes</div>
            <div class="stat-value">{{ $stats['themes']['total'] }}</div>
            <div class="stat-details">
                <span>Active: {{ $stats['themes']['active'] }}</span>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-header articles">Articles</div>
            <div class="stat-value">{{ $stats['articles']['total'] }}</div>
            <div class="stat-details">
                <span>Published: {{ $stats['articles']['published'] }}</span>
                <span>Pending: {{ $stats['articles']['pending'] }}</span>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-header issues">Issues</div>
            <div class="stat-value">{{ $stats['issues']['total'] }}</div>
            <div class="stat-details">
                <span>Public: {{ $stats['issues']['public'] }}</span>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="{{ route('editor.articles.create') }}" class="btn btn-primary">New Article</a>
            <a href="{{ route('editor.issues.create') }}" class="btn btn-primary">New Issue</a>
            <a href="{{ route('editor.themes.create') }}" class="btn btn-primary">New Theme</a>
            <a href="{{ route('editor.users.create') }}" class="btn btn-primary">New User</a>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem 0;
}

.dashboard-header h1 {
    font-size: 1.5rem;
    margin: 0;
}

.management-buttons {
    display: flex;
    gap: 0.5rem;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-box {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-header {
    font-weight: 500;
    padding-bottom: 0.5rem;
    margin-bottom: 0.5rem;
    border-bottom: 2px solid;
}

.stat-header.users { border-color: #3b82f6; }
.stat-header.themes { border-color: #10b981; }
.stat-header.articles { border-color: #f59e0b; }
.stat-header.issues { border-color: #8b5cf6; }

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0.5rem 0;
}

.stat-details {
    font-size: 0.875rem;
    color: #666;
}

.stat-details span {
    display: block;
    margin: 0.25rem 0;
}

.quick-actions {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.quick-actions h2 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-outline {
    border: 1px solid #e2e8f0;
    background: white;
    color: #64748b;
}

.btn-outline:hover {
    background: #f8fafc;
    color: #1e293b;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .management-buttons {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .btn-outline {
        flex: 1;
        text-align: center;
    }
}
</style>
@endsection
