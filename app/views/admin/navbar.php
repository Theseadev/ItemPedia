<!-- ================= TOP ADMIN NAVBAR COMPONENT ================= -->
<header class="sticky top-0 z-20 bg-white/95 dark:bg-[#0c1e33]/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 flex items-center justify-between shadow-2xs transition-colors duration-200">
    <!-- Left: Hamburger Toggle Button (Desktop Collapse & Mobile Open) -->
    <div class="flex items-center gap-3">
        <button type="button" 
                onclick="toggleAdminSidebarCollapse()" 
                class="w-9 h-9 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white border border-slate-200/90 dark:border-slate-700 shadow-2xs flex items-center justify-center active:scale-95 transition cursor-pointer group" 
                title="Sembunyikan / Buka Sidebar">
            <i class="fa-solid fa-bars text-sm group-hover:scale-110 transition-transform"></i>
        </button>

        <!-- Current Breadcrumb / Status Tag (Optional Subtle Context) -->
        <div class="hidden sm:flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-800/60">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Toko Aktif & Online</span>
            </span>
        </div>
    </div>

    <!-- Right: Action Buttons (Cache, Lihat Website, Mode Gelap, Keluar) -->
    <div class="flex items-center gap-2 sm:gap-2.5">
        <!-- 0. Cache Management Dropdown -->
        <div class="relative">
            <button type="button" 
                    id="adminCacheBtn"
                    onclick="toggleAdminCacheDropdown(event)" 
                    class="px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-amber-50 dark:bg-amber-950/40 hover:bg-amber-100/70 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 hover:text-amber-900 dark:hover:text-amber-100 text-xs font-extrabold border border-amber-200/80 dark:border-amber-800/60 shadow-2xs transition flex items-center gap-1.5 active:scale-95 cursor-pointer" 
                    title="Manajemen Simpan Cache & Kecepatan Website">
                <i id="adminCacheIcon" class="fa-solid fa-bolt text-amber-500 text-xs"></i>
                <span class="hidden sm:inline">Cache</span>
                <i class="fa-solid fa-chevron-down text-[8.5px] text-amber-500/80"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="adminCacheDropdownMenu" class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-[#0c1e33] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl z-50 p-2 text-xs space-y-1 animate-in fade-in zoom-in-95 duration-150">
                <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="font-extrabold text-slate-800 dark:text-white flex items-center justify-between">
                        <span>Status Cache</span>
                        <span id="adminCacheStatusBadge" class="text-[10px] font-black px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">Aktif</span>
                    </div>
                    <p id="adminCacheStatsText" class="text-[10.5px] text-slate-400 dark:text-slate-500 mt-0.5">Memuat data cache...</p>
                </div>

                <button type="button" 
                        onclick="executeSaveCache()" 
                        class="w-full text-left px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/70 hover:text-sky-600 dark:hover:text-sky-400 font-bold flex items-center gap-2.5 transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-sky-500 text-xs w-3.5 text-center"></i>
                    <div>
                        <div class="font-extrabold">Simpan & Bangun Cache</div>
                        <div class="text-[10px] text-slate-400 font-normal">Perbarui data cache katalog & laman</div>
                    </div>
                </button>

                <button type="button" 
                        onclick="executeClearCache()" 
                        class="w-full text-left px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 font-bold flex items-center gap-2.5 transition cursor-pointer">
                    <i class="fa-solid fa-trash-can text-rose-500 text-xs w-3.5 text-center"></i>
                    <div>
                        <div class="font-extrabold">Bersihkan Semua Cache</div>
                        <div class="text-[10px] text-slate-400 font-normal">Hapus seluruh file cache sementara</div>
                    </div>
                </button>
            </div>
        </div>

        <!-- 1. Lihat Website Button -->
        <a href="/" 
           target="_blank" 
           class="px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 hover:text-slate-950 dark:hover:text-white text-xs font-bold border border-slate-200/90 dark:border-slate-700 shadow-2xs hover:border-slate-300 dark:hover:border-slate-600 transition flex items-center gap-1.5 active:scale-95 group" 
           title="Buka Website Publik ItemPedia">
            <span class="whitespace-nowrap">Lihat Website</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 dark:text-slate-500 group-hover:text-sky-500 transition-colors"></i>
        </a>

        <!-- 2. Dark/Light Mode Toggle Button -->
        <button type="button" 
                id="adminThemeToggle" 
                onclick="toggleAdminDarkMode()" 
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white border border-slate-200/90 dark:border-slate-700 shadow-2xs flex items-center justify-center active:scale-95 transition cursor-pointer group" 
                title="Ganti Mode Gelap / Terang">
            <i id="adminThemeIcon" class="fa-solid fa-moon text-xs text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors"></i>
        </button>

        <!-- 3. Keluar / Logout Button -->
        <button type="button" 
                onclick="confirmLogoutAdmin()" 
                class="px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100/90 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-300 border border-rose-100 dark:border-rose-800/60 shadow-2xs text-xs font-bold transition flex items-center gap-1.5 active:scale-95 cursor-pointer" 
                title="Keluar dari Panel Admin">
            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
            <span class="whitespace-nowrap">Keluar</span>
        </button>
    </div>
</header>

<!-- Global Toast Notification Container for Admin -->
<div id="adminToastContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

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

// Toggle Cache Dropdown
function toggleAdminCacheDropdown(e) {
    if (e) e.stopPropagation();
    const menu = document.getElementById('adminCacheDropdownMenu');
    if (!menu) return;
    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        loadCacheStatus();
    } else {
        menu.classList.add('hidden');
    }
}

