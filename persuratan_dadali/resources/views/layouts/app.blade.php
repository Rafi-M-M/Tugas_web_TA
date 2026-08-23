<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5">
    <title>@yield('title', 'Manajemen Persuratan Digital')</title>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>

    <!-- Overlay mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="main-content">

        <!-- Topbar -->
        @include('layouts.topbar')

        <!-- Page Content -->
        @yield('content')

        <!-- Footer -->
        <div class="footer">
            <span>
                <i class="far fa-copyright"></i> {{ date('Y') }} <strong>Manajemen Persuratan Digital</strong> — Pondok Pesantren Dadali Dinillah
            </span>
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i id="toastIcon" class="fas fa-check-circle"></i>
        <span id="toastMessage"></span>
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        // Script for session-based toast notifications
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            toastMsg.textContent = msg;
            toastIcon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-times-circle';
            toast.style.backgroundColor = type === 'success' ? '#0f3b5e' : '#dc2626';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 4000);
        }
        @if(session('success')) showToast("{{ session('success') }}", 'success'); @endif
        @if(session('error')) showToast("{{ session('error') }}", 'error'); @endif
    </script>
    @stack('scripts')

</body>
</html>