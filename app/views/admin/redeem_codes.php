<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Promo & Voucher - Seller Center ItemPedia</title>
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
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f0f2f5] dark:bg-[#081220] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col antialiased selection:bg-blue-100 dark:selection:bg-blue-900 selection:text-blue-700 dark:selection:text-blue-200">

    <?php 
    $activeMenu = 'redeem_codes';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5 flex-grow">

            <!-- Toast / Flash Notification -->
            <?php if (!empty($msg)): ?>
            <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-300 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span><?= htmlspecialchars($msg) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-rose-100 dark:bg-rose-900 text-rose-600 dark:text-rose-300 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <!-- Page Header: Title on Left, Action on Right (Sebaris) -->
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Kode Promo & Voucher</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Atur diskon persen untuk menarik lebih banyak pembeli</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <button type="button" 
                            onclick="openAddRedeemModal()" 
                            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition flex items-center gap-2 shadow-xs active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Buat Kode Promo</span>
                    </button>
                </div>
            </div>

            <!-- Full Width Voucher Table Card -->
            <div class="bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Daftar Kode Promo Aktif (<?= count($codes) ?>)</span>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Kupon diskon otomatis dipotong saat checkout</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 uppercase font-extrabold tracking-wider border-b border-slate-200 dark:border-slate-800 text-[10px]">
                            <tr>
                                <th class="py-3.5 px-4 sm:px-6">Kode Promo</th>
                                <th class="py-3.5 px-4">Diskon</th>
                                <th class="py-3.5 px-4">Cakupan Dagangan</th>
                                <th class="py-3.5 px-4">Penggunaan</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium text-slate-700 dark:text-slate-300">
                            <?php if (empty($codes)): ?>
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    Belum ada kode promo. Klik <strong>+ Buat Kode Promo</strong> di atas untuk menambah.
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php foreach ($codes as $c): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 font-mono font-black text-xs">
                                        <i class="fa-solid fa-tag text-[10px]"></i>
                                        <span><?= htmlspecialchars($c['code']) ?></span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-black text-emerald-600 dark:text-emerald-400">
                                    <?= (int)$c['discount_percent'] ?>% OFF
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if (!empty($c['product_name'])): ?>
                                        <span class="font-bold text-blue-600 dark:text-blue-400 block truncate max-w-[200px]" title="<?= htmlspecialchars($c['product_name']) ?>">
                                            <?= htmlspecialchars($c['product_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-800 dark:text-slate-200 font-semibold">Semua Dagangan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-600 dark:text-slate-400">
                                    <?= (int)$c['used_count'] ?> / <?= (int)$c['max_uses'] > 0 ? (int)$c['max_uses'] . 'x' : '∞' ?>
                                </td>
                                <td class="py-3.5 px-4">
                                    <form action="/admin/redeem-codes/toggle" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <?php if ($c['is_active']): ?>
                                            <button type="submit" title="Klik untuk menonaktifkan" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition">
                                                Aktif
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" title="Klik untuk mengaktifkan" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                                Nonaktif
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">
                                    <button type="button" 
                                            onclick='openEditRedeemModal(<?= json_encode($c, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' 
                                            class="p-2 rounded-xl text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition inline-block mr-1 shadow-2xs" 
                                            title="Edit Kode">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="/admin/redeem-codes/delete" method="POST" onsubmit="return confirmFormSubmit(event, { title: 'Hapus Kode Promo?', itemName: '<?= addslashes($c['code']) ?>', itemIcon: 'fa-solid fa-ticket text-rose-500', message: 'Kupon diskon ini akan dihapus permanen dan tidak bisa digunakan pembeli lagi saat checkout.' });" class="inline">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <button type="submit" class="p-2 rounded-xl text-rose-500 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition shadow-2xs" title="Hapus Kode">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Modal Tambah Kode Promo -->
    <div id="addRedeemModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150">
        <div class="bg-white dark:bg-[#0c1e33] rounded-3xl border border-slate-200 dark:border-slate-800 w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-500 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Buat Kode Promo</h3>
                </div>
                <button type="button" onclick="closeAddRedeemModal()" class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/admin/redeem-codes/add" method="POST" class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kode Promo <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" required placeholder="Contoh: HEMAT20" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '')" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-black tracking-wider uppercase placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Besaran Diskon (%) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="discount_percent" required min="1" max="100" value="10" class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-emerald-600 dark:text-emerald-400 font-black focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400 dark:text-slate-500">%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Berlaku Untuk Dagangan</label>
                    <select name="product_id" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-bold focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                        <option value="0">🌟 Semua Dagangan</option>
                        <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['name']) ?> (Rp <?= number_format($p['price'], 0, ',', '.') ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Batas Kuota Penggunaan (0 = Unlimited)</label>
                    <input type="number" name="max_uses" min="0" value="0" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-bold focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                </div>

                <div class="pt-3 flex justify-end gap-2.5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeAddRedeemModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition shadow-xs">
                        Simpan Kode Promo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kode Promo -->
    <div id="editRedeemModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150">
        <div class="bg-white dark:bg-[#0c1e33] rounded-3xl border border-slate-200 dark:border-slate-800 w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-500 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Edit Kode Promo</h3>
                </div>
                <button type="button" onclick="closeEditRedeemModal()" class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/admin/redeem-codes/update" method="POST" class="p-6 space-y-4 text-xs">
                <input type="hidden" name="id" id="edit_redeem_id">

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kode Promo <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="edit_redeem_code" required oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '')" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-black tracking-wider uppercase placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Besaran Diskon (%) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="discount_percent" id="edit_redeem_discount_percent" required min="1" max="100" class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-emerald-600 dark:text-emerald-400 font-black focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400 dark:text-slate-500">%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Berlaku Untuk Dagangan</label>
                    <select name="product_id" id="edit_redeem_product_id" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-bold focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                        <option value="0">🌟 Semua Dagangan</option>
                        <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['name']) ?> (Rp <?= number_format($p['price'], 0, ',', '.') ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Batas Kuota Penggunaan</label>
                    <input type="number" name="max_uses" id="edit_redeem_max_uses" min="0" class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white font-bold focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900">
                </div>

                <div class="pt-3 flex justify-end gap-2.5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeEditRedeemModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openAddRedeemModal() {
        document.getElementById('addRedeemModal').classList.remove('hidden');
    }
    function closeAddRedeemModal() {
        document.getElementById('addRedeemModal').classList.add('hidden');
    }
    function openEditRedeemModal(code) {
        document.getElementById('edit_redeem_id').value = code.id || '';
        document.getElementById('edit_redeem_code').value = code.code || '';
        document.getElementById('edit_redeem_discount_percent').value = code.discount_percent || 10;
        document.getElementById('edit_redeem_product_id').value = code.product_id || 0;
        document.getElementById('edit_redeem_max_uses').value = code.max_uses || 0;

        document.getElementById('editRedeemModal').classList.remove('hidden');
    }
    function closeEditRedeemModal() {
        document.getElementById('editRedeemModal').classList.add('hidden');
    }
    </script>
</body>
</html>
