<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Seller Center ItemPedia</title>
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
    $activeMenu = 'orders';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5 flex-grow">

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

            <!-- Page Header: Title on Left, Action on Right -->
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Pesanan</h1>

                <div class="flex items-center gap-2.5">
                    <button type="button" 
                            onclick="alert('Riwayat pesanan berhasil diekspor!')" 
                            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center gap-2 shadow-2xs active:scale-95">
                        <i class="fa-solid fa-arrow-down-to-bracket text-slate-400"></i>
                        <span>Unduh Riwayat Pesanan</span>
                    </button>
                </div>
            </div>

            <!-- Main Order Card -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                
                <!-- 1. Horizontal Status Tabs Strip (Matching Image 2) -->
                <div class="border-b border-slate-200 bg-white px-4 sm:px-6">
                    <div class="flex items-center gap-2 sm:gap-6 overflow-x-auto no-scrollbar text-xs sm:text-sm font-semibold whitespace-nowrap">
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
                            <a href="/admin/orders?status=<?= $t['key'] ?><?= !empty($searchQuery) ? '&q=' . urlencode($searchQuery) : '' ?><?= !empty($gameFilter) ? '&game=' . urlencode($gameFilter) : '' ?>" 
                               class="py-3.5 px-2 border-b-2 font-bold flex items-center gap-2 transition relative <?= $isActive ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' ?>">
                                <span><?= $t['label'] ?></span>
                                <?php if ($t['count'] > 0): ?>
                                    <span class="px-1.5 py-0.5 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center">
                                        <?= $t['count'] ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 2. Search & Multi-Filter Bar: SEBARIS (1 Single Inline Row) -->
                <div class="p-4 sm:p-5 border-b border-slate-100 bg-white space-y-3.5">
                    <form method="GET" action="/admin/orders" class="flex flex-col sm:flex-row items-center gap-3 w-full">
                        <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter ?? 'ALL') ?>">

                        <!-- Search Input with "Nomor Pesanan" Prefix -->
                        <div class="w-full sm:flex-1 flex items-center rounded-xl bg-white border border-slate-200 shadow-2xs focus-within:border-blue-500 transition overflow-hidden">
                            <div class="px-3.5 py-2.5 bg-slate-50 border-r border-slate-200 text-slate-700 text-xs font-bold flex items-center gap-1.5 whitespace-nowrap">
                                <span>Nomor Pesanan</span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                            </div>
                            <div class="relative flex-grow flex items-center">
                                <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs absolute left-3 pointer-events-none"></i>
                                <input type="text" 
                                       name="q" 
                                       value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                                       placeholder="Cari Nomor Pesanan" 
                                       class="w-full pl-8 pr-3 py-2 text-xs bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none">
                            </div>
                        </div>

                        <!-- Dropdown Pilih Filter / Game -->
                        <div class="w-full sm:w-60">
                            <select name="game" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-500 shadow-2xs">
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
                        <div class="w-full sm:w-44">
                            <select name="sort" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-500 shadow-2xs">
                                <option value="latest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                            </select>
                        </div>
                    </form>

                    <!-- Filter Pills Cepat (Sebaris persis Screenshot) -->
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar text-xs">
                        <a href="/admin/orders?status=<?= htmlspecialchars($statusFilter ?? 'ALL') ?>" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= empty($searchQuery) && empty($gameFilter) ? 'bg-blue-50 text-blue-600 border border-blue-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' ?>">
                            Semua Pesanan
                        </a>
                        <a href="/admin/orders?status=<?= htmlspecialchars($statusFilter ?? 'ALL') ?>&q=Joki" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($searchQuery === 'Joki') ? 'bg-blue-50 text-blue-600 border border-blue-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' ?>">
                            Joki
                        </a>
                        <a href="/admin/orders?status=<?= htmlspecialchars($statusFilter ?? 'ALL') ?>&q=Iklan" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($searchQuery === 'Iklan') ? 'bg-blue-50 text-blue-600 border border-blue-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' ?>">
                            Pesanan Dari Iklan
                        </a>
                        <a href="/admin/orders?status=NEED_PROCESS" 
                           class="px-3.5 py-1.5 rounded-full font-bold transition whitespace-nowrap <?= ($statusFilter === 'NEED_PROCESS') ? 'bg-blue-50 text-blue-600 border border-blue-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' ?>">
                            Pesanan Butuh Cepat
                        </a>
                    </div>
                </div>

                <!-- 3. Orders Content (Tabel atau Empty State Kartun Sesuai Screenshot) -->
                <?php if (empty($orders)): ?>
                <!-- Empty State Illustration Matching Screenshot -->
                <div class="py-20 text-center px-4">
                    <div class="w-36 h-36 mx-auto mb-4 relative flex items-center justify-center">
                        <div class="w-28 h-28 bg-amber-50 rounded-full flex items-center justify-center text-5xl text-amber-400">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </div>
                        <div class="absolute top-1 right-2 w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 text-lg shadow-xs">
                            <i class="fa-solid fa-question"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Kamu belum memiliki pesanan</h3>
                    <p class="text-xs text-slate-400 mt-1">Pesanan yang masuk akan tampil otomatis di sini.</p>
                </div>
                <?php else: ?>
                <!-- Clean Orders Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-4 sm:px-6">Info Invoice</th>
                                <th class="py-3.5 px-4">Pembeli (Roblox)</th>
                                <th class="py-3.5 px-4">Dagangan</th>
                                <th class="py-3.5 px-4">Total Bayar</th>
                                <th class="py-3.5 px-4">Live Chat</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            <?php foreach ($orders as $o): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                
                                <!-- Invoice & Date -->
                                <td class="py-4 px-4 sm:px-6">
                                    <a href="/order/<?= htmlspecialchars($o['invoice_number']) ?>" target="_blank" class="font-mono font-bold text-blue-600 hover:underline flex items-center gap-1.5">
                                        <span><?= htmlspecialchars($o['invoice_number']) ?></span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-blue-400"></i>
                                    </a>
                                    <span class="block text-[11px] text-slate-400 mt-0.5">
                                        <?= date('d M Y, H:i', strtotime($o['created_at'])) ?>
                                    </span>
                                </td>

                                <!-- Roblox Avatar & Username -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="<?= htmlspecialchars($o['roblox_avatar_url']) ?>" 
                                             alt="" 
                                             class="w-9 h-9 rounded-full border border-slate-200 bg-slate-100 object-cover shadow-2xs flex-shrink-0"
                                             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($o['roblox_username']) ?>&background=2563eb&color=fff'">
                                        <div>
                                            <span class="font-extrabold text-slate-900 block"><?= htmlspecialchars($o['roblox_username']) ?></span>
                                            <button type="button" 
                                                    onclick="navigator.clipboard.writeText('<?= addslashes($o['roblox_username']) ?>'); alert('Username disalin: <?= addslashes($o['roblox_username']) ?>')" 
                                                    class="text-[10px] font-bold text-blue-600 hover:underline flex items-center gap-1 mt-0.5">
                                                <i class="fa-regular fa-copy"></i> Salin
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                <!-- Produk & Kategori -->
                                <td class="py-4 px-4 max-w-[220px]">
                                    <span class="inline-block text-[10px] font-extrabold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md mb-0.5 uppercase tracking-wider">
                                        <?= htmlspecialchars($o['category_name'] ?? 'Roblox') ?>
                                    </span>
                                    <span class="font-bold text-slate-800 block truncate" title="<?= htmlspecialchars($o['product_name']) ?>">
                                        <?= htmlspecialchars($o['product_name']) ?>
                                    </span>
                                </td>

                                <!-- Total Harga -->
                                <td class="py-4 px-4">
                                    <span class="font-black text-slate-900 text-sm">
                                        Rp <?= number_format($o['price'], 0, ',', '.') ?>
                                    </span>
                                </td>

                                <!-- Live Chat Button -->
                                <td class="py-4 px-4">
                                    <button type="button" 
                                            onclick="openChatDockForInvoice('<?= htmlspecialchars($o['invoice_number']) ?>')" 
                                            class="py-1.5 px-3 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs active:scale-95">
                                        <i class="fa-solid fa-comments text-xs"></i>
                                        <span>Chat</span>
                                        <?php if (!empty($o['unread_chat_count']) && $o['unread_chat_count'] > 0): ?>
                                            <span class="w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center animate-pulse">
                                                <?= $o['unread_chat_count'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </button>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4">
                                    <?php if ($o['status'] === 'PENDING'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu Bayar
                                        </span>
                                    <?php elseif ($o['status'] === 'PAID'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Sudah Dibayar
                                        </span>
                                    <?php elseif ($o['status'] === 'PROCESSING'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-spin"></span> Sedang Dikirim
                                        </span>
                                    <?php elseif ($o['status'] === 'SUCCESS'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Dibatalkan
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Action Button -->
                                <td class="py-4 px-4 sm:px-6 text-right">
                                    <button type="button" 
                                            onclick="openEditOrderModal(<?= htmlspecialchars(json_encode($o)) ?>)" 
                                            class="py-1.5 px-3 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition shadow-2xs active:scale-95">
                                        <i class="fa-solid fa-pen-to-square text-blue-500 mr-1"></i> Update
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

            </div>

        </div>
    </main>

    <!-- Modal Update Status Pesanan -->
    <div id="orderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden animate-in fade-in duration-150">
        <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base">Update Status Transaksi</h3>
                </div>
                <button type="button" onclick="closeOrderModal()" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/admin/orders/update" method="POST" class="p-5 space-y-4">
                <input type="hidden" name="order_id" id="editOrderId">

                <div class="p-3.5 bg-slate-50 rounded-2xl text-xs space-y-1.5 border border-slate-200/80">
                    <div class="text-slate-500">Invoice: <strong id="editOrderInvoice" class="text-blue-600 font-mono font-bold"></strong></div>
                    <div class="text-slate-500">Pembeli: <strong id="editOrderBuyer" class="text-slate-900 font-bold"></strong></div>
                    <div class="text-slate-500">Produk: <strong id="editOrderProduct" class="text-slate-900 font-bold"></strong></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ubah Status Pengiriman</label>
                    <select name="status" id="editOrderStatus" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                        <option value="PENDING">Menunggu Pembayaran (PENDING)</option>
                        <option value="PAID">Sudah Dibayar (PAID)</option>
                        <option value="PROCESSING">Sedang Diproses Pengiriman (PROCESSING)</option>
                        <option value="SUCCESS">Pesanan Selesai (SUCCESS)</option>
                        <option value="CANCELLED">Dibatalkan (CANCELLED)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Data Akun Roblox (Khusus Akun Game)
                    </label>
                    <p class="text-[11px] text-slate-400 mb-2">Jika produk ini berupa Akun, masukkan Username & Password akun di sini. Pembeli dapat melihatnya otomatis di invoice setelah status 'SUCCESS'.</p>
                    <textarea name="account_data" 
                              id="editOrderAccountData" 
                              rows="3" 
                              placeholder="Username: roblox_account&#10;Password: password123&#10;Catatan: Langsung ganti password & verifikasi email ya kak!"
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white resize-none"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2.5 border-t border-slate-100">
                    <button type="button" onclick="closeOrderModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
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
    function openEditOrderModal(order) {
        document.getElementById('editOrderId').value = order.id;
        document.getElementById('editOrderInvoice').innerText = order.invoice_number;
        document.getElementById('editOrderBuyer').innerText = order.roblox_username;
        document.getElementById('editOrderProduct').innerText = order.product_name;
        document.getElementById('editOrderStatus').value = order.status;
        document.getElementById('editOrderAccountData').value = order.account_data || '';
        document.getElementById('orderModal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }
    </script>
</body>
</html>