// Close Dropdowns on Click Outside
document.addEventListener('click', (e) => {
    const cacheMenu = document.getElementById('adminCacheDropdownMenu');
    const cacheBtn = document.getElementById('adminCacheBtn');
    if (cacheMenu && !cacheMenu.classList.contains('hidden') && cacheBtn && !cacheBtn.contains(e.target) && !cacheMenu.contains(e.target)) {
        cacheMenu.classList.add('hidden');
    }
});

// Load Cache Status via AJAX
async function loadCacheStatus() {
    const textEl = document.getElementById('adminCacheStatsText');
    if (!textEl) return;
    try {
        const res = await fetch('/Banjar/cache/stats');
        const data = await res.json();
        if (data.success && data.stats) {
            textEl.innerText = `${data.stats.total_files} item tersimpan (${data.stats.total_size_formatted})`;
        }
    } catch (e) {
        textEl.innerText = 'Gagal memuat status cache';
    }
}

// Execute Save & Rebuild Cache
async function executeSaveCache() {
    const icon = document.getElementById('adminCacheIcon');
    const menu = document.getElementById('adminCacheDropdownMenu');
    if (menu) menu.classList.add('hidden');
    if (icon) icon.className = 'fa-solid fa-spinner fa-spin text-amber-500 text-xs';

    try {
        const res = await fetch('/Banjar/cache/save', { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            showAdminToast(data.message, 'success');
        } else {
            showAdminToast(data.message || 'Gagal menyimpan cache', 'error');
        }
    } catch (e) {
        showAdminToast('Terjadi kesalahan jaringan saat menyimpan cache', 'error');
    } finally {
        if (icon) icon.className = 'fa-solid fa-bolt text-amber-500 text-xs';
    }
}

// Execute Clear Cache
async function executeClearCache() {
    const icon = document.getElementById('adminCacheIcon');
    const menu = document.getElementById('adminCacheDropdownMenu');
    if (menu) menu.classList.add('hidden');
    if (icon) icon.className = 'fa-solid fa-spinner fa-spin text-rose-500 text-xs';

    try {
        const res = await fetch('/Banjar/cache/clear', { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            showAdminToast(data.message, 'success');
        } else {
            showAdminToast(data.message || 'Gagal membersihkan cache', 'error');
        }
    } catch (e) {
        showAdminToast('Terjadi kesalahan jaringan saat membersihkan cache', 'error');
    } finally {
        if (icon) icon.className = 'fa-solid fa-bolt text-amber-500 text-xs';
    }
}

// Floating Toast Notification
function showAdminToast(message, type = 'success') {
    const container = document.getElementById('adminToastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    const isSuccess = (type === 'success');
    const bgClass = isSuccess ? 'bg-emerald-600 text-white shadow-emerald-500/20' : 'bg-rose-600 text-white shadow-rose-500/20';
    const iconClass = isSuccess ? 'fa-circle-check' : 'fa-triangle-exclamation';

    toast.className = `pointer-events-auto px-4 py-3 rounded-2xl ${bgClass} shadow-xl flex items-center gap-2.5 text-xs font-bold animate-in fade-in slide-in-from-top-3 duration-200 transition-all`;
    toast.innerHTML = `
        <i class="fa-solid ${iconClass} text-sm"></i>
        <span>${message}</span>
        <button type="button" onclick="this.parentElement.remove()" class="ml-2 text-white/80 hover:text-white p-0.5">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('opacity-0', 'scale-95');
        setTimeout(() => toast.remove(), 200);
    }, 4000);
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
                window.location.href = '/Banjar/logout';
            }
        });
    } else {
        if (confirm('Apakah Anda yakin ingin keluar dari panel admin?')) {
            window.location.href = '/Banjar/logout';
        }
    }
}
</script>
