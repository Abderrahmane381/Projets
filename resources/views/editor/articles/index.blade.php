@extends('layouts.app')

@section('title', 'Manage Articles')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <h1>Manage Articles</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Theme</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->theme->name }}</td>
                        <td>{{ $article->author->name }}</td>
                        <td>
                            <form action="{{ route('editor.articles.update-status', $article) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="submitted" {{ $article->status === 'pending_review' ? 'selected' : '' }}>pending_review</option>
                                    <option value="rejected" {{ $article->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('editor.articles.destroy', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No articles available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>
@endsection
