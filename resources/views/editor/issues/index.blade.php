@extends('layouts.app')

@section('title', 'Manage Issues')

@section('content')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Issues</h1>
        <a href="{{ route('editor.issues.create') }}" class="btn btn-primary">Create New Issue</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Articles</th>
                        <th>Published Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issues as $issue)
                        <tr>
                            <td>
                                <strong>{{ $issue->title }}</strong>
                                <div class="text-muted small">{{ Str::limit($issue->description, 50) }}</div>
                            </td>
                            <td align="center">{{ $issue->articles->count() }}</td>
                            <td>{{ $issue->publication_date?->format('M d, Y') ?? 'Not Published' }}</td>
                            <td>
                                <span class="status-badge {{ $issue->is_public ? 'status-active' : 'status-inactive' }}">
                                    {{ $issue->is_public ? 'Public' : 'Private' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('editor.issues.edit', $issue) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('editor.issues.destroy', $issue) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $issues->links() }}
    </div>
</div>

<style>
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background-color: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-inactive {
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
}
</style>
@endsection
