@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Manage Users</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Role</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Articles</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                        <td class="px-6 py-4">{{ $user->articles_count }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-4">
                                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="text-sm border rounded p-1">
                                        @foreach(['guest', 'subscriber', 'theme_responsible', 'editor'] as $role)
                                            <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>
                                                {{ ucfirst($role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>

                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-{{ $user->is_active ? 'red' : 'green' }}-600 hover:text-{{ $user->is_active ? 'red' : 'green' }}-900">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
