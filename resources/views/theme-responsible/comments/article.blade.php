@extends('layouts.app')

@section('title', "Moderate Comments - {$article->title}")

@section('content')
<div class="container">
    <div class="page-header">
        <h1>{{ $article->title }} - Comment Moderation</h1>
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
                        <!-- Delete Comment -->
                        <form action="{{ route('theme-responsible.comments.delete', $comment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>

                        <!-- Change Status (Approve/Reject) -->
                        @if($comment->is_approved == '1')
                            <form action="{{ route('comments.update', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                
                                <button type="submit" name="status" value="rejected" class="btn btn-sm btn-outline-danger">Reject</button>
                            </form>
                        @endif
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

@endsection
