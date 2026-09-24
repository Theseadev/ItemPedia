<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori & Game Roblox - Seller Center ItemPedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/svg+xml" href="/images/logo-icon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f0f2f5] text-slate-800 min-h-screen flex flex-col antialiased selection:bg-blue-100 selection:text-blue-700">

    <?php 
    $activeMenu = 'categories';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 flex-grow">

            <!-- Toast / Flash Notification -->
            <?php if (!empty($msg)): ?>
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span><?= htmlspecialchars($msg) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <!-- Page Header: Title on Left, Action on Right -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Kategori & Game Roblox (CMS)</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola daftar game dan kategori produk bebas untuk filter katalog website</p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap">
                    <button type="button" 
                            onclick="openAddCategoryModal()" 
                            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition flex items-center gap-2 shadow-xs active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Game Baru</span>
                    </button>
                </div>
            </div>

            <!-- SECTION 1: Game Roblox Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-gamepad"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800">Daftar Game Roblox & Kategori Terdaftar (<?= count($games) ?>)</span>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400">Game & kategori aktif muncul otomatis di homepage</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/80 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-4 sm:px-6">Game & Logo</th>
                                <th class="py-3.5 px-4">Kategori Game (Teks)</th>
                                <th class="py-3.5 px-4">Slug URL</th>
                                <th class="py-3.5 px-4">Dagangan</th>
                                <th class="py-3.5 px-4">Urutan</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            <?php if (empty($games)): ?>
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400">
                                    Belum ada data game terdaftar. Klik <strong>Tambah Game Baru</strong> di atas untuk menambah.
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php foreach ($games as $g): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Logo & Nama Game -->
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 p-1 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            <?php if (!empty($g['logo_url'])): ?>
                                                <img src="<?= htmlspecialchars($g['logo_url']) ?>" alt="" class="w-full h-full object-cover rounded-lg" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($g['name']) ?>&background=2563eb&color=fff'">
                                            <?php else: ?>
                                                <i class="<?= htmlspecialchars($g['icon'] ?: 'fa-solid fa-gamepad') ?> text-blue-600 text-base"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="font-extrabold text-slate-900 text-sm block"><?= htmlspecialchars($g['name']) ?></span>
                                            <span class="text-[11px] text-slate-400 line-clamp-1 max-w-[200px]">
                                                <?= htmlspecialchars($g['description'] ?: 'Belum ada deskripsi') ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori Game (Teks) -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5 flex-wrap max-w-[260px]">
                                        <?php 
                                        $gCats = array_filter(array_map('trim', explode(',', $g['categories'] ?? 'Pet, Gems, Item, Akun')));
                                        foreach ($gCats as $gc): 
                                        ?>
                                            <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200/80">
                                                <?= htmlspecialchars($gc) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>

                                <!-- Slug -->
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px] bg-slate-100 border border-slate-200 text-slate-700">
                                        <?= htmlspecialchars($g['slug']) ?>
                                    </span>
                                </td>

                                <!-- Produk Terkait -->
                                <td class="py-3.5 px-4">
                                    <a href="/admin/products?q=<?= urlencode($g['name']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-purple-50 border border-purple-200 text-purple-700 font-extrabold hover:bg-purple-100 transition">
                                        <i class="fa-solid fa-box-open text-[10px]"></i>
                                        <span><?= (int)($g['product_count'] ?? 0) ?> Dagangan</span>
                                    </a>
                                </td>

                                <!-- Urutan -->
                                <td class="py-3.5 px-4 font-black text-slate-700">
                                    #<?= (int)($g['sort_order'] ?? 0) ?>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4">
                                    <?php if ((int)($g['is_active'] ?? 1) === 1): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" 
                                                onclick="openEditCategoryModal(<?= htmlspecialchars(json_encode($g)) ?>)" 
                                                class="p-2 rounded-xl bg-white hover:bg-blue-50 text-blue-600 border border-slate-200 transition shadow-2xs" 
                                                title="Edit Game & Kategori">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>
                                        <form action="/admin/categories/delete" method="POST" onsubmit="return confirmFormSubmit(event, { title: 'Hapus Game Kategori?', itemName: '<?= addslashes($g['name']) ?>', itemIcon: 'fa-solid fa-gamepad text-rose-500', message: 'Game kategori ini akan dihapus dari pilihan katalog game di website.' });" class="inline">
                                            <input type="hidden" name="id" value="<?= $g['id'] ?>">
                                            <button type="submit" 
                                                    class="p-2 rounded-xl bg-white hover:bg-rose-50 text-rose-500 border border-slate-200 transition shadow-2xs" 
                                                    title="Hapus Game">
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

    <!-- Modal Tambah Game Baru (Matching Image with Kategori Form) -->
    <div id="addCategoryModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150">
        <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base">Tambah Game Baru</h3>
                </div>
                <button type="button" onclick="closeAddCategoryModal()" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/admin/categories/add" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                <!-- NAMA GAME ROBLOX -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Game Roblox <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Pet Simulator 99" oninput="generateAddSlug(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <!-- SLUG URL -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Slug URL <span class="text-rose-500">*</span></label>
                    <input type="text" name="slug" id="addCatSlug" required placeholder="pet-simulator-99" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-blue-600 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <!-- FORM PENGISIAN KATEGORI (TEKS BEBAS) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori / Pilihan Item (Teks) <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="categories" 
                           required 
                           value="Pet, Gems, Item, Akun" 
                           placeholder="Contoh: Pet, Gems, Item, Akun, Fruit, Unit, Weapon..." 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Ketik kategori produk apa saja untuk game ini (pisahkan dengan koma atau bebas ketik apa saja)</p>
                </div>

                <!-- UPLOAD LOGO GAME -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Logo Game</label>
                    <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer bg-slate-50 border border-slate-200 rounded-xl p-1.5">
                </div>

                <!-- DESKRIPSI SINGKAT -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi game..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white resize-none"></textarea>
                </div>

                <!-- URUTAN TAMPIL & STATUS -->
                <div class="grid grid-cols-2 gap-3 items-center">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="<?= count($games) + 1 ?>" min="1" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                    </div>
                    <div class="pt-5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-0">
                            <span class="text-xs font-bold text-slate-700">Tampilkan di Web</span>
                        </label>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="pt-3 flex justify-end gap-2.5 border-t border-slate-100">
                    <button type="button" onclick="closeAddCategoryModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold transition shadow-xs">
                        Simpan Game
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Game Roblox (with Kategori Form) -->
    <div id="editCategoryModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150">
        <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base">Edit Game Roblox</h3>
                </div>
                <button type="button" onclick="closeEditCategoryModal()" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/admin/categories/update" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                <input type="hidden" name="id" id="editCatId">

                <!-- NAMA GAME -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Game Roblox</label>
                    <input type="text" name="name" id="editCatName" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <!-- SLUG URL -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Slug URL</label>
                    <input type="text" name="slug" id="editCatSlug" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-blue-600 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <!-- FORM PENGISIAN KATEGORI (TEKS BEBAS) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori / Pilihan Item (Teks)</label>
                    <input type="text" 
                           name="categories" 
                           id="editCatCategories" 
                           required 
                           placeholder="Contoh: Pet, Gems, Item, Akun, Fruit, Unit, Weapon..." 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Ketik kategori produk apa saja untuk game ini (pisahkan dengan koma atau bebas ketik apa saja)</p>
                </div>

                <!-- UPLOAD GANTI LOGO -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Ganti Logo</label>
                    <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer bg-slate-50 border border-slate-200 rounded-xl p-1.5">
                </div>

                <!-- DESKRIPSI SINGKAT -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                    <textarea name="description" id="editCatDesc" rows="2" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white resize-none"></textarea>
                </div>

                <!-- URUTAN TAMPIL & STATUS -->
                <div class="grid grid-cols-2 gap-3 items-center">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampil</label>
                        <input type="number" name="sort_order" id="editCatSort" min="1" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-600 focus:bg-white">
                    </div>
                    <div class="pt-5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="is_active" id="editCatActive" value="1" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-0">
                            <span class="text-xs font-bold text-slate-700">Tampilkan di Web</span>
                        </label>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="pt-3 flex justify-end gap-2.5 border-t border-slate-100">
                    <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold transition shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function generateAddSlug(text) {
        const slug = text.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
        document.getElementById('addCatSlug').value = slug;
    }
    function openAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.remove('hidden');
    }
    function closeAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.add('hidden');
    }
    function openEditCategoryModal(data) {
        document.getElementById('editCatId').value = data.id;
        document.getElementById('editCatName').value = data.name || '';
        document.getElementById('editCatSlug').value = data.slug || '';
        document.getElementById('editCatCategories').value = data.categories || 'Pet, Gems, Item, Akun';
        document.getElementById('editCatDesc').value = data.description || '';
        document.getElementById('editCatSort').value = data.sort_order || 1;
        document.getElementById('editCatActive').checked = Number(data.is_active) === 1;

        document.getElementById('editCategoryModal').classList.remove('hidden');
    }
    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }
    </script>
</body>
</html>
