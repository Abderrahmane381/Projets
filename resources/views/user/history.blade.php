@extends('layouts.app')

@section('title', 'Browsing History')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Browsing History</h1>
        <form action="{{ route('user.history.clear') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn btn-primary">
                Clear History
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('user.history') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Theme</label>
                    <select name="theme" class="form-select">
                        <option  value="">All Themes</option>
                        @foreach($themes as $id => $name)  <!-- Utilise $id et $name -->
                            <option value="{{ $id }}" {{ request('theme') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="mt-1 block w-full rounded-md border-gray-300">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow">
        @if($history->count() > 0)
            <div class="articles-grid">
                @foreach($history as $item)
                    <div class="articles-grid">
                        <h3 class="text-lg font-semibold">
                            <a href="{{ route('articles.show', $item->article) }}" class="text-blue-600 hover:underline">
                                {{ $item->article->title }}
                            </a>
                        </h3>
                        <div class="text-sm text-gray-600 mt-1">
                            Theme: {{ $item->article->theme->name }} | 
                            Last visited: {{ $item->last_visited_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="p-4">
                {{ $history->links() }}
            </div>
        @else
            <p class="p-4 text-gray-600">No browsing history found.</p>
        @endif
    </div>
</div>
@endsection
