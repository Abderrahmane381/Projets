@extends('layouts.app')

@section('title', 'Issues')

@section('content')
<div class="container">
    <div class="issues-header">
        <h1>Latest Issues</h1>
        <div class="issues-filters">
            <select class="form-select">
                <option value="all">All Issues</option>
                <option value="latest">Latest First</option>
                <option value="oldest">Oldest First</option>
            </select>
        </div>
    </div>

    <div class="issues-grid">
        @foreach($issues as $issue)
            <div class="issue-card">
                <div class="issue-status {{ $issue->is_public ? 'status-public' : 'status-private' }}">
                    {{ $issue->is_public ? 'Public' : 'Private' }}
                </div>
                @guest
                    <h2 class="issue-title">{{ $issue->title }}</h2>
                @endguest
                @auth
                    <h2 class="issue-title">
                        <a href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a>
                    </h2>  
                @endauth
                
                <div class="issue-description">
                    {{ Str::limit($issue->description, 150) }}
                </div>
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
                        {{ $issue->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination-container">
        {{ $issues->links() }}
    </div>
</div>

<style>
.issues-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.issues-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.issue-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.issue-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.issue-status {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
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

.issue-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.issue-title a {
    color: #1f2937;
    text-decoration: none;
}

.issue-title a:hover {
    color: #3b82f6;
}

.issue-description {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.issue-meta {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid #e5e7eb;
    padding-top: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.icon {
    width: 1rem;
    height: 1rem;
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
@endsection
