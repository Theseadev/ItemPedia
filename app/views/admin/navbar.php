<!-- ================= TOP ADMIN NAVBAR COMPONENT ================= -->
<header class="sticky top-0 z-20 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 flex items-center justify-between shadow-2xs transition-colors duration-200">
    <!-- Left: Hamburger Toggle Button (Desktop Collapse & Mobile Open) -->
    <div class="flex items-center gap-3">
        <button type="button" 
                onclick="toggleAdminSidebarCollapse()" 
                class="w-9 h-9 rounded-xl bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 border border-slate-200/90 shadow-2xs flex items-center justify-center active:scale-95 transition cursor-pointer group" 
                title="Sembunyikan / Buka Sidebar">
            <i class="fa-solid fa-bars text-sm group-hover:scale-110 transition-transform"></i>
        </button>

        <!-- Current Breadcrumb / Status Tag (Optional Subtle Context) -->
        <div class="hidden sm:flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Toko Aktif & Online</span>
            </span>
        </div>
    </div>

    <!-- Right: Action Buttons (Lihat Website, Mode Gelap, Keluar) -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- 1. Lihat Website Button -->
        <a href="/" 
           target="_blank" 
           class="px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-950 text-xs font-bold border border-slate-200/90 shadow-2xs hover:border-slate-300 transition flex items-center gap-1.5 active:scale-95 group" 
           title="Buka Website Publik ItemPedia">
            <span class="whitespace-nowrap">Lihat Website</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 group-hover:text-blue-600 transition-colors"></i>
        </a>

        <!-- 2. Dark/Light Mode Toggle Button -->
        <button type="button" 
                id="adminThemeToggle" 
                onclick="toggleAdminDarkMode()" 
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 border border-slate-200/90 shadow-2xs flex items-center justify-center active:scale-95 transition cursor-pointer group" 
                title="Ganti Mode Gelap / Terang">
            <i id="adminThemeIcon" class="fa-solid fa-moon text-xs text-slate-500 group-hover:text-slate-800 transition-colors"></i>
        </button>

        <!-- 3. Keluar / Logout Button -->
        <button type="button" 
                onclick="confirmLogoutAdmin()" 
                class="px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl bg-rose-50 hover:bg-rose-100/90 text-rose-600 hover:text-rose-700 border border-rose-100 shadow-2xs text-xs font-bold transition flex items-center gap-1.5 active:scale-95 cursor-pointer" 
                title="Keluar dari Panel Admin">
            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
            <span class="whitespace-nowrap">Keluar</span>
        </button>
    </div>
</header>

<script>
function toggleAdminSidebarCollapse() {
    if (window.innerWidth < 768) {
        if (typeof toggleAdminMobileSidebar === 'function') {
            toggleAdminMobileSidebar(true);
        }
        return;
    }
    const sidebar = document.getElementById('adminDesktopSidebar');
    const mainArea = document.getElementById('adminMainArea');
    if (!sidebar || !mainArea) return;

    if (sidebar.classList.contains('-translate-x-full')) {
        // Expand
        sidebar.classList.remove('-translate-x-full');
        mainArea.classList.add('md:pl-72');
        mainArea.classList.remove('md:pl-0');
        localStorage.setItem('admin_sidebar_collapsed', '0');
    } else {
        // Collapse
        sidebar.classList.add('-translate-x-full');
        mainArea.classList.remove('md:pl-72');
        mainArea.classList.add('md:pl-0');
        localStorage.setItem('admin_sidebar_collapsed', '1');
    }
}

// Restore sidebar collapse state on load
document.addEventListener('DOMContentLoaded', () => {
    if (window.innerWidth >= 768 && localStorage.getItem('admin_sidebar_collapsed') === '1') {
        const sidebar = document.getElementById('adminDesktopSidebar');
        const mainArea = document.getElementById('adminMainArea');
        if (sidebar && mainArea) {
            sidebar.classList.add('-translate-x-full');
            mainArea.classList.remove('md:pl-72');
            mainArea.classList.add('md:pl-0');
        }
    }
    // Check dark mode state
    if (localStorage.getItem('admin_theme') === 'dark') {
        document.documentElement.classList.add('dark');
    }
    updateThemeIcon();
});

function toggleAdminDarkMode() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
    updateThemeIcon();
}

function updateThemeIcon() {
    const icon = document.getElementById('adminThemeIcon');
    if (!icon) return;
    if (document.documentElement.classList.contains('dark')) {
        icon.className = 'fa-solid fa-sun text-amber-500 text-xs';
    } else {
        icon.className = 'fa-solid fa-moon text-slate-500 text-xs';
    }
}

function confirmLogoutAdmin() {
    if (typeof showCustomConfirm === 'function') {
        showCustomConfirm({
            title: 'Keluar dari Panel Admin?',
            message: 'Sesi login admin Anda akan diakhiri dan dialihkan ke halaman utama.',
            confirmText: 'Ya, Keluar',
            cancelText: 'Batal',
            icon: 'fa-arrow-right-from-bracket',
            variant: 'danger',
            onConfirm: () => {
                window.location.href = '/admin/logout';
            }
        });
    } else {
        if (confirm('Apakah Anda yakin ingin keluar dari panel admin?')) {
            window.location.href = '/admin/logout';
        }
    }
}
</script>
