@extends('layouts.app')

@section('title', 'Create Issue')

@section('content')
@include('editor.shared.form-styles')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1>Create New Issue</h1>
        <a href="{{ route('editor.issues.index') }}" class="btn btn-outline">Back to Issues</a>
    </div>

    <div class="form-container">
        <form action="{{ route('editor.issues.store') }}" method="POST" class="form-custom">
            @csrf
            <div class="form-section">
                <h2>Issue Details</h2>
                <div class="form-group">
                    <label for="title">Issue Title</label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" 
                        value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" 
                        class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1"
                            {{ old('is_public') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_public">Make this issue public</label>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>Select Articles</h2>
                <div class="articles-grid">
                    @forelse($publishedArticles as $article)
                        <div class="article-selection-card">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                    id="article_{{ $article->id }}" 
                                    name="articles[]" 
                                    value="{{ $article->id }}"
                                    {{ in_array($article->id, old('articles', [])) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="article_{{ $article->id }}">
                                    <strong>{{ $article->title }}</strong>
                                    <span class="theme-badge">{{ $article->theme->name }}</span>
                                    <span class="article-date">{{ $article->created_at->format('M d, Y') }}</span>
                                </label>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            No published articles available.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Issue</button>
                <a href="{{ route('editor.issues.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-container {
    max-width: 800px;
    margin: 2rem auto;
}

.form-section {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.form-section h2 {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    color: #1e293b;
}

.articles-grid {
    max-height: 400px;
    overflow-y: auto;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
}

.article-selection-card {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.article-selection-card:last-child {
    border-bottom: none;
}

.theme-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background: #e5e7eb;
    border-radius: 9999px;
    font-size: 0.75rem;
    color: #4b5563;
    margin-left: 0.5rem;
}

.article-date {
    color: #6b7280;
    font-size: 0.875rem;
    margin-left: 0.5rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
    background: #f9fafb;
    border-radius: 6px;
}

.custom-control-label {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    cursor: pointer;
}
</style>
@endsection
