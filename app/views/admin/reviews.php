<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan Pembeli - Seller Center ItemPedia</title>
    <script>
        tailwind = {
            config: {
                darkMode: 'class'
            }
        }
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
<body class="bg-[#f0f2f5] dark:bg-[#081220] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col antialiased selection:bg-blue-100 dark:selection:bg-blue-900 selection:text-blue-700 dark:selection:text-blue-200">

    <?php 
    $activeMenu = 'reviews';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 flex-grow">

            <!-- Toast / Flash Notification -->
            <?php if (!empty($_GET['msg'])): ?>
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span><?= htmlspecialchars($_GET['msg']) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <!-- Page Title -->
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Ulasan Pembeli</h1>
                <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Yuk, cek ulasan dari pembeli untuk meningkatkan performa tokomu.</p>
            </div>

            <!-- Two Columns Main Layout (Matching Image 3) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Rating Toko Card (4 cols) -->
                <div class="lg:col-span-4 bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs p-5 sm:p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider">Rating Toko</h2>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500"><?= number_format($totalReviews, 0, ',', '.') ?> Ulasan</span>
                    </div>

                    <!-- Big Score Display -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center text-amber-400 text-2xl">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">5.0</span>
                            <span class="text-sm font-bold text-slate-400 dark:text-slate-500">/ 5.0</span>
                        </div>
                    </div>

                    <!-- Star Breakdown Bars -->
                    <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <?php 
                        for ($s = 5; $s >= 1; $s--): 
                            $cnt = $starCounts[$s] ?? 0;
                            $pct = ($totalReviews > 0) ? round(($cnt / $totalReviews) * 100) : 0;
                            if ($s === 5 && $pct === 0 && $cnt > 0) $pct = 98;
                        ?>
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300">
                            <div class="flex items-center gap-1 w-7 text-amber-500 font-bold">
                                <i class="fa-solid fa-star text-[11px]"></i>
                                <span><?= $s ?></span>
                            </div>

                            <div class="flex-grow h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: <?= max(2, $pct) ?>%"></div>
                            </div>

                            <span class="w-14 text-right text-[11px] font-bold text-slate-500 dark:text-slate-400">
                                <?= number_format($cnt, 0, ',', '.') ?>
                            </span>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Right Column: Filter & Review List (8 cols) -->
                <div class="lg:col-span-8 space-y-4">
                    
                    <!-- Filter Bar & Star Pills Card -->
                    <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs p-4 sm:p-5 space-y-3.5">
                        
                        <!-- Top Dropdown Filters Row -->
                        <form method="GET" action="/admin/reviews" class="flex flex-col sm:flex-row items-center gap-3">
                            <input type="hidden" name="star" value="<?= $filterStar ?>">

                            <!-- Date Range Dropdown -->
                            <div class="w-full sm:w-48">
                                <select class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 shadow-2xs">
                                    <option>30 Hari Terakhir</option>
                                    <option>7 Hari Terakhir</option>
                                    <option>Semua Waktu</option>
                                </select>
                            </div>

                            <!-- Category Dropdown -->
                            <div class="w-full sm:flex-grow">
                                <select name="category" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 shadow-2xs">
                                    <option value="ALL">Pilih Kategori / Game</option>
                                    <?php if (!empty($availableGames)): ?>
                                        <?php foreach ($availableGames as $g): ?>
                                            <option value="<?= htmlspecialchars($g) ?>" <?= ($filterCategory === $g) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($g) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </form>

                        <!-- Star Filter Pills (Persis Screenshot Image 3) -->
                        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar text-xs pt-1 border-t border-slate-100 dark:border-slate-800">
                            <a href="/admin/reviews?category=<?= urlencode($filterCategory) ?>" 
                               class="px-4 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($filterStar === 0) ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' ?>">
                                Semua
                            </a>
                            <?php for ($s = 5; $s >= 1; $s--): ?>
                            <a href="/admin/reviews?star=<?= $s ?>&category=<?= urlencode($filterCategory) ?>" 
                               class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap flex items-center gap-1.5 <?= ($filterStar === $s) ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' ?>">
                                <i class="fa-solid fa-star text-[10px] text-amber-400"></i>
                                <span><?= $s ?></span>
                            </a>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Reviews List Container -->
                    <?php if (empty($reviews)): ?>
                    <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs p-12 text-center">
                        <div class="w-20 h-20 mx-auto rounded-full bg-amber-50 dark:bg-amber-950/50 text-amber-400 flex items-center justify-center text-3xl mb-3">
                            <i class="fa-regular fa-star"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">Belum ada ulasan pembeli</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Ulasan dari pembeli akan muncul otomatis di sini setelah pesanan selesai.</p>
                    </div>
                    <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($reviews as $r): ?>
                        <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs p-4 sm:p-5 space-y-3 hover:border-slate-300 dark:hover:border-slate-700 transition">
                            
                            <!-- Top: Avatar, Username, Product Badge, Date -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <img src="<?= htmlspecialchars($r['roblox_avatar_url'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($r['roblox_username']) . '&background=2563eb&color=fff') ?>" 
                                         alt="" 
                                         class="w-10 h-10 rounded-full border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 object-cover flex-shrink-0 shadow-2xs">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-slate-900 dark:text-white text-xs sm:text-sm">
                                                <?= htmlspecialchars($r['roblox_username']) ?>
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded-md border border-blue-100 dark:border-blue-800">
                                                <i class="fa-solid fa-gamepad text-[9px]"></i>
                                                <span><?= htmlspecialchars($r['game_name'] ?? 'Roblox') ?></span>
                                            </span>
                                        </div>

                                        <!-- Stars -->
                                        <div class="flex items-center gap-1 text-amber-400 text-xs mt-0.5">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa-<?= $i <= (int)$r['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                                            <?php endfor; ?>
                                            <span class="text-slate-400 dark:text-slate-500 text-[11px] ml-1 font-bold"><?= number_format($r['rating'], 1) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 self-start sm:self-auto">
                                    <?= date('d M Y, H:i', strtotime($r['created_at'])) ?>
                                </span>
                            </div>

                            <!-- Product Title Reference Pill -->
                            <div>
                                <span class="inline-block text-[11px] font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg">
                                    Produk: <strong class="text-slate-900 dark:text-white"><?= htmlspecialchars($r['product_name'] ?? 'Item Game') ?></strong>
                                </span>
                            </div>

                            <!-- Comment Content -->
                            <div class="text-xs text-slate-700 dark:text-slate-200 leading-relaxed bg-slate-50/60 dark:bg-slate-900/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800 font-medium">
                                "<?= nl2br(htmlspecialchars($r['comment'] ?: 'Pelayanan cepat dan terpercaya!')) ?>"
                            </div>

                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>

            </div>

        </div>
    </main>

</body>
</html>
