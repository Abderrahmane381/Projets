@extends('layouts.app')

@section('title', 'Manage Articles')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Manage Articles</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Title</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Author</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Theme</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Created</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($articles as $article)
                    <tr>
                        <td class="px-6 py-4">{{ $article->title }}</td>
                        <td class="px-6 py-4">{{ $article->author->name }}</td>
                        <td class="px-6 py-4">{{ $article->theme->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm 
                                {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 
                                   ($article->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($article->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $article->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <form action="{{ route('admin.articles.toggle', $article) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-600 hover:text-blue-900">
                                    {{ $article->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</div>
@endsection
