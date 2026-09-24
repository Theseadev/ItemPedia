<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Dagangan - Seller Center ItemPedia</title>
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
    $activeMenu = 'products';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 flex-grow">

            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500">
                <a href="/admin/products" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Daganganku</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span class="text-slate-700 dark:text-slate-300">Buat Dagangan</span>
            </div>

            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Buat Dagangan Baru</h1>
                    <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Isi rincian informasi daganganmu dengan lengkap dan menarik.</p>
                </div>
            </div>

            <!-- Create Product Form (Matching Image 4) -->
            <form action="/admin/products/add" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <!-- SECTION 1: Tipe Dagangan -->
                <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs p-5 sm:p-6 space-y-4">
                    <h2 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">1</span>
                        <span>Tipe Dagangan</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Game Selection -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Game Roblox <span class="text-rose-500">*</span></label>
                            <select name="game" id="product_game_select" onchange="updateQuickCategorySuggestions()" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 shadow-2xs">
                                <?php foreach ($games as $g): ?>
                                    <option value="<?= htmlspecialchars($g['name']) ?>"><?= htmlspecialchars($g['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Kategori Selection (Teks Bebas) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kategori / Jenis Produk (Teks) <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="sub_category" 
                                   id="sub_category_create_input"
                                   list="categoryListSuggestions" 
                                   required 
                                   placeholder="Ketik kategori apa saja (contoh: Pet, Gems, Item, Akun, Fruit, Unit...)" 
                                   class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 shadow-2xs">
                            
                            <!-- Pilihan Cepat Kategori Dinamis Per Game -->
                            <div id="quickCategorySuggestionsContainer" class="flex items-center gap-1.5 flex-wrap mt-2">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500">Pilihan Cepat:</span>
                                <?php if (!empty($availableCategories)): ?>
                                <?php foreach ($availableCategories as $ac): ?>
                                <button type="button" 
                                        onclick="document.getElementById('sub_category_create_input').value='<?= htmlspecialchars(addslashes($ac)) ?>'" 
                                        class="px-2.5 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/40 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-200 dark:hover:border-blue-800 text-[11px] font-bold text-slate-600 dark:text-slate-300 transition border border-slate-200/60 dark:border-slate-700">
                                    + <?= htmlspecialchars($ac) ?>
                                </button>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <datalist id="categoryListSuggestions">
                                <?php if (!empty($availableCategories)): ?>
                                <?php foreach ($availableCategories as $ac): ?>
                                    <option value="<?= htmlspecialchars($ac) ?>"></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </datalist>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Informasi Produk -->
                <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs p-5 sm:p-6 space-y-5">
                    <h2 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">2</span>
                        <span>Informasi Produk</span>
                    </h2>

                    <!-- Blue Info Box -->
                    <div class="p-3.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800/60 text-blue-800 dark:text-blue-300 text-xs flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-blue-500 text-sm flex-shrink-0 mt-0.5"></i>
                        <span class="leading-relaxed">Pastikan nama dagangan sesuai dengan ketentuan ItemPedia agar tidak terkena moderasi dan mudah ditemukan oleh pembeli.</span>
                    </div>

                    <!-- Nama Dagangan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Dagangan <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="name" 
                               required 
                               placeholder="Contoh: Huge Peacock 100K Power Fast Delivery" 
                               class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-white font-semibold placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 shadow-2xs">
                    </div>

                    <!-- 5 Foto Upload Slots (Matching Image 4) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Foto Dagangan <span class="text-rose-500">*</span></label>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-3">Format gambar .jpg, .jpeg, .png, .webp dan maksimal 5MB. Gambar pertama akan menjadi foto utama.</p>

                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <!-- Slot 1: Gambar Utama -->
                            <label class="relative flex flex-col items-center justify-center h-28 rounded-2xl border-2 border-dashed border-blue-400 dark:border-blue-500 bg-blue-50/30 dark:bg-blue-950/20 hover:bg-blue-50 dark:hover:bg-blue-950/40 cursor-pointer transition text-center p-2 group">
                                <input type="file" name="image_file" accept="image/*" class="hidden" onchange="previewProductImage(this, 'preview1')">
                                <img id="preview1" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl p-0.5">
                                <div id="placeholder1" class="flex flex-col items-center justify-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs mb-1">
                                        <i class="fa-solid fa-camera"></i>
                                    </div>
                                    <span class="text-[11px] font-extrabold text-blue-600 dark:text-blue-400">+ Foto Utama</span>
                                    <span class="text-[9px] text-blue-400 font-bold">Slot 1</span>
                                </div>
                            </label>

                            <!-- Slot 2 -->
                            <label class="relative flex flex-col items-center justify-center h-28 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 hover:bg-slate-100/50 dark:hover:bg-slate-800/50 cursor-pointer transition text-center p-2 group">
                                <input type="file" accept="image/*" class="hidden" onchange="previewProductImage(this, 'preview2')">
                                <img id="preview2" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl p-0.5">
                                <div id="placeholder2" class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-plus text-slate-400 dark:text-slate-500 text-sm mb-1 group-hover:text-slate-600 dark:group-hover:text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Foto 2</span>
                                </div>
                            </label>

                            <!-- Slot 3 -->
                            <label class="relative flex flex-col items-center justify-center h-28 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 hover:bg-slate-100/50 dark:hover:bg-slate-800/50 cursor-pointer transition text-center p-2 group">
                                <input type="file" accept="image/*" class="hidden" onchange="previewProductImage(this, 'preview3')">
                                <img id="preview3" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl p-0.5">
                                <div id="placeholder3" class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-plus text-slate-400 dark:text-slate-500 text-sm mb-1 group-hover:text-slate-600 dark:group-hover:text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Foto 3</span>
                                </div>
                            </label>

                            <!-- Slot 4 -->
                            <label class="relative flex flex-col items-center justify-center h-28 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 hover:bg-slate-100/50 dark:hover:bg-slate-800/50 cursor-pointer transition text-center p-2 group">
                                <input type="file" accept="image/*" class="hidden" onchange="previewProductImage(this, 'preview4')">
                                <img id="preview4" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl p-0.5">
                                <div id="placeholder4" class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-plus text-slate-400 dark:text-slate-500 text-sm mb-1 group-hover:text-slate-600 dark:group-hover:text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Foto 4</span>
                                </div>
                            </label>

                            <!-- Slot 5 -->
                            <label class="relative flex flex-col items-center justify-center h-28 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 hover:bg-slate-100/50 dark:hover:bg-slate-800/50 cursor-pointer transition text-center p-2 group">
                                <input type="file" accept="image/*" class="hidden" onchange="previewProductImage(this, 'preview5')">
                                <img id="preview5" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl p-0.5">
                                <div id="placeholder5" class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-plus text-slate-400 dark:text-slate-500 text-sm mb-1 group-hover:text-slate-600 dark:group-hover:text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Foto 5</span>
                                </div>
                            </label>
                        </div>

                        <!-- Fallback / Image URL Input -->
                        <div class="mt-3">
                            <input type="url" 
                                   name="image_url" 
                                   placeholder="Atau tempel URL Gambar langsung (https://...)" 
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Deskripsi Dagangan with Live Counter -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Deskripsi Dagangan <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500"><span id="charCount">0</span> / 3000</span>
                        </div>
                        <textarea name="description" 
                                  id="descTextarea" 
                                  rows="4" 
                                  maxlength="3000"
                                  oninput="updateCharCount(this)"
                                  placeholder="Tuliskan deskripsi lengkap, cara trade / klaim produk, dan instruksi penting untuk pembeli..." 
                                  class="w-full p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 resize-y shadow-2xs"></textarea>
                    </div>

                    <!-- Warning Box (Matching Image 4) -->
                    <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 text-amber-900 dark:text-amber-300 text-xs flex items-start gap-2.5">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm flex-shrink-0 mt-0.5"></i>
                        <span class="leading-relaxed font-medium">Dilarang mencantumkan nomor WhatsApp pribadi, Discord, transaksi di luar ItemPedia, atau kata-kata yang melanggar ketentuan layanan.</span>
                    </div>
                </div>

                <!-- SECTION 3: Informasi Stok & Harga -->
                <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs p-5 sm:p-6 space-y-4">
                    <h2 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">3</span>
                        <span>Informasi Stok &amp; Harga</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Harga Jual -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Jual Satuan <span class="text-rose-500">*</span></label>
                            <div class="flex items-center rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xs focus-within:border-blue-500 overflow-hidden">
                                <div class="px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold">
                                    Rp
                                </div>
                                <input type="number" 
                                       name="price" 
                                       required 
                                       min="500" 
                                       placeholder="15.000" 
                                       class="w-full px-3 py-2 text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white bg-transparent focus:outline-none">
                            </div>
                        </div>

                        <!-- Harga Coret (Original) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Harga Coret / Asli (Opsional)</label>
                            <div class="flex items-center rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xs focus-within:border-blue-500 overflow-hidden">
                                <div class="px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold">
                                    Rp
                                </div>
                                <input type="number" 
                                       name="price_original" 
                                       placeholder="25.000" 
                                       class="w-full px-3 py-2 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 bg-transparent focus:outline-none">
                            </div>
                        </div>

                        <!-- Stok Produk -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Stok Produk <span class="text-rose-500">*</span></label>
                            <input type="number" 
                                   name="stock" 
                                   required 
                                   min="0" 
                                   value="50" 
                                   class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-bold text-slate-900 dark:text-white focus:outline-none focus:border-blue-500 shadow-2xs">
                        </div>

                        <!-- Badge / Garansi Pengiriman -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Pengiriman / Badge</label>
                            <select name="badge" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 shadow-2xs">
                                <option value="10 Menit">⚡ 10 Menit (Kirim Kilat)</option>
                                <option value="Instan">🚀 Pengiriman Instan</option>
                                <option value="Ready" selected>✨ Ready Stok</option>
                                <option value="Terlaris">🔥 Terlaris</option>
                            </select>
                        </div>
                    </div>

                    <!-- Toggle Harga Grosir (Matching Image 4) -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-extrabold text-slate-900 dark:text-white block">Aktifkan Harga Grosir</span>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 block">Berikan potongan diskon khusus untuk pembelian dalam jumlah banyak.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="wholesale_active" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Bottom Action Bar (Matching Image 4) -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="/admin/products" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs sm:text-sm transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm transition shadow-xs flex items-center gap-2 active:scale-95">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Simpan Dagangan</span>
                    </button>
                </div>

            </form>

        </div>
    </main>

    <script>
    function updateCharCount(el) {
        document.getElementById('charCount').innerText = el.value.length;
    }

    function previewProductImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewId);
                const placeholder = input.parentElement.querySelector('div');
                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    const gamesData = <?= json_encode($games) ?>;
    function updateQuickCategorySuggestions() {
        const select = document.getElementById('product_game_select');
        if (!select) return;
        const selectedGameName = select.value;
        const game = gamesData.find(g => g.name === selectedGameName);
        const container = document.getElementById('quickCategorySuggestionsContainer');
        const datalist = document.getElementById('categoryListSuggestions');
        if (!container || !datalist) return;
        
        let cats = [];
        if (game && game.categories) {
            cats = game.categories.split(',').map(s => s.trim()).filter(Boolean);
        }
        if (cats.length === 0) {
            cats = ['Pet', 'Gems', 'Item', 'Akun', 'Fruit', 'Unit'];
        }

        let btnHtml = '<span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500">Pilihan Cepat (' + escapeHtml(selectedGameName) + '):</span>';
        let dlHtml = '';
        cats.forEach(c => {
            btnHtml += `<button type="button" onclick="document.getElementById('sub_category_create_input').value='${escapeHtml(c)}'" class="px-2.5 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/40 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-200 dark:hover:border-blue-800 text-[11px] font-bold text-slate-600 dark:text-slate-300 transition border border-slate-200/60 dark:border-slate-700">+ ${escapeHtml(c)}</button>`;
            dlHtml += `<option value="${escapeHtml(c)}"></option>`;
        });
        container.innerHTML = btnHtml;
        datalist.innerHTML = dlHtml;
    }
    document.addEventListener('DOMContentLoaded', updateQuickCategorySuggestions);
    </script>
</body>
</html>
