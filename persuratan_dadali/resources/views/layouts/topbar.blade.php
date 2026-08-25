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
        <div class="notification-menu">
            <button class="notification-toggle" id="notificationToggle" type="button" aria-label="Notifikasi" aria-expanded="false">
                <i class="fas fa-bell"></i>
                @if(($unreadCount ?? 0) > 0)
                    <span class="notification-count">{{ $unreadCount }}</span>
                @endif
            </button>
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-dropdown-header">
                    <strong>Notifikasi</strong>
                    @if(($unreadCount ?? 0) > 0)
                        <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
                            @csrf
                            <button type="submit">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
                <div class="notification-list">
                    @forelse(($latestNotifications ?? collect()) as $notification)
                        <form action="{{ route('notifikasi.baca', $notification->id) }}" method="POST" class="notification-item {{ $notification->dibaca_pada ? '' : 'unread' }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">
                                <span class="notification-item-icon"><i class="fas {{ $notification->tipe === 'disposisi_ditinjau' ? 'fa-clipboard-check' : 'fa-code-branch' }}"></i></span>
                                <span class="notification-item-content">
                                    <strong>{{ $notification->judul }}</strong>
                                    <span>{{ \Str::limit($notification->pesan, 90) }}</span>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </span>
                            </button>
                        </form>
                    @empty
                        <div class="notification-empty">Belum ada notifikasi.</div>
                    @endforelse
                </div>
                <a href="{{ route('notifikasi.index') }}" class="notification-see-all">Lihat semua <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
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