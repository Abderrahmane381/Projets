@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Users Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Users</h2>
            <div class="space-y-2">
                <p>Total: {{ $stats['users']['total'] }}</p>
                <p>Subscribers: {{ $stats['users']['subscribers'] }}</p>
                <p>Theme Managers: {{ $stats['users']['theme_responsibles'] }}</p>
                <p>Editors: {{ $stats['users']['editors'] }}</p>
            </div>
        </div>

        <!-- Themes Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Themes</h2>
            <div class="space-y-2">
                <p>Total: {{ $stats['themes']['total'] }}</p>
                <p>Active: {{ $stats['themes']['active'] }}</p>
            </div>
        </div>

        <!-- Articles Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Articles</h2>
            <div class="space-y-2">
                <p>Total: {{ $stats['articles']['total'] }}</p>
                <p>Published: {{ $stats['articles']['published'] }}</p>
                <p>Pending: {{ $stats['articles']['pending'] }}</p>
            </div>
        </div>

        <!-- Issues Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Issues</h2>
            <div class="space-y-2">
                <p>Total: {{ $stats['issues']['total'] }}</p>
                <p>Public: {{ $stats['issues']['public'] }}</p>
                <p>Active: {{ $stats['issues']['active'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.users.index') }}" class="block text-blue-600 hover:underline">Manage Users</a>
                <a href="{{ route('admin.themes.index') }}" class="block text-blue-600 hover:underline">Manage Themes</a>
                <a href="{{ route('admin.articles.index') }}" class="block text-blue-600 hover:underline">Manage Articles</a>
                <a href="{{ route('admin.issues.index') }}" class="block text-blue-600 hover:underline">Manage Issues</a>
            </div>
        </div>
    </div>
</div>
@endsection
