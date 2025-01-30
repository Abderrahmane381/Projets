@extends('layouts.app')

@section('title', "Moderate Comments - {$theme->name}")

@section('content')
@include('editor.shared.form-styles')
<div class="container">
    <div class="page-header">
        <h1>{{ $theme->name }} - Comment Moderation</h1>
    </div>

    <div class="comments-section">
        <div class="filters mb-4">
            <select class="form-select" onchange="filterComments(this.value)">
                <option value="all">All Comments</option>
                <option value="pending">Pending Approval</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="comments-list">
            @forelse($comments as $comment)
                <div class="comment-card">
                    <div class="comment-header">
                        <div class="comment-meta">
                            <span class="author">{{ $comment->user->name }}</span>
                            <span class="date">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="comment-status {{ $comment->status }}">
                            {{ ucfirst($comment->status) }}
                        </div>
                    </div>
                    
                    <div class="comment-content">
                        <a href="{{ route('articles.show', $comment->article) }}" class="article-link">
                            Re: {{ $comment->article->title }}
                        </a>
                        <p>{{ $comment->content }}</p>
                    </div>

                    <div class="comment-actions">
                        @if($comment->status === 'pending')
                            <form action="{{ route('theme-responsible.comments.toggle-approval', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        @endif
                        <form action="{{ route('theme-responsible.comments.delete', $comment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>No comments to moderate.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $comments->links() }}
        </div>
    </div>
</div>

<style>
.comments-list {
    display: grid;
    gap: 1rem;
}

.comment-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.comment-meta {
    display: flex;
    gap: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.author {
    font-weight: 500;
    color: #1f2937;
}

.comment-status {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.comment-status.pending { background: #fef3c7; color: #92400e; }
.comment-status.approved { background: #dcfce7; color: #166534; }
.comment-status.rejected { background: #fee2e2; color: #991b1b; }

.comment-content {
    margin-bottom: 1rem;
}

.article-link {
    display: block;
    color: #3b82f6;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    text-decoration: none;
}

.article-link:hover {
    text-decoration: underline;
}

.comment-actions {
    display: flex;
    gap: 0.5rem;
}

.filters {
    max-width: 200px;
}
</style>
@endsection