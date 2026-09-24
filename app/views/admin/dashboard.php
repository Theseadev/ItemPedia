<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Toko - Seller Center ItemPedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/svg+xml" href="/images/logo-icon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f0f2f5] text-slate-800 min-h-screen flex flex-col antialiased selection:bg-blue-100 selection:text-blue-700">

    <?php 
    $activeMenu = 'dashboard';
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
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Beranda Toko</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Pantau aktifitas harian, pendapatan, dan performa tokomu di ItemPedia.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/admin/products/create" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2 active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Buat Dagangan Baru</span>
                    </a>
                </div>
            </div>

            <!-- Main Content Layout (Full Width) -->
            <div class="space-y-6">
                
                <!-- 1. Aktifitas Penting Card -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Aktifitas Penting</h2>
                            <p class="text-xs text-slate-400">Data berikut adalah data 7 hari terakhir.</p>
                        </div>
                    </div>

                    <!-- 6 Cards Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
                            <!-- Card 1: Perlu Diproses -->
                            <a href="/admin/orders?status=NEED_PROCESS" class="group p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 hover:bg-blue-50/50 hover:border-blue-200 transition">
                                <div class="text-slate-500 text-xs font-semibold group-hover:text-blue-700">Perlu Diproses</div>
                                <div class="text-2xl font-black text-slate-900 mt-1 flex items-center gap-2">
                                    <span><?= $needProcess ?></span>
                                    <?php if ($needProcess > 0): ?>
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    <?php endif; ?>
                                </div>
                            </a>

                            <!-- Card 2: Menunggu Konfirmasi -->
                            <a href="/admin/orders?status=PENDING" class="group p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 hover:bg-blue-50/50 hover:border-blue-200 transition">
                                <div class="text-slate-500 text-xs font-semibold group-hover:text-blue-700">Menunggu Konfirmasi</div>
                                <div class="text-2xl font-black text-slate-900 mt-1"><?= $pendingCount ?></div>
                            </a>

                            <!-- Card 3: Kendala Pesanan -->
                            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                                <div class="text-slate-500 text-xs font-semibold">Kendala Pesanan</div>
                                <div class="text-2xl font-black text-slate-900 mt-1">0</div>
                            </div>

                            <!-- Card 4: Sedang Dibatalkan -->
                            <a href="/admin/orders?status=PROCESSING" class="group p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 hover:bg-blue-50/50 hover:border-blue-200 transition">
                                <div class="text-slate-500 text-xs font-semibold group-hover:text-blue-700">Sedang Dibatalkan</div>
                                <div class="text-2xl font-black text-slate-900 mt-1">0</div>
                            </a>

                            <!-- Card 5: Stok Habis -->
                            <a href="/admin/products?tab=OUT_OF_STOCK" class="group p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 hover:bg-rose-50/50 hover:border-rose-200 transition">
                                <div class="text-slate-500 text-xs font-semibold group-hover:text-rose-600">Stok Habis</div>
                                <div class="text-2xl font-black text-slate-900 mt-1 <?= $outOfStockCount > 0 ? 'text-rose-600' : '' ?>">
                                    <?= $outOfStockCount ?>
                                </div>
                            </a>

                            <!-- Card 6: Pemenang Lelang -->
                            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                                <div class="text-slate-500 text-xs font-semibold">Pemenang Lelang</div>
                                <div class="text-2xl font-black text-slate-900 mt-1">0</div>
                            </div>
                        </div>
                    </div>

                <!-- 2. Performa Toko & Chart -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-extrabold text-slate-900">Performa Toko</h2>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            
                            <!-- Left: Statistik 30 Hari (4 cols) -->
                            <div class="md:col-span-4 space-y-4 border-b md:border-b-0 md:border-r border-slate-100 pb-5 md:pb-0 md:pr-4">
                                <div>
                                    <span class="text-xs font-extrabold text-slate-900 block">Statistik Toko</span>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">30 hari terakhir</span>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100">
                                        <span class="text-slate-500 font-semibold">Pembeli</span>
                                        <span class="font-extrabold text-slate-900"><?= $totalBuyers ?></span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100">
                                        <span class="text-slate-500 font-semibold">Pesanan Sukses</span>
                                        <span class="font-extrabold text-emerald-600"><?= $successCount ?></span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs py-1">
                                        <span class="text-slate-500 font-semibold">Pesanan Dibatalkan</span>
                                        <span class="font-extrabold text-rose-500"><?= $cancelledCount ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Pendapatan & SVG Area Chart (8 cols) -->
                            <div class="md:col-span-8 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-extrabold text-slate-900 block">Pendapatan (Rp)</span>
                                        <span class="text-lg font-black text-slate-900 mt-0.5 block">Rp 1.061.000</span>
                                    </div>
                                    <div class="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-[11px] font-bold text-slate-600 flex items-center gap-1.5">
                                        <span>7 hari terakhir</span>
                                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                                    </div>
                                </div>

                                <!-- Vector SVG Area Chart -->
                                <div class="w-full h-44 relative pt-2">
                                    <svg viewBox="0 0 500 130" class="w-full h-full overflow-hidden" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.30"/>
                                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0"/>
                                            </linearGradient>
                                        </defs>

                                        <!-- Background Horizontal Lines -->
                                        <line x1="0" y1="25" x2="500" y2="25" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="3 3" />
                                        <line x1="0" y1="65" x2="500" y2="65" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="3 3" />
                                        <line x1="0" y1="105" x2="500" y2="105" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="3 3" />

                                        <!-- Area Fill -->
                                        <path d="M 0,115 C 60,115 110,85 170,40 C 230,0 280,105 340,90 C 390,75 430,15 470,20 L 500,50 L 500,130 L 0,130 Z" fill="url(#chartGradient)"></path>

                                        <!-- Line Curve -->
                                        <path d="M 0,115 C 60,115 110,85 170,40 C 230,0 280,105 340,90 C 390,75 430,15 470,20 L 500,50" fill="none" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>

                                        <!-- Interactive Data Points -->
                                        <circle cx="170" cy="40" r="4.5" fill="#ffffff" stroke="#2563eb" stroke-width="2.5"></circle>
                                        <circle cx="340" cy="90" r="4.5" fill="#ffffff" stroke="#2563eb" stroke-width="2.5"></circle>
                                        <circle cx="470" cy="20" r="4.5" fill="#ffffff" stroke="#2563eb" stroke-width="2.5"></circle>
                                        <circle cx="500" cy="50" r="4.5" fill="#ffffff" stroke="#2563eb" stroke-width="2.5"></circle>
                                    </svg>

                                    <!-- X-Axis Labels -->
                                    <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-2 px-1">
                                        <?php foreach ($dates as $d): ?>
                                            <span><?= $d ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
