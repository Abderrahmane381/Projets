@extends('layouts.app')

@section('title', 'Create Article')

@section('content')
<div class="container">
    <header class="section-header">
        <h1>Create Article for {{ $theme->name }}</h1>
        <div class="header-actions">
            <a href="{{ route('theme-responsible.articles.index', $theme) }}" class="btn btn-secondary">Back to Articles</a>
        </div>
    </header>

    <div class="article-form">
        <form action="{{ route('theme-responsible.articles.store', $theme) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="draft">Draft</option>
                    <option value="pending_review">Submit for Review</option>
                    <option value="published">Publish</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Create Article</button>
        </form>
    </div>
</div>
@endsection
