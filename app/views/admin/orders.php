<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Seller Center ItemPedia</title>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('admin_theme') === 'dark' || (!localStorage.getItem('admin_theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="icon" type="image/svg+xml" href="/images/logo-icon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f0f2f5] dark:bg-[#081220] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col antialiased selection:bg-blue-100 selection:text-blue-700 transition-colors duration-200">

    <?php 
    $activeMenu = 'orders';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5 flex-grow">

            <!-- Toast / Flash Notification -->
            <?php if (!empty($_GET['msg'])): ?>
            <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span><?= htmlspecialchars($_GET['msg']) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <!-- Page Header: Title on Left, Action on Right -->
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Riwayat Pesanan</h1>

                <div class="flex items-center gap-2.5">
                    <button type="button" 
                            onclick="alert('Riwayat pesanan berhasil diekspor!')" 
                            class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition flex items-center gap-2 shadow-2xs active:scale-95">
                        <i class="fa-solid fa-arrow-down-to-bracket text-slate-400 dark:text-slate-500"></i>
                        <span>Unduh Riwayat Pesanan</span>
                    </button>
                </div>
            </div>

            <!-- 1. Top Navigation & Filters Container -->
            <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs overflow-hidden">
                
                <!-- Horizontal Status Tabs Strip -->
                <div class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1e33] px-3 sm:px-5">
                    <div class="flex items-center gap-1 sm:gap-4 overflow-x-auto no-scrollbar text-xs font-bold whitespace-nowrap">
                        <?php 
                        $curTab = $statusFilter ?? 'ALL';
                        $tabs = [
                            ['key' => 'NEED_PROCESS', 'label' => 'Perlu Diproses', 'count' => $tabCounts['NEED_PROCESS'] ?? 0],
                            ['key' => 'PENDING', 'label' => 'Menunggu Konfirmasi', 'count' => $tabCounts['PENDING'] ?? 0],
                            ['key' => 'SUCCESS', 'label' => 'Pesanan Selesai', 'count' => $tabCounts['SUCCESS'] ?? 0],
                            ['key' => 'PROCESSING', 'label' => 'Sedang Dibatalkan', 'count' => 0],
                            ['key' => 'CANCELLED', 'label' => 'Pesanan Dibatalkan', 'count' => $tabCounts['CANCELLED'] ?? 0],
                            ['key' => 'ALL', 'label' => 'Semua Pesanan', 'count' => $tabCounts['ALL'] ?? 0]
                        ];
                        ?>

                        <?php foreach ($tabs as $t): 
                            $isActive = ($curTab === $t['key']);
                        ?>
                            <a href="/Banjar/orders?status=<?= $t['key'] ?><?= !empty($searchQuery) ? '&q=' . urlencode($searchQuery) : '' ?><?= !empty($gameFilter) ? '&game=' . urlencode($gameFilter) : '' ?>" 
                               class="py-3 px-2 sm:px-3 border-b-2 text-xs font-bold flex items-center gap-1.5 transition relative whitespace-nowrap <?= $isActive ? 'border-sky-500 text-sky-500 dark:text-sky-400' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-700' ?>">
                                <span><?= $t['label'] ?></span>
                                <?php if ($t['count'] > 0): ?>
                                    <span class="px-1.5 py-0.2 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center">
                                        <?= $t['count'] ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Search & Multi-Filter Bar -->
                <div class="p-3 sm:p-4 bg-white dark:bg-[#0c1e33] space-y-3">
                    <form method="GET" action="/Banjar/orders" class="flex flex-col sm:flex-row items-center gap-2.5 w-full">
                        <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter ?? 'ALL') ?>">

                        <!-- Search Input with "Nomor Pesanan" Prefix -->
                        <div class="w-full sm:flex-1 flex items-center rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xs focus-within:border-sky-500 transition overflow-hidden">
                            <div class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-bold flex items-center gap-1 whitespace-nowrap">
                                <span>Nomor Pesanan</span>
                                <i class="fa-solid fa-chevron-down text-[8.5px] text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <div class="relative flex-grow flex items-center">
                                <i class="fa-solid fa-magnifying-glass text-slate-400 dark:text-slate-500 text-[11px] absolute left-3 pointer-events-none"></i>
                                <input type="text" 
                                       name="q" 
                                       value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                                       placeholder="Cari Nomor Pesanan" 
                                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-transparent text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none">
                            </div>
                        </div>

                        <!-- Dropdown Pilih Filter / Game -->
                        <div class="w-full sm:w-52">
                            <select name="game" onchange="this.form.submit()" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sky-500 shadow-2xs">
                                <option value="ALL">Pilih Filter</option>
                                <?php if (!empty($availableGames)): ?>
                                    <?php foreach ($availableGames as $gName): ?>
                                        <option value="<?= htmlspecialchars($gName) ?>" <?= ($gameFilter === $gName) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($gName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Dropdown Urutan / Terbaru -->
                        <div class="w-full sm:w-36">
                            <select name="sort" onchange="this.form.submit()" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sky-500 shadow-2xs">
                                <option value="latest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                            </select>
                        </div>
                    </form>

                    <!-- Filter Pills Cepat -->
                    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar text-xs">
                        <a href="/Banjar/orders?status=<?= htmlspecialchars($statusFilter ?? 'ALL') ?>" 
                           class="px-3 py-1 rounded-full text-[11px] font-bold transition whitespace-nowrap <?= empty($searchQuery) && empty($gameFilter) ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Semua Pesanan
                        </a>
                        <a href="/Banjar/orders?status=<?= htmlspecialchars($statusFilter ?? 'ALL') ?>&q=Joki" 
                           class="px-3 py-1 rounded-full text-[11px] font-bold transition whitespace-nowrap <?= ($searchQuery === 'Joki') ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Joki
                        </a>
                        <a href="/Banjar/orders?status=<?= htmlspecialchars($statusFilter ?? 'ALL') ?>&q=Iklan" 
                           class="px-3 py-1 rounded-full text-[11px] font-bold transition whitespace-nowrap <?= ($searchQuery === 'Iklan') ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Pesanan Dari Iklan
                        </a>
                        <a href="/Banjar/orders?status=NEED_PROCESS" 
                           class="px-3 py-1 rounded-full text-[11px] font-bold transition whitespace-nowrap <?= ($statusFilter === 'NEED_PROCESS') ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Pesanan Butuh Cepat
                        </a>
                    </div>
                </div>

            </div>

            <!-- 2. Orders Content (Cards List Layout Matching User Screenshot) -->
            <?php if (empty($orders)): ?>
            <!-- Empty State Illustration Matching Screenshot -->
            <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs py-20 text-center px-4">
                <div class="w-36 h-36 mx-auto mb-4 relative flex items-center justify-center">
                    <div class="w-28 h-28 bg-amber-50 dark:bg-amber-950/60 rounded-full flex items-center justify-center text-5xl text-amber-400">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>
                    <div class="absolute top-1 right-2 w-10 h-10 bg-sky-50 dark:bg-sky-950/60 rounded-full flex items-center justify-center text-sky-500 text-lg shadow-xs">
                        <i class="fa-solid fa-question"></i>
                    </div>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm sm:text-base">Kamu belum memiliki pesanan</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Pesanan yang masuk akan tampil otomatis di sini.</p>
            </div>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($orders as $o): 
                    // Compute status display text matching screenshot
                    $statusText = 'Menunggu Pembayaran';
                    $statusClass = 'text-amber-600 dark:text-amber-400';
                    if ($o['status'] === 'PAID') {
                        $statusText = 'Butuh diproses';
                        $statusClass = 'text-slate-900 dark:text-white font-extrabold';
                    } elseif ($o['status'] === 'PROCESSING') {
                        $statusText = 'Sedang dikirim';
                        $statusClass = 'text-purple-600 dark:text-purple-400 font-extrabold';
                    } elseif ($o['status'] === 'SUCCESS') {
                        $statusText = 'Pesanan Selesai';
                        $statusClass = 'text-emerald-600 dark:text-emerald-400 font-extrabold';
                    } elseif ($o['status'] === 'CANCELLED') {
                        $statusText = 'Pesanan Dibatalkan';
                        $statusClass = 'text-rose-600 dark:text-rose-400 font-extrabold';
                    }
                ?>
                <!-- Individual Order Card (Itemku Seller Style) -->
                <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs overflow-hidden transition hover:shadow-sm">
                    
                    <!-- Card Top Strip (4 Columns: Status, Invoice, Tanggal, Respons Sebelum) -->
                    <div class="px-4 py-3 sm:px-6 sm:py-3.5 bg-slate-50/70 dark:bg-slate-900/60 border-b border-slate-100 dark:border-slate-800">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-6 text-xs">
                            <!-- 1. Status Pesanan -->
                            <div>
                                <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Status Pesanan</span>
                                <span class="text-xs <?= $statusClass ?> block truncate mt-0.5">
                                    <?= $statusText ?>
                                </span>
                            </div>

                            <!-- 2. Nomor Pesanan -->
                            <div>
                                <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Nomor Pesanan</span>
                                <a href="/order/<?= htmlspecialchars($o['invoice_number']) ?>" target="_blank" class="font-mono font-bold text-xs text-sky-600 dark:text-sky-400 hover:underline inline-flex items-center gap-1 mt-0.5" title="Lihat Invoice Pembeli">
                                    <span><?= htmlspecialchars($o['invoice_number']) ?></span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>

                            <!-- 3. Tanggal Transaksi -->
                            <div>
                                <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Tanggal Transaksi</span>
                                <span class="font-semibold text-xs text-slate-700 dark:text-slate-300 block mt-0.5">
                                    <?= date('d M Y H:i:s', strtotime($o['created_at'])) ?>
                                </span>
                            </div>

                            <!-- 4. Respons Sebelum -->
                            <div>
                                <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Respons Sebelum</span>
                                <span class="font-semibold text-xs text-slate-700 dark:text-slate-300 block mt-0.5">
                                    <?= date('d M Y H:i:s', strtotime($o['created_at'] . ' +2 days')) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                            
                            <!-- Left: Product Information (Col 1-5) -->
                            <div class="lg:col-span-5 space-y-1">
                                <h3 class="font-black text-slate-900 dark:text-white text-sm sm:text-base leading-snug">
                                    <?= htmlspecialchars($o['product_name']) ?>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">
                                    <?= htmlspecialchars($o['category_name'] ?? 'Roblox') ?>
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    1 <?= htmlspecialchars($o['sub_category'] ?? 'Pets') ?>
                                </p>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200 pt-0.5">
                                    Rp <?= number_format($o['price'], 0, ',', '.') ?>
                                </p>
                            </div>

                            <!-- Right: Pembeli, Username Roblox, Catatan (Col 6-12) -->
                            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs pt-1">
                                <!-- Pembeli -->
                                <div class="space-y-0.5">
                                    <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Pembeli</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">
                                        <?= htmlspecialchars($o['buyer_name'] ?? $o['roblox_username'] ?? 'Pembeli') ?>
                                    </span>
                                    <button type="button" 
                                            onclick="openChatDockForInvoice('<?= htmlspecialchars($o['invoice_number']) ?>')" 
                                            class="text-sky-600 dark:text-sky-400 hover:underline font-bold text-[11px] inline-flex items-center gap-1 cursor-pointer pt-0.5">
                                        <i class="fa-solid fa-comments text-[10px]"></i>
                                        <span>Hubungi Pembeli</span>
                                        <?php if (!empty($o['unread_chat_count']) && $o['unread_chat_count'] > 0): ?>
                                            <span class="px-1 py-0.2 rounded-full bg-rose-500 text-white text-[8px] font-black animate-pulse">
                                                <?= $o['unread_chat_count'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </button>
                                </div>

                                <!-- Username Roblox -->
                                <div class="space-y-0.5">
                                    <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Username Roblox</span>
                                    <div class="flex items-center gap-1.5">
                                        <?php if (!empty($o['roblox_avatar_url'])): ?>
                                            <img src="<?= htmlspecialchars($o['roblox_avatar_url']) ?>" alt="" class="w-5 h-5 rounded-full border border-slate-200 dark:border-slate-700 bg-white object-cover">
                                        <?php endif; ?>
                                        <span class="font-extrabold text-slate-900 dark:text-white truncate">
                                            <?= htmlspecialchars($o['roblox_username'] ?? '-') ?>
                                        </span>
                                    </div>
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('<?= addslashes($o['roblox_username']) ?>'); alert('Username disalin: <?= addslashes($o['roblox_username']) ?>')" 
                                            class="text-sky-600 dark:text-sky-400 hover:underline font-bold text-[11px] inline-flex items-center gap-1 cursor-pointer pt-0.5">
                                        <i class="fa-regular fa-copy text-[10px]"></i>
                                        <span>Salin</span>
                                    </button>
                                </div>

                                <!-- Catatan -->
                                <div class="space-y-0.5">
                                    <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Catatan</span>
                                    <p class="font-medium text-slate-600 dark:text-slate-300 line-clamp-3 text-xs">
                                        <?= !empty($o['note']) ? htmlspecialchars($o['note']) : '-' ?>
                                    </p>
                                </div>
                            </div>

                        </div>

                        <!-- Tulis Catatan / Update Trigger Button on Left -->
                        <div class="pt-2 flex items-center gap-3">
                            <button type="button" 
                                    onclick="openEditOrderModal(<?= htmlspecialchars(json_encode($o)) ?>)" 
                                    class="text-sky-600 dark:text-sky-400 hover:underline font-bold text-xs inline-flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                                <span>Tulis Catatan</span>
                            </button>
                        </div>

                        <!-- Jika ada account data terkirim -->
                        <?php if (!empty($o['account_data'])): ?>
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 text-xs font-mono text-slate-800 dark:text-slate-200 space-y-1">
                            <div class="text-[10.5px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Data Akun Terkirim:</div>
                            <div class="whitespace-pre-wrap leading-relaxed"><?= htmlspecialchars($o['account_data']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Strip of Card -->
                    <div class="px-4 py-3 sm:px-6 bg-white dark:bg-[#0c1e33] border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                        <!-- Total Pendapatan -->
                        <div>
                            <span class="block text-[10.5px] text-slate-400 dark:text-slate-500 font-medium">Total Pendapatan</span>
                            <span class="font-black text-sm sm:text-base text-orange-500 dark:text-orange-400">
                                Rp <?= number_format($o['price'], 0, ',', '.') ?>
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2.5">
                            <button type="button" 
                                    onclick="openCancelOrderModal(<?= htmlspecialchars(json_encode($o)) ?>)" 
                                    class="px-3.5 py-1.5 rounded-lg border border-sky-500 text-sky-600 dark:text-sky-400 hover:bg-sky-50 dark:hover:bg-slate-800 font-bold text-xs transition active:scale-95 cursor-pointer">
                                Batalkan Pesanan
                            </button>
                            <button type="button" 
                                    onclick="openEditOrderModal(<?= htmlspecialchars(json_encode($o)) ?>)" 
                                    class="px-4 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs transition shadow-xs active:scale-95 cursor-pointer">
                                <?= ($o['status'] === 'SUCCESS') ? 'Pesanan Selesai' : 'Sudah Dikirim' ?>
                            </button>
                        </div>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </main>

    <!-- Modal Update Status Pesanan -->
    <div id="orderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden animate-in fade-in duration-150">
        <div class="bg-white dark:bg-[#0c1e33] border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/60 dark:bg-slate-900/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold text-sm border border-sky-100 dark:border-sky-800/60">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Update Status Transaksi</h3>
                </div>
                <button type="button" onclick="closeOrderModal()" class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/Banjar/orders/update" method="POST" class="p-5 space-y-4">
                <input type="hidden" name="order_id" id="editOrderId">

                <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 rounded-2xl text-xs space-y-1.5 border border-slate-200/80 dark:border-slate-800">
                    <div class="text-slate-500 dark:text-slate-400">Invoice: <strong id="editOrderInvoice" class="text-sky-600 dark:text-sky-400 font-mono font-bold"></strong></div>
                    <div class="text-slate-500 dark:text-slate-400">Pembeli: <strong id="editOrderBuyer" class="text-slate-900 dark:text-white font-bold"></strong></div>
                    <div class="text-slate-500 dark:text-slate-400">Produk: <strong id="editOrderProduct" class="text-slate-900 dark:text-white font-bold"></strong></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Ubah Status Pengiriman</label>
                    <select name="status" id="editOrderStatus" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500">
                        <option value="PENDING">Menunggu Pembayaran (PENDING)</option>
                        <option value="PAID">Sudah Dibayar / Butuh Diproses (PAID)</option>
                        <option value="PROCESSING">Sedang Diproses Pengiriman (PROCESSING)</option>
                        <option value="SUCCESS">Pesanan Selesai (SUCCESS)</option>
                        <option value="CANCELLED">Dibatalkan (CANCELLED)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Data Akun Roblox (Khusus Akun Game)
                    </label>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-2">Jika produk ini berupa Akun, masukkan Username & Password akun di sini. Pembeli dapat melihatnya otomatis di invoice setelah status 'SUCCESS'.</p>
                    <textarea name="account_data" 
                              id="editOrderAccountData" 
                              rows="3" 
                              placeholder="Username: roblox_account&#10;Password: password123&#10;Catatan: Langsung ganti password & verifikasi email ya kak!"
                              class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-slate-900 resize-none"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2.5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeOrderModal()" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-extrabold rounded-xl transition shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditOrderModal(order) {
        document.getElementById('editOrderId').value = order.id;
        document.getElementById('editOrderInvoice').innerText = order.invoice_number;
        document.getElementById('editOrderBuyer').innerText = order.roblox_username;
        document.getElementById('editOrderProduct').innerText = order.product_name;
        document.getElementById('editOrderStatus').value = order.status;
        document.getElementById('editOrderAccountData').value = order.account_data || '';
        document.getElementById('orderModal').classList.remove('hidden');
    }
    function openCancelOrderModal(order) {
        document.getElementById('editOrderId').value = order.id;
        document.getElementById('editOrderInvoice').innerText = order.invoice_number;
        document.getElementById('editOrderBuyer').innerText = order.roblox_username;
        document.getElementById('editOrderProduct').innerText = order.product_name;
        document.getElementById('editOrderStatus').value = 'CANCELLED';
        document.getElementById('editOrderAccountData').value = order.account_data || '';
        document.getElementById('orderModal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }
    </script>
</body>
</html>
