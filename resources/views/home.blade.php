@extends('layouts.app')

@section('title', 'Welcome')

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- Message de succès -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="hero-title">Welcome to Tech Horizons</h1>
            <p class="hero-subtitle">Explore the latest in technology innovation and development</p>
            <div class="flex justify-center gap-8">
                @guest
                    <a href="{{ route('register') }}" class="button button-primary">
                        Join Now
                    </a>
                    <a href="{{ route('themes.index') }}" class="button button-secondary">
                    Browse themes
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <div class="section-divider"></div>

    <!-- Featured Themes Section -->
    <section class="mb-16">
        <h2 class="section-header text-3xl font-bold mb-12">Featured Technology Themes</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredThemes as $theme)
                <div class="theme-card">
                    <div>
                        <h3 class="text-xl font-bold mb-3">{{ $theme->name }}</h3>
                        <p class="text-gray-600 mb-6">{{ Str::limit($theme->description, 100) }}</p>
                        <div class="flex items-center justify-between mb-6">
                            <span class="stats-badge">{{ $theme->articles_count }} Articles</span>
                            <span class="stats-badge">{{ $theme->subscribers_count }} Subscribers</span>
                        </div>
                    </div>
                    @auth
                        <a href="{{ route('themes.show', $theme) }}" class="button button-theme">
                             Explore Theme
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- Latest Content Section -->
    <div class="grid md:grid-cols-2 gap-12">
        <!-- Latest Articles -->
        @auth
            <section class="content-wrapper">
            
                <h2 class="section-header text-2xl font-bold">Latest Articles</h2>
                <div class="space-y-6">
                    @foreach($latestArticles as $article)
                        <div class="article-card p-6">
                            <h3 class="text-xl font-semibold mb-3">
                                <a href="{{ route('articles.show', $article) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($article->content, 150) }}</p>
                            <div class="flex items-center justify-between text-sm border-t pt-4 mt-4">
                                <div class="flex items-center text-gray-500 space-x-4">
                                    <span>By {{ $article->author->name }}</span>
                                    <span>•</span>
                                    <span>{{ $article->created_at->diffForHumans() }}</span>
                                </div>
                        
                                <a href="{{ route('articles.show', $article) }}" 
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endauth
        <!-- Latest Issue -->
        <section class="content-wrapper">
            <h2 class="section-header text-2xl font-bold">Latest Magazine Issue</h2>
            @if($latestIssue)
                <div class="issue-card">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3">{{ $latestIssue->title }}</h3>
                        <p class="text-gray-600 mb-6">{{ Str::limit($latestIssue->description, 150) }}</p>
                        <div class="flex items-center justify-between mb-6 text-sm text-gray-500">
                            <span class="stats-badge">
                                {{ $latestIssue->articles->count() }} Articles
                            </span>
                            <span class="stats-badge">
                                {{ $latestIssue->publication_date->format('M Y') }}
                            </span>
                        </div>
                        @auth
                            <a href="{{ route('issues.show', $latestIssue) }}" 
                                class="block text-center bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-all transform hover:scale-105">
                                Read Issue
                            </a>
                        @endauth
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-500 border border-gray-200">
                    No issues published yet.
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
