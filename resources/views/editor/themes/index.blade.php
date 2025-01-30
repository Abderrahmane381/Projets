@extends('layouts.app')

@section('title', 'Manage Themes')

@section('content')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Themes</h1>
        <a href="{{ route('editor.themes.create') }}" class="btn btn-primary">Add New Theme</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Responsible</th>
                        <th>Articles</th>
                        <th>Subscribers</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($themes as $theme)
                        <tr>
                            <td class="theme-name">
                                <strong>{{ $theme->name }}</strong>
                                <div class="theme-description text-muted">{{ Str::limit($theme->description, 50) }}</div>
                            </td>
                            <td>{{ $theme->responsible->name ?? 'Not Assigned' }}</td>
                            <td><span class="badge bg-info">{{ $theme->articles_count }}</span></td>
                            <td><span class="badge bg-secondary">{{ $theme->subscribers_count }}</span></td>
                            <td>
                                <span class="status-badge {{ $theme->is_active ? 'status-active' : 'status-inactive' }}">
                                    @if($theme->is_active)
                                        <svg class="status-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Active
                                    @else
                                        <svg class="status-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Inactive
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('editor.themes.edit', $theme) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('editor.themes.destroy', $theme) }}" method="POST" class="d-inline">
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
        {{ $themes->links() }}
    </div>
</div>

<style>
.theme-name {
    min-width: 200px;
}

.theme-description {
    font-size: 0.875rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.badge {
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.4em 0.8em;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.8rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.status-active {
    background-color: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}
</style>
@endsection
