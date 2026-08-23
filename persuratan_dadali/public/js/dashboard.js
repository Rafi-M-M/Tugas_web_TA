// ===== Toggle Sidebar Mobile =====
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');

function toggleSidebar() {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
}

menuToggle.addEventListener('click', toggleSidebar);
overlay.addEventListener('click', toggleSidebar);

// close sidebar on link click (mobile)
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }
    });
});

// ===== Set Current Date =====
(function setDate() {
    const now = new Date();
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
        'November', 'Desember'
    ];
    const dayName = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    document.getElementById('currentDate').textContent = `${dayName}, ${date} ${month} ${year}`;
})();

// ===== Theme Toggle =====
(function initTheme() {
    const savedTheme = localStorage.getItem('dadali-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = savedTheme ? savedTheme === 'dark' : prefersDark;

    const toggle = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');

    function applyTheme(darkMode) {
        document.body.classList.toggle('dark-mode', darkMode);
        if (icon) {
            icon.className = darkMode ? 'fas fa-sun' : 'fas fa-moon';
        }
        if (toggle) {
            toggle.setAttribute('aria-pressed', darkMode ? 'true' : 'false');
        }
        localStorage.setItem('dadali-theme', darkMode ? 'dark' : 'light');
    }

    applyTheme(isDark);

    if (toggle) {
        toggle.addEventListener('click', function () {
            applyTheme(!document.body.classList.contains('dark-mode'));
        });
    }
})();

// ===== Small interaction: click on "Selengkapnya" shows alert (demo) =====
document.querySelectorAll('.more-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const label = link.closest('.stat-card')?.querySelector('.stat-label')?.textContent || 'statistik';
        alert(`📊 Menampilkan detail ${label}... (demo interaksi)`);
    });
});

// ===== View All on card header =====
document.querySelectorAll('.view-all').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        alert('📋 Menampilkan semua data surat... (demo interaksi)');
    });
});

// ===== Sidebar menu active state (demo) =====
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', function(e) {
        // e.preventDefault(); // Dihapus agar navigasi berfungsi
        document.querySelectorAll('.sidebar-menu a').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});

console.log('🚀 Dashboard Manajemen Persuratan Digital — Dadali Dinillah');
console.log('📌 Responsive siap untuk HP & desktop.');
