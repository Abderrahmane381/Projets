@extends('layouts.app')

@section('title', 'Manage Articles')

@section('content')
<div class="container">
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h1>Manage Articles</h1>
    </div>

    <div class="card">
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
                            <td class="article-title"><strong>{{ $article->title }}</strong></td>
                            <td>{{ $article->theme->name }}</td>
                            <td>{{ $article->author->name }}</td>
                            <td>
                                <form action="{{ route('editor.articles.update-status', $article) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending_review" {{ $article->status === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="rejected" {{ $article->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('editor.articles.destroy', $article) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this article?')">
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
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>

<style>
.article-title {
    min-width: 150px;
}

.table th, .table td {
    vertical-align: middle;
    text-align: center;
}

.card {
    border-radius: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

.btn-outline-danger {
    color: #2563eb;
    border-color: #dc3545;
    transition: background-color 0.3s;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}

.form-select-sm {
    border-radius: 0.5rem;
    padding: 0.25rem 0.5rem;
}

.table-hover tbody tr:hover {
    background-color: #f9fafb;
}
</style>
@endsection
