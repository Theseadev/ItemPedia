<?php
ob_start();
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center max-w-xl mx-auto mb-10">
        <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center mx-auto text-xl mb-3 shadow-sm">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Lacak Status Pesanan</h1>
        <p class="text-xs sm:text-sm text-slate-500">
            Ketik nomor invoice (contoh: <span class="text-sky-600 font-mono font-bold">ITP-2026...</span>) atau username Roblox kamu saat pemesanan.
        </p>
    </div>

    <!-- Search Form Box -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 mb-10 shadow-card">
        <form action="/lacak" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <input type="text" 
                       name="q" 
                       value="<?= htmlspecialchars($query) ?>" 
                       required 
                       placeholder="Masukkan Nomor Invoice atau Username Roblox..."
                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-sky-400 focus:bg-white transition">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            </div>
            <button type="submit" class="py-3 px-6 bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-sm rounded-2xl shadow-md shadow-sky-200 transition flex items-center justify-center gap-2">
                <span>Cari Pesanan</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>

    <!-- Results List -->
    <?php if (!empty($query)): ?>
        <?php if (empty($orders)): ?>
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-card">
            <i class="fa-solid fa-circle-question text-3xl text-slate-400 mb-2"></i>
            <h3 class="text-base font-bold text-slate-800 mb-1">Pesanan Tidak Ditemukan</h3>
            <p class="text-xs text-slate-400">Pastikan nomor invoice atau username Roblox yang dimasukkan sudah sesuai.</p>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                Ditemukan <?= count($orders) ?> Pesanan
            </h2>

            <?php foreach ($orders as $o): ?>
            <div class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-sky-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-card">
                <div class="flex items-center gap-4">
                    <img src="<?= htmlspecialchars($o['roblox_avatar_url']) ?>" 
                         alt="Avatar" 
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($o['roblox_username']) ?>&background=38bdf8&color=fff'"
                         class="w-12 h-12 rounded-full border-2 border-slate-200 bg-slate-50 object-cover flex-shrink-0">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-xs font-bold text-sky-600"><?= htmlspecialchars($o['invoice_number']) ?></span>
                            <?php if ($o['status'] === 'PENDING'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Menunggu Bayar</span>
                            <?php elseif ($o['status'] === 'PAID'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800">Lunas</span>
                            <?php elseif ($o['status'] === 'PROCESSING'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">Diproses</span>
                            <?php elseif ($o['status'] === 'SUCCESS'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Selesai</span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Batal</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($o['product_name']) ?></h3>
                        <div class="text-xs text-slate-500 mt-0.5">
                            Akun: <strong class="text-slate-800"><?= htmlspecialchars($o['roblox_username']) ?></strong> • 
                            Rp <?= number_format($o['price'], 0, ',', '.') ?>
                        </div>
                    </div>
                </div>

                <a href="/order/<?= htmlspecialchars($o['invoice_number']) ?>" 
                   class="py-2.5 px-4 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold text-xs rounded-xl transition text-center sm:text-left flex-shrink-0 flex items-center justify-center gap-1.5">
                    <span>Buka Invoice</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$bodyContent = ob_get_clean();
require __DIR__ . '/layout.php';
?>
