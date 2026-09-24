<?php
ob_start();
$isPending = ($order['status'] === 'PENDING');
$isPaid = ($order['status'] === 'PAID');
$isProcessing = ($order['status'] === 'PROCESSING');
$isSuccess = ($order['status'] === 'SUCCESS');
$isCancelled = ($order['status'] === 'CANCELLED');

/**
 * Helper Server-Side Render Sticker
 */
function renderStickerMessageHtml($rawMessage) {
    if (preg_match('/^\[sticker:([a-z0-9_-]+)\]$/i', trim($rawMessage), $matches)) {
        $stickerId = strtolower($matches[1]);
        $stickers = [
            'deal'   => ['icon' => 'fa-handshake', 'bg' => 'from-amber-400 to-yellow-500', 'badge' => 'DEAL!'],
            'ready'  => ['icon' => 'fa-gamepad', 'bg' => 'from-emerald-400 to-teal-500', 'badge' => 'READY'],
            'sultan' => ['icon' => 'fa-gem', 'bg' => 'from-purple-500 to-indigo-600', 'badge' => 'SULTAN'],
            'fast'   => ['icon' => 'fa-bolt', 'bg' => 'from-sky-400 to-blue-600', 'badge' => 'FAST!'],
            'gg'     => ['icon' => 'fa-trophy', 'bg' => 'from-yellow-400 to-amber-500', 'badge' => 'GG WP!'],
            'thanks' => ['icon' => 'fa-heart', 'bg' => 'from-rose-400 to-pink-500', 'badge' => 'THANK YOU'],
            'cool'   => ['icon' => 'fa-glasses', 'bg' => 'from-cyan-400 to-blue-500', 'badge' => 'EZ PZ'],
            'fire'   => ['icon' => 'fa-fire-flame-curved', 'bg' => 'from-orange-500 to-red-600', 'badge' => 'FIRE!'],
            'portal' => ['icon' => 'fa-circle-nodes', 'bg' => 'from-violet-500 to-fuchsia-600', 'badge' => 'JOIN ME'],
            'cry'    => ['icon' => 'fa-face-sad-tear', 'bg' => 'from-blue-300 to-sky-400', 'badge' => 'HUHU'],
            'shock'  => ['icon' => 'fa-face-surprise', 'bg' => 'from-fuchsia-400 to-pink-600', 'badge' => 'NO WAY!'],
            'bye'    => ['icon' => 'fa-hand', 'bg' => 'from-lime-400 to-emerald-500', 'badge' => 'BYE BYE']
        ];
        $s = $stickers[$stickerId] ?? $stickers['deal'];
        return '<div class="inline-block p-1">
            <div class="p-2 sm:p-2.5 rounded-3xl bg-gradient-to-tr ' . $s['bg'] . ' text-white shadow-md inline-flex flex-col items-center justify-center text-center w-24 sm:w-28 h-24 sm:h-28 border-2 border-white/60 select-none animate-in zoom-in-95 duration-200">
                <div class="text-3xl sm:text-4xl drop-shadow-md mb-1"><i class="fa-solid ' . $s['icon'] . '"></i></div>
                <span class="text-[10px] sm:text-[11px] font-black tracking-wider uppercase bg-black/25 px-2 py-0.5 rounded-full border border-white/30 drop-shadow-sm">' . $s['badge'] . '</span>
            </div>
        </div>';
    }
    return null;
}

/**
 * Helper Server-Side Render Chat Text with Auto-Links and Roblox Share Buttons
 */
