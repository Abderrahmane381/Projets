<!-- resources/views/theme-responsible/subscriptions.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestion des abonnés - {{ $theme->name }}</h2>
    
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form action="{{ route('theme-subscriptions.update-role', [$theme, $user]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                                    @foreach(['guest', 'subscriber'] as $roleName)
                                        <option value="{{ $roleName }}" {{ $user->roles->first()?->name === $roleName ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $roleName)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('theme-subscriptions.destroy', [$theme, $user]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Supprimer cet abonnement ?')">
                                    <i class="fas fa-trash-alt"></i>
                                DELETE</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    <style>
.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.page-header {
    padding: 1.5rem 0;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 2rem;
}

.card {
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.table {
    --bs-table-bg: transparent;
    margin-bottom: 0;
}

.table th {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    padding: 1rem 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.table td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
    border-color: #f1f5f9;
}

.table-hover tbody tr:hover {
    background-color: #f8fafc;
}

.form-select {
    border-radius: 0.5rem;
    border: 1px solid #cbd5e1;
    font-size: 0.875rem;
    padding: 0.375rem 2rem 0.375rem 0.75rem;
    background-position: right 0.75rem center;
    transition: all 0.2s ease;
}

.form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1;
    transition: all 0.2s ease;
}

.status-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

.status-active {
    background-color: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-inactive {
    background-color: #fff1f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.status-toggle-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.toggle-deactivate {
    background-color: #fff1f2;
    color: #dc2626;
    border-color: #fecaca;
}

.toggle-deactivate:hover {
    background-color: #ffe4e6;
}

.toggle-activate {
    background-color: #f0fdf4;
    color: #16a34a;
    border-color: #86efac;
}

.toggle-activate:hover {
    background-color: #dcfce7;
}

.btn-danger {
    background-color: #ef4444;
    border-color: #ef4444;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 0.5rem;
}

.btn-danger:hover {
    background-color: #dc2626;
    border-color: #dc2626;
}

.pagination {
    margin-top: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-responsive {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        overflow-x: auto;
    }
    
    .table {
        min-width: 800px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 0 1rem;
    }
    
    .status-badge,
    .status-toggle-btn {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
    
    .form-select {
        font-size: 0.75rem;
        padding-right: 1.5rem;
    }
}
</style>
</style>
@endsection
