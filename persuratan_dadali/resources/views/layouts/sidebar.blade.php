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

        <li class="menu-label">Pengelolaan</li>
        <li><a href="{{ route('akun.index') }}" class="{{ request()->routeIs('akun.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Akun</a></li>

        <li class="menu-label" style="margin-top: auto;">Akun</li>
        <li>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="width: 100%; text-align: left; border: none; background: none; color: inherit; padding: 10px 0; cursor: pointer; display: flex; align-items: center; gap: 12px; font-size: 14px;">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </li>
    </ul>

    <div class="sidebar-footer">
        <i class="fas fa-shield-alt"></i>
        <span>Hak Akses: Admin</span>
    </div>
</aside>
