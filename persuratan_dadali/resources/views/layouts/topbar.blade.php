<div class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
        <h1>@yield('page-title', 'Dashboard') <span>| @yield('page-subtitle', 'Manajemen Persuratan')</span></h1>
    </div>
    <div class="topbar-right">
        <div class="date-badge">
            <i class="fas fa-calendar-day"></i>
            <span id="currentDate"></span>
        </div>
        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode" title="Mode malam">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
        @auth
        @php
            $name = auth()->user()->name;
            $initials = strtoupper(collect(explode(' ', $name))->map(fn($word) => mb_substr($word, 0, 1))->take(2)->implode(''));
        @endphp
        <div class="admin-badge">
            <div class="avatar">{{ $initials }}</div>
            <div class="admin-info">
                <div class="name">{{ $name }}</div>
                <div class="role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        @endauth
    </div>
</div>