function formatChatMessageTextHtml($rawMessage, $isSeller = false) {
    $escaped = htmlspecialchars($rawMessage, ENT_QUOTES, 'UTF-8');
    $detectedRobloxUrl = null;

    // Detect http:// or https:// URLs
    $formatted = preg_replace_callback('/(https?:\/\/[^\s]+)/i', function($m) use (&$detectedRobloxUrl, $isSeller) {
        $url = $m[1];
        if (strpos($url, 'roblox.com') !== false) {
            $detectedRobloxUrl = $url;
        }
        $linkColorClass = $isSeller 
            ? 'text-blue-600 hover:text-blue-800 bg-blue-50/80 hover:bg-blue-100' 
            : 'text-amber-200 hover:text-white underline decoration-amber-300 bg-white/20 hover:bg-white/30';
        return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="' . $linkColorClass . ' underline font-bold break-all inline-flex items-center gap-1 px-1.5 py-0.5 rounded transition"><span>' . $url . '</span><i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i></a>';
    }, $escaped);

    // Detect raw Roblox share code if not already detected
    if (!$detectedRobloxUrl) {
        $rawCodePattern = '/([a-z0-9_-]{8,}&type=Server|games\/share\?code=[a-z0-9_-]+(&type=Server)?)/i';
        $formatted = preg_replace_callback($rawCodePattern, function($m) use (&$detectedRobloxUrl, $isSeller) {
            $match = $m[1];
            if (strpos($match, 'code=') !== false) {
                $parts = explode('code=', $match);
                $code = explode('&', $parts[1])[0];
            } else {
                $code = explode('&', $match)[0];
            }
            $fullUrl = 'https://www.roblox.com/games/share?code=' . $code . '&type=Server';
            $detectedRobloxUrl = $fullUrl;
            $linkColorClass = $isSeller 
                ? 'text-blue-600 hover:text-blue-800 bg-blue-50/80 hover:bg-blue-100' 
                : 'text-amber-200 hover:text-white underline decoration-amber-300 bg-white/20 hover:bg-white/30';
            return '<a href="' . $fullUrl . '" target="_blank" rel="noopener noreferrer" class="' . $linkColorClass . ' underline font-bold break-all inline-flex items-center gap-1 px-1.5 py-0.5 rounded transition"><span>' . $match . '</span><i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i></a>';
        }, $formatted);
    }

    if ($detectedRobloxUrl) {
        $btnClass = $isSeller 
            ? 'bg-blue-600 hover:bg-blue-700 text-white' 
            : 'bg-white text-blue-700 hover:bg-blue-50 font-black shadow-sm';
        $formatted .= '
            <div class="mt-2 pt-2 border-t ' . ($isSeller ? 'border-slate-200/60' : 'border-white/20') . '">
                <a href="' . $detectedRobloxUrl . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 ' . $btnClass . ' rounded-xl text-[11px] font-extrabold shadow-2xs transition active:scale-95">
                    <i class="fa-solid fa-gamepad text-xs"></i>
                    <span>Buka / Join Server Roblox</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ml-0.5 opacity-80"></i>
                </a>
            </div>
        ';
    }

    return $formatted;
}
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Tombol Kembali & ID Transaksi -->
    <div class="mb-5 flex items-center justify-between">
        <a href="/" class="text-xs font-bold text-slate-500 hover:text-sky-600 transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Katalog</span>
        </a>
        <span class="text-xs text-slate-400">ID Transaksi: <strong class="text-sky-600 font-mono"><?= htmlspecialchars($order['invoice_number']) ?></strong></span>
    </div>

    <!-- Alert Notifikasi Simulasi -->
    <?php if (isset($_GET['paid']) && $_GET['paid'] === 'success'): ?>
    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-4 shadow-sm animate-in fade-in">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <strong class="font-bold text-slate-900 text-sm sm:text-base">Pembayaran Berhasil Dikonfirmasi!</strong>
            <p class="text-xs text-emerald-700 mt-0.5">Pesananmu kini berstatus <strong>Lunas</strong>. Silakan koordinasikan serah terima item dengan penjual di Live Chat di bawah.</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Alert Ulasan Berhasil -->
    <?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-center gap-4 shadow-sm animate-in fade-in">
        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
            <i class="fa-solid fa-star"></i>
        </div>
        <div>
            <strong class="font-bold text-slate-900 text-sm sm:text-base">Terima Kasih Atas Ulasanmu!</strong>
            <p class="text-xs text-amber-800 mt-0.5">Rating dan ulasan kamu telah tersimpan dan langsung tampil di katalog toko ItemPedia.</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Alert Pesan Error -->
    <?php if (!empty($_GET['error'])): ?>
    <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-4 shadow-sm animate-in fade-in">
        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl flex-shrink-0">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <strong class="font-bold text-slate-900 text-sm sm:text-base">Perhatian</strong>
            <p class="text-xs text-rose-700 mt-0.5"><?= htmlspecialchars($_GET['error']) ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Header Status & Stepper Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <?php if ($isPending): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock animate-spin"></i> Menunggu Pembayaran
                        </span>
                    <?php elseif ($isPaid): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-sky-600"></i> Pembayaran Berhasil (Lunas)
                        </span>
                    <?php elseif ($isProcessing): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-gear fa-spin text-purple-600"></i> Sedang Diproses Seller
                        </span>
                    <?php elseif ($isSuccess): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Pesanan Selesai
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                            Dibatalkan
                        </span>
                    <?php endif; ?>

                    <span class="text-xs text-slate-400 font-medium">
                        <?= date('d M Y, H:i', strtotime($order['created_at'])) ?>
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Invoice #<?= htmlspecialchars($order['invoice_number']) ?>
                </h1>
            </div>

            <div class="sm:text-right">
                <span class="text-xs text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Total Pembayaran:</span>
                <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                    <div class="flex items-center gap-1.5 sm:justify-end mb-1">
                        <span class="text-xs font-bold text-slate-400 line-through">
                            Rp <?= number_format($order['price'] + $order['discount_amount'], 0, ',', '.') ?>
                        </span>
                        <span class="px-2 py-0.5 text-[10px] font-black rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Diskon: -Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?>
                        </span>
                    </div>
                <?php endif; ?>
                <span class="text-2xl sm:text-3xl font-black text-sky-600">
                    Rp <?= number_format($order['price'], 0, ',', '.') ?>
                </span>
                <?php if (!empty($order['redeem_code'])): ?>
                    <div class="text-[11px] font-bold text-amber-600 mt-0.5 flex items-center sm:justify-end gap-1">
                        <i class="fa-solid fa-ticket text-amber-500"></i>
                        <span>Kode: <strong class="uppercase tracking-wider"><?= htmlspecialchars($order['redeem_code']) ?></strong></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Progress Stepper -->
        <div class="pt-5">
            <div class="grid grid-cols-4 gap-2 text-center text-[10px] sm:text-xs font-bold uppercase tracking-wider">
                <div class="space-y-1.5 <?= $isPending || $isPaid || $isProcessing || $isSuccess ? 'text-sky-600' : 'text-slate-400' ?>">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 mx-auto rounded-xl flex items-center justify-center text-xs <?= $isPending || $isPaid || $isProcessing || $isSuccess ? 'bg-sky-500 text-white font-black shadow-sm' : 'bg-slate-100 text-slate-400' ?>">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>1. Order</div>
                </div>
                <div class="space-y-1.5 <?= $isPaid || $isProcessing || $isSuccess ? 'text-sky-600' : 'text-slate-400' ?>">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 mx-auto rounded-xl flex items-center justify-center text-xs <?= $isPaid || $isProcessing || $isSuccess ? 'bg-sky-500 text-white font-black shadow-sm' : 'bg-slate-100 text-slate-400' ?>">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div>2. Lunas</div>
                </div>
                <div class="space-y-1.5 <?= $isProcessing || $isSuccess ? 'text-purple-600' : ($isPaid ? 'text-sky-600' : 'text-slate-400') ?>">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 mx-auto rounded-xl flex items-center justify-center text-xs <?= $isProcessing || $isSuccess ? 'bg-purple-600 text-white font-black shadow-sm' : ($isPaid ? 'bg-sky-100 text-sky-600 font-bold border border-sky-300 animate-pulse' : 'bg-slate-100 text-slate-400') ?>">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>3. Kirim Item</div>
                </div>
                <div class="space-y-1.5 <?= $isSuccess ? 'text-emerald-600' : 'text-slate-400' ?>">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 mx-auto rounded-xl flex items-center justify-center text-xs <?= $isSuccess ? 'bg-emerald-500 text-white font-black shadow-sm' : 'bg-slate-100 text-slate-400' ?>">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>4. Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Box Khusus Akun Game (Jika Selesai & Ada Data Akun) -->
    <?php if ($isSuccess && !empty($order['account_data'])): ?>
    <div class="mb-6 p-6 rounded-3xl bg-emerald-50 border-2 border-emerald-300 shadow-sm animate-in fade-in">
        <div class="flex items-center gap-3.5 mb-4">
            <div class="w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-xl shadow-sm">
                <i class="fa-solid fa-key"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-base sm:text-lg">DATA AKUN ROBLOX KAMU</h3>
                <p class="text-xs text-emerald-700">Simpan dan segera amankan akun ini dengan menambahkan email pribadi & mengganti password!</p>
            </div>
        </div>

        <div class="relative bg-white rounded-2xl p-5 border border-emerald-200 font-mono text-sm text-slate-800 whitespace-pre-wrap leading-relaxed shadow-sm" id="accountDataText"><?= htmlspecialchars($order['account_data']) ?></div>

        <button type="button" 
                onclick="copyAccountData()"
                class="mt-4 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition flex items-center gap-2 shadow-sm active:scale-95">
            <i class="fa-regular fa-copy"></i>
            <span id="copyBtnText">Salin Data Akun</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- TAMPILAN UTAMA -->
    <?php if ($isPending): ?>
    <!-- 1. JIKA PESANAN PENDING (Scan QRIS Pembayaran) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        
        <!-- Kolom Kiri: QRIS Code -->
        <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 text-center shadow-sm space-y-4">
            <div class="flex items-center justify-center gap-2 text-xs font-black text-sky-700 uppercase tracking-wider bg-sky-50 py-1.5 px-3 rounded-xl border border-sky-100 inline-flex mx-auto">
                <i class="fa-solid fa-qrcode"></i>
                <span>Scan QRIS untuk Membayar</span>
            </div>

            <!-- QR Code Box -->
            <div class="p-4 bg-slate-50 rounded-3xl border border-slate-200 inline-block shadow-inner">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode('https://itempedia.local/order/' . $order['invoice_number']) ?>" 
                     alt="QRIS Code" 
                     class="w-48 h-48 mx-auto rounded-xl">
            </div>

            <!-- Total Bayar -->
            <div>
                <span class="text-xs text-slate-400 block font-bold">Nominal yang Harus Dibayar:</span>
                <span class="text-2xl font-black text-slate-900">Rp <?= number_format($order['price'], 0, ',', '.') ?></span>
            </div>

            <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                Scan kode QRIS di atas menggunakan aplikasi BCA, GoPay, OVO, DANA, ShopeePay, SeaBank, atau Mobile Banking apa saja.
            </p>

            <!-- Mode Sandbox Pembayaran Cepat -->
            <div class="pt-4 border-t border-slate-100">
                <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 text-left mb-3 flex items-start gap-2.5">
                    <i class="fa-solid fa-flask text-amber-600 text-sm flex-shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="font-bold">Mode Simulasi Uji Coba:</strong>
                        <p class="text-[11px] text-amber-800 mt-0.5">Klik tombol di bawah untuk langsung mensimulasikan pembayaran lunas tanpa uang sungguhan.</p>
                    </div>
                </div>
                <a href="/order/<?= htmlspecialchars($order['invoice_number']) ?>/simulate" 
                   class="w-full py-3 px-4 bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs sm:text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2 active:scale-95">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Simulasi Bayar Berhasil (Instant)</span>
                </a>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Produk & Akun -->
        <div class="lg:col-span-6 space-y-5">
            <!-- Card Item Dipesan -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Item Dipesan</span>
                <?php 
                $itemsBreakdown = !empty($order['items_json']) ? json_decode($order['items_json'], true) : [];
                ?>
                <?php if (!empty($itemsBreakdown) && is_array($itemsBreakdown) && count($itemsBreakdown) > 1): ?>
                    <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                        <?php foreach ($itemsBreakdown as $ib): ?>
                        <div class="flex items-center gap-3 p-2.5 bg-slate-50 border border-slate-100 rounded-2xl">
                            <img src="<?= htmlspecialchars($ib['image_url'] ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500') ?>" 
                                 alt="" 
                                 class="w-12 h-12 rounded-xl object-cover bg-white flex-shrink-0 border border-slate-200">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-900 text-xs sm:text-sm leading-snug truncate"><?= htmlspecialchars($ib['name']) ?></h4>
                                <div class="text-[11px] text-slate-500 font-medium mt-0.5 flex items-center justify-between">
                                    <span><?= (int)$ib['qty'] ?>x @ Rp <?= number_format($ib['price'], 0, ',', '.') ?></span>
                                    <span class="font-bold text-sky-600">Rp <?= number_format($ib['subtotal'] ?? ($ib['price'] * $ib['qty']), 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-4">
                        <img src="<?= htmlspecialchars($order['product_image'] ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500') ?>" 
                             alt="" 
                             class="w-16 h-16 rounded-2xl object-cover bg-slate-50 flex-shrink-0 border border-slate-200">
                        <div class="min-w-0">
                            <span class="text-[10px] font-black text-sky-600 uppercase tracking-wider bg-sky-50 px-2 py-0.5 rounded-md border border-sky-100"><?= htmlspecialchars($order['category_name']) ?></span>
                            <h4 class="font-bold text-slate-900 text-sm sm:text-base leading-snug mt-1 truncate"><?= htmlspecialchars($order['product_name']) ?></h4>
                            <div class="text-sky-600 font-black text-sm mt-0.5">Rp <?= number_format($order['price'], 0, ',', '.') ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Card Target Roblox -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Target Akun Roblox</span>
                <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <img src="<?= htmlspecialchars($order['roblox_avatar_url']) ?>" 
                         alt="Avatar" 
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($order['roblox_username']) ?>&background=38bdf8&color=fff'"
                         class="w-12 h-12 rounded-full border-2 border-sky-400 bg-white object-cover flex-shrink-0 shadow-2xs">
                    <div class="min-w-0">
                        <span class="text-[11px] text-slate-400 block font-medium">Username Roblox Penerima:</span>
                        <span class="font-black text-slate-900 text-sm truncate block"><?= htmlspecialchars($order['roblox_username']) ?></span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 flex items-center gap-1.5 pt-1">
                    <i class="fa-solid fa-lock text-sky-500"></i>
                    <span>Ruang Live Chat dengan penjual akan otomatis aktif setelah pembayaran lunas.</span>
                </p>
            </div>
        </div>

    </div>

    <?php else: ?>
    <!-- 2. JIKA PESANAN SUDAH LUNAS / PROSES / SELESAI (Split View: Detail & Live Chat Berdampingan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 items-start">
        
        <!-- KOLOM KIRI (5 Grid): Detail Pesanan & Panduan Pengambilan Item -->
        <div class="lg:col-span-5 space-y-5">
            
            <!-- Card 1: Ringkasan Item & Akun Roblox -->
            <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Item Dipesan</span>
                    <span class="text-[10px] font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">
                        LUNAS
                    </span>
                </div>

                <!-- Info Produk -->
                <?php if (!empty($itemsBreakdown) && is_array($itemsBreakdown) && count($itemsBreakdown) > 1): ?>
                    <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                        <?php foreach ($itemsBreakdown as $ib): ?>
                        <div class="flex items-center gap-3 p-2.5 bg-slate-50 border border-slate-100 rounded-2xl">
                            <img src="<?= htmlspecialchars($ib['image_url'] ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500') ?>" 
                                 alt="" 
                                 class="w-12 h-12 rounded-xl object-cover bg-white flex-shrink-0 border border-slate-200">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-900 text-xs leading-snug truncate"><?= htmlspecialchars($ib['name']) ?></h4>
                                <div class="text-[11px] text-slate-500 font-medium mt-0.5 flex items-center justify-between">
                                    <span><?= (int)$ib['qty'] ?>x @ Rp <?= number_format($ib['price'], 0, ',', '.') ?></span>
                                    <span class="font-bold text-sky-600">Rp <?= number_format($ib['subtotal'] ?? ($ib['price'] * $ib['qty']), 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-3.5">
                        <img src="<?= htmlspecialchars($order['product_image'] ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500') ?>" 
                             alt="" 
                             class="w-14 h-14 rounded-2xl object-cover bg-slate-50 flex-shrink-0 border border-slate-200">
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-sky-600 uppercase tracking-wider"><?= htmlspecialchars($order['category_name']) ?></span>
                            <h4 class="font-bold text-slate-900 text-sm leading-snug truncate"><?= htmlspecialchars($order['product_name']) ?></h4>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-sky-600 font-black text-sm">Rp <?= number_format($order['price'], 0, ',', '.') ?></span>
                                <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200">
                                        -Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Akun Roblox Penerima -->
                <div class="p-3.5 rounded-2xl bg-sky-50/60 border border-sky-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="<?= htmlspecialchars($order['roblox_avatar_url']) ?>" 
                             alt="Avatar" 
                             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($order['roblox_username']) ?>&background=38bdf8&color=fff'"
                             class="w-10 h-10 rounded-full border-2 border-sky-400 bg-white object-cover flex-shrink-0">
                        <div class="min-w-0">
                            <span class="text-[10px] text-sky-700 font-bold block uppercase tracking-wider">Penerima Roblox:</span>
                            <span class="font-black text-slate-900 text-xs sm:text-sm truncate block"><?= htmlspecialchars($order['roblox_username']) ?></span>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="copyRobloxUsername()" 
                            class="px-2.5 py-1.5 bg-white hover:bg-sky-100 text-sky-700 text-[11px] font-bold rounded-xl border border-sky-200 transition flex items-center gap-1 flex-shrink-0 shadow-2xs">
                        <i class="fa-regular fa-copy"></i>
                        <span id="copyUsernameBtnText">Salin</span>
                    </button>
                </div>

                <?php if (!empty($order['note'])): ?>
                <div class="text-xs bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-600">
                    <strong class="text-slate-800 block text-[11px] mb-0.5">Catatan Pesanan:</strong>
                    "<?= htmlspecialchars($order['note']) ?>"
                </div>
                <?php endif; ?>
            </div>

            <!-- Card 2: Panduan Serah Terima Item (Langkah Jelas & Anti-Bingung) -->
            <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-sm space-y-3.5">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <h3 class="font-black text-slate-900 text-sm">Cara Mengambil Item Kamu:</h3>
                </div>

                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="w-5 h-5 rounded-full bg-sky-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <div>
                            <strong class="text-slate-800 block font-bold">Standby di Game Roblox</strong>
                            <span>Buka game Roblox kamu atau siapkan Private Server kamu.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="w-5 h-5 rounded-full bg-sky-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        <div>
                            <strong class="text-slate-800 block font-bold">Kirim Link / Koordinasi di Chat</strong>
                            <span>Kirimkan link private server kamu di Live Chat sebelah kanan, atau tunggu seller join servermu.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="w-5 h-5 rounded-full bg-sky-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                        <div>
                            <strong class="text-slate-800 block font-bold">Trade & Selesai!</strong>
                            <span>Seller akan trade item langsung ke akun <strong><?= htmlspecialchars($order['roblox_username']) ?></strong>.</span>
                        </div>
                    </div>
                </div>

                <!-- Bantuan Cepat WhatsApp Seller -->
                <div class="pt-2 border-t border-slate-100">
                    <a href="https://wa.me/6281234567890?text=<?= urlencode('Halo admin ItemPedia, saya ingin konfirmasi pesanan ' . $order['invoice_number'] . ' untuk username ' . $order['roblox_username']) ?>" 
                       target="_blank" 
                       class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 transition flex items-center gap-1.5 justify-center py-1">
                        <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                        <span>Butuh bantuan admin langsung? Chat WhatsApp</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (7 Grid): Ruang Live Chat Berdampingan -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col relative" style="min-height: 520px;">
            
            <!-- Chat Top Bar Header -->
            <div class="p-4 bg-slate-50/90 border-b border-slate-200 flex items-center justify-between gap-3 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="relative w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center text-base shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-headset"></i>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white animate-pulse"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-sm text-slate-900">Seller ItemPedia</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-300 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Online & Siap Trade
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500">Koordinasi serah terima item langsung di sini secara realtime.</p>
                    </div>
                </div>

                <!-- Tombol Notifikasi Browser -->
                <button type="button" 
                        id="btnEnableNotification" 
                        onclick="requestNotificationPermission()"
                        class="px-3 py-1.5 bg-white hover:bg-sky-50 border border-slate-200 hover:border-sky-300 text-slate-600 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-2xs flex-shrink-0">
                    <i class="fa-solid fa-bell text-amber-500" id="notifBellIcon"></i>
                    <span id="notifStatusText" class="hidden sm:inline">Notifikasi</span>
                </button>
            </div>

            <!-- Notice Banner Ringkas -->
            <div class="bg-sky-50/80 px-4 py-2 border-b border-sky-100 flex items-center justify-between text-[11px] text-sky-800 flex-shrink-0">
                <span class="flex items-center gap-1.5 font-bold">
                    <i class="fa-solid fa-circle-info text-sky-600"></i>
                    <span>Kirim link private server kamu di sini atau standby di game ya!</span>
                </span>
                <span class="text-slate-400 font-mono text-[10px] hidden sm:inline">Auto-Sync 3s</span>
            </div>

            <!-- Wadah Pesan Chat (Scrollable) -->
            <div id="chatMessagesContainer" class="p-4 sm:p-5 space-y-3.5 overflow-y-auto bg-slate-50/40 flex-grow scrollbar-none" style="height: 340px;">
                <?php if (empty($chatMessages)): ?>
                    <div id="chatEmptyState" class="text-center py-12 text-slate-400">
                        <i class="fa-regular fa-comment-dots text-4xl mb-2 text-slate-300"></i>
                        <p class="text-xs font-bold">Belum ada percakapan. Mulai kirim pesan ke penjual di bawah!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($chatMessages as $msg): ?>
                        <?php 
                        $isSeller = ($msg['sender'] === 'seller');
                        $isRead = ((int)($msg['is_read'] ?? 0) === 1);
                        $stickerHtml = renderStickerMessageHtml($msg['message']);
                        ?>

                        <?php if ($isSeller): ?>
                            <!-- Bubble Seller -->
                            <div class="flex items-start gap-2.5 max-w-[90%] sm:max-w-[80%]" data-msg-id="<?= $msg['id'] ?>">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center text-xs font-bold shadow-2xs flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[11px] font-black text-slate-900"><?= htmlspecialchars($msg['sender_name']) ?></span>
                                        <span class="text-[8px] font-extrabold bg-sky-100 text-sky-700 px-1.5 py-0.2 rounded border border-sky-200">Official Seller</span>
                                    </div>
                                    <?php if ($stickerHtml): ?>
                                        <?= $stickerHtml ?>
                                    <?php else: ?>
                                        <div class="p-3 rounded-2xl rounded-tl-sm bg-white border border-slate-200 text-xs text-slate-800 leading-relaxed font-medium shadow-2xs"><?= formatChatMessageTextHtml($msg['message'], true) ?></div>
                                    <?php endif; ?>
                                    <span class="text-[10px] text-slate-400 font-semibold block"><?= $msg['time_formatted'] ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Bubble Buyer -->
                            <div class="flex items-start justify-end gap-2.5 max-w-[90%] sm:max-w-[80%] ml-auto" data-msg-id="<?= $msg['id'] ?>">
                                <div class="space-y-1 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <span class="text-[8px] font-extrabold bg-emerald-100 text-emerald-700 px-1.5 py-0.2 rounded border border-emerald-200">Kamu</span>
                                        <span class="text-[11px] font-black text-slate-900"><?= htmlspecialchars($msg['sender_name']) ?></span>
                                    </div>
                                    <?php if ($stickerHtml): ?>
                                        <?= $stickerHtml ?>
                                    <?php else: ?>
                                        <div class="p-3 rounded-2xl rounded-tr-sm bg-gradient-to-r from-sky-500 to-blue-600 text-white text-xs leading-relaxed font-medium shadow-2xs text-left"><?= formatChatMessageTextHtml($msg['message'], false) ?></div>
                                    <?php endif; ?>
                                    <span class="text-[10px] text-slate-400 font-semibold flex items-center justify-end gap-1">
                                        <span><?= $msg['time_formatted'] ?></span>
                                        <!-- Centang 2: Abu-abu jika belum dibaca, Biru jika sudah dibaca -->
                                        <i class="fa-solid fa-check-double <?= $isRead ? 'text-sky-500' : 'text-slate-400' ?> text-[10px] buyer-check-icon" 
                                           data-msg-id="<?= $msg['id'] ?>" 
                                           title="<?= $isRead ? 'Sudah dibaca oleh penjual' : 'Terkirim (Belum dibaca)' ?>"></i>
                                    </span>
                                </div>
                                <img src="<?= htmlspecialchars($order['roblox_avatar_url']) ?>" 
                                     alt="Avatar" 
                                     onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($order['roblox_username']) ?>&background=38bdf8&color=fff'"
                                     class="w-8 h-8 rounded-full border border-sky-400 bg-white object-cover flex-shrink-0 mt-0.5 shadow-2xs">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Tombol Balas Cepat (Quick Chips untuk Trade) -->
            <div class="px-4 py-2 bg-slate-50 border-t border-slate-100 flex items-center gap-1.5 overflow-x-auto scrollbar-none flex-shrink-0">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider flex items-center gap-1 whitespace-nowrap mr-1">
                    <i class="fa-solid fa-bolt text-amber-500"></i> Cepat:
                </span>
                <button type="button" onclick="setQuickMessage('Halo kak, saya sudah standby di dalam game ya!')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-sky-400 hover:bg-sky-50 text-[11px] font-bold text-slate-700 whitespace-nowrap transition shadow-2xs">
                    🎮 Standby di game
                </button>
                <button type="button" onclick="setQuickMessage('Ini link private server saya kak: ')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-sky-400 hover:bg-sky-50 text-[11px] font-bold text-slate-700 whitespace-nowrap transition shadow-2xs">
                    🔗 Kirim link server
                </button>
                <button type="button" onclick="setQuickMessage('Kapan item saya dikirimkan ya kak?')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-sky-400 hover:bg-sky-50 text-[11px] font-bold text-slate-700 whitespace-nowrap transition shadow-2xs">
                    📦 Kapan dikirim?
                </button>
                <button type="button" onclick="setQuickMessage('Item sudah saya terima dengan lengkap kak, terima kasih banyak!')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-sky-400 hover:bg-sky-50 text-[11px] font-bold text-slate-700 whitespace-nowrap transition shadow-2xs">
                    ✅ Item sudah diterima!
                </button>
            </div>

            <!-- Form Kirim Pesan & Floating Sticker Drawer -->
            <div class="p-3.5 sm:p-4 bg-slate-50 border-t border-slate-200 flex-shrink-0 relative">
                
                <!-- Floating Sticker Picker Drawer -->
                <div id="stickerDrawer" class="hidden absolute bottom-full left-3 right-3 sm:left-4 sm:right-4 mb-2 p-3.5 bg-white/98 backdrop-blur-md rounded-2xl border-2 border-sky-200 shadow-xl z-30 animate-in fade-in zoom-in-95 duration-150">
                    <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
                        <span class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <i class="fa-solid fa-icons text-amber-500"></i>
                            <span>Sticker Game & Trade ItemPedia</span>
                        </span>
                        <button type="button" onclick="toggleStickerDrawer(false)" class="text-slate-400 hover:text-slate-600 text-xs w-6 h-6 rounded-lg hover:bg-slate-100 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 max-h-52 overflow-y-auto p-1 scrollbar-none" id="stickerGridContainer">
                        <!-- Populated via JS -->
                    </div>
                </div>

                <form id="buyerChatForm" onsubmit="sendBuyerMessage(event)" class="flex items-center gap-2">
                    <!-- Tombol Buka Sticker -->
                    <button type="button" 
                            id="btnToggleSticker" 
                            onclick="toggleStickerDrawer()" 
                            class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:border-amber-400 hover:bg-amber-50 text-amber-500 flex items-center justify-center text-lg transition flex-shrink-0 shadow-2xs active:scale-95"
                            title="Kirim Sticker Seru">
                        <i class="fa-solid fa-face-smile"></i>
                    </button>

                    <div class="relative flex-grow">
                        <textarea id="chatInputMessage" 
                                  rows="1" 
                                  placeholder="Ketik pesan untuk penjual di sini... (Enter untuk kirim)"
                                  class="w-full px-3.5 py-2.5 bg-white border-2 border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:border-sky-500 transition resize-none"></textarea>
                    </div>
                    <button type="submit" 
                            id="btnSendChat"
                            class="px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 flex-shrink-0 active:scale-95">
                        <span>Kirim</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </form>
                <div class="flex items-center justify-between text-[10px] text-slate-400 mt-2 px-1">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-shield-halved text-emerald-500"></i> Ruang chat terenkripsi & realtime
                    </span>
                    <span id="chatPollingStatus" class="flex items-center gap-1 font-semibold text-emerald-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Terhubung
                    </span>
                </div>
            </div>

        </div>

    </div>
    <?php endif; ?>

    <!-- BAGIAN ULASAN: HANYA TAMPIL KETIKA PESANAN SUDAH SELESAI (SUCCESS) -->
    <?php if ($isSuccess): ?>
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm mb-8 animate-in fade-in">
        <div class="max-w-2xl mx-auto">
            <?php if (!empty($review)): ?>
                <!-- Ulasan Sudah Terkirim -->
                <div class="bg-emerald-50/50 p-6 rounded-2xl border border-emerald-200 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-sm text-slate-900">Ulasan Anda untuk Produk Ini</h4>
                                <span class="text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Terverifikasi & Tayang di Toko
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <i class="fa-solid fa-star <?= $s <= (int)$review['rating'] ? 'text-amber-400' : 'text-slate-200' ?>"></i>
                            <?php endfor; ?>
                            <span class="text-xs font-black text-slate-700 ml-1">(<?= (int)$review['rating'] ?>/5)</span>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 italic text-xs font-semibold text-slate-700 leading-relaxed shadow-2xs">
                        "<?= htmlspecialchars($review['comment']) ?>"
                    </div>
                    <div class="text-[10px] text-slate-400 font-medium flex justify-between items-center">
                        <span>Pembeli: <strong><?= htmlspecialchars($order['roblox_username']) ?></strong></span>
                        <span><?= date('d M Y, H:i', strtotime($review['created_at'])) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <!-- Form Tulis Ulasan Baru (Hanya Muncul Saat Pesanan Selesai) -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-lg">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-base text-slate-900">Beri Ulasan & Bintang Pesanan</h4>
                            <p class="text-xs text-slate-500">Item sudah diterima dengan baik? Bagikan kepuasan belanja kamu untuk membantu reputasi toko!</p>
                        </div>
                    </div>

                    <form action="/order/<?= htmlspecialchars($order['invoice_number']) ?>/review" method="POST" class="space-y-4">
                        <!-- Star Selector -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Pilih Penilaian Bintang:</label>
                            <div class="flex items-center gap-2" id="starRatingContainer">
                                <input type="hidden" name="rating" id="ratingValue" value="5">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button type="button" 
                                        onclick="selectRating(<?= $i ?>)" 
                                        class="star-btn text-2xl text-amber-400 hover:scale-125 transition-transform" 
                                        data-star="<?= $i ?>">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                                <?php endfor; ?>
                                <span id="ratingText" class="text-xs font-extrabold text-slate-700 ml-2 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">5.0 - Sangat Puas</span>
                            </div>
                        </div>

                        <!-- Comment Input -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tulis Ulasan / Pengalaman Belanja:</label>
                            <textarea name="comment" 
                                      rows="3" 
                                      required 
                                      placeholder="Contoh: Pengiriman super kilat, barang sesuai deskripsi, penjual ramah dan fast respon..." 
                                      class="w-full p-3.5 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:bg-white transition resize-none"></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2 active:scale-95">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Ulasan Sekarang</span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
const INVOICE_NUMBER = <?= json_encode($order['invoice_number']) ?>;
const ROBLOX_USERNAME = <?= json_encode($order['roblox_username']) ?>;
const ROBLOX_AVATAR = <?= json_encode($order['roblox_avatar_url'] ?: "https://ui-avatars.com/api/?name=" . urlencode($order['roblox_username']) . "&background=38bdf8&color=fff") ?>;

// ================= STICKER PACK DEFINITION =================
const STICKER_PACK = [
    { id: 'deal', icon: 'fa-handshake', bg: 'from-amber-400 to-yellow-500', badge: 'DEAL!', title: 'Trade Setuju' },
    { id: 'ready', icon: 'fa-gamepad', bg: 'from-emerald-400 to-teal-500', badge: 'READY', title: 'Standby Game' },
    { id: 'sultan', icon: 'fa-gem', bg: 'from-purple-500 to-indigo-600', badge: 'SULTAN', title: 'Sultan Roblox' },
    { id: 'fast', icon: 'fa-bolt', bg: 'from-sky-400 to-blue-600', badge: 'FAST!', title: 'Kirim Kilat' },
    { id: 'gg', icon: 'fa-trophy', bg: 'from-yellow-400 to-amber-500', badge: 'GG WP!', title: 'Mantap Bro' },
    { id: 'thanks', icon: 'fa-heart', bg: 'from-rose-400 to-pink-500', badge: 'THANK YOU', title: 'Makasih' },
    { id: 'cool', icon: 'fa-glasses', bg: 'from-cyan-400 to-blue-500', badge: 'EZ PZ', title: 'Santuy' },
    { id: 'fire', icon: 'fa-fire-flame-curved', bg: 'from-orange-500 to-red-600', badge: 'FIRE!', title: 'Hype / Menyala' },
    { id: 'portal', icon: 'fa-circle-nodes', bg: 'from-violet-500 to-fuchsia-600', badge: 'JOIN ME', title: 'Link Server' },
    { id: 'cry', icon: 'fa-face-sad-tear', bg: 'from-blue-300 to-sky-400', badge: 'HUHU', title: 'Terharu' },
    { id: 'shock', icon: 'fa-face-surprise', bg: 'from-fuchsia-400 to-pink-600', badge: 'NO WAY!', title: 'Kaget' },
    { id: 'bye', icon: 'fa-hand', bg: 'from-lime-400 to-emerald-500', badge: 'BYE BYE', title: 'Sampai Jumpa' }
];

function renderStickerHtml(stickerId) {
    const s = STICKER_PACK.find(item => item.id === stickerId) || STICKER_PACK[0];
    return `
        <div class="inline-block p-1">
            <div class="p-2 sm:p-2.5 rounded-3xl bg-gradient-to-tr ${s.bg} text-white shadow-md inline-flex flex-col items-center justify-center text-center w-24 sm:w-28 h-24 sm:h-28 border-2 border-white/60 select-none animate-in zoom-in-95 duration-200">
                <div class="text-3xl sm:text-4xl drop-shadow-md mb-1"><i class="fa-solid ${s.icon}"></i></div>
                <span class="text-[10px] sm:text-[11px] font-black tracking-wider uppercase bg-black/25 px-2 py-0.5 rounded-full border border-white/30 drop-shadow-sm">${s.badge}</span>
            </div>
        </div>
    `;
}

function initStickerGrid() {
    const container = document.getElementById('stickerGridContainer');
    if (!container || container.children.length > 0) return;

    container.innerHTML = STICKER_PACK.map(s => `
        <button type="button" 
                onclick="sendStickerMessage('${s.id}')" 
                class="group flex flex-col items-center justify-center p-2 rounded-2xl hover:bg-sky-50 border border-slate-100 hover:border-sky-300 transition active:scale-95" 
                title="${s.title}">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr ${s.bg} text-white flex flex-col items-center justify-center shadow-xs group-hover:scale-110 transition-transform">
                <i class="fa-solid ${s.icon} text-base drop-shadow-xs"></i>
            </div>
            <span class="text-[9px] font-black text-slate-700 mt-1 truncate max-w-full">${s.badge}</span>
        </button>
    `).join('');
}

function toggleStickerDrawer(forceState) {
    const drawer = document.getElementById('stickerDrawer');
    if (!drawer) return;
    initStickerGrid();

    if (forceState !== undefined) {
        if (forceState) drawer.classList.remove('hidden');
        else drawer.classList.add('hidden');
    } else {
        drawer.classList.toggle('hidden');
    }
}

async function sendStickerMessage(stickerId) {
    toggleStickerDrawer(false);
    const message = `[sticker:${stickerId}]`;

    try {
        const formData = new FormData();
        formData.append('message', message);
        formData.append('sender', 'buyer');
        formData.append('sender_name', ROBLOX_USERNAME);

        const res = await fetch(`/api/chat/${encodeURIComponent(INVOICE_NUMBER)}/send`, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success && data.message) {
            const emptyState = document.getElementById('chatEmptyState');
            if (emptyState) emptyState.remove();

            const msgId = Number(data.message.id);
            if (!knownMessageIds.has(msgId)) {
                knownMessageIds.add(msgId);
                const container = document.getElementById('chatMessagesContainer');
                if (container) {
                    container.insertAdjacentHTML('beforeend', renderMessageBubble(data.message));
                    scrollChatToBottom();
                }
            }
        }
    } catch (err) {
        console.error("Gagal mengirim sticker:", err);
    }
}

function copyRobloxUsername() {
    navigator.clipboard.writeText(ROBLOX_USERNAME).then(() => {
        const btn = document.getElementById('copyUsernameBtnText');
        if (btn) {
            btn.innerText = 'Tersalin!';
            setTimeout(() => { btn.innerText = 'Salin'; }, 2000);
        }
    });
}

function copyAccountData() {
    const el = document.getElementById('accountDataText');
    if (!el) return;
    const text = el.innerText;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copyBtnText');
        if (btn) {
            btn.innerText = 'Berhasil Disalin!';
            setTimeout(() => { btn.innerText = 'Salin Data Akun'; }, 2000);
        }
    });
}

function selectRating(stars) {
    const valInput = document.getElementById('ratingValue');
    if (valInput) valInput.value = stars;
    const starBtns = document.querySelectorAll('#starRatingContainer .star-btn');
    starBtns.forEach((btn, idx) => {
        const icon = btn.querySelector('i');
        if (idx < stars) {
            icon.className = 'fa-solid fa-star text-amber-400';
        } else {
            icon.className = 'fa-solid fa-star text-slate-200';
        }
    });
    const labels = {
        1: '1.0 - Sangat Kecewa',
        2: '2.0 - Kurang Puas',
        3: '3.0 - Cukup Baik',
        4: '4.0 - Puas',
        5: '5.0 - Sangat Puas'
    };
    const rText = document.getElementById('ratingText');
    if (rText) rText.innerText = labels[stars] || (stars + '.0');
}

// ================= JAVASCRIPT LIVE CHAT & BROWSER NOTIFICATION =================
let knownMessageIds = new Set();
let isWindowFocused = true;
let originalPageTitle = document.title;
let titleFlashInterval = null;

// Catat semua ID pesan yang sudah dirender awal oleh server
document.querySelectorAll('#chatMessagesContainer [data-msg-id]').forEach(el => {
    knownMessageIds.add(Number(el.dataset.msgId));
});

window.addEventListener('focus', () => {
    isWindowFocused = true;
    stopTitleFlashing();
});
window.addEventListener('blur', () => {
    isWindowFocused = false;
});

function checkNotificationPermissionState() {
    if (!("Notification" in window)) {
        const btn = document.getElementById('btnEnableNotification');
        if (btn) btn.style.display = 'none';
        return;
    }
    const btn = document.getElementById('btnEnableNotification');
    const text = document.getElementById('notifStatusText');
    const icon = document.getElementById('notifBellIcon');
    if (!btn || !text) return;

    if (Notification.permission === 'granted') {
        text.innerText = 'Aktif';
        btn.className = 'px-3 py-1.5 bg-emerald-50 border border-emerald-300 text-emerald-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-2xs cursor-default flex-shrink-0';
        if (icon) icon.className = 'fa-solid fa-bell text-emerald-600';
    } else if (Notification.permission === 'denied') {
        text.innerText = 'Diblokir';
        btn.className = 'px-3 py-1.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-2xs flex-shrink-0';
        if (icon) icon.className = 'fa-solid fa-bell-slash text-rose-500';
    }
}

function requestNotificationPermission() {
    if (!("Notification" in window)) {
        alert("Browser ini tidak mendukung Web Notification API.");
        return;
    }
    if (Notification.permission === 'granted') {
        playNotificationSound();
        showBrowserNotification('ItemPedia System', 'Notifikasi browser sudah aktif dan siap menerima pesan dari penjual!');
        return;
    }
    Notification.requestPermission().then(permission => {
        checkNotificationPermissionState();
        if (permission === 'granted') {
            playNotificationSound();
            showBrowserNotification('ItemPedia', 'Notifikasi pesan penjual telah berhasil diaktifkan! 🔔');
        }
    });
}

function playNotificationSound() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const now = ctx.currentTime;

        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(587.33, now);
        gain1.gain.setValueAtTime(0.2, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.3);

        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(880, now + 0.12);
        gain2.gain.setValueAtTime(0.25, now + 0.12);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.12);
        osc2.stop(now + 0.6);
    } catch (e) {
        console.log("Audio notification suppressed:", e);
    }
}

