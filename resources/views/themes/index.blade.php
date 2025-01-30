@extends('layouts.app')

@section('title', 'Technology Themes')

@section('content')
<div class="themes-container">
    <header class="page-header">
        <h1>Technology Themes</h1>
        @if (auth()->check() && auth()->user()->hasRole('editor'))
            <a href="{{ route('editor.themes.create') }}" class="btn btn-primary">New Theme</a>
        @endif
    </header>

    <div class="themes-grid">
        @forelse($themes as $theme)
            <div class="theme-card">
                <div class="theme-content">
                    <h2>{{ $theme->name }}</h2>
                    <p>{{ $theme->description }}</p>
                    
                    <div class="theme-meta">
                        <span>{{ $theme->articles_count }} Articles</span>
                        <span>{{ $theme->subscribers_count }} Subscribers</span>
                    </div>

                    <div class="theme-responsible">
                        <small>Managed by: {{ $theme->responsible?->name ?? 'Unassigned' }}</small>
                    </div>
                </div>

                <div class="theme-actions">
                    @auth
                    <a href="{{ route('themes.show', $theme) }}" class="btn">View Articles</a>
                        @if(auth()->user()->isSubscriber())
                            @if(auth()->user()->themeSubscriptions->contains($theme))
                                <form action="{{ route('themes.unsubscribe', $theme) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline">Unsubscribe</button>
                                </form>
                            @else
                                <form action="{{ route('themes.subscribe', $theme) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Subscribe</button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>No themes available at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
