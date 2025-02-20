<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
 

    

    
    
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="nav-brand">Tech Horizons</a>
            <div class="auth-section">
                @auth
                    <div class="nav-links">
                        <a href="{{ route('themes.index') }}" class="nav-link {{ request()->routeIs('themes.*') ? 'active' : '' }}">Themes</a>
                        <a href="{{ route('articles.index') }}" class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}">Articles</a>
                        <a href="{{ route('issues.index') }}" class="nav-link {{ request()->routeIs('issues.*') ? 'active' : '' }}">Issues</a>
                    </div>
                    <div x-data="{ isOpen: false }" class="dropdown">
                        <button @click="isOpen = !isOpen" type="button" class="dropdown-button">
                            {{ auth()->user()->name }}
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="isOpen" 
                             @click.away="isOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="dropdown-menu">
                            @if(auth()->user()->isEditor())
                                <a href="{{ route('editor.dashboard') }}" class="menu-item">Editor Dashboard</a>
                            @endif

                            @if(auth()->user()->isThemeResponsible())
                                <a href="{{ route('theme-responsible.dashboard') }}" class="menu-item">Theme Manager</a>
                            @endif

                            @if(auth()->user()->isSubscriber())
                                <a href="{{ route('user.dashboard') }}" class="menu-item">My Dashboard</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="menu-item">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="nav-links">
                        <a href="{{ route('themes.index') }}" class="nav-link {{ request()->routeIs('themes.*') ? 'active' : '' }}">Themes</a>
                        <a href="{{ route('issues.index') }}" class="nav-link {{ request()->routeIs('issues.*') ? 'active' : '' }}">Issues</a>
                    </div>
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success">
                    {{ session('success') }}
                    <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-error">
                    {{ session('error') }}
                    <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            </div>
        @endif


        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Tech Horizons. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