function showBrowserNotification(senderName, messageText) {
    if ("Notification" in window && Notification.permission === "granted") {
        try {
            const notif = new Notification(`Pesan Baru dari ${senderName}`, {
                body: messageText,
                icon: 'https://img.icons8.com/color/96/chat--v1.png',
                tag: 'itempedia-order-' + INVOICE_NUMBER
            });
            notif.onclick = function() {
                window.focus();
                this.close();
            };
        } catch(e) {
            console.error("Notification constructor error:", e);
        }
    }
}

function startTitleFlashing(msgPreview) {
    if (titleFlashInterval) return;
    let toggle = false;
    titleFlashInterval = setInterval(() => {
        document.title = toggle ? '🔔 (Pesan Baru!) Penjual ItemPedia' : originalPageTitle;
        toggle = !toggle;
    }, 1000);
}

function stopTitleFlashing() {
    if (titleFlashInterval) {
        clearInterval(titleFlashInterval);
        titleFlashInterval = null;
    }
    document.title = originalPageTitle;
}

function setQuickMessage(text) {
    const input = document.getElementById('chatInputMessage');
    if (input) {
        input.value = text;
        input.focus();
    }
}

function scrollChatToBottom() {
    const container = document.getElementById('chatMessagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, function(m) {
        return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[m];
    });
}

