<?php
ob_start();
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header Profil Pembeli -->
    <div class="bg-white dark:bg-[#0c1e33] rounded-3xl border-2 border-sky-100 dark:border-slate-800 p-6 sm:p-8 shadow-card mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="<?= htmlspecialchars($buyer['avatar_url']) ?>" 
                         alt="<?= htmlspecialchars($buyer['name']) ?>" 
                         class="w-16 h-16 rounded-full border-2 border-sky-400 bg-white dark:bg-slate-800 object-cover shadow-sm">
                    <!-- Google Badge Icon -->
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-white dark:bg-slate-800 shadow-md border border-slate-200 dark:border-slate-700 flex items-center justify-center p-1">
                        <svg class="w-full h-full" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white"><?= htmlspecialchars($buyer['name']) ?></h1>
                        <span class="text-[10px] font-extrabold bg-sky-100 dark:bg-sky-950/80 text-sky-700 dark:text-sky-300 px-2 py-0.5 rounded-full border border-sky-300 dark:border-sky-700">Akun Terverifikasi</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium mt-0.5"><?= htmlspecialchars($buyer['email']) ?></p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="/#katalog" class="px-4 py-2.5 bg-sky-50 dark:bg-slate-800 hover:bg-sky-100 dark:hover:bg-slate-700 text-sky-700 dark:text-sky-300 font-bold text-xs rounded-xl border border-sky-200/60 dark:border-slate-700 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Beli Item Baru</span>
                </a>
                <a href="/auth/logout" class="px-4 py-2.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-300 font-bold text-xs rounded-xl border border-rose-200/60 dark:border-rose-900 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Daftar Pesanan -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-black text-slate-900 dark:text-white">Riwayat Pesanan Saya</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Semua transaksi item & akun Roblox yang kamu beli</p>
        </div>
        <span class="text-xs font-bold text-slate-400 dark:text-slate-500"><?= count($orders) ?> Pesanan Terdaftar</span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="bg-white dark:bg-[#0c1e33] rounded-3xl border border-slate-200 dark:border-slate-800 p-12 text-center shadow-card">
            <div class="w-16 h-16 rounded-3xl bg-sky-50 dark:bg-slate-800 text-sky-500 dark:text-sky-400 flex items-center justify-center text-3xl mx-auto mb-3 shadow-inner">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h3 class="text-base font-black text-slate-900 dark:text-white mb-1">Belum Ada Pesanan</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-6">
                Kamu belum memiliki riwayat pembelian item atau akun Roblox dengan akun Google ini.
            </p>
            <a href="/#katalog" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-xs rounded-2xl shadow-md shadow-sky-300 dark:shadow-sky-950 transition active:scale-95">
                <i class="fa-solid fa-gamepad"></i>
                <span>Jelajahi Katalog Toko</span>
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $o): ?>
            <div class="p-5 rounded-3xl bg-white dark:bg-[#0c1e33] border border-slate-200/90 dark:border-slate-800 hover:border-sky-300 dark:hover:border-sky-500 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-card">
                <div class="flex items-center gap-4">
                    <img src="<?= htmlspecialchars($o['roblox_avatar_url']) ?>" 
                         alt="Roblox" 
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($o['roblox_username']) ?>&background=38bdf8&color=fff'"
                         class="w-14 h-14 rounded-2xl border-2 border-sky-300 dark:border-sky-600 bg-slate-50 dark:bg-slate-800 object-cover flex-shrink-0">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-xs font-black text-sky-600 dark:text-sky-400"><?= htmlspecialchars($o['invoice_number']) ?></span>
                            <?php if ($o['status'] === 'PENDING'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Menunggu Bayar</span>
                            <?php elseif ($o['status'] === 'PAID'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950/80 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">Lunas</span>
                            <?php elseif ($o['status'] === 'PROCESSING'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 dark:bg-purple-950/80 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800">Diproses</span>
                            <?php elseif ($o['status'] === 'SUCCESS'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Selesai</span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-950/80 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Batal</span>
                            <?php endif; ?>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></span>
                        </div>
                        <h3 class="font-black text-slate-900 dark:text-white text-sm"><?= htmlspecialchars($o['product_name']) ?></h3>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Akun Roblox: <strong class="text-slate-800 dark:text-slate-200 font-bold"><?= htmlspecialchars($o['roblox_username']) ?></strong> • 
                            <span class="text-orange-600 dark:text-orange-400 font-black">Rp <?= number_format($o['price'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="/order/<?= htmlspecialchars($o['invoice_number']) ?>" 
                       class="w-full sm:w-auto py-2.5 px-5 bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 active:scale-95">
                        <i class="fa-solid fa-comments"></i>
                        <span>Buka Chat & Invoice</span>
                        <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php
$bodyContent = ob_get_clean();
require __DIR__ . '/layout.php';
?>
