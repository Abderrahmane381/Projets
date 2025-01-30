@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Users</h1>
        <a href="{{ route('editor.users.create') }}" class="btn btn-primary">Add New User</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('editor.users.update-role', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                                        @foreach(['guest', 'subscriber', 'theme_responsible', 'editor'] as $roleName)
                                            <option value="{{ $roleName }}" {{ $user->roles->first()?->name === $roleName ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $roleName)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                    @if($user->is_active)
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
                                <form action="{{ route('editor.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="status-toggle-btn {{ $user->is_active ? 'toggle-deactivate' : 'toggle-activate' }}">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <!-- Formulaire pour supprimer un utilisateur -->
                                @unless($user->hasRole('editor'))
                                <form action="{{ route('editor.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                @endunless
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<style>
.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.form-select {
    min-width: 140px;
}

.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

.bg-success {
    background-color: #10b981 !important;
}

.bg-danger {
    background-color: #ef4444 !important;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
}

.page-header {
    background: transparent;
    padding: 0;
    color: #1e293b;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.8rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
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

.status-toggle-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.toggle-deactivate {
    background-color: #fee2e2;
    color: #991b1b;
    border-color: #fecaca;
}

.toggle-deactivate:hover {
    background-color: #fecaca;
    border-color: #ef4444;
}

.toggle-activate {
    background-color: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}

.toggle-activate:hover {
    background-color: #bbf7d0;
    border-color: #22c55e;
}
</style>
@endsection
