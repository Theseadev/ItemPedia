<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daganganku - Seller Center ItemPedia</title>
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
    $activeMenu = 'products';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5 flex-grow">

            <!-- Toast / Flash Notification -->
            <?php if (!empty($msg)): ?>
            <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span><?= htmlspecialchars($msg) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <!-- Page Header (Matching Image 5) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Daganganku</h1>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Kelola katalog item, sesuaikan stok, dan atur tipe pengiriman tokomu.</p>
                </div>

                <!-- Top Right Action Buttons -->
                <div class="flex items-center gap-2.5">
                    <button type="button" 
                            onclick="alert('Data katalog produk berhasil diekspor!')" 
                            class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition flex items-center gap-2 shadow-2xs active:scale-95">
                        <i class="fa-solid fa-arrow-down text-slate-400 dark:text-slate-500"></i>
                        <span>Download</span>
                    </button>
                    
                    <button type="button" 
                            onclick="alert('Fitur upload massal CSV siap digunakan!')" 
                            class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition flex items-center gap-2 shadow-2xs active:scale-95">
                        <i class="fa-solid fa-arrow-up text-slate-400 dark:text-slate-500"></i>
                        <span>Upload</span>
                    </button>

                    <a href="/admin/products/create" 
                       class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-xs transition flex items-center gap-2 shadow-xs active:scale-95">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Dagangan</span>
                    </a>
                </div>
            </div>

            <!-- Main Product Table Container (Matching Image 5) -->
            <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs overflow-hidden">
                
                <!-- 1. Multi-Filter Bar: SEBARIS (Single Inline Row) -->
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-[#0c1e33] space-y-3.5">
                    <form method="GET" action="/admin/products" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center w-full">
                        <input type="hidden" name="tab" value="<?= htmlspecialchars($tabFilter ?? 'ALL') ?>">

                        <!-- Search Input (5 cols) -->
                        <div class="sm:col-span-5 relative flex items-center">
                            <i class="fa-solid fa-magnifying-glass text-slate-400 dark:text-slate-500 text-xs absolute left-3.5 pointer-events-none"></i>
                            <input type="text" 
                                   name="q" 
                                   value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                                   placeholder="Cari Daganganmu di sini" 
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-sky-500 shadow-2xs">
                        </div>

                        <!-- Dropdown Kategori / Game (3 cols) -->
                        <div class="sm:col-span-3">
                            <select name="category" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sky-500 shadow-2xs">
                                <option value="ALL">Semua Game & Kategori</option>
                                <optgroup label="Filter Game">
                                    <?php if (!empty($games)): ?>
                                        <?php foreach ($games as $g): ?>
                                            <option value="<?= htmlspecialchars($g['name']) ?>" <?= ($categoryFilter === $g['name']) ? 'selected' : '' ?>>
                                                🎮 <?= htmlspecialchars($g['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </optgroup>
                                <?php if (!empty($availableCategories)): ?>
                                <optgroup label="Filter Kategori Produk">
                                    <?php foreach ($availableCategories as $ac): ?>
                                        <option value="<?= htmlspecialchars($ac) ?>" <?= ($categoryFilter === $ac) ? 'selected' : '' ?>>
                                            🏷️ <?= htmlspecialchars($ac) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Min & Max Stock (2 cols) -->
                        <div class="sm:col-span-2 flex items-center gap-1.5">
                            <input type="number" 
                                   name="min_stock" 
                                   value="<?= $minStock !== null ? $minStock : '' ?>"
                                   placeholder="Min" 
                                   class="w-1/2 px-2.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 text-center shadow-2xs">
                            <span class="text-slate-400 dark:text-slate-500 text-xs">-</span>
                            <input type="number" 
                                   name="max_stock" 
                                   value="<?= $maxStock !== null ? $maxStock : '' ?>"
                                   placeholder="Max" 
                                   class="w-1/2 px-2.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 text-center shadow-2xs">
                        </div>

                        <!-- Dropdown Urutan / Sort (2 cols) -->
                        <div class="sm:col-span-2">
                            <select name="sort" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-sky-500 shadow-2xs">
                                <option value="latest">Urutan ▾</option>
                                <option value="cheapest">Harga Termurah</option>
                                <option value="expensive">Harga Termahal</option>
                                <option value="bestseller">Terlaris</option>
                            </select>
                        </div>
                    </form>

                    <!-- Filter Pills Cepat (Matching Image 5) -->
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar text-xs">
                        <a href="/admin/products" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($tabFilter === 'ALL' && empty($searchQuery) && empty($categoryFilter)) ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Semua
                        </a>
                        <a href="/admin/products?tab=OUT_OF_STOCK" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($tabFilter === 'OUT_OF_STOCK') ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Stok Habis
                        </a>
                        <a href="/admin/products?tab=UNSOLD" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($tabFilter === 'UNSOLD') ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Belum Terjual
                        </a>
                        <a href="/admin/products?tab=WHOLESALE" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($tabFilter === 'WHOLESALE') ? 'bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                            Grosir
                        </a>
                        <div class="relative inline-block">
                            <select onchange="if(this.value) window.location.href='/admin/products?badge=' + this.value" class="px-3 py-1.5 rounded-full font-bold bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs focus:outline-none cursor-pointer">
                                <option value="">Delivery ▾</option>
                                <option value="10 Menit">⚡ 10 Menit</option>
                                <option value="Instan">🚀 Instan</option>
                                <option value="Ready">✨ Standar / Ready</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Products Table (Matching Image 5) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3.5 px-4 sm:px-6 w-12 text-center">
                                    <input type="checkbox" onclick="toggleSelectAllProducts(this)" class="rounded text-sky-600 focus:ring-sky-500">
                                </th>
                                <th class="py-3.5 px-4 min-w-[280px]">Informasi Dagangan</th>
                                <th class="py-3.5 px-4 min-w-[130px]">Harga Satuan</th>
                                <th class="py-3.5 px-4 min-w-[120px]">Stok</th>
                                <th class="py-3.5 px-4 min-w-[140px]">Pengiriman</th>
                                <th class="py-3.5 px-4 min-w-[90px]">Terjual</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs font-medium text-slate-700 dark:text-slate-300">
                            <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400 dark:text-slate-500">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-2xl mx-auto mb-2">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Tidak ada dagangan ditemukan</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Silakan buat dagangan baru atau sesuaikan filter pencarian.</p>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php foreach ($products as $p): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/50 transition group">
                                
                                <!-- Checkbox -->
                                <td class="py-4 px-4 sm:px-6 text-center">
                                    <input type="checkbox" class="product-item-checkbox rounded text-sky-600 focus:ring-sky-500" value="<?= $p['id'] ?>">
                                </td>

                                <!-- Informasi Dagangan (Thumbnail, Title, Game, Red [Stok Habis] Tag) -->
                                <td class="py-4 px-4">
                                    <div class="flex items-start gap-3">
                                        <img src="<?= htmlspecialchars($p['image_url']) ?>" 
                                             alt="" 
                                             class="w-12 h-12 rounded-xl object-cover bg-slate-100 dark:bg-slate-800 flex-shrink-0 border border-slate-200 dark:border-slate-700 shadow-2xs" 
                                             onerror="this.src='https://images.unsplash.com/photo-1542751371-adc38448a05e?w=100'">
                                        <div class="space-y-1 min-w-0">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <?php if ((int)$p['stock'] <= 0): ?>
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 uppercase tracking-wider">
                                                        Stok Habis
                                                    </span>
                                                <?php endif; ?>
                                                <span class="font-extrabold text-slate-900 dark:text-white block group-hover:text-sky-500 transition leading-snug truncate max-w-[280px]" title="<?= htmlspecialchars($p['name']) ?>">
                                                    <?= htmlspecialchars($p['name']) ?>
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[11px] text-slate-400 dark:text-slate-500">
                                                <span class="font-semibold text-slate-600 dark:text-slate-400"><?= htmlspecialchars($p['game']) ?></span>
                                                <span>•</span>
                                                <span><?= htmlspecialchars($p['sub_category'] ?? 'Pet') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Harga Satuan -->
                                <td class="py-4 px-4">
                                    <span class="font-black text-slate-900 dark:text-white text-sm block">
                                        Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                    </span>
                                    <?php if (!empty($p['price_original']) && $p['price_original'] > $p['price']): ?>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold line-through block mt-0.5">
                                            Rp <?= number_format($p['price_original'], 0, ',', '.') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Stok (Inline Editable Input Box - Matching Image 5) -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <input type="number" 
                                               id="stock_input_<?= $p['id'] ?>"
                                               value="<?= (int)$p['stock'] ?>" 
                                               min="0"
                                               class="w-16 px-2 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-black text-slate-900 dark:text-white text-center focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-slate-900 shadow-2xs">
                                        <button type="button" 
                                                onclick="saveInlineStock(<?= $p['id'] ?>)" 
                                                class="w-7 h-7 rounded-lg bg-sky-50 dark:bg-sky-950/60 hover:bg-sky-600 hover:text-white text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800 flex items-center justify-center text-xs transition shadow-2xs active:scale-95" 
                                                title="Simpan Stok">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Pengiriman (Dropdown / Badge) -->
                                <td class="py-4 px-4">
                                    <?php 
                                     $badge = $p['badge'] ?? 'Ready';
                                     if ($badge === '10 Menit'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                            <i class="fa-solid fa-stopwatch text-[10px]"></i> 10 Menit
                                        </span>
                                    <?php elseif ($badge === 'Instan'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                                            <i class="fa-solid fa-bolt text-[10px]"></i> Instan
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            <i class="fa-solid fa-box text-[10px]"></i> Standar
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Terjual -->
                                <td class="py-4 px-4 font-extrabold text-slate-700 dark:text-slate-300">
                                    <?= (int)($p['total_sold'] ?? 0) ?>
                                </td>

                                <!-- Aksi (3 Dots Dropdown / Edit / Hapus) -->
                                <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                                    <div class="relative inline-block text-left">
                                        <button type="button" 
                                                onclick='openEditProductModal(<?= json_encode($p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' 
                                                class="p-2 text-slate-600 dark:text-slate-300 hover:text-sky-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition inline-flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-2xs" 
                                                title="Edit Dagangan">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>
                                        <form action="/admin/products/delete" method="POST" onsubmit="return confirmFormSubmit(event, { title: 'Hapus Dagangan Produk?', itemName: '<?= addslashes($p['name']) ?>', itemIcon: 'fa-solid fa-box text-rose-500', message: 'Dagangan ini akan dihapus permanen dari katalog dan tidak akan tampil lagi di toko.' });" class="inline-block">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/60 rounded-xl transition inline-flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-2xs ml-1" title="Hapus Dagangan">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </main>

    <!-- Modal Edit Produk (Popup Modal) -->
    <div id="editProductModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150">
        <div class="bg-white dark:bg-[#0c1e33] rounded-3xl border border-slate-200 dark:border-slate-800 w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold text-sm border border-sky-100 dark:border-sky-800/60">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Edit Data Dagangan</h3>
                </div>
                <button type="button" onclick="closeEditProductModal()" class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="/admin/products/update" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-4 text-xs">
                <input type="hidden" name="id" id="edit_product_id">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Game Roblox <span class="text-rose-500">*</span></label>
                        <select name="game" id="edit_product_game" onchange="updateEditQuickCategorySuggestions()" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500">
                            <?php if (!empty($games)): ?>
                                <?php foreach ($games as $g): ?>
                                    <option value="<?= htmlspecialchars($g['name']) ?>"><?= htmlspecialchars($g['name']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Build A Zoo">Build A Zoo</option>
                                <option value="Pet Simulator 99">Pet Simulator 99</option>
                                <option value="Blox Fruits">Blox Fruits</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Tipe Kategori (Teks) <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="sub_category" 
                               id="edit_product_sub_category" 
                               list="editCategoryListSuggestions" 
                               required 
                               placeholder="Contoh: Pet, Gems, Item, Akun, Fruit, Unit..."
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-bold focus:outline-none focus:border-sky-500">
                        
                        <div id="editQuickCategorySuggestionsContainer" class="flex items-center gap-1 flex-wrap mt-1.5">
                            <?php if (!empty($availableCategories)): ?>
                            <?php foreach (array_slice($availableCategories, 0, 6) as $ac): ?>
                            <button type="button" 
                                    onclick="document.getElementById('edit_product_sub_category').value='<?= htmlspecialchars(addslashes($ac)) ?>'" 
                                    class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-950 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700">
                                + <?= htmlspecialchars($ac) ?>
                            </button>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <datalist id="editCategoryListSuggestions">
                            <?php if (!empty($availableCategories)): ?>
                            <?php foreach ($availableCategories as $ac): ?>
                                <option value="<?= htmlspecialchars($ac) ?>"></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </datalist>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Dagangan <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="edit_product_name" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-bold focus:outline-none focus:border-sky-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price" id="edit_product_price" required class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-emerald-600 dark:text-emerald-400 font-black focus:outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga Coret (Rp)</label>
                        <input type="number" name="price_original" id="edit_product_price_original" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-500 dark:text-slate-400 font-bold focus:outline-none focus:border-sky-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Jumlah Stok</label>
                        <input type="number" name="stock" id="edit_product_stock" min="0" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-bold focus:outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Badge Tier / Label</label>
                        <input type="text" name="badge" id="edit_product_badge" placeholder="Prismatic, Divine, Sultan" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-purple-600 dark:text-purple-400 font-bold focus:outline-none focus:border-sky-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Ganti Banner Dagangan (Opsional)</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 dark:file:bg-sky-950 file:text-sky-600 dark:file:text-sky-400 hover:file:bg-sky-100 cursor-pointer bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-1.5">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Atau URL Gambar</label>
                    <input type="text" name="image_url" id="edit_product_image_url" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Deskripsi Dagangan</label>
                    <textarea name="description" id="edit_product_description" rows="3" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 resize-none"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeEditProductModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white font-extrabold rounded-xl shadow-xs transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleSelectAllProducts(master) {
        document.querySelectorAll('.product-item-checkbox').forEach(cb => {
            cb.checked = master.checked;
        });
    }

    async function saveInlineStock(productId) {
        const input = document.getElementById('stock_input_' + productId);
        if (!input) return;
        const newStock = input.value;

        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('stock', newStock);

            const res = await fetch('/admin/products/quick-stock', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                input.classList.add('border-emerald-500', 'bg-emerald-50');
                setTimeout(() => {
                    input.classList.remove('border-emerald-500', 'bg-emerald-50');
                }, 1500);
            }
        } catch (e) {
            alert('Gagal mengupdate stok');
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    const gamesData = <?= json_encode($games) ?>;
    function updateEditQuickCategorySuggestions() {
        const select = document.getElementById('edit_product_game');
        if (!select) return;
        const selectedGameName = select.value;
        const game = gamesData.find(g => g.name === selectedGameName);
        const container = document.getElementById('editQuickCategorySuggestionsContainer');
        const datalist = document.getElementById('editCategoryListSuggestions');
        if (!container || !datalist) return;
        
        let cats = [];
        if (game && game.categories) {
            cats = game.categories.split(',').map(s => s.trim()).filter(Boolean);
        }
        if (cats.length === 0) {
            cats = ['Pet', 'Gems', 'Item', 'Akun', 'Fruit', 'Unit'];
        }

        let btnHtml = '';
        let dlHtml = '';
        cats.forEach(c => {
            btnHtml += `<button type="button" onclick="document.getElementById('edit_product_sub_category').value='${escapeHtml(c)}'" class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-950 text-[10px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700">+ ${escapeHtml(c)}</button>`;
            dlHtml += `<option value="${escapeHtml(c)}"></option>`;
        });
        container.innerHTML = btnHtml;
        datalist.innerHTML = dlHtml;
    }

    function openEditProductModal(product) {
        document.getElementById('edit_product_id').value = product.id || '';
        document.getElementById('edit_product_name').value = product.name || '';
        document.getElementById('edit_product_game').value = product.game || 'Build A Zoo';
        updateEditQuickCategorySuggestions();
        document.getElementById('edit_product_sub_category').value = product.sub_category || (product.category_id == 2 ? 'Akun' : 'Pet');
        document.getElementById('edit_product_price').value = product.price || 0;
        document.getElementById('edit_product_price_original').value = product.price_original || '';
        document.getElementById('edit_product_stock').value = product.stock ?? 0;
        document.getElementById('edit_product_badge').value = product.badge || '';
        document.getElementById('edit_product_image_url').value = product.image_url || '';
        document.getElementById('edit_product_description').value = product.description || '';

        document.getElementById('editProductModal').classList.remove('hidden');
    }
    function closeEditProductModal() {
        document.getElementById('editProductModal').classList.add('hidden');
    }
    </script>
</body>
</html>
