<header class="site-header">
    <a href="{{ url('/') }}" class="brand">
        <span class="brand-mark">T</span>
        <span class="brand-copy">
            <strong>{{ config('app.name', 'Thesis Management System') }}</strong>
            <span>Academic Workspace</span>
        </span>
    </a>

    <nav class="site-nav">
        <button class="nav-btn ghost" id="themeToggle" type="button" aria-pressed="false">Dark theme</button>
        <a class="nav-btn ghost" href="{{ route('books.index') }}">Browse books</a>
        @if (Route::has('login'))
            @auth
                <a class="nav-btn primary" href="{{ url('/dashboard') }}">Go to dashboard</a>
            @else
                <a class="nav-btn ghost" href="{{ route('login') }}">Log in</a>
                @if (Route::has('register'))
                    <a class="nav-btn primary" href="{{ route('register') }}">Create account</a>
                @endif
            @endauth
        @endif
    </nav>
</header>
