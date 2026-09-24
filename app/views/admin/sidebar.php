<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '/admin';
$active = $activeMenu ?? 'dashboard';

// Hitung pending orders untuk badge
$db = \App\Config\Database::getConnection();
$pendingCount = 0;
try {
    $pendingCount = (int)$db->query("SELECT COUNT(*) FROM orders WHERE status IN ('PENDING', 'PAID', 'PROCESSING')")->fetchColumn();
} catch (\Exception $e) {}

// Hitung unread chat untuk badge
$unreadChatCount = 0;
try {
    $unreadChatCount = (int)$db->query("SELECT COUNT(*) FROM order_messages WHERE sender = 'buyer' AND is_read = 0")->fetchColumn();
} catch (\Exception $e) {}

// Cek active groups untuk auto expand accordion
$isDaganganActive = in_array($active, ['products', 'product_create', 'categories']);
$isTransaksiActive = in_array($active, ['orders', 'reviews', 'chat']);
$isKontenActive = in_array($active, ['pages']);
$isPromosiActive = in_array($active, ['redeem_codes']);
?>

<!-- Mobile Sidebar Backdrop & Drawer -->
<div id="adminMobileSidebarBackdrop" onclick="toggleAdminMobileSidebar(false)" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 md:hidden transition-opacity"></div>
<aside id="adminMobileSidebarDrawer" class="fixed top-0 bottom-0 left-0 w-72 bg-[#0c1e33] border-r border-white/10 z-50 transform -translate-x-full md:hidden transition-transform duration-300 flex flex-col justify-between shadow-2xl overflow-y-auto">
    <div>
        <!-- Drawer Header with Store Info (Matching Screenshot) -->
        <div class="p-4 border-b border-white/10">
            <div class="p-3.5 rounded-2xl bg-white/[0.06] border border-white/10 flex items-center justify-between shadow-lg shadow-black/20">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white/10 p-1.5 flex items-center justify-center border border-white/20 shadow-inner flex-shrink-0">
                        <img src="/images/logo-icon.png" srcset="/images/logo-icon@2x.png 2x" alt="ItemPedia" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h2 class="font-extrabold text-white text-sm tracking-tight leading-tight">Admin Panel</h2>
                        <p class="text-[11px] text-sky-200/80 font-medium leading-tight">ItemPedia Seller Center</p>
                    </div>
                </div>
                <button type="button" onclick="toggleAdminMobileSidebar(false)" class="w-7 h-7 rounded-lg bg-white/10 text-slate-300 hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="p-3.5 space-y-2 text-xs font-semibold">
            
            <!-- 1. Single Item: Dashboard -->
            <a href="/admin" class="w-full rounded-2xl px-3.5 py-3 flex items-center justify-between transition <?= $active === 'dashboard' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white font-extrabold shadow-md shadow-sky-500/25' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-shapes text-base <?= $active === 'dashboard' ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span class="text-sm">Dashboard</span>
                </div>
                <?php if ($active === 'dashboard'): ?>
                    <span class="w-2 h-2 rounded-full bg-white shadow-xs"></span>
                <?php endif; ?>
            </a>

            <!-- 2. Accordion Group: Konten Website -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('mobile_group_konten', 'mobile_chev_konten')" 
                        class="w-full rounded-2xl px-3.5 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i class="fa-regular fa-newspaper text-slate-400 text-base"></i>
                        <span class="text-sm font-bold">Konten Website</span>
                    </div>
                    <i id="mobile_chev_konten" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isKontenActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="mobile_group_konten" class="border-l-2 border-sky-500/30 ml-6 pl-3.5 space-y-1 my-1.5 <?= $isKontenActive ? '' : 'hidden' ?>">
                    <a href="/admin/pages" class="block px-3 py-2 rounded-xl text-xs font-semibold transition <?= $active === 'pages' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-regular fa-file-lines text-xs mr-2 text-slate-400"></i> Edit Laman & Teks
                    </a>
                    <a href="/admin/pages#faqs" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-white/[0.06] transition">
                        <i class="fa-regular fa-circle-question text-xs mr-2 text-slate-400"></i> Tanya Jawab (FAQ)
                    </a>
                </div>
            </div>

            <!-- 3. Accordion Group: Kelola Dagangan -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('mobile_group_dagangan', 'mobile_chev_dagangan')" 
                        class="w-full rounded-2xl px-3.5 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-box-archive text-slate-400 text-base"></i>
                        <span class="text-sm font-bold">Kelola Dagangan</span>
                    </div>
                    <i id="mobile_chev_dagangan" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isDaganganActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="mobile_group_dagangan" class="border-l-2 border-sky-500/30 ml-6 pl-3.5 space-y-1 my-1.5 <?= $isDaganganActive ? '' : 'hidden' ?>">
                    <a href="/admin/products" class="block px-3 py-2 rounded-xl text-xs font-semibold transition <?= $active === 'products' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-boxes-stacked text-xs mr-2 text-slate-400"></i> Semua Dagangan
                    </a>
                    <a href="/admin/products/create" class="block px-3 py-2 rounded-xl text-xs font-semibold transition <?= $active === 'product_create' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-circle-plus text-xs mr-2 text-slate-400"></i> Tambah Dagangan Baru
                    </a>
                    <a href="/admin/categories" class="block px-3 py-2 rounded-xl text-xs font-semibold transition <?= $active === 'categories' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-gamepad text-xs mr-2 text-slate-400"></i> Kategori & Game
                    </a>
                </div>
            </div>

            <!-- 4. Accordion Group: Transaksi & Pesanan -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('mobile_group_transaksi', 'mobile_chev_transaksi')" 
                        class="w-full rounded-2xl px-3.5 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clipboard-list text-slate-400 text-base"></i>
                        <span class="text-sm font-bold">Transaksi & Chat</span>
                    </div>
                    <i id="mobile_chev_transaksi" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isTransaksiActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="mobile_group_transaksi" class="border-l-2 border-sky-500/30 ml-6 pl-3.5 space-y-1 my-1.5 <?= $isTransaksiActive ? '' : 'hidden' ?>">
                    <a href="/admin/orders" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition <?= $active === 'orders' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <div class="flex items-center">
                            <i class="fa-solid fa-receipt text-xs mr-2 text-slate-400"></i> Riwayat Pesanan
                        </div>
                        <?php if ($pendingCount > 0): ?>
                            <span class="px-2 py-0.2 rounded-full text-[10px] font-black bg-rose-500 text-white animate-pulse"><?= $pendingCount ?></span>
                        <?php endif; ?>
                    </a>
                    <button type="button" onclick="toggleSellerDockChat(true)" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-white/[0.06] transition text-left">
                        <div class="flex items-center">
                            <i class="fa-solid fa-comments text-xs mr-2 text-slate-400"></i> Live Chat Pembeli
                        </div>
                        <?php if ($unreadChatCount > 0): ?>
                            <span class="px-2 py-0.2 rounded-full text-[10px] font-black bg-emerald-500 text-white animate-pulse"><?= $unreadChatCount ?></span>
                        <?php endif; ?>
                    </button>
                    <a href="/admin/reviews" class="block px-3 py-2 rounded-xl text-xs font-semibold transition <?= $active === 'reviews' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-star text-xs mr-2 text-slate-400"></i> Ulasan & Rating
                    </a>
                </div>
            </div>

            <!-- 5. Accordion Group: Promosi -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('mobile_group_promo', 'mobile_chev_promo')" 
                        class="w-full rounded-2xl px-3.5 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-tags text-slate-400 text-base"></i>
                        <span class="text-sm font-bold">Promosi & Diskon</span>
                    </div>
                    <i id="mobile_chev_promo" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isPromosiActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="mobile_group_promo" class="border-l-2 border-sky-500/30 ml-6 pl-3.5 space-y-1 my-1.5 <?= $isPromosiActive ? '' : 'hidden' ?>">
                    <a href="/admin/redeem-codes" class="block px-3 py-2 rounded-xl text-xs font-semibold transition <?= $active === 'redeem_codes' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-ticket text-xs mr-2 text-slate-400"></i> Kode Promo Voucher
                    </a>
                </div>
            </div>

        </div>
    </div>

