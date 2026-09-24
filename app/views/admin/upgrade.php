<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Sistem (Upgrade) - Seller Center ItemPedia</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .terminal-scroll::-webkit-scrollbar { width: 6px; }
        .terminal-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        .terminal-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
    </style>
</head>
<body class="bg-[#f0f2f5] dark:bg-[#081220] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col antialiased selection:bg-blue-100 dark:selection:bg-blue-900 selection:text-blue-700 dark:selection:text-blue-200">

    <?php 
    $activeMenu = 'upgrade';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 flex-grow">

            <!-- Page Header: Title & Quick Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-[#0c1e33] p-5 sm:p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white flex items-center justify-center shadow-md shadow-sky-500/20">
                            <i class="fa-solid fa-cloud-arrow-down text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Pembaruan Sistem (Auto Upgrade)</h1>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Tarik pembaruan & fitur terbaru langsung dari repositori GitHub resmi</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="https://github.com/<?= htmlspecialchars($versionInfo['repo'] ?? 'Theseadev/ItemPedia') ?>" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold transition flex items-center gap-1.5 border border-slate-200 dark:border-slate-700">
                        <i class="fa-brands fa-github text-sm"></i>
                        <span>Buka GitHub</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 dark:text-slate-500"></i>
                    </a>
                    <button type="button" onclick="checkSystemUpdate(true)" id="btnCheckUpdate" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-sky-500/25 active:scale-95 cursor-pointer">
                        <i id="iconCheckUpdate" class="fa-solid fa-arrows-rotate text-xs"></i>
                        <span id="textCheckUpdate">Cek Pembaruan</span>
                    </button>
                </div>
            </div>

            <!-- Top 3 Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                
                <!-- Card 1: Versi Terpasang Saat Ini -->
                <div class="bg-white dark:bg-[#0c1e33] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Versi Terpasang</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800">v<?= htmlspecialchars($versionInfo['version'] ?? '1.1.0') ?></span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-slate-900 dark:text-white font-mono tracking-tight" id="badgeCurrentCommit"><?= htmlspecialchars($versionInfo['commit_hash'] ?? '232a5bf') ?></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">branch: <?= htmlspecialchars($versionInfo['branch'] ?? 'main') ?></span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between">
                        <span>Pembaruan terakhir:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300" id="textLastUpdated"><?= htmlspecialchars($versionInfo['last_updated'] ?? date('Y-m-d H:i')) ?></span>
                    </div>
                </div>

                <!-- Card 2: Status GitHub Remote -->
                <div class="bg-white dark:bg-[#0c1e33] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status GitHub Remote</span>
                        <span id="pillRemoteStatus" class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span id="pillRemoteText">Memeriksa...</span>
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-slate-900 dark:text-white font-mono tracking-tight" id="badgeLatestCommit">-</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">GitHub main</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between">
                        <span>Repository:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[170px]"><?= htmlspecialchars($versionInfo['repo'] ?? 'Theseadev/ItemPedia') ?></span>
                    </div>
                </div>

                <!-- Card 3: Mode Pembaruan & Lingkungan -->
                <div class="bg-white dark:bg-[#0c1e33] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Engine Pembaruan</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <?= !empty($versionInfo['git_available']) ? 'Git CLI Engine' : 'ZIP Archive Engine' ?>
                        </span>
                    </div>
                    <div class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        <?= !empty($versionInfo['git_available']) 
                            ? '<span class="font-bold text-emerald-600 dark:text-emerald-400"><i class="fa-solid fa-bolt mr-1"></i> Mode Git Fast-Sync Aktif</span>. Mendukung fetch & pull langsung dari Git.' 
                            : '<span class="font-bold text-sky-600 dark:text-sky-400"><i class="fa-solid fa-box mr-1"></i> Mode Universal ZIP Aktif</span>. Cocok untuk cPanel / shared hosting tanpa Git CLI.' ?>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between">
                        <span>Status Hak Tulis:</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Writable (Aman)
                        </span>
                    </div>
                </div>

            </div>

            <!-- Action Banner: Update Available or Up to Date -->
            <div id="upgradeActionBox" class="p-6 rounded-2xl border transition-all duration-300 bg-white dark:bg-[#0c1e33] border-slate-200/80 dark:border-slate-800 shadow-xs">
                <!-- Loading State placeholder -->
                <div id="upgradeBoxLoading" class="py-4 flex items-center justify-center gap-3 text-slate-500 dark:text-slate-400 text-sm font-semibold">
                    <i class="fa-solid fa-spinner fa-spin text-sky-500 text-lg"></i>
                    <span>Sedang memeriksa pembaruan dari GitHub...</span>
                </div>

                <!-- Update Available State (Hidden initially) -->
                <div id="upgradeBoxAvailable" class="hidden space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 rounded-xl bg-gradient-to-r from-sky-500/10 via-blue-500/10 to-indigo-500/10 dark:from-sky-950/40 dark:via-blue-950/40 dark:to-indigo-950/40 border border-sky-200/70 dark:border-sky-800/60">
                        <div class="flex items-start sm:items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-sky-500 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-sky-500/30 text-xl">
                                <i class="fa-solid fa-rocket animate-bounce"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900 dark:text-white text-base sm:text-lg tracking-tight">Pembaruan Sistem Tersedia!</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5" id="textUpdateAvailableMsg">Terdapat perubahan terbaru di GitHub yang siap dipasang ke hosting Anda.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="startUpgradeProcess()" id="btnStartUpgrade" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-sky-500/30 active:scale-95 transition flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-cloud-arrow-down"></i>
                                <span>Upgrade Sekarang (1-Click)</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Up to Date State (Hidden initially) -->
                <div id="upgradeBoxUpToDate" class="hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200/70 dark:border-emerald-800/60">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-500/20 text-lg">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-emerald-950 dark:text-emerald-200 text-sm sm:text-base tracking-tight">Website Anda Sudah Menggunakan Versi Terbaru!</h3>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">Sistem ItemPedia telah sinkron dengan commit terbaru repositori GitHub.</p>
                        </div>
                    </div>
                    <button type="button" onclick="startUpgradeProcess(true)" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-emerald-100 dark:hover:bg-slate-700 text-emerald-800 dark:text-emerald-300 text-xs font-bold border border-emerald-300 dark:border-emerald-700 transition flex items-center justify-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                        <span>Paksa Re-Sync / Update Ulang</span>
                    </button>
                </div>

                <!-- Error State (Hidden initially) -->
                <div id="upgradeBoxError" class="hidden p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-triangle-exclamation text-base text-rose-600 dark:text-rose-400"></i>
                        <span id="textUpgradeError">Gagal terhubung ke GitHub API.</span>
                    </div>
                    <button type="button" onclick="checkSystemUpdate(true)" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-700 font-bold hover:bg-rose-100 dark:hover:bg-slate-700 transition">
                        Coba Lagi
                    </button>
                </div>
            </div>

            <!-- Two Columns: Changelog & Security Guarantees -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left 2 Cols: Commit Timeline / Changelog -->
                <div class="lg:col-span-2 bg-white dark:bg-[#0c1e33] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-code-commit text-slate-400 dark:text-slate-500"></i>
                            <h2 class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base">Riwayat Commit & Pembaruan GitHub</h2>
                        </div>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">8 Commit Terakhir</span>
                    </div>

                    <div class="p-5 flex-grow" id="changelogContainer">
                        <div class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
                            <i class="fa-solid fa-spinner fa-spin text-sky-500 text-base mb-2"></i>
                            <p>Memuat riwayat pembaruan...</p>
                        </div>
                    </div>
                </div>

                <!-- Right 1 Col: Data Safety & Guarantees -->
                <div class="space-y-4">
                    <div class="bg-gradient-to-br from-[#0c1e33] to-[#162e4a] text-white p-5 rounded-2xl border border-white/10 shadow-lg space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/20 border border-sky-400/30 text-sky-400 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <h3 class="font-black text-sm tracking-tight">Garansi Keamanan Data 100%</h3>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Proses upgrade dirancang aman dan tidak akan merusak data toko Anda. Berkas berikut diproteksi penuh:
                        </p>
                        <ul class="space-y-2.5 text-xs text-slate-200">
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                                <span><strong>Database SQLite:</strong> Semua transaksi, pesanan, data akun, dan produk tersimpan utuh.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                                <span><strong>Media Upload:</strong> Foto bukti transfer pembeli di folder <code class="bg-white/10 px-1 py-0.5 rounded text-[11px] font-mono">public/uploads/</code> tidak dihapus.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                                <span><strong>Konfigurasi .env:</strong> Kredensial payment gateway & admin tetap terjaga.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                                <span><strong>Auto-Migration:</strong> Skema tabel database otomatis diperbarui otomatis.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Host & System Advice -->
                    <div class="bg-white dark:bg-[#0c1e33] p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3">
                        <h4 class="font-extrabold text-slate-900 dark:text-white text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-sky-500"></i>
                            <span>Panduan Alur Kerja</span>
                        </h4>
                        <ol class="space-y-2 text-xs text-slate-600 dark:text-slate-300 list-decimal list-inside leading-relaxed">
                            <li>Lakukan perubahan kode di lokal / komputer Anda.</li>
                            <li>Push commit terbaru ke repository GitHub <span class="font-mono text-[11px] bg-slate-100 dark:bg-slate-800 px-1 py-0.5 rounded text-slate-800 dark:text-slate-200">Theseadev/ItemPedia</span>.</li>
                            <li>Buka halaman ini di website hosting Anda dan klik <strong>Upgrade Sekarang</strong>.</li>
                            <li>Website hosting akan otomatis sinkron & fitur terbaru langsung aktif!</li>
                        </ol>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Real-time Upgrade Progress Modal -->
    <div id="upgradeModal" class="hidden fixed inset-0 bg-black/75 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-[#0f172a] border border-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full text-slate-100 overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in-95">
            
            <!-- Modal Header -->
            <div class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between bg-slate-900/80">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-500/20 border border-sky-400/30 text-sky-400 flex items-center justify-center">
                        <i class="fa-solid fa-terminal text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-sm sm:text-base text-white">ItemPedia Upgrade Terminal</h3>
                        <p class="text-xs text-slate-400" id="terminalStatusSubtitle">Sedang memperbarui sistem...</p>
                    </div>
                </div>
                <div id="terminalStatusBadge" class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-sky-500/20 text-sky-300 border border-sky-400/30 flex items-center gap-1.5">
                    <i class="fa-solid fa-spinner fa-spin text-xs"></i>
                    <span>PROCESSING</span>
                </div>
            </div>

            <!-- Terminal Output Area -->
            <div class="p-4 sm:p-5 bg-black/50 font-mono text-xs overflow-y-auto flex-grow terminal-scroll space-y-2 min-h-[250px] max-h-[360px]" id="terminalOutput">
                <div class="text-slate-400">=== MEMULAI SESI PEMBARUAN SISTEM ITEMPEDIA ===</div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/80 flex items-center justify-between">
                <div class="text-[11px] text-slate-400" id="terminalFooterInfo">
                    Jangan tutup browser saat proses berlangsung...
                </div>
                <button type="button" id="btnTerminalFinish" onclick="closeUpgradeModal(true)" class="hidden px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-extrabold transition shadow-lg shadow-emerald-500/25 cursor-pointer">
                    <i class="fa-solid fa-check mr-1.5"></i> Selesai & Muat Ulang Halaman
                </button>
            </div>

        </div>
    </div>

    <!-- Script for Live Check & Upgrade Handling -->
    <script>
    let remoteUpdateData = null;

    document.addEventListener('DOMContentLoaded', () => {
        checkSystemUpdate(false);
    });

    async function checkSystemUpdate(isManual = false) {
        const btn = document.getElementById('btnCheckUpdate');
        const icon = document.getElementById('iconCheckUpdate');
        const text = document.getElementById('textCheckUpdate');
        const boxLoading = document.getElementById('upgradeBoxLoading');
        const boxAvailable = document.getElementById('upgradeBoxAvailable');
        const boxUpToDate = document.getElementById('upgradeBoxUpToDate');
        const boxError = document.getElementById('upgradeBoxError');
        const pillRemote = document.getElementById('pillRemoteStatus');
        const pillRemoteText = document.getElementById('pillRemoteText');
        const badgeLatest = document.getElementById('badgeLatestCommit');
        const changelogContainer = document.getElementById('changelogContainer');

        if (icon) icon.classList.add('fa-spin');
        if (text) text.innerText = 'Memeriksa...';
        if (btn) btn.disabled = true;

        if (isManual) {
            boxLoading.classList.remove('hidden');
            boxAvailable.classList.add('hidden');
            boxUpToDate.classList.add('hidden');
            boxError.classList.add('hidden');
        }

        try {
            const res = await fetch('/api/admin/upgrade/check', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });
            const data = await res.json();
            remoteUpdateData = data;

            boxLoading.classList.add('hidden');

            if (!data.success) {
                boxError.classList.remove('hidden');
                document.getElementById('textUpgradeError').innerText = data.error || 'Gagal memeriksa pembaruan dari GitHub.';
                pillRemote.className = 'px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 flex items-center gap-1';
                pillRemoteText.innerText = 'Gagal Cek';
                changelogContainer.innerHTML = `<div class="py-8 text-center text-rose-500 text-xs font-semibold">${data.error || 'Gagal memuat riwayat GitHub'}</div>`;
                return;
            }

            // Update badge commit remote
            badgeLatest.innerText = data.latest_commit || '-';
            
            // Render Changelog
            renderChangelog(data.changelog || []);

            if (data.has_update) {
                // Ada Update
                boxAvailable.classList.remove('hidden');
                boxUpToDate.classList.add('hidden');
                boxError.classList.add('hidden');

                document.getElementById('textUpdateAvailableMsg').innerText = `Versi baru: "${data.latest_message}" (${data.latest_date})`;

                pillRemote.className = 'px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800 flex items-center gap-1';
                pillRemoteText.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span> Ada Update!';
            } else {
                // Up to Date
                boxAvailable.classList.add('hidden');
                boxUpToDate.classList.remove('hidden');
                boxError.classList.add('hidden');

                pillRemote.className = 'px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center gap-1';
                pillRemoteText.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i> Terkini';
            }

        } catch (err) {
            boxLoading.classList.add('hidden');
            boxError.classList.remove('hidden');
            document.getElementById('textUpgradeError').innerText = 'Kesalahan jaringan atau server saat memeriksa GitHub.';
        } finally {
            if (icon) icon.classList.remove('fa-spin');
            if (text) text.innerText = 'Cek Pembaruan';
            if (btn) btn.disabled = false;
        }
    }

    function renderChangelog(commits) {
        const container = document.getElementById('changelogContainer');
        if (!commits || commits.length === 0) {
            container.innerHTML = '<div class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">Tidak ada riwayat commit ditemukan.</div>';
            return;
        }

        let html = '<div class="relative border-l-2 border-slate-100 dark:border-slate-800 ml-3 space-y-4">';
        commits.forEach((c, idx) => {
            const isCurrent = c.is_current;
            html += `
                <div class="relative pl-6 group">
                    <span class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full border-2 ${isCurrent ? 'bg-sky-500 border-white dark:border-slate-900 ring-2 ring-sky-300 dark:ring-sky-700' : 'bg-slate-300 dark:bg-slate-700 border-white dark:border-slate-900'}"></span>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-xs text-slate-900 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition">${escapeHtml(c.message)}</span>
                                ${isCurrent ? '<span class="px-1.5 py-0.2 rounded text-[10px] font-black bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800">AKTIF</span>' : ''}
                            </div>
                            <div class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-2">
                                <span><i class="fa-regular fa-user mr-1"></i>${escapeHtml(c.author)}</span>
                                <span>&bull;</span>
                                <span><i class="fa-regular fa-clock mr-1"></i>${escapeHtml(c.date)}</span>
                            </div>
                        </div>
                        <a href="${c.url}" target="_blank" class="self-start sm:self-auto font-mono text-[11px] px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold border border-slate-200 dark:border-slate-700 transition">
                            ${c.sha} <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ml-0.5 text-slate-400 dark:text-slate-500"></i>
                        </a>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    function startUpgradeProcess(isForce = false) {
        const modal = document.getElementById('upgradeModal');
        const terminal = document.getElementById('terminalOutput');
        const badge = document.getElementById('terminalStatusBadge');
        const finishBtn = document.getElementById('btnTerminalFinish');
        const subtitle = document.getElementById('terminalStatusSubtitle');
        const footerInfo = document.getElementById('terminalFooterInfo');

        modal.classList.remove('hidden');
        terminal.innerHTML = '<div class="text-slate-400">=== MEMULAI SESI PEMBARUAN SISTEM ITEMPEDIA ===</div>';
        finishBtn.classList.add('hidden');
        badge.className = 'px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-sky-500/20 text-sky-300 border border-sky-400/30 flex items-center gap-1.5';
        badge.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i><span>PROCESSING</span>';
        subtitle.innerText = 'Sedang memperbarui berkas sistem dari GitHub...';
        footerInfo.innerText = 'Jangan tutup browser saat proses berlangsung...';

        appendTerminalLog('Menghubungkan ke endpoint updater...');

        fetch('/api/admin/upgrade/execute', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mode: 'auto', force: isForce })
        })
        .then(res => res.json())
        .then(data => {
            if (data.logs && Array.isArray(data.logs)) {
                data.logs.forEach(log => appendTerminalLog(log));
            }

            if (data.success) {
                badge.className = 'px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-500/20 text-emerald-400 border border-emerald-400/30 flex items-center gap-1.5';
                badge.innerHTML = '<i class="fa-solid fa-circle-check text-xs"></i><span>BERHASIL</span>';
                subtitle.innerText = 'Pembaruan selesai sukses!';
                footerInfo.innerText = 'Sistem telah berhasil diperbarui ke versi terbaru.';
                finishBtn.classList.remove('hidden');
            } else {
                badge.className = 'px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-500/20 text-rose-400 border border-rose-400/30 flex items-center gap-1.5';
                badge.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-xs"></i><span>GAGAL</span>';
                subtitle.innerText = 'Terjadi kesalahan saat upgrade.';
                appendTerminalLog('❌ ERROR: ' + (data.error || 'Terjadi kegagalan saat proses pembaruan.'));
                finishBtn.innerText = 'Tutup & Kembali';
                finishBtn.className = 'px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 text-white text-xs font-extrabold transition cursor-pointer';
                finishBtn.classList.remove('hidden');
            }
        })
        .catch(err => {
            badge.className = 'px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-500/20 text-rose-400 border border-rose-400/30 flex items-center gap-1.5';
            badge.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-xs"></i><span>ERROR</span>';
            appendTerminalLog('❌ Jaringan terputus / Timeout: ' + err.message);
            finishBtn.innerText = 'Tutup';
            finishBtn.className = 'px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 text-white text-xs font-extrabold transition cursor-pointer';
            finishBtn.classList.remove('hidden');
        });
    }

    function appendTerminalLog(text) {
        const terminal = document.getElementById('terminalOutput');
        const div = document.createElement('div');
        div.className = 'text-slate-200 leading-relaxed';
        
        if (text.includes('✅')) {
            div.className = 'text-emerald-400 font-semibold';
        } else if (text.includes('🚀') || text.includes('🌐') || text.includes('🛡️') || text.includes('🗄️') || text.includes('📝')) {
            div.className = 'text-sky-300 font-bold mt-1';
        } else if (text.includes('⚠️')) {
            div.className = 'text-amber-300';
        } else if (text.includes('❌') || text.includes('ERROR')) {
            div.className = 'text-rose-400 font-bold';
        } else if (text.includes('🎉')) {
            div.className = 'text-emerald-300 font-black text-sm mt-2';
        }

        div.innerText = text;
        terminal.appendChild(div);
        terminal.scrollTop = terminal.scrollHeight;
    }

    function closeUpgradeModal(reload = false) {
        document.getElementById('upgradeModal').classList.add('hidden');
        if (reload) {
            window.location.reload();
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    </script>
</body>
</html>