function formatChatMessageHtml(rawText, isSeller) {
    if (!rawText) return '';
    let escaped = escapeHtml(rawText);
    let detectedRobloxUrl = null;

    // Detect http:// or https:// URLs
    const urlPattern = /(https?:\/\/[^\s]+)/gi;
    let formatted = escaped.replace(urlPattern, function(url) {
        if (url.includes('roblox.com')) {
            detectedRobloxUrl = url;
        }
        const linkClass = isSeller 
            ? 'text-blue-600 hover:text-blue-800 underline font-bold break-all inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 px-1.5 py-0.5 rounded transition'
            : 'text-amber-200 hover:text-white underline decoration-amber-300 font-bold break-all inline-flex items-center gap-1 bg-white/20 hover:bg-white/30 px-1.5 py-0.5 rounded transition';
        return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="${linkClass}"><span>${url}</span><i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i></a>`;
    });

    // Detect raw Roblox share code
    if (!detectedRobloxUrl) {
        const rawCodePattern = /([a-z0-9_-]{8,}&type=Server|games\/share\?code=[a-z0-9_-]+(&type=Server)?)/gi;
        formatted = formatted.replace(rawCodePattern, function(match) {
            const cleanCode = match.includes('code=') ? match.split('code=')[1].split('&')[0] : match.split('&')[0];
            const fullRobloxUrl = 'https://www.roblox.com/games/share?code=' + cleanCode + '&type=Server';
            detectedRobloxUrl = fullRobloxUrl;
            const linkClass = isSeller 
                ? 'text-blue-600 hover:text-blue-800 underline font-bold break-all inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 px-1.5 py-0.5 rounded transition'
                : 'text-amber-200 hover:text-white underline decoration-amber-300 font-bold break-all inline-flex items-center gap-1 bg-white/20 hover:bg-white/30 px-1.5 py-0.5 rounded transition';
            return `<a href="${fullRobloxUrl}" target="_blank" rel="noopener noreferrer" class="${linkClass}"><span>${match}</span><i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i></a>`;
        });
    }

    if (detectedRobloxUrl) {
        const btnClass = isSeller 
            ? 'bg-blue-600 hover:bg-blue-700 text-white' 
            : 'bg-white text-blue-700 hover:bg-blue-50 font-black shadow-sm';
        const borderClass = isSeller ? 'border-slate-200/60' : 'border-white/20';
        formatted += `
            <div class="mt-2 pt-2 border-t ${borderClass}">
                <a href="${detectedRobloxUrl}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 ${btnClass} rounded-xl text-[11px] font-extrabold shadow-2xs transition active:scale-95">
                    <i class="fa-solid fa-gamepad text-xs"></i>
                    <span>Buka / Join Server Roblox</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ml-0.5 opacity-80"></i>
                </a>
            </div>
        `;
    }

    return formatted;
}

function renderMessageBubble(msg) {
    const isSeller = (msg.sender === 'seller');
    const isRead = Number(msg.is_read) === 1;
    const stickerMatch = msg.message ? msg.message.match(/^\[sticker:([a-z0-9_-]+)\]$/i) : null;
    const stickerId = stickerMatch ? stickerMatch[1].toLowerCase() : null;

    let contentHtml = '';
    if (stickerId) {
        contentHtml = renderStickerHtml(stickerId);
    } else {
        const formattedText = formatChatMessageHtml(msg.message, isSeller);
        if (isSeller) {
            contentHtml = `<div class="p-3 rounded-2xl rounded-tl-sm bg-white border border-slate-200 text-xs text-slate-800 leading-relaxed font-medium shadow-2xs">${formattedText}</div>`;
        } else {
            contentHtml = `<div class="p-3 rounded-2xl rounded-tr-sm bg-gradient-to-r from-sky-500 to-blue-600 text-white text-xs leading-relaxed font-medium shadow-2xs text-left">${formattedText}</div>`;
        }
    }

    if (isSeller) {
        return `
            <div class="flex items-start gap-2.5 max-w-[90%] sm:max-w-[80%]" data-msg-id="${msg.id}">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center text-xs font-bold shadow-2xs flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-1.5">
                        <span class="text-[11px] font-black text-slate-900">${escapeHtml(msg.sender_name)}</span>
                        <span class="text-[8px] font-extrabold bg-sky-100 text-sky-700 px-1.5 py-0.2 rounded border border-sky-200">Official Seller</span>
                    </div>
                    ${contentHtml}
                    <span class="text-[10px] text-slate-400 font-semibold block">${msg.time_formatted || 'Baru saja'}</span>
                </div>
            </div>
        `;
    } else {
        // Centang 2: Biru jika sudah dibaca (isRead), Abu-abu jika belum
        const checkColor = isRead ? 'text-sky-500' : 'text-slate-400';
        const checkTitle = isRead ? 'Sudah dibaca oleh penjual' : 'Terkirim (Belum dibaca)';
        return `
            <div class="flex items-start justify-end gap-2.5 max-w-[90%] sm:max-w-[80%] ml-auto" data-msg-id="${msg.id}">
                <div class="space-y-1 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <span class="text-[8px] font-extrabold bg-emerald-100 text-emerald-700 px-1.5 py-0.2 rounded border border-emerald-200">Kamu</span>
                        <span class="text-[11px] font-black text-slate-900">${escapeHtml(msg.sender_name)}</span>
                    </div>
                    ${contentHtml}
                    <span class="text-[10px] text-slate-400 font-semibold flex items-center justify-end gap-1">
                        <span>${msg.time_formatted || 'Baru saja'}</span>
                        <i class="fa-solid fa-check-double ${checkColor} text-[10px] buyer-check-icon" data-msg-id="${msg.id}" title="${checkTitle}"></i>
                    </span>
                </div>
                <img src="${ROBLOX_AVATAR}" 
                     alt="Avatar" 
                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(ROBLOX_USERNAME)}&background=38bdf8&color=fff'"
                     class="w-8 h-8 rounded-full border border-sky-400 bg-white object-cover flex-shrink-0 mt-0.5 shadow-2xs">
            </div>
        `;
    }
}

async function sendBuyerMessage(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('chatInputMessage');
    if (!input) return;
    const message = input.value.trim();
    if (!message) return;

    input.value = '';
    const btn = document.getElementById('btnSendChat');
    if (btn) btn.disabled = true;

    try {
        const formData = new FormData();
        formData.append('message', message);
        formData.append('sender', 'buyer');
        formData.append('sender_name', ROBLOX_USERNAME);

        const res = await fetch(`/api/chat/${encodeURIComponent(INVOICE_NUMBER)}/send`, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success && data.message) {
            const emptyState = document.getElementById('chatEmptyState');
            if (emptyState) emptyState.remove();

            const msgId = Number(data.message.id);
            if (!knownMessageIds.has(msgId)) {
                knownMessageIds.add(msgId);
                const container = document.getElementById('chatMessagesContainer');
                if (container) {
                    container.insertAdjacentHTML('beforeend', renderMessageBubble(data.message));
                    scrollChatToBottom();
                }
            }
        }
    } catch (err) {
        console.error("Gagal mengirim pesan chat:", err);
    } finally {
        if (btn) btn.disabled = false;
        input.focus();
    }
}

let isPollingChat = false;
async function pollChatMessages() {
    if (isPollingChat) return;
    isPollingChat = true;

    try {
        const maxKnownId = knownMessageIds.size > 0 ? Math.max(...knownMessageIds) : 0;
        const res = await fetch(`/api/chat/${encodeURIComponent(INVOICE_NUMBER)}?role=buyer&after_id=${maxKnownId}`);
        const data = await res.json();

        if (data.success) {
            // Update centang 2 abu-abu jadi biru jika seller sudah membaca pesan pembeli
            if (data.last_read_buyer_msg_id) {
                document.querySelectorAll('#chatMessagesContainer .buyer-check-icon').forEach(icon => {
                    const id = Number(icon.dataset.msgId);
                    if (id <= data.last_read_buyer_msg_id) {
                        icon.classList.remove('text-slate-400');
                        icon.classList.add('text-sky-500');
                        icon.title = 'Sudah dibaca oleh penjual';
                    }
                });
            }

            if (Array.isArray(data.messages) && data.messages.length > 0) {
                const emptyState = document.getElementById('chatEmptyState');
                if (emptyState) emptyState.remove();

                const container = document.getElementById('chatMessagesContainer');
                let hasNewSellerMsg = false;
                let latestSellerMsg = null;

                data.messages.forEach(msg => {
                    const id = Number(msg.id);
                    if (!knownMessageIds.has(id)) {
                        knownMessageIds.add(id);
                        if (container) {
                            container.insertAdjacentHTML('beforeend', renderMessageBubble(msg));
                        }
                        if (msg.sender === 'seller') {
                            hasNewSellerMsg = true;
                            latestSellerMsg = msg;
                        }
                    }
                });

                if (hasNewSellerMsg && latestSellerMsg) {
                    playNotificationSound();
                    const notifText = latestSellerMsg.message.startsWith('[sticker:') ? 'Mengirimkan stiker seru!' : latestSellerMsg.message;
                    showBrowserNotification(latestSellerMsg.sender_name, notifText);
                    if (!isWindowFocused) {
                        startTitleFlashing(notifText);
                    }
                }

                scrollChatToBottom();
            }
        }
    } catch (err) {
        console.error("Polling error:", err);
    } finally {
        isPollingChat = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    checkNotificationPermissionState();
    scrollChatToBottom();

    const input = document.getElementById('chatInputMessage');
    if (input) {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendBuyerMessage();
            }
        });
    }

    if ("Notification" in window && Notification.permission === "default") {
        setTimeout(() => {
            const bell = document.getElementById('notifBellIcon');
            if (bell) bell.classList.add('animate-bounce');
        }, 1500);
    }

    // Polling interval 3 detik
    setInterval(pollChatMessages, 3000);
});
</script>

<?php
$bodyContent = ob_get_clean();
require __DIR__ . '/layout.php';
?>