</aside>


<style>
.sidebar-no-scroll::-webkit-scrollbar { display: none; width: 0; }
.sidebar-no-scroll { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<!-- ================= DESKTOP FIXED SIDEBAR (W-72 WIDE NAVY THEME) ================= -->
<aside id="adminDesktopSidebar" class="hidden md:flex fixed top-0 bottom-0 left-0 w-72 bg-[#0c1e33] border-r border-white/10 flex-col justify-between z-30 select-none overflow-y-auto shadow-2xl sidebar-no-scroll transition-transform duration-300">
    <div>
        
        <!-- 1. Top Brand Header Card (Spacious & Clean) -->
        <div class="p-4">
            <div class="p-3.5 rounded-2xl bg-white/[0.06] border border-white/10 flex items-center gap-3.5 shadow-lg shadow-black/20 hover:bg-white/[0.08] transition">
                <div class="w-11 h-11 rounded-xl bg-white/10 p-1.5 flex items-center justify-center border border-white/20 shadow-inner flex-shrink-0">
                    <img src="/images/logo-icon.png" srcset="/images/logo-icon@2x.png 2x" alt="ItemPedia" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0 flex-grow">
                    <h1 class="font-extrabold text-white text-sm tracking-tight leading-tight whitespace-nowrap">Admin Panel</h1>
                    <p class="text-[11px] text-sky-200/80 font-medium leading-tight whitespace-nowrap">ItemPedia Seller Center</p>
                </div>
            </div>
        </div>

        <!-- 2. Sidebar Accordion Navigation List -->
        <div class="px-3.5 pb-4 space-y-2.5 text-xs font-semibold">

            <!-- Item: Dashboard (Active Pill with Blue Gradient & Indicator Dot) -->
            <a href="/admin" class="w-full rounded-2xl px-4 py-3 flex items-center justify-between transition group <?= $active === 'dashboard' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white font-extrabold shadow-md shadow-sky-500/25' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                <div class="flex items-center gap-3.5 whitespace-nowrap">
                    <i class="fa-solid fa-shapes text-base <?= $active === 'dashboard' ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                    <span class="text-sm">Dashboard</span>
                </div>
                <?php if ($active === 'dashboard'): ?>
                    <span class="w-2 h-2 rounded-full bg-white shadow-xs flex-shrink-0"></span>
                <?php endif; ?>
            </a>

            <!-- Group 1: Konten Website -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('desktop_group_konten', 'desktop_chev_konten')" 
                        class="w-full rounded-2xl px-4 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer group">
                    <div class="flex items-center gap-3.5 whitespace-nowrap">
                        <i class="fa-regular fa-newspaper text-slate-400 group-hover:text-white text-base"></i>
                        <span class="text-sm font-bold">Konten Website</span>
                    </div>
                    <i id="desktop_chev_konten" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isKontenActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="desktop_group_konten" class="border-l-2 border-sky-500/30 ml-6 pl-4 space-y-1.5 my-1.5 <?= $isKontenActive ? '' : 'hidden' ?>">
                    <a href="/admin/pages" class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $active === 'pages' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-regular fa-file-lines text-xs mr-2.5 text-slate-400"></i>
                        <span>Edit Laman & Teks</span>
                    </a>
                    <a href="/admin/pages#faqs" class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap text-slate-300 hover:text-white hover:bg-white/[0.06] transition">
                        <i class="fa-regular fa-circle-question text-xs mr-2.5 text-slate-400"></i>
                        <span>Tanya Jawab (FAQ)</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Kelola Dagangan -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('desktop_group_dagangan', 'desktop_chev_dagangan')" 
                        class="w-full rounded-2xl px-4 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer group">
                    <div class="flex items-center gap-3.5 whitespace-nowrap">
                        <i class="fa-solid fa-box-archive text-slate-400 group-hover:text-white text-base"></i>
                        <span class="text-sm font-bold">Kelola Dagangan</span>
                    </div>
                    <i id="desktop_chev_dagangan" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isDaganganActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="desktop_group_dagangan" class="border-l-2 border-sky-500/30 ml-6 pl-4 space-y-1.5 my-1.5 <?= $isDaganganActive ? '' : 'hidden' ?>">
                    <a href="/admin/products" class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $active === 'products' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-boxes-stacked text-xs mr-2.5 text-slate-400"></i>
                        <span>Semua Dagangan</span>
                    </a>
                    <a href="/admin/products/create" class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $active === 'product_create' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-circle-plus text-xs mr-2.5 text-slate-400"></i>
                        <span>Tambah Dagangan</span>
                    </a>
                    <a href="/admin/categories" class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $active === 'categories' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-gamepad text-xs mr-2.5 text-slate-400"></i>
                        <span>Kategori & Game</span>
                    </a>
                </div>
            </div>

            <!-- Group 3: Transaksi & Pesanan -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('desktop_group_transaksi', 'desktop_chev_transaksi')" 
                        class="w-full rounded-2xl px-4 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer group">
                    <div class="flex items-center gap-3.5 whitespace-nowrap">
                        <i class="fa-solid fa-clipboard-list text-slate-400 group-hover:text-white text-base"></i>
                        <span class="text-sm font-bold">Transaksi & Chat</span>
                    </div>
                    <i id="desktop_chev_transaksi" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isTransaksiActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="desktop_group_transaksi" class="border-l-2 border-sky-500/30 ml-6 pl-4 space-y-1.5 my-1.5 <?= $isTransaksiActive ? '' : 'hidden' ?>">
                    <a href="/admin/orders" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $active === 'orders' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <div class="flex items-center">
                            <i class="fa-solid fa-receipt text-xs mr-2.5 text-slate-400"></i>
                            <span>Riwayat Pesanan</span>
                        </div>
                        <?php if ($pendingCount > 0): ?>
                            <span class="px-2 py-0.2 rounded-full text-[10px] font-black bg-rose-500 text-white animate-pulse"><?= $pendingCount ?></span>
                        <?php endif; ?>
                    </a>
                    <button type="button" onclick="toggleSellerDockChat(true)" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap text-slate-300 hover:text-white hover:bg-white/[0.06] transition text-left">
                        <div class="flex items-center">
                            <i class="fa-solid fa-comments text-xs mr-2.5 text-slate-400"></i>
                            <span>Live Chat Pembeli</span>
                        </div>
                        <?php if ($unreadChatCount > 0): ?>
                            <span class="px-2 py-0.2 rounded-full text-[10px] font-black bg-emerald-500 text-white animate-pulse"><?= $unreadChatCount ?></span>
                        <?php endif; ?>
                    </button>
                    <a href="/admin/reviews" class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $active === 'reviews' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-star text-xs mr-2.5 text-slate-400"></i>
                        <span>Ulasan & Rating</span>
                    </a>
                </div>
            </div>

            <!-- Group 4: Promosi & Diskon -->
            <div class="rounded-2xl overflow-hidden">
                <button type="button" 
                        onclick="toggleAdminMenu('desktop_group_promo', 'desktop_chev_promo')" 
                        class="w-full rounded-2xl px-4 py-3 flex items-center justify-between text-white hover:bg-white/[0.06] transition cursor-pointer group">
                    <div class="flex items-center gap-3.5 whitespace-nowrap">
                        <i class="fa-solid fa-tags text-slate-400 group-hover:text-white text-base"></i>
                        <span class="text-sm font-bold">Promosi & Diskon</span>
                    </div>
                    <i id="desktop_chev_promo" class="fa-solid fa-chevron-up text-xs text-slate-400 transition-transform duration-200 <?= $isPromosiActive ? '' : 'rotate-180' ?>"></i>
                </button>
                <div id="desktop_group_promo" class="border-l-2 border-sky-500/30 ml-6 pl-4 space-y-1.5 my-1.5 <?= $isPromosiActive ? '' : 'hidden' ?>">
                    <a href="/admin/redeem-codes" class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $active === 'redeem_codes' ? 'text-sky-400 font-extrabold bg-sky-500/10' : 'text-slate-300 hover:text-white hover:bg-white/[0.06]' ?>">
                        <i class="fa-solid fa-ticket text-xs mr-2.5 text-slate-400"></i>
                        <span>Kode Promo Voucher</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</aside>

<!-- Interactive Accordion Menu Script -->
<script>
function toggleAdminMenu(groupId, chevId) {
    const groupEl = document.getElementById(groupId);
    const chevEl = document.getElementById(chevId);
    if (!groupEl) return;

    if (groupEl.classList.contains('hidden')) {
        groupEl.classList.remove('hidden');
        if (chevEl) chevEl.classList.remove('rotate-180');
    } else {
        groupEl.classList.add('hidden');
        if (chevEl) chevEl.classList.add('rotate-180');
    }
}

function toggleAdminMobileSidebar(open) {
    const backdrop = document.getElementById('adminMobileSidebarBackdrop');
    const drawer = document.getElementById('adminMobileSidebarDrawer');
    if (!backdrop || !drawer) return;

    if (open) {
        backdrop.classList.remove('hidden');
        drawer.classList.remove('-translate-x-full');
    } else {
        backdrop.classList.add('hidden');
        drawer.classList.add('-translate-x-full');
    }
}
</script>

<?php include __DIR__ . '/chat_widget.php'; ?>
<?php include __DIR__ . '/../components/custom_confirm.php'; ?>
