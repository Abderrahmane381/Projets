@extends('layouts.app')

@section('title', 'Article Discussion')

@section('content')
<div class="chat-container">
    <div class="chat-header">
        <h1>Discussion: {{ $article->title }}</h1>
        <a href="{{ route('articles.show', $article) }}" class="btn btn-outline">Back to Article</a>
    </div>

    <div class="chat-messages" id="chat-messages">
        @forelse($messages as $message)
            <div class="message {{ $message->user_id === auth()->id() ? 'message-own' : '' }}">
                <div class="message-header">
                    <span class="message-author">{{ $message->user->name }}</span>
                    <span class="message-time">{{ $message->created_at->diffForHumans() }}</span>
                </div>
                <div class="message-content">
                    {{ $message->content }}
                </div>
            </div>
        @empty
            <div class="empty-state">No Comments yet. Start the discussion!</div>
        @endforelse
    </div>

    <form action="{{ route('comments.store', $article) }}" method="POST" class="chat-form">
        @csrf
        <div class="form-group">
            <textarea name="message" required 
                placeholder="Type your comment here..."
                class="chat-input"></textarea>
            @error('message')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Send Comment</button>
    </form>

    @if(auth()->user()->isThemeResponsible() && $article->theme->responsible_id === auth()->id())
        <div class="moderation-link">
            <a href="{{ route('chat.moderate', $article) }}" class="btn btn-outline">
                Moderate Comments
            </a>
        </div>
    @endif
</div>
@endsection
