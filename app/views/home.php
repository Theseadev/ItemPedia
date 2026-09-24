<?php
ob_start();
?>

<!-- Hero Section: Premium Gaming Marketplace Banner Slider & Fast Search -->
<section class="relative pt-3 pb-4 sm:pt-6 sm:pb-8 overflow-hidden">
    <!-- Ambient Pastel & Cyan Glow Orbs -->
    <div class="absolute top-4 left-1/4 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-4 right-1/4 w-80 h-80 bg-indigo-100/40 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Wide Gaming Hero Promo Slider (Itemku / Lapakgaming Style) -->
        <div class="relative">
            <!-- Glowing Aura Backdrop -->
            <div class="absolute -inset-1.5 bg-gradient-to-r from-sky-400/30 via-indigo-500/25 to-blue-500/30 rounded-3xl blur-xl opacity-70 pointer-events-none"></div>

            <div id="heroPromoCarousel" class="relative bg-slate-900 rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-sky-200/80 shadow-soft-lg group select-none">
                
                <!-- Top Slim Auto-Slide Glowing Progress Bar -->
                <div class="absolute top-0 left-0 right-0 h-1 sm:h-1.5 bg-white/10 z-30 overflow-hidden">
                    <div id="heroProgressBar" class="h-full bg-gradient-to-r from-amber-400 via-sky-400 to-indigo-400 w-0 transition-all duration-75"></div>
                </div>

                <!-- Navigation Arrow Buttons (Desktop Hover) -->
                <button type="button" 
                        onclick="heroPrevSlide()" 
                        class="hidden md:flex absolute left-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-slate-900/60 hover:bg-slate-900/90 text-white/80 hover:text-white border border-white/20 items-center justify-center backdrop-blur-md transition-all active:scale-95 shadow-lg opacity-0 group-hover:opacity-100 cursor-pointer"
                        title="Banner Sebelumnya">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button type="button" 
                        onclick="heroNextSlide()" 
                        class="hidden md:flex absolute right-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-slate-900/60 hover:bg-slate-900/90 text-white/80 hover:text-white border border-white/20 items-center justify-center backdrop-blur-md transition-all active:scale-95 shadow-lg opacity-0 group-hover:opacity-100 cursor-pointer"
                        title="Banner Berikutnya">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>

                <!-- Slides Container -->
                <div class="relative overflow-hidden min-h-[175px] sm:min-h-[240px] md:min-h-[280px] lg:min-h-[300px]">
                    
                    <!-- SLIDE 1: BUILD A ZOO PETS EVENT -->
                    <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out flex flex-col justify-center p-3.5 pt-3 pb-6 sm:p-8 md:p-10 text-white opacity-100 z-10" 
                         data-index="0"
                         style="background: linear-gradient(90deg, rgba(15, 23, 42, 0.94) 0%, rgba(15, 23, 42, 0.82) 60%, rgba(15, 23, 42, 0.5) 100%), url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;">
                        
                        <div class="relative z-10 max-w-3xl space-y-1.5 sm:space-y-3.5 text-left">
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-nowrap sm:flex-wrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-gradient-to-r from-rose-500 to-amber-500 text-white font-black text-[9px] sm:text-xs uppercase tracking-wider shadow-sm flex-shrink-0">
                                    <i class="fa-solid fa-fire text-amber-200"></i> PROMO SPESIAL
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-white/15 border border-white/25 text-sky-200 font-extrabold text-[9px] sm:text-xs backdrop-blur-md flex-shrink-0">
                                    🐾 Build A Zoo
                                </span>
                            </div>

                            <h2 class="text-[13px] sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-sm truncate sm:whitespace-normal">
                                PET LANGKA &amp; MEWAH BUILD A ZOO
                            </h2>

                            <p class="text-[10px] sm:text-sm md:text-base text-slate-200 font-medium leading-tight sm:leading-relaxed max-w-2xl line-clamp-1 sm:line-clamp-2">
                                Mucy ($2.753M/s), Chomp, Aurefang ready stok. Fast trade via VIP Server 3–5 menit langsung masuk inventori.
                            </p>

                            <!-- Badges -->
                            <div class="flex items-center gap-1.5 sm:gap-2.5 flex-nowrap sm:flex-wrap pt-0.5 text-[9px] sm:text-xs font-bold overflow-hidden">
                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg bg-sky-500/25 border border-sky-400/40 text-sky-200 flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-bolt text-amber-300"></i> Trade 3–5 Mnt
                                </span>
                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg bg-emerald-500/25 border border-emerald-400/40 text-emerald-200 flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-shield-halved text-emerald-300"></i> 100% Anti Hack
                                </span>
                                <span class="hidden sm:inline-flex px-3 py-1 rounded-lg bg-amber-500/25 border border-amber-400/40 text-amber-200 items-center gap-1.5 flex-shrink-0">
                                    <i class="fa-solid fa-tag text-amber-300"></i> Mulai Rp 28.000
                                </span>
                            </div>

                            <!-- CTA Buttons (Single Line on Mobile) -->
                            <div class="flex items-center gap-2 sm:gap-2.5 pt-0.5 sm:pt-2">
                                <a href="/?game=Build+A+Zoo#katalog" class="btn-shimmer inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-6 py-1.5 sm:py-2.5 bg-gradient-to-r from-amber-400 via-amber-500 to-orange-500 hover:from-amber-300 hover:to-orange-400 active:scale-95 text-slate-950 font-black text-[10px] sm:text-sm rounded-lg sm:rounded-xl shadow-md shadow-amber-500/30 transition-all whitespace-nowrap">
                                    <i class="fa-solid fa-gamepad text-[9px] sm:text-xs"></i>
                                    <span>Belanja Pet</span>
                                    <i class="fa-solid fa-arrow-right text-[8px] sm:text-[10px]"></i>
                                </a>
                                <a href="/lacak" class="inline-flex items-center gap-1.5 px-2.5 sm:px-4 py-1.5 sm:py-2.5 bg-white/15 hover:bg-white/25 text-white border border-white/30 rounded-lg sm:rounded-xl font-bold text-[10px] sm:text-sm backdrop-blur-md transition-all whitespace-nowrap">
                                    <i class="fa-solid fa-receipt text-sky-300 text-[9px] sm:text-xs"></i>
                                    <span>Lacak Pesanan</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- SLIDE 2: AKUN ROBLOX POLOSAN BERGARANSI -->
                    <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out flex flex-col justify-center p-3.5 pt-3 pb-6 sm:p-8 md:p-10 text-white opacity-0 pointer-events-none invisible z-0" 
                         data-index="1"
                         style="background: linear-gradient(90deg, rgba(15, 23, 42, 0.94) 0%, rgba(30, 27, 75, 0.85) 60%, rgba(15, 23, 42, 0.5) 100%), url('https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;">
                        
                        <div class="relative z-10 max-w-3xl space-y-1.5 sm:space-y-3.5 text-left">
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-nowrap sm:flex-wrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-black text-[9px] sm:text-xs uppercase tracking-wider shadow-sm flex-shrink-0">
                                    <i class="fa-solid fa-crown text-amber-300"></i> 100% GARANSI
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-white/15 border border-white/25 text-purple-200 font-extrabold text-[9px] sm:text-xs backdrop-blur-md flex-shrink-0">
                                    👑 Akun Polosan
                                </span>
                            </div>

                            <h2 class="text-[13px] sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-sm truncate sm:whitespace-normal">
                                AKUN ROBLOX POLOSAN SIAP PAKAI
                            </h2>

                            <p class="text-[10px] sm:text-sm md:text-base text-purple-100/90 font-medium leading-tight sm:leading-relaxed max-w-2xl line-clamp-1 sm:line-clamp-2">
                                Belum pernah diverifikasi email ataupun no HP. 100% aman permanen, bebas ganti password langsung di invoice.
                            </p>

                            <div class="flex items-center gap-1.5 sm:gap-2.5 flex-nowrap sm:flex-wrap pt-0.5 text-[9px] sm:text-xs font-bold overflow-hidden">
                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg bg-purple-500/25 border border-purple-400/40 text-purple-200 flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-lock text-emerald-300"></i> No Email/HP
                                </span>
                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg bg-emerald-500/25 border border-emerald-400/40 text-emerald-200 flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-shield-halved text-emerald-300"></i> Garansi Permanen
                                </span>
                                <span class="hidden sm:inline-flex px-3 py-1 rounded-lg bg-sky-500/25 border border-sky-400/40 text-sky-200 items-center gap-1.5 flex-shrink-0">
                                    <i class="fa-solid fa-bolt text-amber-300"></i> Data Instan
                                </span>
                            </div>

                            <!-- CTA Buttons (Single Line on Mobile) -->
                            <div class="flex items-center gap-2 sm:gap-2.5 pt-0.5 sm:pt-2">
                                <a href="/?kategori=akun-game#katalog" class="btn-shimmer inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-6 py-1.5 sm:py-2.5 bg-gradient-to-r from-purple-400 via-indigo-500 to-sky-500 hover:from-purple-300 hover:to-sky-400 active:scale-95 text-white font-black text-[10px] sm:text-sm rounded-lg sm:rounded-xl shadow-md shadow-indigo-500/30 transition-all whitespace-nowrap">
                                    <i class="fa-solid fa-user-shield text-[9px] sm:text-xs"></i>
                                    <span>Pilih Akun</span>
                                    <i class="fa-solid fa-arrow-right text-[8px] sm:text-[10px]"></i>
                                </a>
                                <a href="#katalog" class="inline-flex items-center gap-1.5 px-2.5 sm:px-4 py-1.5 sm:py-2.5 bg-white/15 hover:bg-white/25 text-white border border-white/30 rounded-lg sm:rounded-xl font-bold text-[10px] sm:text-sm backdrop-blur-md transition-all whitespace-nowrap">
                                    <i class="fa-solid fa-boxes-stacked text-purple-300 text-[9px] sm:text-xs"></i>
                                    <span>Semua Produk</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- SLIDE 3: SCAN QRIS INSTAN -->
                    <div class="carousel-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out flex flex-col justify-center p-3.5 pt-3 pb-6 sm:p-8 md:p-10 text-white opacity-0 pointer-events-none invisible z-0" 
                         data-index="2"
                         style="background: linear-gradient(90deg, rgba(6, 78, 59, 0.94) 0%, rgba(15, 23, 42, 0.85) 60%, rgba(15, 23, 42, 0.5) 100%), url('https://images.unsplash.com/photo-1538481199705-c710c4e965fc?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;">
                        
                        <div class="relative z-10 max-w-3xl space-y-1.5 sm:space-y-3.5 text-left">
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-nowrap sm:flex-wrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-black text-[9px] sm:text-xs uppercase tracking-wider shadow-sm flex-shrink-0">
                                    <i class="fa-solid fa-bolt text-amber-300"></i> PROSES INSTAN
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-white/15 border border-white/25 text-teal-200 font-extrabold text-[9px] sm:text-xs backdrop-blur-md flex-shrink-0">
                                    ⚡ QRIS Otomatis
                                </span>
                            </div>

                            <h2 class="text-[13px] sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-sm truncate sm:whitespace-normal">
                                SCAN QRIS, LUNAS DALAM 3 DETIK!
                            </h2>

                            <p class="text-[10px] sm:text-sm md:text-base text-teal-100/90 font-medium leading-tight sm:leading-relaxed max-w-2xl line-clamp-1 sm:line-clamp-2">
                                Dukung semua e-wallet (DANA, GoPay, OVO, ShopeePay) &amp; semua m-banking. Terverifikasi 100% otomatis tanpa upload bukti.
                            </p>

                            <div class="flex items-center gap-1.5 sm:gap-2.5 flex-nowrap sm:flex-wrap pt-0.5 text-[9px] sm:text-xs font-bold overflow-hidden">
                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg bg-teal-500/25 border border-teal-400/40 text-teal-200 flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-qrcode text-teal-300"></i> All E-Wallet &amp; Bank
                                </span>
                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg bg-emerald-500/25 border border-emerald-400/40 text-emerald-200 flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-circle-check text-emerald-300"></i> 3 Detik Beres
                                </span>
                                <span class="hidden sm:inline-flex px-3 py-1 rounded-lg bg-sky-500/25 border border-sky-400/40 text-sky-200 items-center gap-1.5 flex-shrink-0">
                                    <i class="fa-solid fa-shield-check text-sky-300"></i> Enkripsi 256-Bit
                                </span>
                            </div>

                            <!-- CTA Buttons (Single Line on Mobile) -->
                            <div class="flex items-center gap-2 sm:gap-2.5 pt-0.5 sm:pt-2">
                                <a href="#katalog" class="btn-shimmer inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-6 py-1.5 sm:py-2.5 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-500 hover:from-emerald-300 hover:to-cyan-400 active:scale-95 text-slate-950 font-black text-[10px] sm:text-sm rounded-lg sm:rounded-xl shadow-md shadow-teal-500/30 transition-all whitespace-nowrap">
                                    <i class="fa-solid fa-cart-shopping text-[9px] sm:text-xs"></i>
                                    <span>Lihat Katalog</span>
                                    <i class="fa-solid fa-arrow-down text-[8px] sm:text-[10px]"></i>
                                </a>
                                <a href="/lacak" class="inline-flex items-center gap-1.5 px-2.5 sm:px-4 py-1.5 sm:py-2.5 bg-white/15 hover:bg-white/25 text-white border border-white/30 rounded-lg sm:rounded-xl font-bold text-[10px] sm:text-sm backdrop-blur-md transition-all whitespace-nowrap">
                                    <i class="fa-solid fa-magnifying-glass text-teal-300 text-[9px] sm:text-xs"></i>
                                    <span>Lacak Invoice</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bottom Indicator Dots -->
                <div class="absolute bottom-1.5 sm:bottom-2.5 left-1/2 -translate-x-1/2 z-30 flex items-center gap-1.5 sm:gap-2">
                    <button type="button" onclick="heroGoToSlide(0)" class="hero-indicator w-5 sm:w-8 h-1 sm:h-1.5 rounded-full bg-amber-400 shadow-xs transition-all duration-300 cursor-pointer" aria-label="Slide 1"></button>
                    <button type="button" onclick="heroGoToSlide(1)" class="hero-indicator w-1.5 sm:w-2.5 h-1 sm:h-1.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300 cursor-pointer" aria-label="Slide 2"></button>
                    <button type="button" onclick="heroGoToSlide(2)" class="hero-indicator w-1.5 sm:w-2.5 h-1 sm:h-1.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300 cursor-pointer" aria-label="Slide 3"></button>
                </div>

            </div>
        </div>

        <!-- Integrated Search Bar & Quick Tag Bar (Itemku style toolbar) -->
        <div class="mt-3 sm:mt-5 bg-white/95 dark:bg-[#0c1e33]/95 backdrop-blur-md rounded-2xl sm:rounded-3xl border-2 border-sky-100 dark:border-slate-800 shadow-card p-3 sm:p-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-3">
                <!-- Search Input Form -->
                <form action="/#katalog" method="GET" class="w-full md:w-1/2 relative">
                    <input type="text" 
                           name="q" 
                           value="<?= htmlspecialchars($searchQuery) ?>" 
                           placeholder="Cari item, pet, nama game, atau akun Roblox..." 
                           class="w-full pl-9 pr-20 sm:pl-11 sm:pr-24 py-2 sm:py-2.5 bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-200 dark:border-slate-700 hover:border-sky-300 dark:hover:border-sky-500 focus:border-sky-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 transition-all outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-sky-500 text-xs sm:text-sm"></i>
                    <button type="submit" class="absolute right-1.5 sm:right-2 top-1/2 -translate-y-1/2 px-3 sm:px-4 py-1 sm:py-1.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 active:scale-95 text-white text-[11px] sm:text-xs font-black rounded-lg sm:rounded-xl transition-all shadow-2xs cursor-pointer">
                        Cari
                    </button>
                </form>

                <!-- Quick Tags -->
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 w-full md:w-auto">
                    <span class="text-slate-400 dark:text-slate-500 font-extrabold flex items-center gap-1">
                        <i class="fa-solid fa-fire text-amber-500"></i> Populer:
                    </span>
                    <a href="/?game=Build+A+Zoo#katalog" class="px-2.5 py-1 bg-sky-50 dark:bg-slate-800 hover:bg-sky-100 dark:hover:bg-slate-700 text-sky-700 dark:text-sky-300 border border-sky-200/80 dark:border-slate-700 rounded-lg transition-colors">🐾 Build A Zoo</a>
                    <a href="/?kategori=akun-game#katalog" class="px-2.5 py-1 bg-indigo-50 dark:bg-slate-800 hover:bg-indigo-100 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-slate-700 rounded-lg transition-colors">👑 Akun Polosan</a>
                    <a href="/?game=Chop+Your+Tree#katalog" class="px-2.5 py-1 bg-amber-50 dark:bg-slate-800 hover:bg-amber-100 dark:hover:bg-slate-700 text-amber-800 dark:text-amber-300 border border-amber-200/80 dark:border-slate-700 rounded-lg transition-colors">🪓 Chop Your Tree</a>
                    <a href="/?game=Catch+and+Tame#katalog" class="px-2.5 py-1 bg-emerald-50 dark:bg-slate-800 hover:bg-emerald-100 dark:hover:bg-slate-700 text-emerald-800 dark:text-emerald-300 border border-emerald-200/80 dark:border-slate-700 rounded-lg transition-colors">⭐ Catch &amp; Tame</a>
                </div>
            </div>
        </div>

        <!-- Live Transaksi Pembelian Real-Time Marquee Stream (Diatas 4 Card Highlight) -->
        <div class="pt-4 sm:pt-6 pb-1">
            <div class="bg-gradient-to-r from-sky-50/90 via-white to-blue-50/70 dark:from-[#0b1b30] dark:via-[#0c1e33] dark:to-[#0f243d] rounded-2xl sm:rounded-3xl border-2 border-sky-100 dark:border-slate-800 shadow-card p-3 sm:p-4 overflow-hidden relative group">
                
                <!-- Header Live Pulse Badge -->
                <div class="flex flex-wrap items-center justify-between gap-2 px-1 mb-2.5 sm:mb-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-[11px] font-black uppercase tracking-wider bg-rose-500 text-white shadow-2xs">
                            <i class="fa-solid fa-bolt text-[9px] text-amber-300"></i> LIVE PEMBELIAN
                        </span>
                        <div class="flex items-center gap-1.5 text-xs font-black text-slate-800 dark:text-slate-100">
                            <span class="relative flex h-2 w-2 sm:h-2.5 sm:w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 sm:h-2.5 sm:w-2.5 bg-emerald-500"></span>
                            </span>
                            <span class="hidden sm:inline text-slate-600 dark:text-slate-300 font-bold">Aktivitas Transaksi Pembeli Real-Time</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold text-sky-700 dark:text-sky-300 bg-sky-100/70 dark:bg-sky-950/70 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-lg sm:rounded-xl border border-sky-200/50 dark:border-sky-800/50">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                        <span>100% Otomatis</span>
                    </div>
                </div>

                <!-- Horizontal Infinite Smooth Marquee Track -->
                <div class="relative overflow-hidden w-full mask-linear-fade">
                    <div class="flex items-center gap-2.5 sm:gap-3 w-max animate-marquee-live py-0.5">
                        <?php 
                        $itemsToLoop = !empty($recentPurchases) ? $recentPurchases : [];
                        $loopedFeed = array_merge($itemsToLoop, $itemsToLoop);
                        ?>
                        <?php foreach ($loopedFeed as $lp): ?>
                        <div class="flex items-center gap-2.5 bg-white dark:bg-[#0f172a] hover:bg-sky-50/40 dark:hover:bg-slate-800/80 border border-slate-200/90 dark:border-slate-800 hover:border-sky-300 dark:hover:border-sky-500 shadow-2xs hover:shadow-xs transition-all duration-200 rounded-xl sm:rounded-2xl px-2.5 py-1.5 sm:px-3 sm:py-2 min-w-[230px] sm:min-w-[270px] flex-shrink-0">
                            <!-- Avatar -->
                            <div class="relative w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl overflow-hidden bg-sky-50 dark:bg-slate-800 border border-sky-100 dark:border-slate-700 flex-shrink-0">
                                <img src="<?= htmlspecialchars($lp['avatar_url']) ?>" 
                                     alt="<?= htmlspecialchars($lp['username']) ?>"
                                     class="w-full h-full object-cover"
                                     loading="lazy"
                                     onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($lp['username']) ?>&background=0ea5e9&color=fff'">
                                <div class="absolute bottom-0 right-0 w-2 h-2 sm:w-2.5 sm:h-2.5 bg-emerald-500 border border-white dark:border-slate-900 rounded-full"></div>
                            </div>

                            <!-- Info -->
                            <div class="flex-grow min-w-0 pr-1 text-left">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-xs font-black text-slate-900 dark:text-white truncate">
                                        @<?= htmlspecialchars($lp['username']) ?>
                                    </span>
                                    <span class="text-[8px] sm:text-[9px] font-extrabold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/70 px-1 py-0.2 sm:px-1.5 sm:py-0.5 rounded flex items-center gap-0.5 flex-shrink-0 border border-emerald-200/50 dark:border-emerald-800/50">
                                        <?= htmlspecialchars($lp['time_ago']) ?>
                                    </span>
                                </div>
                                <div class="text-[10px] sm:text-[11px] font-bold text-slate-700 dark:text-slate-300 truncate mt-0.5" title="<?= htmlspecialchars($lp['product_name']) ?>">
                                    <?= htmlspecialchars($lp['product_name']) ?>
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-0.5">
                                    <span class="text-[8px] sm:text-[9px] font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/70 px-1 py-0.2 rounded truncate max-w-[100px] sm:max-w-[120px] border border-sky-200/50 dark:border-sky-800/50">
                                        <?= htmlspecialchars($lp['game']) ?>
                                    </span>
                                    <span class="text-[11px] sm:text-xs font-black text-orange-500 flex-shrink-0">
                                        Rp <?= number_format($lp['price'], 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- 4 Distinct Highlight Cards (Clean FontAwesome Icons & Sharp Design) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-4 pt-3 sm:pt-4 pb-2">
            <!-- Card 1: Kecepatan Pengiriman -->
            <div class="interactive-card bg-white dark:bg-[#0c1e33] p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border-2 border-sky-100 dark:border-slate-800 hover:border-sky-300 dark:hover:border-sky-500 shadow-card text-center group transition-all duration-200">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-sky-50 dark:bg-sky-950/70 text-sky-500 dark:text-sky-400 flex items-center justify-center mx-auto mb-2 sm:mb-2.5 border border-sky-200 dark:border-sky-800 group-hover:scale-110 group-hover:bg-sky-100 dark:group-hover:bg-sky-900/60 transition-all duration-300 text-lg sm:text-xl">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div class="text-base sm:text-xl font-black text-sky-700 dark:text-sky-300">3 - 5 Mnt</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5">Rata-rata Pengiriman</div>
            </div>

            <!-- Card 2: 100% Anti Hack-Back -->
            <div class="interactive-card bg-white dark:bg-[#0c1e33] p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border-2 border-emerald-100 dark:border-slate-800 hover:border-emerald-300 dark:hover:border-emerald-500 shadow-card text-center group transition-all duration-200">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2 sm:mb-2.5 border border-emerald-200 dark:border-emerald-800 group-hover:scale-110 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/60 transition-all duration-300 text-lg sm:text-xl">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="text-base sm:text-xl font-black text-emerald-700 dark:text-emerald-300">100% Anti</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5">Garansi Akun Polosan</div>
            </div>

            <!-- Card 3: 2,400+ Transaksi Sukses -->
            <div class="interactive-card bg-white dark:bg-[#0c1e33] p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border-2 border-amber-100 dark:border-slate-800 hover:border-amber-300 dark:hover:border-amber-500 shadow-card text-center group transition-all duration-200">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-amber-50 dark:bg-amber-950/70 text-amber-500 dark:text-amber-400 flex items-center justify-center mx-auto mb-2 sm:mb-2.5 border border-amber-200 dark:border-amber-800 group-hover:scale-110 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/60 transition-all duration-300 text-lg sm:text-xl">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="text-base sm:text-xl font-black text-amber-700 dark:text-amber-300">2,400+</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5">Transaksi Sukses</div>
            </div>

            <!-- Card 4: QRIS Instan Semua E-Wallet -->
            <div class="interactive-card bg-white dark:bg-[#0c1e33] p-3.5 sm:p-5 rounded-2xl sm:rounded-3xl border-2 border-blue-100 dark:border-slate-800 hover:border-blue-300 dark:hover:border-blue-500 shadow-card text-center group transition-all duration-200">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 sm:mb-2.5 border border-blue-200 dark:border-blue-800 group-hover:scale-110 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/60 transition-all duration-300 text-lg sm:text-xl">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div class="text-base sm:text-xl font-black text-blue-700 dark:text-blue-300">QRIS Instan</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5">Semua E-Wallet &amp; Bank</div>
            </div>
        </div>

    </div>
</section>

<!-- Game Roblox Category Selector - Soft Blue Theme -->
<section class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 -mt-2 mb-6 sm:mb-8 relative z-20">
    <div class="relative bg-gradient-to-r from-sky-100/90 via-sky-50/70 to-blue-100/80 dark:from-[#0b1b30] dark:via-[#0c1e33] dark:to-[#0f243d] rounded-2xl sm:rounded-3xl border-2 border-sky-200 dark:border-slate-800 shadow-soft-lg p-3.5 sm:p-6 overflow-hidden">
        
        <!-- Soft Ambient Light Glows -->
        <div class="absolute -right-12 -top-12 w-56 h-56 bg-sky-200/50 dark:bg-sky-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-56 h-56 bg-blue-200/40 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Section Header -->
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 mb-3.5 sm:mb-5 pb-3 sm:pb-4 border-b border-sky-200/80 dark:border-slate-700/80">
            
            <!-- Left: Title & Subtitle -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center text-sm sm:text-base shadow-sm ring-2 ring-white dark:ring-slate-700 flex-shrink-0">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                <div>
                    <h3 class="text-xs sm:text-base font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5 sm:gap-2">
                        PILIH GAME ROBLOX
                        <span class="inline-flex relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                    </h3>
                    <p class="text-[11px] sm:text-xs font-semibold text-slate-600 dark:text-slate-400">
                        Klik game untuk filter produk otomatis
                    </p>
                </div>
            </div>

            <!-- Right: Game Count Badge -->
            <div class="flex items-center gap-2">
                <div class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full text-[10px] sm:text-xs font-black text-sky-800 dark:text-sky-300 bg-white/95 dark:bg-slate-800/95 border border-sky-200 dark:border-slate-700 shadow-2xs backdrop-blur-sm">
                    <i class="fa-solid fa-layer-group text-sky-500"></i>
                    <span><?= count($games) ?> Game Roblox</span>
                </div>
            </div>
        </div>

        <!-- Game Logo Cards Grid: Dynamic items from Database -->
        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4">
            <?php foreach ($games as $g): ?>
            <?php $isActiveGame = ($activeGame === $g['id']); ?>
            <a href="/?game=<?= urlencode($g['id']) ?>#katalog" 
               data-game-id="<?= htmlspecialchars($g['id']) ?>"
               data-game-name="<?= htmlspecialchars($g['name']) ?>"
               onclick="selectGameFilter('<?= htmlspecialchars(addslashes($g['id'])) ?>', event)"
               class="game-selection-card interactive-card flex flex-col items-center p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border-2 transition-all duration-300 group/card cursor-pointer <?= $isActiveGame ? 'bg-sky-50/90 dark:bg-sky-950/80 border-sky-500 ring-4 ring-sky-200 dark:ring-sky-900/50 shadow-md scale-105' : 'bg-white dark:bg-[#0f172a] hover:bg-white dark:hover:bg-[#132238] border-slate-200/80 dark:border-slate-800 hover:border-sky-300 dark:hover:border-sky-500 hover:shadow-card hover:-translate-y-0.5' ?>">
                
                <!-- Game Logo / Icon Wrapper -->
                <div class="relative w-14 h-14 sm:w-18 sm:h-18 rounded-xl sm:rounded-2xl p-1 bg-white dark:bg-slate-800 border-2 <?= $isActiveGame ? 'border-sky-400 shadow-sm' : 'border-slate-100 dark:border-slate-700 group-hover/card:border-sky-300 shadow-2xs' ?> flex items-center justify-center overflow-hidden mb-1.5 sm:mb-2.5 transition-transform group-hover/card:scale-110">
                    <?php if ($g['id'] === 'all'): ?>
                        <div class="w-full h-full rounded-lg sm:rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white text-xl sm:text-2xl shadow-inner">
                            <i class="fa-solid fa-cubes"></i>
                        </div>
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($g['logo']) ?>" 
                             alt="<?= htmlspecialchars($g['name']) ?>" 
                             class="w-full h-full object-cover rounded-lg sm:rounded-xl"
                             loading="lazy"
                             decoding="async"
                             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($g['name']) ?>&background=0ea5e9&color=fff'">
                    <?php endif; ?>

                    <div class="game-check-badge absolute top-0 right-0 bg-sky-600 text-white rounded-bl-lg px-1.5 py-0.5 text-[9px] sm:text-[10px] <?= $isActiveGame ? '' : 'hidden' ?>">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>

                <!-- Game Name -->
                <span class="game-name-label text-xs font-black text-center <?= $isActiveGame ? 'text-sky-700 dark:text-sky-300' : 'text-slate-800 dark:text-slate-200 group-hover/card:text-sky-600 dark:group-hover/card:text-sky-400' ?> leading-tight">
                    <?= htmlspecialchars($g['name']) ?>
                </span>
                
                <span class="game-status-label text-[9px] sm:text-[10px] <?= $isActiveGame ? 'text-sky-600 dark:text-sky-400 font-black' : 'text-slate-400 dark:text-slate-500 font-bold' ?> mt-0.5">
                    <?= $isActiveGame ? 'Dipilih' : 'Pilih' ?>
                </span>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Main Store Catalog Section -->
<section class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 py-4 sm:py-8 scroll-mt-24" id="katalog">

    <!-- Notifikasi Alert jika ada -->
    <?php if (!empty($_GET['error'])): ?>
    <div class="mb-6 sm:mb-8 p-3.5 sm:p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border-2 border-rose-300 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs sm:text-sm flex items-center gap-3 shadow-sm animate-bounce-subtle">
        <i class="fa-solid fa-circle-exclamation text-lg sm:text-xl text-rose-600 flex-shrink-0"></i>
        <span><?= htmlspecialchars($_GET['error']) ?></span>
    </div>
    <?php endif; ?>

    <!-- Section Header with Active Game Pill -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3 sm:gap-4 mb-4 sm:mb-8">
        <div>
            <div class="inline-flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-black uppercase tracking-wider text-sky-700 dark:text-sky-300 bg-sky-100 dark:bg-sky-950/80 px-2.5 sm:px-3 py-1 rounded-lg border border-sky-200 dark:border-sky-800 mb-1.5 sm:mb-2">
                <i class="fa-solid fa-gamepad text-sky-600 dark:text-sky-400"></i>
                <span id="catalogActiveGameLabel">Katalog: <?= htmlspecialchars($activeGame === 'all' ? 'Semua Game' : $activeGame) ?></span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-950 dark:text-white tracking-tight">
                PILIH PRODUK ROBLOX
            </h2>
        </div>

        <div class="flex items-center gap-2">
            <a href="/#katalog" 
               id="catalogResetGameBtn" 
               onclick="selectGameFilter('all', event)" 
               class="px-3 py-1 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 transition flex items-center gap-1.5 cursor-pointer <?= $activeGame !== 'all' ? '' : 'hidden' ?>">
                <i class="fa-solid fa-xmark"></i>
                <span id="catalogResetGameText">Reset Game: <?= htmlspecialchars($activeGame) ?></span>
            </a>
        </div>
    </div>

    <!-- Filter Pills & Search Bar Toolbar -->
    <div class="bg-white dark:bg-[#0c1e33] p-2.5 sm:p-4 rounded-2xl sm:rounded-3xl border-2 border-sky-100 dark:border-slate-800 shadow-card mb-5 sm:mb-8 flex flex-col md:flex-row items-center justify-between gap-3 sm:gap-4">
        
        <!-- Category Filter Tabs (In-Place Instant Filter Tanpa Reload & Tanpa Scroll) -->
        <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto md:overflow-visible overflow-y-hidden w-full md:w-auto py-0.5 scrollbar-none no-scrollbar" id="categoryTabsContainer">
            <?php 
            $tabsToRender = !empty($categoryTabs) ? $categoryTabs : [
                ['slug' => 'semua', 'name' => 'Semua']
            ];
            ?>
            <?php foreach ($tabsToRender as $tab): ?>
            <?php $isActiveTab = (strtolower($activeCategory) === strtolower($tab['slug'])); ?>
            <button type="button" 
                    data-slug="<?= htmlspecialchars($tab['slug']) ?>"
                    onclick="selectCategoryTab('<?= htmlspecialchars(addslashes($tab['slug'])) ?>', event)" 
                    class="cat-filter-tab px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-black transition-all duration-200 whitespace-nowrap flex items-center justify-center cursor-pointer <?= $isActiveTab ? 'bg-sky-500 text-white shadow-md shadow-sky-300 dark:shadow-sky-950' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700' ?>">
                <span><?= htmlspecialchars($tab['name']) ?></span>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Search Bar -->
        <form action="/#katalog" method="GET" class="w-full md:w-80 relative" id="catalogSearchForm">
            <input type="hidden" name="kategori" id="searchCategoryInput" value="<?= htmlspecialchars($activeCategory) ?>">
            <input type="hidden" name="game" id="searchGameInput" value="<?= htmlspecialchars($activeGame) ?>">
            <input type="text" 
                    name="q" 
                    id="catalogSearchInput"
                    value="<?= htmlspecialchars($searchQuery) ?>" 
                    placeholder="Cari item, pet, game, akun..." 
                    class="w-full pl-9 pr-9 py-2 sm:py-2.5 bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl text-xs sm:text-sm text-slate-900 dark:text-white font-bold placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-slate-900 transition">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <?php if (!empty($searchQuery)): ?>
            <a href="/?kategori=<?= htmlspecialchars($activeCategory) ?>&game=<?= urlencode($activeGame) ?>#katalog" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 text-xs" title="Hapus pencarian">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <?php endif; ?>
        </form>
    </div>

    <?php
    $activeCatLower = strtolower($activeCategory ?? 'semua');
    $initialVisibleCount = 0;
    foreach ($products as $chk) {
        $chkSub = strtolower($chk['sub_category'] ?? ($chk['category_name'] === 'Akun Roblox' ? 'akun' : 'pet'));
        $chkSlug = strtolower($chk['category_slug'] ?? '');
        $isMatch = ($activeCatLower === 'semua' || $activeCatLower === 'all' || $chkSub === $activeCatLower || $chkSlug === $activeCatLower ||
                    ($activeCatLower === 'item' && (in_array($chkSub, ['item', 'pet', 'egg', 'food']) || str_contains($chkSlug, 'item'))) ||
                    ($activeCatLower === 'akun' && ($chkSub === 'akun' || str_contains($chkSlug, 'akun'))) ||
                    ($activeCatLower === 'item-game' && (in_array($chkSub, ['item', 'pet', 'egg', 'food']) || str_contains($chkSlug, 'item'))) ||
                    ($activeCatLower === 'akun-game' && ($chkSub === 'akun' || str_contains($chkSlug, 'akun'))));
        if ($isMatch) {
            $initialVisibleCount++;
        }
    }
    ?>

    <!-- Product Grid: Clean White Cards with High-End Micro Animations -->
    <?php if (empty($products)): ?>
    <div class="text-center py-16 sm:py-20 bg-white dark:bg-[#0c1e33] rounded-2xl sm:rounded-3xl border-2 border-slate-200 dark:border-slate-800 shadow-card max-w-xl mx-auto px-4">
        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-xl sm:text-2xl mb-3">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-1">Produk Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">Tidak ada produk yang cocok untuk pencarian atau filter saat ini.</p>
        <a href="/#katalog" class="inline-block mt-4 px-4 py-2 rounded-xl bg-sky-100 dark:bg-sky-950/80 text-xs font-black text-sky-700 dark:text-sky-300 hover:bg-sky-200 dark:hover:bg-sky-900 transition border border-sky-200 dark:border-sky-800">
            Lihat Semua Produk
        </a>
    </div>
    <?php else: ?>
    <!-- Product Grid: Itemku-style Clean Cards (3 cols mobile, 5 cols desktop, 16:9 banner, orange price, rating) -->
    <div id="productGridContainer" class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1.5 sm:gap-4">
        <?php foreach ($products as $p): ?>
        <?php 
            $pGame = strtolower($p['game'] ?? 'build a zoo');
            $pSubCat = strtolower($p['sub_category'] ?? ($p['category_name'] === 'Akun Roblox' ? 'akun' : 'pet'));
            $pCatSlug = strtolower($p['category_slug'] ?? '');
            $gameMatch = ($activeGame === 'all' || $pGame === strtolower($activeGame));
            $catMatch = ($activeCatLower === 'semua' || $activeCatLower === 'all' || $pSubCat === $activeCatLower || $pCatSlug === $activeCatLower ||
                              ($activeCatLower === 'item' && (in_array($pSubCat, ['item', 'pet', 'egg', 'food']) || str_contains($pCatSlug, 'item'))) ||
                              ($activeCatLower === 'akun' && ($pSubCat === 'akun' || str_contains($pCatSlug, 'akun'))) ||
                              ($activeCatLower === 'item-game' && (in_array($pSubCat, ['item', 'pet', 'egg', 'food']) || str_contains($pCatSlug, 'item'))) ||
                              ($activeCatLower === 'akun-game' && ($pSubCat === 'akun' || str_contains($pCatSlug, 'akun'))));
            $isCardVisible = ($gameMatch && $catMatch);
        ?>
        <div onclick="openCheckoutModal(<?= htmlspecialchars(json_encode($p)) ?>)" 
             data-game="<?= htmlspecialchars($pGame) ?>"
             data-category="<?= $pSubCat ?>"
             data-catslug="<?= $pCatSlug ?>"
             data-name="<?= htmlspecialchars(strtolower($p['name'])) ?>"
             data-desc="<?= htmlspecialchars(strtolower($p['description'] ?? '')) ?>"
             class="product-card-item bg-white dark:bg-[#0c1e33] rounded-lg sm:rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-2xs hover:shadow-lg hover:border-sky-300 dark:hover:border-sky-500 hover:-translate-y-1 transition duration-200 flex flex-col justify-between overflow-hidden cursor-pointer group transform-gpu <?= $isCardVisible ? '' : 'hidden' ?>">
            
            <!-- Card Visual / Image: 16:9 Aspect Ratio matching Seller's Marketplace Banners -->
            <div class="relative aspect-video w-full bg-slate-50 dark:bg-slate-900/60 overflow-hidden border-b border-slate-100 dark:border-slate-800">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" 
                     alt="<?= htmlspecialchars($p['name']) ?>" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy"
                     decoding="async"
                     onerror="this.src='https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500&auto=format&fit=crop&q=60'">
                
                <?php if ($p['stock'] <= 0): ?>
                <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center backdrop-blur-[2px] z-10">
                    <span class="px-1.5 py-0.5 bg-rose-600 text-white text-[8px] sm:text-[10px] font-black rounded uppercase tracking-wider shadow-sm">
                        Habis
                    </span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Card Content matching Itemku format -->
            <div class="p-1.5 sm:p-3.5 flex flex-col flex-grow justify-between">
                <div>
                    <!-- Judul Produk: 2 baris rapi dengan line-clamp-2 -->
                    <h3 class="text-[10px] sm:text-[13px] font-bold text-slate-800 dark:text-slate-100 line-clamp-2 leading-tight sm:leading-snug group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors h-6 sm:h-9" title="<?= htmlspecialchars($p['name']) ?>">
                        <?= htmlspecialchars($p['name']) ?>
                    </h3>

                    <!-- Kategori / Subtitle (Itemku style: Pet, Egg, Food, Item, Akun) -->
                    <p class="text-[8.5px] sm:text-[11px] text-slate-400 dark:text-slate-400 font-medium mt-0.5 sm:mt-1 truncate">
                        <?= htmlspecialchars(!empty($p['sub_category']) ? $p['sub_category'] : ($p['category_name'] === 'Akun Roblox' ? 'Akun' : 'Pet')) ?>
                    </p>
                </div>

                <div>
                    <!-- Harga Bold Oranye Khas Marketplace Gaming -->
                    <div class="mt-1 sm:mt-2.5 flex items-baseline gap-1 sm:gap-1.5 flex-wrap">
                        <span class="text-[11px] sm:text-base font-extrabold text-orange-500 dark:text-orange-400 tracking-tight">
                            Rp <?= number_format($p['price'], 0, ',', '.') ?>
                        </span>
                        <?php if ($p['price_original'] > $p['price']): ?>
                        <span class="text-[7.5px] sm:text-[10px] text-slate-400 dark:text-slate-500 line-through font-normal">
                            Rp <?= number_format($p['price_original'], 0, ',', '.') ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Footer: Terjual, Stok & Rating Bintang Kuning -->
                    <div class="flex items-center justify-between mt-1 sm:mt-2 pt-1 sm:pt-2 border-t border-slate-100 dark:border-slate-800 text-[8px] sm:text-[11px] text-slate-400 dark:text-slate-400 font-medium">
                        <span class="truncate pr-0.5">
                            <?= (int)($p['total_sold'] ?? 0) ?> Terjual <span class="hidden sm:inline text-slate-300 dark:text-slate-600">|</span> <span class="hidden sm:inline"><?php if ((int)$p['stock'] === 1): ?><span class="text-red-500 font-bold">Sisa 1</span><?php elseif ((int)$p['stock'] > 1): ?><span class="text-blue-600 dark:text-sky-400 font-bold">Stok <?= (int)$p['stock'] ?></span><?php else: ?><span class="text-rose-500 font-bold">Habis</span><?php endif; ?></span>
                        </span>
                        <span class="flex items-center gap-0.5 font-bold text-slate-700 dark:text-slate-200 flex-shrink-0">
                            <i class="fa-solid fa-star text-amber-400 text-[8px] sm:text-xs"></i>
                            <span><?= number_format((float)($p['rating'] ?? 5.0), 1) ?></span>
                        </span>
                    </div>

                    <!-- Tombol Aksi: + Keranjang & Beli -->
                    <div class="flex items-center gap-1 sm:gap-1.5 mt-1.5 sm:mt-2.5 pt-1 sm:pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" 
                                onclick="event.stopPropagation(); addToCart(<?= htmlspecialchars(json_encode($p)) ?>, 1, false);" 
                                class="flex-1 py-1 sm:py-1.5 px-1 sm:px-2 bg-sky-50 dark:bg-slate-800 hover:bg-sky-100 dark:hover:bg-slate-700 active:scale-95 text-sky-700 dark:text-sky-300 font-bold rounded-md sm:rounded-xl border border-sky-200/80 dark:border-slate-700 flex items-center justify-center gap-0.5 sm:gap-1 transition-all cursor-pointer shadow-2xs hover:shadow-xs group/btn"
                                title="Tambah ke keranjang belanja">
                            <i class="fa-solid fa-cart-plus text-sky-600 dark:text-sky-400 group-hover/btn:scale-110 transition-transform text-[10px] sm:text-[11px]"></i>
                            <span class="hidden sm:inline text-[11px] font-extrabold">Keranjang</span>
                        </button>
                        <button type="button" 
                                onclick="event.stopPropagation(); openCheckoutModal(<?= htmlspecialchars(json_encode($p)) ?>);" 
                                class="py-1 sm:py-1.5 px-1.5 sm:px-3 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 active:scale-95 text-white text-[9.5px] sm:text-xs font-black rounded-md sm:rounded-xl shadow-2xs transition-all flex items-center justify-center gap-1 cursor-pointer flex-shrink-0">
                            <span>Beli</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- In-Place Empty State Box jika kategori yang dipilih belum ada produk (misal Egg, Food, Item) -->
    <div id="noProductsFoundBox" class="text-center py-12 sm:py-16 bg-white dark:bg-[#0c1e33] rounded-2xl sm:rounded-3xl border-2 border-slate-200 dark:border-slate-800 shadow-sm max-w-md mx-auto my-6 px-4 <?= ($initialVisibleCount === 0) ? '' : 'hidden' ?>">
        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-sky-50 dark:bg-slate-800 text-sky-500 dark:text-sky-400 flex items-center justify-center mx-auto text-lg sm:text-xl mb-3 border border-sky-100 dark:border-slate-700">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
        <h3 class="text-sm sm:text-base font-black text-slate-900 dark:text-white mb-1">Belum Ada Produk</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium" id="noProductsText">
            Tidak ada produk untuk kategori "<?= htmlspecialchars(strtoupper($activeCatLower)) ?>" saat ini.
        </p>
        <button type="button" onclick="selectCategoryTab('semua', event)" class="inline-block mt-4 px-4 py-2 rounded-xl bg-sky-100 dark:bg-sky-950/80 hover:bg-sky-200 dark:hover:bg-sky-900 text-xs font-black text-sky-700 dark:text-sky-300 transition cursor-pointer border border-sky-200 dark:border-sky-800">
            Lihat Semua Produk
        </button>
    </div>
    <?php endif; ?>

</section>

<!-- Review Testimoni Pelanggan Asli -->
<section class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 py-8 sm:py-12 relative">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 max-w-2xl sm:max-w-none mx-auto mb-5 sm:mb-10 text-center sm:text-left">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-950/80 px-3.5 py-1 rounded-xl border border-emerald-200 dark:border-emerald-800 mb-2">
                <i class="fa-solid fa-star text-emerald-600 dark:text-emerald-400"></i>
                <span>Ulasan Pelanggan</span>
            </div>
            <h2 class="text-xl sm:text-3xl font-black text-slate-950 dark:text-white tracking-tight">
                TRANSAKSI TERBARU PEMBELI
            </h2>
            <p class="hidden sm:block text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Ulasan nyata dari pembeli yang telah sukses bertransaksi</p>
        </div>

        <!-- Navigation Arrows for Mobile (Top-Right of section) -->
        <?php if (!empty($reviews) && count($reviews) > 1): ?>
        <div class="flex md:hidden items-center justify-center gap-2.5 self-center sm:self-auto pt-1">
            <button type="button" 
                    onclick="reviewPrevSlide()" 
                    class="w-9 h-9 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 shadow-sm hover:bg-sky-50 dark:hover:bg-slate-700 active:scale-90 flex items-center justify-center text-slate-700 dark:text-slate-300 text-xs transition cursor-pointer"
                    aria-label="Ulasan Sebelumnya">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <span id="reviewSlideCounter" class="text-xs font-black text-slate-600 dark:text-slate-400 min-w-[45px] text-center">
                1 / <?= count($reviews) ?>
            </span>
            <button type="button" 
                    onclick="reviewNextSlide()" 
                    class="w-9 h-9 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 shadow-sm hover:bg-sky-50 dark:hover:bg-slate-700 active:scale-90 flex items-center justify-center text-slate-700 dark:text-slate-300 text-xs transition cursor-pointer"
                    aria-label="Ulasan Berikutnya">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Review Container: 100% 1-Card Full Width Carousel on Mobile, Multi-Col Grid on Desktop -->
    <div id="reviewCarouselContainer" class="relative overflow-hidden md:overflow-visible rounded-2xl sm:rounded-3xl">
        <div id="reviewSlideTrack" class="flex md:grid transition-transform duration-500 ease-in-out md:transform-none md:grid-cols-2 lg:grid-cols-3 gap-0 md:gap-6">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $idx => $rev): ?>
                <div class="review-slide-item w-full min-w-full flex-shrink-0 md:w-auto md:min-w-0 p-1 md:p-0 select-none">
                    <div class="interactive-card bg-white dark:bg-[#0c1e33] p-4 sm:p-6 rounded-2xl sm:rounded-3xl border-2 border-slate-200/90 dark:border-slate-800 shadow-card flex flex-col justify-between space-y-3.5 hover:border-sky-300 dark:hover:border-sky-500 transition-all h-full">
                        <div class="space-y-2.5 sm:space-y-3">
                            <div class="flex items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <img src="<?= htmlspecialchars($rev['roblox_avatar_url'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($rev['roblox_username']) . '&background=38bdf8&color=fff') ?>" 
                                         alt="<?= htmlspecialchars($rev['roblox_username']) ?>" 
                                         loading="lazy"
                                         decoding="async"
                                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($rev['roblox_username']) ?>&background=38bdf8&color=fff'"
                                         class="w-10 h-10 sm:w-11 sm:h-11 rounded-full border-2 border-sky-400 object-cover bg-slate-50 dark:bg-slate-800 flex-shrink-0">
                                    <div class="min-w-0">
                                        <div class="font-black text-xs sm:text-sm text-slate-950 dark:text-white flex items-center gap-1 truncate">
                                            <span class="truncate"><?= htmlspecialchars($rev['roblox_username']) ?></span>
                                            <i class="fa-solid fa-circle-check text-emerald-600 text-[10px] sm:text-xs flex-shrink-0" title="Pembeli Terverifikasi"></i>
                                        </div>
                                        <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-bold block truncate" title="<?= htmlspecialchars($rev['product_name']) ?>">
                                            Beli <?= htmlspecialchars($rev['product_name']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-amber-400 text-[10px] sm:text-xs flex items-center flex-shrink-0">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <i class="fa-solid fa-star <?= $s <= (int)$rev['rating'] ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="text-[11px] sm:text-xs font-semibold text-slate-700 dark:text-slate-300 leading-relaxed italic line-clamp-3 sm:line-clamp-none">
                                "<?= htmlspecialchars($rev['comment']) ?>"
                            </p>
                        </div>
                        <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                                <i class="fa-solid fa-shield-check"></i> Transaksi Berhasil
                            </span>
                            <span><?= !empty($rev['created_at']) ? date('d M Y', strtotime($rev['created_at'])) : 'Baru saja' ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12 bg-white dark:bg-[#0c1e33] rounded-3xl border-2 border-slate-200 dark:border-slate-800 w-full">
                    <i class="fa-regular fa-comment-dots text-3xl text-slate-400 mb-2"></i>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Belum ada ulasan saat ini.</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Ulasan pelanggan nyata akan otomatis tampil di sini setelah pesanan selesai.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mobile Indicator Dots -->
    <?php if (!empty($reviews) && count($reviews) > 1): ?>
    <div id="reviewDotsContainer" class="flex md:hidden items-center justify-center gap-1.5 mt-3.5">
        <?php foreach ($reviews as $idx => $rev): ?>
        <button type="button" 
                onclick="reviewGoToSlide(<?= $idx ?>)" 
                class="review-dot h-1.5 rounded-full transition-all duration-300 cursor-pointer <?= $idx === 0 ? 'bg-sky-500 w-5' : 'bg-slate-300 dark:bg-slate-700 w-2' ?>" 
                aria-label="Ulasan <?= $idx + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<!-- FAQ Accordion Section -->
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-sky-700 dark:text-sky-300 bg-sky-100 dark:bg-sky-950/80 px-3 py-1 rounded-full border border-sky-200 dark:border-sky-800 mb-2.5">
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#0284c7" stroke-width="2"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" stroke="#0284c7" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="17" r="1" fill="#0284c7"/>
            </svg>
            <span>BANTUAN &amp; PANDUAN</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-950 dark:text-white tracking-tight">PERTANYAAN UMUM (FAQ)</h2>
        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1">Panduan singkat seputar transaksi di ItemPedia</p>
    </div>

    <div class="space-y-3.5">
        <?php if (!empty($faqs)): ?>
            <?php 
            $faqIcons = [
                ['icon' => 'fa-solid fa-gamepad', 'wrap' => 'bg-sky-50 dark:bg-sky-950/60 border-sky-200 dark:border-sky-800 text-sky-600 dark:text-sky-400'],
                ['icon' => 'fa-solid fa-lock', 'wrap' => 'bg-indigo-50 dark:bg-indigo-950/60 border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400'],
                ['icon' => 'fa-solid fa-shield-halved', 'wrap' => 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400'],
                ['icon' => 'fa-solid fa-credit-card', 'wrap' => 'bg-amber-50 dark:bg-amber-950/60 border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400'],
                ['icon' => 'fa-solid fa-circle-question', 'wrap' => 'bg-purple-50 dark:bg-purple-950/60 border-purple-200 dark:border-purple-800 text-purple-600 dark:text-purple-400']
            ];
            $i = 0;
            ?>
            <?php foreach ($faqs as $f): ?>
            <?php 
            $itemConfig = $faqIcons[$i % count($faqIcons)];
            $i++;
            ?>
            <details class="interactive-card bg-white dark:bg-[#0c1e33] rounded-2xl border-2 border-slate-200 dark:border-slate-800 p-5 cursor-pointer shadow-sm group">
                <summary class="font-black text-sm text-slate-900 dark:text-white flex items-center justify-between list-none">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl border flex items-center justify-center flex-shrink-0 <?= $itemConfig['wrap'] ?>">
                            <i class="<?= $itemConfig['icon'] ?> text-xs"></i>
                        </div>
                        <span><?= htmlspecialchars($f['question']) ?></span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-sky-600 dark:text-sky-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-3 leading-relaxed border-t border-slate-100 dark:border-slate-800 pt-3 pl-11">
                    <?= nl2br(htmlspecialchars($f['answer'])) ?>
                </p>
            </details>
            <?php endforeach; ?>
        <?php else: ?>
            <details class="interactive-card bg-white dark:bg-[#0c1e33] rounded-2xl border-2 border-slate-200 dark:border-slate-800 p-5 cursor-pointer shadow-sm group">
                <summary class="font-black text-sm text-slate-900 dark:text-white flex items-center justify-between list-none">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-950/60 border border-sky-100 dark:border-sky-800 text-sky-600 dark:text-sky-400 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-gamepad text-xs"></i>
                        </div>
                        <span>Bagaimana cara serah terima item Chop Your Tree, Build A Zoo, dan Catch and Tame?</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-sky-600 dark:text-sky-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-3 leading-relaxed border-t border-slate-100 dark:border-slate-800 pt-3 pl-11">
                    Setelah pembayaran QRIS berhasil, seller kami akan mengontak WhatsApp kamu atau kamu bisa langsung menghubungi WhatsApp di invoice. Seller akan membagikan link VIP Private Server Roblox, dan item/pet akan di-trade secara instan di dalam game.
                </p>
            </details>
        <?php endif; ?>
    </div>
</section>

<!-- CLEAN & ANIMATED CHECKOUT MODAL (PERSEGI PANJANG DUA KOLOM) -->
<div id="checkoutModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-slate-900/60 backdrop-blur-md hidden transition-all duration-300">
    <div id="modalBox" class="bg-white dark:bg-[#0c1e33] border-2 border-sky-200 dark:border-slate-700 rounded-3xl w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300">
        
        <!-- Modal Topbar -->
        <div class="flex items-center justify-between px-5 py-4 border-b-2 border-slate-100 dark:border-slate-800 bg-sky-50/60 dark:bg-slate-800/60 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sky-500 text-white flex items-center justify-center text-sm shadow-md shadow-sky-300 dark:shadow-sky-950">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-950 dark:text-white text-sm sm:text-base">Detail Produk & Pemesanan</h3>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400">ItemPedia • Transaksi Instan & Terverifikasi Roblox</p>
                </div>
            </div>
            <button type="button" onclick="closeCheckoutModal()" class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition active:scale-90 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Body: 2 Kolom Landscape Grid (Scrollable jika layar sempit) -->
        <div class="overflow-y-auto p-5 sm:p-7 grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 flex-grow">
            
            <!-- ================= KOLOM KIRI ================= -->
            <!-- Gambar Produk, Deskripsi Produk, Ulasan Produk -->
            <div class="lg:col-span-6 flex flex-col space-y-4">
                
                <!-- 1. Gambar Produk: Bersih Total Tanpa Teks / Badge yang Menimpa -->
                <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 shadow-sm">
                    <img id="modalProductImage" src="" alt="Produk Roblox" class="w-full h-full object-cover">
                </div>

                <!-- Judul & Harga Satuan -->
                <div>
                    <h3 id="modalProductName" class="text-base sm:text-lg font-black text-slate-950 dark:text-white leading-snug"></h3>
                    <div class="flex items-baseline gap-2 mt-1 flex-wrap">
                        <span id="modalProductPrice" class="text-xl sm:text-2xl font-black text-orange-500 dark:text-orange-400"></span>
                        <span id="modalProductPriceOriginal" class="text-xs text-slate-400 dark:text-slate-500 line-through font-bold hidden"></span>
                        <span id="modalProductSold" class="text-xs text-slate-500 dark:text-slate-400 font-bold ml-auto"></span>
                    </div>
                </div>

                <!-- 2. Deskripsi Produk -->
                <div class="space-y-1.5 pt-1">
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-file-lines text-sky-500"></i>
                        <span>Deskripsi Produk</span>
                    </h4>
                    <div id="modalProductDesc" class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200/90 dark:border-slate-700 rounded-2xl text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium max-h-36 overflow-y-auto">
                    </div>
                </div>

                <!-- 3. Ulasan Produk -->
                <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-800 flex-grow">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-comments text-amber-500"></i>
                            <span>Ulasan Pembeli</span>
                        </h4>
                        <span id="modalReviewHeaderCount" class="text-[11px] font-bold text-slate-400 dark:text-slate-500"></span>
                    </div>

                    <!-- List Ulasan Real untuk Produk Ini -->
                    <div id="modalReviewsList" class="space-y-2 max-h-48 overflow-y-auto pr-1 scrollbar-none">
                        <!-- Ulasan diisi via JavaScript -->
                    </div>
                </div>

            </div>

            <!-- ================= KOLOM KANAN ================= -->
            <!-- Username Roblox, Jumlah Dibeli, Pesan ke Penjual, Tombol Beli -->
            <div class="lg:col-span-6 bg-slate-50/70 dark:bg-slate-800/40 p-4 sm:p-5 rounded-2xl border-2 border-sky-100 dark:border-slate-800 flex flex-col justify-between">
                
                <form action="/order/create" method="POST" id="checkoutForm" class="space-y-4">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <input type="hidden" name="roblox_avatar" id="modalRobloxAvatar">

                    <!-- 1. Username Roblox -->
                    <div>
                        <label class="block text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-1.5">
                            Username Roblox Kamu <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <div class="relative flex-grow">
                                <input type="text" 
                                       name="roblox_username" 
                                       id="robloxUsernameInput" 
                                       required 
                                       placeholder="Ketik username Roblox kamu..."
                                       class="w-full pl-9 pr-3 py-2.5 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-950 dark:text-white font-bold focus:outline-none focus:border-sky-500 transition">
                                <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            </div>
                            <button type="button" 
                                    id="btnCheckRoblox"
                                    onclick="checkRobloxUser()"
                                    class="btn-shimmer px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white font-black text-xs rounded-xl shadow-md shadow-sky-300 dark:shadow-sky-950 transition flex items-center gap-1.5 flex-shrink-0 active:scale-95 cursor-pointer">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span>Cek Avatar</span>
                            </button>
                        </div>
                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-400 mt-1">Klik "Cek Avatar" untuk mencocokkan karakter avatar Roblox.</p>

                        <!-- Box Hasil Cek Avatar Roblox -->
                        <div id="robloxAvatarBox" class="hidden mt-2.5 p-3 rounded-xl bg-sky-50 dark:bg-sky-950/60 border-2 border-sky-300 dark:border-sky-800 flex items-center gap-3 shadow-sm">
                            <img id="avatarPreviewImg" 
                                 src="" 
                                 alt="Roblox Avatar" 
                                 class="w-12 h-12 rounded-full border-2 border-sky-500 bg-white object-cover flex-shrink-0 shadow-sm"
                                 onerror="this.src='https://ui-avatars.com/api/?name=Roblox&background=38bdf8&color=fff'">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span id="robloxVerifiedUsername" class="font-black text-xs text-slate-950 dark:text-white truncate"></span>
                                    <span class="inline-flex items-center gap-1 text-[9px] font-extrabold text-emerald-800 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-950/80 px-1.5 py-0.5 rounded border border-emerald-300 dark:border-emerald-800">
                                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400"></i> Akun Valid
                                    </span>
                                </div>
                                <span id="robloxDisplayName" class="text-[11px] text-slate-600 dark:text-slate-300 block mt-0.5 font-bold"></span>
                            </div>
                        </div>

                        <!-- Box Error jika username tidak ada -->
                        <div id="robloxErrorBox" class="hidden mt-2 p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 border-2 border-rose-200 dark:border-rose-800 text-[11px] font-bold text-rose-800 dark:text-rose-300 flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                            <span id="robloxErrorMsg"></span>
                        </div>
                    </div>

                    <!-- 2. Jumlah Di Beli (Quantity) -->
                    <div class="bg-white dark:bg-slate-800 p-3.5 rounded-2xl border-2 border-slate-200/90 dark:border-slate-700 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                Jumlah Dibeli
                            </label>
                            <span id="modalQtyStockInfo" class="text-[11px] font-bold text-slate-500 dark:text-slate-400"></span>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <!-- Minus / Plus Buttons -->
                            <div class="flex items-center border-2 border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900">
                                <button type="button" 
                                        onclick="changeQuantity(-1)" 
                                        class="w-8 h-8 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 active:scale-95 transition font-black cursor-pointer">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       id="modalQtyInput" 
                                       value="1" 
                                       min="1" 
                                       max="10" 
                                       oninput="handleQuantityChange()" 
                                       class="w-12 text-center text-xs font-black bg-white dark:bg-slate-800 py-1.5 focus:outline-none border-x-2 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white">
                                <button type="button" 
                                        onclick="changeQuantity(1)" 
                                        class="w-8 h-8 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 active:scale-95 transition font-black cursor-pointer">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>

                            <!-- Real-time Subtotal -->
                            <div class="text-right">
                                <span class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Harga:</span>
                                <div class="flex flex-col items-end">
                                    <span id="modalDiscountBadge" class="hidden text-[10px] font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/80 px-1.5 py-0.5 rounded border border-emerald-200 dark:border-emerald-800 mb-0.5">
                                        Hemat -Rp 0
                                    </span>
                                    <span id="modalSubtotalStrikethrough" class="hidden text-[11px] font-bold text-slate-400 dark:text-slate-500 line-through">Rp 0</span>
                                    <span id="modalTotalPrice" class="text-base font-black text-orange-600 dark:text-orange-400">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kode Redeem Diskon (Khusus 1 Produk Saja) -->
                    <div class="bg-white dark:bg-slate-800 p-3.5 rounded-2xl border-2 border-slate-200/90 dark:border-slate-700 shadow-sm space-y-2.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-ticket text-amber-500"></i>
                                <span>Punya Kode Redeem?</span>
                            </label>
                            <span class="text-[10px] font-bold text-amber-600 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/70 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-800">
                                Diskon 1 Produk
                            </span>
                        </div>

                        <!-- Form Input Kode -->
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" 
                                       id="modalRedeemCodeInput" 
                                       placeholder="KODE PROMO (CONTOH: ROBLOX20)" 
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();applyRedeemCode();}"
                                       class="w-full pl-3 pr-8 py-2.5 bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-900 transition placeholder:normal-case placeholder:font-normal placeholder:tracking-normal placeholder:text-slate-400 dark:placeholder:text-slate-500">
                                <button type="button"
                                        id="modalClearRedeemBtn"
                                        onclick="cancelRedeemCode()"
                                        class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            </div>
                            <button type="button" 
                                    id="btnApplyRedeem" 
                                    onclick="applyRedeemCode()" 
                                    class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white font-black text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 flex-shrink-0 cursor-pointer">
                                <i class="fa-solid fa-check"></i>
                                <span>Pakai</span>
                            </button>
                        </div>

                        <!-- Hidden input untuk dikirim bersama form POST checkout -->
                        <input type="hidden" name="redeem_code" id="modalAppliedRedeemCode" value="">

                        <!-- Status Box Sukses / Error -->
                        <div id="redeemStatusBox" class="hidden text-xs rounded-xl p-2.5 items-start gap-2">
                            <i id="redeemStatusIcon" class="mt-0.5 text-xs"></i>
                            <div class="min-w-0 flex-1">
                                <p id="redeemStatusMsg" class="font-bold text-[11px] leading-tight"></p>
                                <p id="redeemStatusSub" class="text-[10px] opacity-80 mt-0.5"></p>
                            </div>
                            <button type="button" id="btnCancelRedeemInBox" onclick="cancelRedeemCode()" class="text-[10px] font-bold underline ml-auto text-emerald-700 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300">
                                Batal
                            </button>
                        </div>
                    </div>

                    <!-- 3. Isi Pesan ke Penjual -->
                    <div>
                        <label class="block text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-1.5">
                            Isi Pesan ke Penjual (Opsional)
                        </label>
                        <textarea name="note" 
                                  rows="2" 
                                  placeholder="Contoh: Tolong trade sekarang ya kak, saya standby di private server..."
                                  class="w-full px-3 py-2 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-950 dark:text-white font-medium focus:outline-none focus:border-sky-500 transition resize-none"></textarea>
                    </div>

                    <!-- 4. Tombol Aksi (+ Keranjang & Beli Sekarang) & Footer -->
                    <div class="pt-2 space-y-2.5">
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    onclick="addCurrentProductToCart()" 
                                    class="flex-1 py-3 px-3 bg-sky-50 dark:bg-slate-800 hover:bg-sky-100 dark:hover:bg-slate-700 active:scale-95 text-sky-700 dark:text-sky-300 font-bold text-xs sm:text-sm rounded-2xl border-2 border-sky-200 dark:border-slate-700 transition flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-cart-plus text-sky-600 dark:text-sky-400"></i>
                                <span>Keranjang</span>
                            </button>
                            <button type="submit" 
                                    id="btnSubmitOrder"
                                    class="btn-shimmer flex-[1.4] py-3.5 px-4 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-400 hover:to-blue-500 text-white font-black text-xs sm:text-sm rounded-2xl shadow-lg shadow-sky-400/30 transition-all duration-200 flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
                                <span>Beli Sekarang</span>
                                <i class="fa-solid fa-bolt text-amber-300 text-xs"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 dark:text-slate-500 px-1">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-qrcode text-sky-500"></i> QRIS Otomatis
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved text-emerald-500"></i> 100% Aman & Garansi
                            </span>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

<script>
    let currentProduct = null;
    let currentUnitPrice = 0;
    let currentStock = 1;

    function addCurrentProductToCart() {
        if (!currentProduct) return;
        const qty = parseInt(document.getElementById('modalQtyInput')?.value) || 1;
        addToCart(currentProduct, qty, true);
        closeCheckoutModal();
    }

    function openCheckoutModal(product) {
        currentProduct = product;
        currentUnitPrice = Number(product.price) || 0;
        currentStock = Number(product.stock) || 0;

        // Populate Left Column
        document.getElementById('modalProductId').value = product.id;
        document.getElementById('modalProductName').innerText = product.name;
        document.getElementById('modalProductImage').src = product.image_url;
        document.getElementById('modalProductImage').onerror = function() {
            this.src = 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500&auto=format&fit=crop&q=60';
        };

        // Deskripsi Produk
        document.getElementById('modalProductDesc').innerText = product.description || 'Tidak ada deskripsi untuk produk ini.';

        // Harga & Diskon
        document.getElementById('modalProductPrice').innerText = 'Rp ' + currentUnitPrice.toLocaleString('id-ID');
        const origPriceEl = document.getElementById('modalProductPriceOriginal');
        if (product.price_original && Number(product.price_original) > currentUnitPrice) {
            origPriceEl.innerText = 'Rp ' + Number(product.price_original).toLocaleString('id-ID');
            origPriceEl.classList.remove('hidden');
        } else {
            origPriceEl.classList.add('hidden');
        }

        // Info Terjual
        document.getElementById('modalProductSold').innerText = (product.total_sold || 0) + ' Terjual';

        // Ulasan Produk (Di bawah Deskripsi)
        renderModalReviews(product.reviews || []);

        // Populate Right Column: Quantity
        const qtyInput = document.getElementById('modalQtyInput');
        qtyInput.value = 1;
        qtyInput.max = Math.max(1, currentStock);

        const qtyStockInfo = document.getElementById('modalQtyStockInfo');
        const submitBtn = document.getElementById('btnSubmitOrder');

        if (currentStock === 1) {
            qtyStockInfo.innerHTML = 'Sisa: <span class="text-red-500 font-bold">Terakhir</span>';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else if (currentStock > 1) {
            qtyStockInfo.innerHTML = 'Sisa: <span class="text-blue-600 font-bold">' + currentStock + '</span>';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            qtyStockInfo.innerHTML = '<span class="text-rose-500 font-bold">Stok Habis</span>';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        cancelRedeemCode();
        updateTotalPrice();

        // Reset Avatar Box & Form State
        document.getElementById('robloxAvatarBox').classList.add('hidden');
        document.getElementById('robloxErrorBox').classList.add('hidden');
        document.getElementById('modalRobloxAvatar').value = '';

        // Tampilkan Modal dengan animasi scale
        const modal = document.getElementById('checkoutModal');
        const box = document.getElementById('modalBox');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function renderModalReviews(reviews) {
        const container = document.getElementById('modalReviewsList');
        const countHeader = document.getElementById('modalReviewHeaderCount');

        if (!reviews || reviews.length === 0) {
            countHeader.innerText = '0 Ulasan';
            container.innerHTML = `
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700 text-center">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Belum ada ulasan untuk produk ini.</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Jadilah pembeli pertama yang memberikan ulasan!</p>
                </div>
            `;
            return;
        }

        countHeader.innerText = reviews.length + ' Ulasan';
        let html = '';
        reviews.forEach(rev => {
            const avatar = rev.roblox_avatar_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(rev.roblox_username) + '&background=38bdf8&color=fff');
            const rating = Number(rev.rating) || 5;
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += i <= rating 
                    ? '<i class="fa-solid fa-star text-amber-400 text-[10px]"></i>' 
                    : '<i class="fa-regular fa-star text-slate-300 dark:text-slate-600 text-[10px]"></i>';
            }

            html += `
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img src="${avatar}" alt="" class="w-6 h-6 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 object-cover flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?name=Roblox&background=38bdf8&color=fff'">
                            <span class="text-xs font-black text-slate-900 dark:text-white">@${escapeHtml(rev.roblox_username)}</span>
                        </div>
                        <div class="flex items-center gap-0.5">
                            ${stars}
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-600 dark:text-slate-300 font-medium leading-relaxed italic pl-1 border-l-2 border-sky-400">
                        "${escapeHtml(rev.comment)}"
                    </p>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function changeQuantity(delta) {
        if (currentStock <= 0) return;
        const input = document.getElementById('modalQtyInput');
        let val = parseInt(input.value) || 1;
        val += delta;
        if (val < 1) val = 1;
        if (val > currentStock) val = currentStock;
        input.value = val;
        updateTotalPrice();
    }

    function handleQuantityChange() {
        if (currentStock <= 0) return;
        const input = document.getElementById('modalQtyInput');
        let val = parseInt(input.value) || 1;
        if (val < 1) val = 1;
        if (val > currentStock) val = currentStock;
        input.value = val;
        updateTotalPrice();
    }

    let appliedRedeem = null;

    function updateTotalPrice() {
        const qty = parseInt(document.getElementById('modalQtyInput')?.value) || 1;
        const subtotal = currentUnitPrice * qty;
        
        let discount = 0;
        if (appliedRedeem && appliedRedeem.discount_percent > 0) {
            // Diskon persen HANYA berlaku untuk 1 produk saja
            discount = Math.round(currentUnitPrice * (appliedRedeem.discount_percent / 100));
        }
        const finalTotal = Math.max(0, subtotal - discount);

        const strikethroughEl = document.getElementById('modalSubtotalStrikethrough');
        const discountBadgeEl = document.getElementById('modalDiscountBadge');
        const totalEl = document.getElementById('modalTotalPrice');

        if (discount > 0) {
            if (strikethroughEl) {
                strikethroughEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
                strikethroughEl.classList.remove('hidden');
            }
            if (discountBadgeEl) {
                discountBadgeEl.innerText = 'Hemat -Rp ' + discount.toLocaleString('id-ID') + ' (' + appliedRedeem.discount_percent + '%)';
                discountBadgeEl.classList.remove('hidden');
            }
            if (totalEl) {
                totalEl.innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');
            }
        } else {
            if (strikethroughEl) strikethroughEl.classList.add('hidden');
            if (discountBadgeEl) discountBadgeEl.classList.add('hidden');
            if (totalEl) {
                totalEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            }
        }
    }

    function applyRedeemCode() {
        const input = document.getElementById('modalRedeemCodeInput');
        if (!input) return;
        const code = input.value.trim().toUpperCase();
        const btn = document.getElementById('btnApplyRedeem');
        const statusBox = document.getElementById('redeemStatusBox');
        const statusIcon = document.getElementById('redeemStatusIcon');
        const statusMsg = document.getElementById('redeemStatusMsg');
        const statusSub = document.getElementById('redeemStatusSub');
        const cancelBtn = document.getElementById('btnCancelRedeemInBox');
        const clearBtn = document.getElementById('modalClearRedeemBtn');
        const hiddenInput = document.getElementById('modalAppliedRedeemCode');

        if (!code) {
            alert('Silakan masukkan kode redeem terlebih dahulu!');
            input.focus();
            return;
        }

        if (!currentProduct) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cek...';

        const qty = parseInt(document.getElementById('modalQtyInput')?.value) || 1;

        fetch('/api/redeem-code/check', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                code: code,
                product_id: currentProduct.id,
                quantity: qty
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Pakai</span>';

            if (data.success) {
                appliedRedeem = {
                    code: data.code,
                    discount_percent: data.discount_percent,
                    discount_amount: data.discount_amount
                };
                hiddenInput.value = data.code;
                input.value = data.code;
                input.readOnly = true;
                input.className = 'w-full pl-3 pr-8 py-2.5 bg-emerald-50 border-2 border-emerald-400 rounded-xl text-xs font-black uppercase tracking-wider text-emerald-800 focus:outline-none transition';
                btn.classList.add('hidden');
                clearBtn?.classList.remove('hidden');

                statusBox.className = 'text-xs rounded-xl p-2.5 flex items-start gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800';
                statusIcon.className = 'fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs';
                statusMsg.innerText = `Kode ${data.code} aktif! Diskon ${data.discount_percent}% (1 produk).`;
                statusSub.innerText = `Hemat ${data.formatted_discount} pada transaksi ini.`;
                cancelBtn?.classList.remove('hidden');

                updateTotalPrice();
            } else {
                appliedRedeem = null;
                hiddenInput.value = '';
                statusBox.className = 'text-xs rounded-xl p-2.5 flex items-start gap-2 bg-rose-50 border border-rose-200 text-rose-800';
                statusIcon.className = 'fa-solid fa-circle-xmark text-rose-600 mt-0.5 text-xs';
                statusMsg.innerText = data.message || 'Kode redeem tidak valid.';
                statusSub.innerText = 'Silakan periksa kembali kode promo Anda.';
                cancelBtn?.classList.add('hidden');
                updateTotalPrice();
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Pakai</span>';
            statusBox.className = 'text-xs rounded-xl p-2.5 flex items-start gap-2 bg-rose-50 border border-rose-200 text-rose-800';
            statusIcon.className = 'fa-solid fa-triangle-exclamation text-rose-600 mt-0.5 text-xs';
            statusMsg.innerText = 'Gagal menghubungi server.';
            statusSub.innerText = 'Periksa koneksi internet Anda lalu coba lagi.';
            cancelBtn?.classList.add('hidden');
        });
    }

    function cancelRedeemCode() {
        appliedRedeem = null;
        const input = document.getElementById('modalRedeemCodeInput');
        const btn = document.getElementById('btnApplyRedeem');
        const statusBox = document.getElementById('redeemStatusBox');
        const clearBtn = document.getElementById('modalClearRedeemBtn');
        const hiddenInput = document.getElementById('modalAppliedRedeemCode');

        if (input) {
            input.value = '';
            input.readOnly = false;
            input.className = 'w-full pl-3 pr-8 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-black uppercase tracking-wider text-slate-900 focus:outline-none focus:border-amber-500 focus:bg-white transition placeholder:normal-case placeholder:font-normal placeholder:tracking-normal placeholder:text-slate-400';
        }
        if (btn) {
            btn.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Pakai</span>';
        }
        if (clearBtn) clearBtn.classList.add('hidden');
        if (hiddenInput) hiddenInput.value = '';
        if (statusBox) statusBox.className = 'hidden text-xs rounded-xl p-2.5 items-start gap-2';

        updateTotalPrice();
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function closeCheckoutModal() {
        const modal = document.getElementById('checkoutModal');
        const box = document.getElementById('modalBox');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    document.getElementById('checkoutModal').addEventListener('click', function(e) {
        if (e.target === this) closeCheckoutModal();
    });

    async function checkRobloxUser() {
        const input = document.getElementById('robloxUsernameInput');
        const username = input.value.trim();
        const btn = document.getElementById('btnCheckRoblox');
        const avatarBox = document.getElementById('robloxAvatarBox');
        const errorBox = document.getElementById('robloxErrorBox');

        if (!username) {
            alert('Silakan masukkan username Roblox terlebih dahulu!');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cek...';
        avatarBox.classList.add('hidden');
        errorBox.classList.add('hidden');

        try {
            const res = await fetch('/api/roblox-avatar?username=' + encodeURIComponent(username));
            const data = await res.json();

            if (data.success) {
                const img = document.getElementById('avatarPreviewImg');
                img.src = data.avatarUrl;
                img.onerror = function() {
                    this.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.username) + '&background=38bdf8&color=fff';
                };

                document.getElementById('robloxVerifiedUsername').innerText = '@' + data.username;
                const display = data.displayName && data.displayName !== 'undefined' ? data.displayName : data.username;
                document.getElementById('robloxDisplayName').innerText = 'Display Name: ' + display;
                document.getElementById('modalRobloxAvatar').value = data.avatarUrl;
                avatarBox.classList.remove('hidden');
            } else {
                document.getElementById('robloxErrorMsg').innerText = data.message || 'Akun tidak ditemukan di Roblox';
                errorBox.classList.remove('hidden');
            }
        } catch (err) {
            document.getElementById('robloxErrorMsg').innerText = 'Gagal menghubungi server Roblox. Coba lagi dalam beberapa detik.';
            errorBox.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i> Cek Avatar';
        }
    }

    // ================= DYNAMIC GAME & CATEGORY FILTERING (0ms, INSTANT, NO RELOAD) =================
    const gameCategoriesMap = <?= json_encode($gameCategoriesMap ?? []) ?>;
    let currentSelectedGame = '<?= addslashes($activeGame) ?>';
    let currentSelectedCategory = '<?= addslashes($activeCategory) ?>';

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function applyCatalogFilters() {
        const game = (currentSelectedGame || 'all').toLowerCase();
        let cat = (currentSelectedCategory || 'semua').toLowerCase();

        // 1. Update Game Cards UI
        const gameCards = document.querySelectorAll('.game-selection-card');
        gameCards.forEach(card => {
            const gid = (card.getAttribute('data-game-id') || '').toLowerCase();
            const badge = card.querySelector('.game-check-badge');
            const label = card.querySelector('.game-status-label');
            const title = card.querySelector('.game-name-label');
            const isSelected = (gid === game) || (game === 'all' && gid === 'all');

            if (isSelected) {
                card.className = 'game-selection-card interactive-card flex flex-col items-center p-4 rounded-2xl border-2 transition-all duration-300 group/card cursor-pointer bg-sky-50/90 border-sky-500 ring-4 ring-sky-200 shadow-md scale-105';
                if (badge) badge.classList.remove('hidden');
                if (label) {
                    label.className = 'game-status-label text-[10px] font-black text-sky-600 mt-0.5';
                    label.textContent = 'Dipilih';
                }
                if (title) title.className = 'game-name-label text-xs font-black text-center text-sky-700 leading-tight';
            } else {
                card.className = 'game-selection-card interactive-card flex flex-col items-center p-4 rounded-2xl border-2 transition-all duration-300 group/card cursor-pointer bg-white hover:bg-white border-slate-200/80 hover:border-sky-300 hover:shadow-card hover:-translate-y-0.5';
                if (badge) badge.classList.add('hidden');
                if (label) {
                    label.className = 'game-status-label text-[10px] font-bold text-slate-400 mt-0.5';
                    label.textContent = 'Pilih';
                }
                if (title) title.className = 'game-name-label text-xs font-black text-center text-slate-800 group-hover/card:text-sky-600 leading-tight';
            }
        });

        // 2. Update Header & Reset Button
        const headerLabel = document.getElementById('catalogActiveGameLabel');
        if (headerLabel) {
            let displayGame = 'Semua Game';
            if (game !== 'all') {
                const activeCard = document.querySelector(`.game-selection-card[data-game-id="${currentSelectedGame}"]`);
                displayGame = activeCard ? activeCard.getAttribute('data-game-name') : currentSelectedGame;
            }
            headerLabel.textContent = 'Katalog: ' + displayGame;
        }

        const resetBtn = document.getElementById('catalogResetGameBtn');
        if (resetBtn) {
            if (game !== 'all') {
                resetBtn.classList.remove('hidden');
                const resetText = document.getElementById('catalogResetGameText');
                if (resetText) resetText.textContent = 'Reset Game: ' + (currentSelectedGame);
            } else {
                resetBtn.classList.add('hidden');
            }
        }

        // 3. Dynamically Rebuild / Update Category Tabs for the Selected Game
        const categoryTabsContainer = document.getElementById('categoryTabsContainer');
        if (categoryTabsContainer) {
            const gameTabs = gameCategoriesMap[game] || gameCategoriesMap['all'] || [{slug: 'semua', name: 'Semua'}];
            
            // Check if current category is valid for this game
            const hasCat = gameTabs.some(t => t.slug.toLowerCase() === cat);
            if (!hasCat && cat !== 'semua') {
                currentSelectedCategory = 'semua';
                cat = 'semua';
            }
            const activeSlug = (currentSelectedCategory || 'semua').toLowerCase();

            let tabsHtml = '';
            gameTabs.forEach(tab => {
                const isTabActive = (tab.slug.toLowerCase() === activeSlug);
                const activeClass = isTabActive 
                    ? 'bg-sky-500 text-white shadow-md shadow-sky-300 dark:shadow-sky-950' 
                    : 'bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700';
                
                tabsHtml += `
                    <button type="button" 
                            data-slug="${escapeHtml(tab.slug)}"
                            onclick="selectCategoryTab('${escapeHtml(tab.slug)}', event)" 
                            class="cat-filter-tab px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-black transition-all duration-200 whitespace-nowrap flex items-center justify-center cursor-pointer ${activeClass}">
                        <span>${escapeHtml(tab.name)}</span>
                    </button>
                `;
            });
            categoryTabsContainer.innerHTML = tabsHtml;
        }

        // 4. Filter Products
        const finalCat = (currentSelectedCategory || 'semua').toLowerCase();
        const searchInput = document.getElementById('catalogSearchInput');
        const liveQuery = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const cards = document.querySelectorAll('.product-card-item');
        let visibleCount = 0;
        cards.forEach(card => {
            const itemGame = (card.getAttribute('data-game') || '').toLowerCase();
            const itemCat = (card.getAttribute('data-category') || '').toLowerCase();
            const itemCatSlug = (card.getAttribute('data-catslug') || '').toLowerCase();
            const itemName = (card.getAttribute('data-name') || '').toLowerCase();
            const itemDesc = (card.getAttribute('data-desc') || '').toLowerCase();

            // Game Match
            let gameMatch = (game === 'all' || itemGame === game);

            // Category Match (Dynamic)
            let catMatch = false;
            const normalizedCat = finalCat.replace(/[^a-z0-9]/g, '');
            const normalizedItemCat = itemCat.replace(/[^a-z0-9]/g, '');
            const normalizedItemCatSlug = itemCatSlug.replace(/[^a-z0-9]/g, '');

            if (finalCat === 'semua' || finalCat === 'all' || !finalCat) {
                catMatch = true;
            } else if (itemCat === finalCat || itemCatSlug === finalCat || normalizedItemCat === normalizedCat || normalizedItemCatSlug === normalizedCat) {
                catMatch = true;
            } else if (finalCat === 'item' && (['item', 'pet', 'egg', 'food', 'weapon', 'potion', 'axe', 'booster', 'monster', 'lasso'].includes(itemCat) || itemCatSlug.includes('item'))) {
                catMatch = true;
            } else if (finalCat === 'akun' && (itemCat === 'akun' || itemCatSlug.includes('akun'))) {
                catMatch = true;
            }

            // Search query match (if user has typed or searched something)
            let searchMatch = true;
            if (liveQuery) {
                searchMatch = itemName.includes(liveQuery) || 
                              itemDesc.includes(liveQuery) || 
                              itemGame.includes(liveQuery) || 
                              itemCat.includes(liveQuery) || 
                              itemCatSlug.includes(liveQuery);
            }

            if (gameMatch && catMatch && searchMatch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // 5. Empty State
        const emptyBox = document.getElementById('noProductsFoundBox');
        if (emptyBox) {
            if (visibleCount === 0) {
                emptyBox.classList.remove('hidden');
                const noText = document.getElementById('noProductsText');
                if (noText) {
                    let gName = game === 'all' ? '' : ` di game ${currentSelectedGame}`;
                    let cName = finalCat === 'semua' ? '' : ` kategori "${finalCat}"`;
                    let qName = liveQuery ? ` dengan pencarian "${liveQuery}"` : '';
                    noText.textContent = `Tidak ada produk${cName}${gName}${qName} saat ini.`;
                }
            } else {
                emptyBox.classList.add('hidden');
            }
        }

        // 6. Update Hidden Inputs for Search Form
        const searchCatInput = document.getElementById('searchCategoryInput');
        if (searchCatInput) searchCatInput.value = currentSelectedCategory;
        const searchGameInput = document.getElementById('searchGameInput');
        if (searchGameInput) searchGameInput.value = currentSelectedGame;

        // 7. Update URL
        try {
            const currentUrl = new URL(window.location.href);
            if (game !== 'all') currentUrl.searchParams.set('game', currentSelectedGame);
            else currentUrl.searchParams.delete('game');

            if (finalCat !== 'semua') currentUrl.searchParams.set('kategori', currentSelectedCategory);
            else currentUrl.searchParams.delete('kategori');

            currentUrl.hash = '';
            window.history.pushState({ game: currentSelectedGame, kategori: currentSelectedCategory }, '', currentUrl.toString());
        } catch (e) {}
    }

    function selectGameFilter(gameId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        currentSelectedGame = gameId;
        currentSelectedCategory = 'semua';
        applyCatalogFilters();

        const katalogEl = document.getElementById('katalog');
        if (katalogEl) {
            katalogEl.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function selectCategoryTab(categorySlug, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        currentSelectedCategory = categorySlug;
        applyCatalogFilters();
    }

    // Live search input listener
    const searchInputEl = document.getElementById('catalogSearchInput');
    if (searchInputEl) {
        searchInputEl.addEventListener('input', function() {
            applyCatalogFilters();
        });
    }

    window.addEventListener('popstate', function(e) {
        try {
            const url = new URL(window.location.href);
            currentSelectedGame = url.searchParams.get('game') || 'all';
            currentSelectedCategory = url.searchParams.get('kategori') || 'semua';
            applyCatalogFilters();
        } catch(e) {}
    });

    // ================= HERO PROMO CAROUSEL LOGIC =================
    let currentHeroSlide = 0;
    const heroSlides = document.querySelectorAll('#heroPromoCarousel .carousel-slide');
    const totalHeroSlides = heroSlides.length;
    const progressBar = document.getElementById('heroProgressBar');
    const slideDuration = 4500; // 4.5 detik tiap slide
    const tickInterval = 45;    // tick setiap 45ms untuk animasi progress bar yang halus
    let elapsed = 0;
    let heroTimer = null;

    function heroGoToSlide(index) {
        if (!totalHeroSlides) return;
        currentHeroSlide = (index + totalHeroSlides) % totalHeroSlides;
        
        heroSlides.forEach((slide, idx) => {
            if (idx === currentHeroSlide) {
                slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0', 'invisible');
                slide.classList.add('opacity-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'pointer-events-none', 'z-0', 'invisible');
            }
        });

        // Update indicator dots
        const indicators = document.querySelectorAll('.hero-indicator');
        indicators.forEach((dot, idx) => {
            if (idx === currentHeroSlide) {
                dot.className = 'hero-indicator w-5 sm:w-8 h-1 sm:h-1.5 rounded-full bg-amber-400 shadow-xs transition-all duration-300 cursor-pointer';
            } else {
                dot.className = 'hero-indicator w-1.5 sm:w-2.5 h-1 sm:h-1.5 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300 cursor-pointer';
            }
        });

        // Reset progress bar setiap perpindahan slide
        elapsed = 0;
        if (progressBar) progressBar.style.width = '0%';
    }

    function heroNextSlide() {
        heroGoToSlide(currentHeroSlide + 1);
    }

    function heroPrevSlide() {
        heroGoToSlide(currentHeroSlide - 1);
    }

    function startHeroTimer() {
        if (heroTimer) clearInterval(heroTimer);
        heroTimer = setInterval(() => {
            elapsed += tickInterval;
            const pct = Math.min((elapsed / slideDuration) * 100, 100);
            if (progressBar) {
                progressBar.style.width = pct + '%';
            }
            if (elapsed >= slideDuration) {
                heroNextSlide();
            }
        }, tickInterval);
    }

    function stopHeroTimer() {
        if (heroTimer) {
            clearInterval(heroTimer);
            heroTimer = null;
        }
    }

    const carouselEl = document.getElementById('heroPromoCarousel');
    if (carouselEl && totalHeroSlides > 0) {
        carouselEl.addEventListener('mouseenter', stopHeroTimer);
        carouselEl.addEventListener('mouseleave', startHeroTimer);

        // Touch swipe support untuk HP
        let touchStartX = 0;
        carouselEl.addEventListener('touchstart', (e) => {
            if (e.changedTouches && e.changedTouches[0]) {
                touchStartX = e.changedTouches[0].screenX;
            }
        }, { passive: true });

        carouselEl.addEventListener('touchend', (e) => {
            if (e.changedTouches && e.changedTouches[0]) {
                const touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 45) {
                    heroNextSlide();
                } else if (touchEndX - touchStartX > 45) {
                    heroPrevSlide();
                }
            }
        }, { passive: true });

        startHeroTimer();
    }

    // ================= MOBILE REVIEW SLIDER LOGIC =================
    let currentReviewSlide = 0;
    const reviewItems = document.querySelectorAll('.review-slide-item');
    const totalReviewSlides = reviewItems.length;
    const reviewTrack = document.getElementById('reviewSlideTrack');
    const reviewCounter = document.getElementById('reviewSlideCounter');
    let reviewAutoTimer = null;

    function reviewGoToSlide(idx) {
        if (!totalReviewSlides || !reviewTrack) return;
        currentReviewSlide = (idx + totalReviewSlides) % totalReviewSlides;
        
        // Geser 1 kartu penuh (100%) khusus di mobile
        if (window.innerWidth < 768) {
            reviewTrack.style.transform = `translateX(-${currentReviewSlide * 100}%)`;
        } else {
            reviewTrack.style.transform = 'none';
        }

        if (reviewCounter) {
            reviewCounter.textContent = `${currentReviewSlide + 1} / ${totalReviewSlides}`;
        }

        const dots = document.querySelectorAll('.review-dot');
        dots.forEach((dot, i) => {
            if (i === currentReviewSlide) {
                dot.className = 'review-dot h-1.5 rounded-full bg-sky-500 w-5 transition-all duration-300 cursor-pointer';
            } else {
                dot.className = 'review-dot h-1.5 rounded-full bg-slate-300 w-2 transition-all duration-300 cursor-pointer';
            }
        });
    }

    function reviewNextSlide() {
        reviewGoToSlide(currentReviewSlide + 1);
    }

    function reviewPrevSlide() {
        reviewGoToSlide(currentReviewSlide - 1);
    }

    function startReviewAutoSlide() {
        if (reviewAutoTimer) clearInterval(reviewAutoTimer);
        if (window.innerWidth < 768 && totalReviewSlides > 1) {
            reviewAutoTimer = setInterval(() => {
                reviewNextSlide();
            }, 3800);
        }
    }

    function stopReviewAutoSlide() {
        if (reviewAutoTimer) {
            clearInterval(reviewAutoTimer);
            reviewAutoTimer = null;
        }
    }

    const reviewBoxEl = document.getElementById('reviewCarouselContainer');
    if (reviewBoxEl && totalReviewSlides > 1) {
        reviewBoxEl.addEventListener('mouseenter', stopReviewAutoSlide);
        reviewBoxEl.addEventListener('mouseleave', startReviewAutoSlide);
        reviewBoxEl.addEventListener('touchstart', stopReviewAutoSlide, { passive: true });
        reviewBoxEl.addEventListener('touchend', () => {
            setTimeout(startReviewAutoSlide, 2000);
        }, { passive: true });

        // Touch swipe support di mobile
        let touchStartXRev = 0;
        reviewBoxEl.addEventListener('touchstart', (e) => {
            if (e.changedTouches && e.changedTouches[0]) {
                touchStartXRev = e.changedTouches[0].screenX;
            }
        }, { passive: true });

        reviewBoxEl.addEventListener('touchend', (e) => {
            if (e.changedTouches && e.changedTouches[0]) {
                const touchEndXRev = e.changedTouches[0].screenX;
                if (touchStartXRev - touchEndXRev > 40) {
                    reviewNextSlide();
                } else if (touchEndXRev - touchStartXRev > 40) {
                    reviewPrevSlide();
                }
            }
        }, { passive: true });

        window.addEventListener('resize', () => {
            reviewGoToSlide(currentReviewSlide);
        });

        startReviewAutoSlide();
    }
</script>

<?php
$bodyContent = ob_get_clean();
require __DIR__ . '/layout.php';
?>
