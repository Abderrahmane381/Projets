@extends('layouts.app')

@section('title', 'Manage Themes')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Manage Themes</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Articles</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($themes as $theme)
                    <tr>
                        <td class="px-6 py-4">{{ $theme->name }}</td>
                        <td class="px-6 py-4">{{ Str::limit($theme->description, 100) }}</td>
                        <td class="px-6 py-4">{{ $theme->articles_count }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm {{ $theme->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $theme->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <form action="{{ route('admin.themes.toggle', $theme) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-600 hover:text-blue-900">
                                    {{ $theme->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $themes->links() }}
    </div>
</div>
@endsection
