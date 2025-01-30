@extends('layouts.app')

@section('title', 'Manage Issues')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Manage Issues</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Title</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Theme</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Articles</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Published</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Visibility</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($issues as $issue)
                    <tr>
                        <td class="px-6 py-4">{{ $issue->title }}</td>
                        <td class="px-6 py-4">{{ $issue->theme->name }}</td>
                        <td class="px-6 py-4">{{ $issue->articles_count }}</td>
                        <td class="px-6 py-4">
                            {{ $issue->publication_date ? $issue->publication_date->format('Y-m-d') : 'Not published' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm {{ $issue->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $issue->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm {{ $issue->is_public ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $issue->is_public ? 'Public' : 'Private' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.issues.toggle', $issue) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-{{ $issue->is_active ? 'red' : 'green' }}-600 hover:text-{{ $issue->is_active ? 'red' : 'green' }}-900">
                                        {{ $issue->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <a href="{{ route('editor.issues.edit', $issue) }}" class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </a>
                                <form action="{{ route('editor.issues.destroy', $issue) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                        Delete
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
        {{ $issues->links() }}
    </div>
</div>
@endsection
