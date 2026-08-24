<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-envelope-open-text"></i>
        <div>
            <h2>Persuratan <small>Dadali Dinillah</small></h2>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-label">Navigasi</li>
        <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
        <li><a href="{{ route('surat.masuk.index') }}" class="{{ request()->routeIs('surat.masuk.*') ? 'active' : '' }}"><i class="fas fa-inbox"></i> Surat Masuk</a></li>
        <li><a href="{{ route('surat.keluar.index') }}" class="{{ request()->routeIs('surat.keluar.*') ? 'active' : '' }}"><i class="fas fa-paper-plane"></i> Surat Keluar</a></li>
        <li><a href="{{ route('template.index') }}" class="{{ request()->routeIs('template.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Template Surat</a></li>
        <li><a href="{{ route('disposisi.index') }}" class="{{ request()->routeIs('disposisi.*') ? 'active' : '' }}"><i class="fas fa-code-branch"></i> Disposisi</a></li>
        <li><a href="{{ route('arsip.index') }}" class="{{ request()->routeIs('arsip.*') ? 'active' : '' }}"><i class="fas fa-archive"></i> Arsip Surat</a></li>

        @if(auth()->user()?->role === 'admin')
            <li class="menu-label">Pengelolaan</li>
            <li><a href="{{ route('akun.index') }}" class="{{ request()->routeIs('akun.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Akun</a></li>
            <!-- <li><a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}"><i class="fas fa-user-plus"></i> Register</a></li> -->
            <li><a href="{{-- route('pengaturan.index') --}}"><i class="fas fa-cog"></i> Pengaturan</a></li>
        @endif
        <li class="menu-label">Logout</li>
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" style="display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; color: rgba(255,255,255,0.75); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s ease;">
            <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            </form>
        </li>
    </ul>

    <div class="sidebar-footer">
        <i class="fas fa-shield-alt"></i>
        <span>Hak Akses: {{ ucfirst(auth()->user()?->role ?? '-') }}</span>
    </div>
</aside>
