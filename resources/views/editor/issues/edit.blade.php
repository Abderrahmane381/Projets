@extends('layouts.app')

@section('title', 'Edit Issue')

@section('content')
@include('editor.shared.form-styles')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1>Edit Issue</h1>
        <a href="{{ route('editor.issues.index') }}" class="btn btn-outline">Back to Issues</a>
    </div>

    <div class="form-container">
        <form action="{{ route('editor.issues.update', $issue) }}" method="POST" class="form-custom">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <div class="form-group">
                    <label for="title">Issue Title</label>
                    <input type="text" id="title" name="title" 
                        class="form-control @error('title') is-invalid @enderror" 
                        value="{{ old('title', $issue->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" 
                        class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $issue->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Articles</label>
                    <div class="articles-grid">
                        @foreach($publishedArticles as $article)
                            <div class="article-selection-card">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" 
                                        id="article_{{ $article->id }}" 
                                        name="articles[]" 
                                        value="{{ $article->id }}"
                                        {{ in_array($article->id, old('articles', $issue->articles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="article_{{ $article->id }}">
                                        <strong>{{ $article->title }}</strong>
                                        <span class="theme-badge">{{ $article->theme->name }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('articles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_public" 
                            name="is_public" value="1" {{ old('is_public', $issue->is_public) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_public">Make Public</label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Issue</button>
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
}

.articles-grid {
    max-height: 400px;
    overflow-y: auto;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    margin-top: 0.5rem;
}

.article-selection-card {
    padding: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

.article-selection-card:last-child {
    border-bottom: none;
}

.theme-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background: #f3f4f6;
    border-radius: 9999px;
    font-size: 0.75rem;
    color: #4b5563;
    margin-left: 0.5rem;
}

.form-check-label {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}
</style>
@endsection
