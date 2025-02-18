@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div id="flash-messages"></div>

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
</div>
@endsection
