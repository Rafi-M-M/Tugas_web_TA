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
        <div class="admin-menu">
            <button class="admin-badge" id="adminMenuToggle" type="button" aria-label="User menu" aria-expanded="false">
                <div class="avatar">{{ $initials }}</div>
                <div class="admin-info">
                    <div class="name">{{ $name }}</div>
                    <div class="role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </button>
            <div class="admin-dropdown" id="adminDropdown">
                <a href="{{ route('akun.index') }}" class="admin-dropdown-item">
                    <i class="fas fa-users"></i> Manajemen Akun
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="admin-dropdown-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</div>