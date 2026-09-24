<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentBuyer = $_SESSION['buyer_user'] ?? null;
$waAdmin = preg_replace('/[^0-9]/', '', (string)($settings['whatsapp_admin'] ?? '6281234567890'));
$waDisplay = $settings['whatsapp_display'] ?? '+62 812-3456-7890';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['store_name'] ?? 'ItemPedia') ?> - Marketplace Item & Akun Roblox</title>
    
    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" href="/images/logo-icon.png">
    <link rel="icon" type="image/svg+xml" href="/images/logo-icon.svg">
    <link rel="apple-touch-icon" href="/images/logo-icon@2x.png">
    
    <!-- Tailwind CSS CDN with Dark Mode -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        pastel: {
                            50: '#f0f7ff',
                            100: '#e0f0fe',
                            200: '#bae2fd',
                            300: '#7ccafc',
                            400: '#38adf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1'
                        }
                    },
                    animation: {
                        'float-slow': 'floatSlow 5s ease-in-out infinite',
                        'float-reverse': 'floatReverse 6s ease-in-out infinite',
                        'pulse-gentle': 'pulseGentle 3s ease-in-out infinite',
                        'shimmer-wave': 'shimmerWave 3s infinite linear',
                        'bounce-subtle': 'bounceSubtle 2s infinite ease-in-out',
                        'spin-slow': 'spin 12s linear infinite'
                    },
                    keyframes: {
                        floatSlow: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-10px) rotate(1deg)' }
                        },
                        floatReverse: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(10px) rotate(-1deg)' }
                        },
                        pulseGentle: {
                            '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                            '50%': { opacity: '0.88', transform: 'scale(1.02)' }
                        },
                        shimmerWave: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' }
                        },
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-4px)' }
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Instant Theme Initialization Script (Anti-Flicker) -->
    <script>
        if (localStorage.getItem('itempedia_theme') === 'dark' || (!('itempedia_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 96px;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef6fc;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        html.dark body {
            background: #081220;
            color: #f1f5f9;
        }
        html.dark ::-webkit-scrollbar-track {
            background: #0c182c;
        }
        html.dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
        html.dark ::-webkit-scrollbar-thumb:hover {
            background: #38bdf8;
        }
        /* Highlight Pulse Animation when Navigation Scrolldown Reaches Target */
        @keyframes targetSectionGlow {
            0% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0); }
            35% { box-shadow: 0 0 0 10px rgba(14, 165, 233, 0.28); }
            100% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0); }
        }
        .scroll-target-glow {
            animation: targetSectionGlow 1.6s ease-out;
            border-radius: 1.5rem;
        }
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom Smooth Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0ea5e9;
        }
        /* Sembunyikan scrollbar untuk elemen dengan class scrollbar-none */
        .scrollbar-none::-webkit-scrollbar,
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .scrollbar-none,
        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
        /* Marquee Animation (Hardware Accelerated) */
        @keyframes marquee {
            0% { transform: translate3d(0%, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }
        .animate-marquee {
            display: flex;
            width: 200%;
            animation: marquee 25s linear infinite;
            will-change: transform;
            transform: translate3d(0, 0, 0);
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
        .animate-marquee-live {
            display: flex;
            width: max-content;
            animation: marquee 70s linear infinite;
            will-change: transform;
            transform: translate3d(0, 0, 0);
        }
        .animate-marquee-live:hover {
            animation-play-state: paused;
        }
        .mask-linear-fade {
            mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
        }
        /* Card Hover Depth - Lightweight GPU Translation */
        .interactive-card {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
            will-change: transform;
            transform: translateZ(0);
        }
        .interactive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 28px -6px rgba(14, 165, 233, 0.18);
        }
        /* Shimmer Button */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
            transform: translateZ(0);
        }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                60deg,
                transparent,
                rgba(255, 255, 255, 0.35),
                transparent
            );
            transform: rotate(25deg) translateY(-100%);
            transition: transform 0.5s ease;
        }
        .btn-shimmer:hover::after {
            transform: rotate(25deg) translateY(100%);
        }
        /* Dynamic Laser Scanline for QRIS UI */
        @keyframes scanlineSweep {
            0% { transform: translateY(0px); opacity: 0.7; }
            50% { transform: translateY(95px); opacity: 1; filter: drop-shadow(0 0 6px #06b6d4); }
            100% { transform: translateY(0px); opacity: 0.7; }
        }
        .animate-scanline {
            animation: scanlineSweep 2.5s ease-in-out infinite;
        }

        /* Ambient Glowing Pulse */
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 4px rgba(14, 165, 233, 0.3)); }
            50% { transform: scale(1.03); filter: drop-shadow(0 0 14px rgba(14, 165, 233, 0.55)); }
        }
        .animate-pulse-glow {
            animation: pulseGlow 3.5s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-[#eef6fc] text-slate-800 min-h-screen flex flex-col selection:bg-sky-200 selection:text-sky-950">

    <!-- Static Ambient Background Gradients (Optimized for smooth 60fps scrolling) -->
    <div class="fixed -top-20 -left-20 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl pointer-events-none -z-10 transform-gpu"></div>
    <div class="fixed top-1/3 -right-20 w-96 h-96 bg-blue-200/35 rounded-full blur-3xl pointer-events-none -z-10 transform-gpu"></div>
    <div class="fixed -bottom-20 left-1/3 w-96 h-96 bg-indigo-100/35 rounded-full blur-3xl pointer-events-none -z-10 transform-gpu"></div>

    <!-- Master Header / Navbar: Lightweight Glass -->
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-[#0c1e33]/95 backdrop-blur-md border-b-2 border-sky-100 dark:border-slate-800 shadow-xs transition-colors">
        <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Logo & Brand Badge -->
                <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                    <a href="/" class="flex items-center gap-2 sm:gap-3 group min-w-0">
                        <div class="w-9 h-9 sm:w-12 sm:h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-all duration-300">
                            <img src="/images/logo-icon.png" srcset="/images/logo-icon@2x.png 2x" alt="ItemPedia" class="w-full h-full object-contain filter drop-shadow-sm group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-950 dark:text-white truncate">
                                    Item<span class="text-sky-600 dark:text-sky-400">Pedia</span>
                                </span>
                                <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-100 dark:bg-sky-950/80 text-sky-700 dark:text-sky-300 border border-sky-300 dark:border-sky-700">ROBLOX</span>
                            </div>
                            <p class="hidden sm:block text-[11px] text-slate-500 dark:text-slate-400 font-bold tracking-tight">
                                Toko Item & Akun Terpercaya
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Nav Links with Micro-Interactions -->
                <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
                    <a href="/#katalog" class="p-2 sm:px-3.5 sm:py-2 text-xs sm:text-sm font-black text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 rounded-xl sm:rounded-2xl hover:bg-sky-50 dark:hover:bg-slate-800/80 hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5" title="Katalog Produk">
                        <i class="fa-solid fa-gamepad text-sky-500 dark:text-sky-400 text-sm sm:text-base"></i>
                        <span class="hidden sm:inline">Katalog</span>
                    </a>

                    <!-- Tombol Keranjang Belanja -->
                    <button type="button" 
                            onclick="openCartDrawer()" 
                            class="relative p-2 sm:px-3.5 sm:py-2 text-xs sm:text-sm font-black text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 rounded-xl sm:rounded-2xl hover:bg-sky-50 dark:hover:bg-slate-800/80 hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer group"
                            title="Keranjang Belanja">
                        <div class="relative flex items-center justify-center">
                            <i class="fa-solid fa-cart-shopping text-sky-500 dark:text-sky-400 text-sm sm:text-base group-hover:rotate-6 transition-transform"></i>
                            <span id="navCartCount" class="hidden absolute -top-2 -right-2 min-w-[16px] h-[16px] px-1 bg-rose-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-white dark:border-slate-800 shadow-xs animate-pulse">0</span>
                        </div>
                        <span class="hidden sm:inline">Keranjang</span>
                    </button>

                    <!-- Tombol Ganti Tema Gelap / Terang (Dark Mode Toggle) -->
                    <button type="button" 
                            id="themeToggleBtn" 
                            onclick="toggleSiteTheme()" 
                            class="p-2 sm:px-3 sm:py-2 text-xs sm:text-sm font-black text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-amber-400 rounded-xl sm:rounded-2xl bg-slate-50 dark:bg-slate-800/80 hover:bg-sky-50 dark:hover:bg-slate-700/80 border border-slate-200/80 dark:border-slate-700/80 hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs" 
                            title="Ganti Mode Gelap / Terang">
                        <i id="themeToggleIcon" class="fa-solid fa-moon text-sky-500 dark:text-amber-400 text-sm sm:text-base transition-transform duration-300"></i>
                        <span class="hidden xl:inline text-xs font-bold" id="themeToggleLabel">Mode</span>
                    </button>

                    <!-- Buyer Auth: Tombol Google Sign-In / Profil Pembeli -->
                    <?php if (empty($currentBuyer)): ?>
                        <button type="button" 
                                onclick="openGoogleLoginModal()" 
                                class="btn-shimmer flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3.5 py-1.5 sm:py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border-2 border-slate-200 dark:border-slate-700 hover:border-sky-300 dark:hover:border-sky-500 rounded-xl sm:rounded-2xl shadow-2xs hover:shadow-xs transition-all active:scale-95 text-slate-800 dark:text-slate-100 font-bold text-xs sm:text-sm"
                                title="Masuk dengan Google">
                            <!-- Official Google 4-Color Icon -->
                            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span class="hidden sm:inline">Masuk Google</span>
                            <span class="sm:hidden text-xs">Masuk</span>
                        </button>
                    <?php else: ?>
                        <!-- Profil Pembeli Terverifikasi Google -->
                        <div class="relative" id="buyerProfileDropdownWrap">
                            <button type="button" 
                                    onclick="toggleBuyerDropdown()" 
                                    class="flex items-center gap-1.5 p-1 sm:px-2.5 sm:py-1.5 bg-white dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-slate-700 border-2 border-sky-300 dark:border-sky-600 rounded-xl sm:rounded-2xl transition shadow-2xs active:scale-95">
                                <div class="relative flex-shrink-0">
                                    <img src="<?= htmlspecialchars($currentBuyer['avatar_url']) ?>" 
                                         alt="Google User" 
                                         class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-sky-400 object-cover bg-white">
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full bg-white border border-slate-200 flex items-center justify-center p-0.5 shadow-2xs">
                                        <svg class="w-full h-full" viewBox="0 0 24 24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                        </svg>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-slate-800 dark:text-slate-100 max-w-[80px] sm:max-w-[120px] truncate hidden md:inline">
                                    <?= htmlspecialchars($currentBuyer['name']) ?>
                                </span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="buyerDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-[#0f172a] rounded-2xl shadow-xl border-2 border-sky-100 dark:border-slate-800 py-2 z-50 animate-in fade-in zoom-in-95">
                                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Akun Google</p>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate"><?= htmlspecialchars($currentBuyer['name']) ?></p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate"><?= htmlspecialchars($currentBuyer['email']) ?></p>
                                </div>
                                <a href="/pesanan-saya" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-sky-50 dark:hover:bg-slate-800 hover:text-sky-600 dark:hover:text-sky-400 transition">
                                    <i class="fa-solid fa-receipt text-amber-500 text-sm"></i>
                                    <span>Pesanan Saya</span>
                                </a>
                                <a href="/auth/logout" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition border-t border-slate-100 dark:border-slate-800">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                                    <span>Keluar Akun</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <?= $bodyContent ?? '' ?>
    </main>

    <!-- Master Footer -->
    <footer class="bg-white dark:bg-[#0c1e33] border-t-2 border-sky-100 dark:border-slate-800 mt-28 pt-16 pb-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-14">
                
                <!-- Col 1: Brand & Bio -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                            <img src="/images/logo-icon.png" srcset="/images/logo-icon@2x.png 2x" alt="ItemPedia" class="w-full h-full object-contain filter drop-shadow-xs">
                        </div>
                        <span class="text-2xl font-black text-slate-900 dark:text-white">
                            Item<span class="text-sky-600 dark:text-sky-400">Pedia</span>
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 max-w-md leading-relaxed font-medium">
                        <?= htmlspecialchars($settings['store_tagline'] ?? 'Platform toko Roblox mandiri tangan pertama. Jual beli item game Chop Your Tree, Build A Zoo, Catch and Tame, dan Akun Roblox siap pakai.') ?>
                    </p>
                    <div class="flex flex-wrap items-center gap-3 text-xs font-black text-slate-700 dark:text-slate-200 pt-2">
                        <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 px-3 py-1.5 rounded-xl border border-emerald-200 dark:border-emerald-800">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Akun Polosan Asli</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sky-700 dark:text-sky-300 bg-sky-50 dark:bg-sky-950/50 px-3 py-1.5 rounded-xl border border-sky-200 dark:border-sky-800">
                            <i class="fa-solid fa-bolt"></i>
                            <span>Proses Cepat 3-5 Mnt</span>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Kategori Favorit -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white mb-4">
                        Kategori Game
                    </h4>
                    <ul class="space-y-2.5 text-xs font-bold text-slate-600 dark:text-slate-300">
                        <li><a href="/?game=Build+A+Zoo#katalog" class="hover:text-sky-600 dark:hover:text-sky-400 transition flex items-center gap-2"><i class="fa-solid fa-arrow-right text-[9px] text-sky-500"></i> Build A Zoo (Mucy, Chomp, Dino)</a></li>
                        <li><a href="/?kategori=item-game#katalog" class="hover:text-sky-600 dark:hover:text-sky-400 transition flex items-center gap-2"><i class="fa-solid fa-arrow-right text-[9px] text-sky-500"></i> Item & Pet Game Roblox</a></li>
                        <li><a href="/?kategori=akun-game#katalog" class="hover:text-sky-600 dark:hover:text-sky-400 transition flex items-center gap-2"><i class="fa-solid fa-arrow-right text-[9px] text-sky-500"></i> Akun Sultan & Polosan Siap Pakai</a></li>
                    </ul>
                </div>

                <!-- Col 3: Pembayaran -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">
                        Metode Pembayaran
                    </h4>
                    <p class="text-xs text-slate-600 mb-3 leading-relaxed font-medium">
                        Pembayaran otomatis instan via QRIS:
                    </p>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- 1. QRIS -->
                        <div class="h-11 px-2.5 rounded-xl bg-white border border-slate-200/90 shadow-xs hover:border-slate-300 hover:shadow-sm transition flex items-center justify-center group" title="QRIS Standard">
                            <svg class="h-6 w-auto max-w-[82%] object-contain group-hover:scale-105 transition-transform" viewBox="0 0 80 30" fill="none">
                                <g fill="#0f172a">
                                    <path d="M76.723 10.477H64.024V7.936h12.699v-5.08H56.405v12.699h12.699v2.541H56.405v5.08h20.318zM53.867 2.856h-5.08v20.317h5.08zm-27.937 0v5.08h15.238v2.541H25.931v12.699h5.077v-7.544l7.621 7.544h7.619l-7.95-7.621h7.95V2.856zM10.69 15.555h5.067v-5.067h-5.066zm1.273-3.808H14.5v2.538h-2.538z"/>
                                    <path d="M8.152 2.856H3.707a.64.64 0 0 0-.587.393.6.6 0 0 0-.048.244V22.54a.635.635 0 0 0 .635.634H15.77v-5.066H8.152zm14.603 0H10.69v5.08h7.621v7.619h5.067V3.493a.64.64 0 0 0-.179-.45.65.65 0 0 0-.445-.187m.638 15.24h-5.08v11.43h5.08z"/>
                                    <path d="M10.16 0H.635A.637.637 0 0 0 0 .635v9.525h1.27V1.893a.637.637 0 0 1 .634-.624h8.267zm68.57 16.507v8.266a.637.637 0 0 1-.634.635h-8.267v1.259h9.526a.64.64 0 0 0 .645-.635v-9.525z"/>
                                </g>
                            </svg>
                        </div>

                        <!-- 2. GoPay -->
                        <div class="h-11 px-2.5 rounded-xl bg-white border border-slate-200/90 shadow-xs hover:border-slate-300 hover:shadow-sm transition flex items-center justify-center group" title="GoPay">
                            <svg class="h-4.5 w-auto max-w-[85%] object-contain group-hover:scale-105 transition-transform" viewBox="0 0 80 18" fill="none">
                                <path fill="#00AED6" d="M8.732 17.463A8.732 8.732 0 1 0 8.732 0a8.732 8.732 0 0 0 0 17.463"/>
                                <path fill="#fff" fill-rule="evenodd" d="M13.817 8.46A2.03 2.03 0 0 0 11.7 6.55H6.185a.364.364 0 0 1 0-.728h5.588a1.74 1.74 0 0 0-1.274-1.604 14.2 14.2 0 0 0-4.922 0A2.32 2.32 0 0 0 3.831 6.16a17.3 17.3 0 0 0 0 5.17c.196.998.988 1.77 1.99 1.943 2.02.251 4.065.251 6.086 0a2.13 2.13 0 0 0 1.689-1.834c.18-.982.255-1.981.221-2.98m-1.811 1.222v.324a.364.364 0 0 1-.728 0v-.324a.546.546 0 1 1 .728 0" clip-rule="evenodd"/>
                                <path fill="#0f172a" d="M24.366 13.078a3.75 3.75 0 0 0 3.263 1.595c1.52 0 2.639-.972 2.639-2.292v-.696h-.037a4.14 4.14 0 0 1-3.134 1.173 5.079 5.079 0 1 1-.092-10.155 4.36 4.36 0 0 1 3.226 1.136h.037v-.953h2.603v9.459c0 2.75-2.181 4.656-5.242 4.656a6.26 6.26 0 0 1-5.206-2.31zm5.793-5.884c0-1.1-1.247-2.108-2.64-2.108-1.76 0-2.933 1.063-2.933 2.658a2.568 2.568 0 0 0 2.823 2.731c1.522 0 2.75-.953 2.75-2.145zM39.69 2.61c3.172 0 5.482 2.255 5.482 5.133s-2.31 5.132-5.482 5.132a5.143 5.143 0 1 1 0-10.265m0 2.383a2.75 2.75 0 1 0 2.769 2.75 2.63 2.63 0 0 0-2.769-2.75m6.948-2.108h2.604v.862h.037a4.32 4.32 0 0 1 3.133-1.137 5.134 5.134 0 0 1 .019 10.265 4.7 4.7 0 0 1-3.043-1.026h-.037v4.876h-2.713zm5.354 2.126c-1.431 0-2.64 1.009-2.64 2.109v1.228c0 1.173 1.173 2.144 2.657 2.144a2.74 2.74 0 0 0-.017-5.48M62.568 6.81c1.778-.238 2.31-.495 2.31-.99 0-.642-.678-1.026-1.723-1.026a2.29 2.29 0 0 0-2.42 1.74l-2.567-.53c.367-1.98 2.402-3.392 4.913-3.392 2.841 0 4.602 1.449 4.602 3.813V12.6h-2.439v-1.063h-.037a4.01 4.01 0 0 1-3.281 1.338c-2.145 0-3.629-1.173-3.629-2.896 0-1.815 1.21-2.75 4.271-3.171m2.53 1.027h-.037c-.239.348-.752.55-2.07.788-1.597.293-2.164.604-2.164 1.173 0 .587.476.843 1.502.843 1.56 0 2.769-.715 2.769-1.65zm7.496 4.234-4.49-9.184h2.988l2.95 6.342h.037l2.915-6.342H80l-6.726 13.84h-2.99z"/>
                            </svg>
                        </div>

                        <!-- 3. DANA -->
                        <div class="h-11 px-2.5 rounded-xl bg-white border border-slate-200/90 shadow-xs hover:border-slate-300 hover:shadow-sm transition flex items-center justify-center group" title="DANA">
                            <svg class="h-4.5 w-auto max-w-[85%] object-contain group-hover:scale-105 transition-transform" viewBox="0 0 80 23" fill="none">
                                <path fill="#008CEB" fill-rule="evenodd" d="M31.623 17.349c1.52.109 3.292-1.073 3.916-1.747 2.17-2.348 2.222-5.742.038-8.201-.813-.915-2.722-1.927-3.93-1.784H27.27v11.717l4.363.015zm-1.885-2.539V8.14a16 16 0 0 1 2.057 0 3.2 3.2 0 0 1 1.493.578c2.513 1.718 1.466 5.812-1.535 6.086q-1.007.071-2.015.006m40.292 2.56h2.494v-2.526h4.948l.015 2.513h2.494v-4.163c0-1.257.105-2.823-.21-3.96a4.947 4.947 0 0 0-9.526-.053c-.335 1.123-.222 2.733-.222 3.98 0 1.407-.015 2.822 0 4.23zm2.496-5.066c-.015-1.047-.126-2.168.366-2.974a2.43 2.43 0 0 1 2.126-1.209 2.47 2.47 0 0 1 2.11 1.22c.487.837.371 1.884.359 2.965zm-31.02 5.064h2.486c0-.528-.067-2.143.023-2.526h4.93l.016 2.523h2.49c-.012-1.398 0-2.795 0-4.188 0-1.194.111-2.903-.21-3.962a4.948 4.948 0 0 0-9.526-.049c-.333 1.09-.22 2.754-.22 3.967 0 1.399-.031 2.834 0 4.228zm2.488-5.064c-.017-1.047-.124-2.183.368-2.972a2.453 2.453 0 0 1 4.237 0c.488.838.37 1.901.362 2.967zm11.832-2.111-.035 1.744c0 1.466-.072 4.203 0 5.395h2.437c.057-.838-.088-6.788.065-6.978 0-1.156 1.209-2.233 2.427-2.237a2.43 2.43 0 0 1 1.7.679c.31.283.786.928.778 1.579.113.065.023 6.283.059 6.972h2.44l-.02-7.11c.061-.557-.274-1.418-.49-1.854a4.934 4.934 0 0 0-8.852-.075c-.21.43-.561 1.313-.509 1.885" clip-rule="evenodd"/>
                                <path fill="#008CEB" d="M11.437 22.873c6.316 0 11.436-5.12 11.436-11.436C22.873 5.12 17.753 0 11.437 0 5.12 0 0 5.12 0 11.437s5.12 11.436 11.437 11.436"/>
                                <path fill="#FEFEFE" d="M18.1 11.485v3.219c0 .21-.09.253-.265.15a6 6 0 0 0-.733-.372 5 5 0 0 0-2.407-.32c-.89.115-1.765.334-2.605.653-.838.282-1.659.588-2.513.825a7 7 0 0 1-2.182.287 4.63 4.63 0 0 1-2.304-.719.57.57 0 0 1-.274-.506V8.377c0-.103 0-.224.092-.276s.186.033.266.085a4.4 4.4 0 0 0 2.544.754 6.8 6.8 0 0 0 1.902-.354c.871-.27 1.713-.628 2.57-.932q1-.385 2.046-.628a4.44 4.44 0 0 1 3.503.647.77.77 0 0 1 .367.685V11.5z"/>
                            </svg>
                        </div>

                        <!-- 4. OVO -->
                        <div class="h-11 px-2.5 rounded-xl bg-white border border-slate-200/90 shadow-xs hover:border-slate-300 hover:shadow-sm transition flex items-center justify-center group" title="OVO">
                            <svg class="h-5 w-auto max-w-[85%] object-contain group-hover:scale-105 transition-transform" viewBox="0 0 77 24" fill="none">
                                <path d="M21.19,20.24a12.54,12.54,0,0,1-8.8,3.45,12.59,12.59,0,0,1-8.85-3.45,11.68,11.68,0,0,1,0-16.77A12.55,12.55,0,0,1,12.4,0a12.54,12.54,0,0,1,8.8,3.45,11.67,11.67,0,0,1,0,16.77M12.4,3.86a7.73,7.73,0,0,0-7.8,8,7.78,7.78,0,1,0,15.56,0,7.72,7.72,0,0,0-7.75-8m38-1.07L40.89,24l-.54-.1c-2.72-.49-3.27-1.17-4.54-3.93L28.68,4.44H26V.67H36.13V4.44H33.71l5.73,12.85L45,4.44H42V.67h8.4Zm23,17.45a13,13,0,0,1-17.64,0,11.68,11.68,0,0,1,0-16.77,13,13,0,0,1,17.64,0,11.65,11.65,0,0,1,0,16.77M64.65,3.86a7.74,7.74,0,0,0-7.81,8,7.78,7.78,0,1,0,15.56,0,7.72,7.72,0,0,0-7.75-8" fill="#4c2a86" fill-rule="evenodd"/>
                            </svg>
                        </div>

                        <!-- 5. ShopeePay -->
                        <div class="h-11 px-2.5 rounded-xl bg-white border border-slate-200/90 shadow-xs hover:border-slate-300 hover:shadow-sm transition flex items-center justify-center group" title="ShopeePay">
                            <svg class="h-6 w-auto max-w-[85%] object-contain group-hover:scale-105 transition-transform" viewBox="0 0 80 36" fill="none">
                                <g fill-rule="evenodd" clip-rule="evenodd">
                                    <path fill="#EE4D2D" d="m24.955.007-23.9 3.187S0 3.317 0 4.59v22.697s.144 2.656 3.18 2.656h27.087s2.414-.577 2.414-2.994V8.22s.047-2.656-3.138-2.656H3.423a1.66 1.66 0 0 1-1.274-1.238c-.08-.42.09-.464.536-.464H26.67V1.275S26.585-.112 24.955.007"/>
                                    <path fill="#fff" d="M16.825 26.09c-1.982 0-3.441-.807-5.594-2.937a.978.978 0 0 1 1.376-1.389c2.267 2.241 3.317 2.798 5.128 2.308 1.053-.289 1.997-1.627 2.052-3.166.043-1.195-.8-1.976-2.506-2.317-3.063-.613-4.872-1.784-5.374-3.483-.403-1.367.127-2.894 1.455-4.182l.055-.05c2.856-2.386 6.17-.608 7.525.667a.978.978 0 0 1-1.338 1.425c-.114-.107-2.68-2.437-4.903-.614-.76.751-1.104 1.574-.916 2.206.271.92 1.65 1.673 3.88 2.12 3.792.758 4.115 3.263 4.077 4.305-.08 2.268-1.978 4.809-3.76 5.034a9 9 0 0 1-1.157.073"/>
                                    <path fill="#EE4D2D" d="M42.734 5.427a5.3 5.3 0 0 0-1.39-.596c-1.01-.288-1.738-.657-2.086-1.129-.31-.41-.31-.943.062-1.627.21-.41.721-.669 1.377-.72.83-.034 1.654.16 2.384.557a.443.443 0 0 0 .397-.793C42.46.597 41.458.4 40.624.462c-.97.073-1.73.496-2.097 1.178-.56 1.03-.522 1.874 0 2.581.483.633 1.364 1.118 2.568 1.452a5 5 0 0 1 1.178.496c.472.289.782.622.932.981.144.339.164.718.058 1.07a2.5 2.5 0 0 1-.237.522c0 .025-.024.05-.049.087-.36.533-.943.806-1.651.806-.782 0-1.738-.31-2.754-.93a6 6 0 0 1-.41-.261.447.447 0 0 0-.496.744q.219.137.421.298c1.167.708 2.271 1.054 3.214 1.054 1.019 0 1.862-.396 2.396-1.19a1 1 0 0 1 .075-.125 3 3 0 0 0 .334-.744 2.4 2.4 0 0 0-.1-1.663 3.07 3.07 0 0 0-1.273-1.391m6.839-.981a2.86 2.86 0 0 0-1.54.05 4.2 4.2 0 0 0-1.539.864V.884a.447.447 0 0 0-.893 0v9.22a.447.447 0 0 0 .893 0v-3.45l.025-.023c.56-.684 1.18-1.105 1.775-1.29a1.9 1.9 0 0 1 1.055-.038c.284.077.533.247.707.484.194.3.289.653.273 1.01v3.298a.447.447 0 1 0 .894 0V6.792a2.54 2.54 0 0 0-.447-1.514 2.16 2.16 0 0 0-1.203-.832m7.594 4.567a2.22 2.22 0 0 1-3.128 0 2.22 2.22 0 0 1 0-3.128 2.211 2.211 0 0 1 3.128 3.128M55.603 4.36a3.09 3.09 0 0 0 0 6.179 3.09 3.09 0 0 0 0-6.18m9.083 4.654a2.24 2.24 0 0 1-1.563.65A2.21 2.21 0 0 1 61.56 5.89a2.24 2.24 0 0 1 1.563-.645 2.21 2.21 0 0 1 1.563 3.768M63.135 4.36a3.08 3.08 0 0 0-2.184.905 1 1 0 0 0-.1.1v-.558a.447.447 0 0 0-.893 0v8.785a.447.447 0 1 0 .894 0V9.534q.055.045.1.1a3.09 3.09 0 1 0 2.183-5.274M68.5 6.766c.11-.36.308-.689.576-.954a1.88 1.88 0 0 1 2.68 0c.266.27.47.596.597.954zm3.884-1.586a2.72 2.72 0 0 0-1.96-.819 2.65 2.65 0 0 0-1.962.82c-1.067 1.153-1.054 2.394-.633 3.648.188.534.557.986 1.042 1.277.494.308 1.07.46 1.652.433a5.7 5.7 0 0 0 2.059-.472.444.444 0 0 0 .157-.741.445.445 0 0 0-.505-.078 4.5 4.5 0 0 1-1.73.41 2.14 2.14 0 0 1-1.167-.297 1.9 1.9 0 0 1-.695-.866 4 4 0 0 1-.197-.819h4.107c.223 0 .956.05.576-1.204a3 3 0 0 0-.747-1.293zm2.893 1.586c.11-.36.308-.689.577-.954a1.885 1.885 0 0 1 2.68 0c.267.27.47.596.596.954zm4.727.466v-.326a2.6 2.6 0 0 0-.098-.432 3 3 0 0 0-.745-1.298 2.7 2.7 0 0 0-1.96-.82 2.65 2.65 0 0 0-1.961.82c-1.067 1.153-1.056 2.395-.633 3.648a2.43 2.43 0 0 0 1.042 1.278c.494.308 1.07.459 1.651.433a5.6 5.6 0 0 0 2.06-.472.446.446 0 1 0-.348-.82 4.5 4.5 0 0 1-1.73.41 2.14 2.14 0 0 1-1.167-.297 1.9 1.9 0 0 1-.695-.865 4 4 0 0 1-.2-.819h4.11c.162.001.595.027.67-.44zM45.03 22.064h-4.637v-6.739h4.182q.108 0 .215.012c.637.07 3.42.552 3.42 3.537 0 3.187-3.182 3.187-3.182 3.187zm-.818-9.307h-6.43v17.185h2.611v-5.13h4.098a7.7 7.7 0 0 0 3.92-1.032c1.406-.832 2.81-2.299 2.81-4.834 0-2.378-1.234-3.886-2.546-4.82a7.7 7.7 0 0 0-4.463-1.372zm13.472 14.819a3.867 3.867 0 1 1 3.867-3.874v.01a3.866 3.866 0 0 1-3.867 3.864m3.867-9.85v1.21a6.147 6.147 0 1 0 0 9.547v1.368h2.608V17.726zm3.781-.166h3.488l4.09 7.831 3.948-7.83H80v.021l-9.37 17.442h-3.07l3.577-6.482z"/>
                                </g>
                            </svg>
                        </div>

                        <!-- 6. BCA -->
                        <div class="h-11 px-2.5 rounded-xl bg-white border border-slate-200/90 shadow-xs hover:border-slate-300 hover:shadow-sm transition flex items-center justify-center group" title="BCA Virtual Account / QR">
                            <svg class="h-4.5 w-auto max-w-[85%] object-contain group-hover:scale-105 transition-transform" viewBox="0 0 80 26" fill="none">
                                <g fill="#0060AF">
                                    <path d="M11.788 19.03c0-.999.01-3.67-.014-3.999.022-3.974-2.868-6.778-4.694-6.56-1.263.11-2.322.625-2.89 2.107-.527 1.381-.056 3.218 1.695 3.65 1.874.462 2.967.847 3.759 1.39.97.666 1.762 1.937 1.783 3.414"/>
                                    <path d="M12.535 25.072c-3.302 0-6.697-.813-10.086-2.422l-.084-.041-.04-.085C.806 19.314 0 15.801 0 12.365c0-3.43.771-6.793 2.294-10l.042-.085.084-.042C5.556.752 8.93 0 12.45 0c3.279 0 6.78.838 10.126 2.427l.086.038.04.087c1.55 3.27 2.367 6.781 2.367 10.16 0 3.366-.785 6.731-2.337 10l-.041.085-.086.04c-3.088 1.462-6.57 2.235-10.07 2.235M2.762 22.21c3.293 1.549 6.578 2.33 9.773 2.33 3.39 0 6.76-.74 9.76-2.144 1.49-3.167 2.245-6.426 2.245-9.684 0-3.27-.789-6.673-2.28-9.847C19.014 1.34 15.624.53 12.45.53c-3.409 0-6.675.723-9.716 2.15C1.274 5.786.53 9.043.53 12.364c0 3.327.772 6.73 2.231 9.845"/>
                                    <path d="M11.005 19.032c.006-1.28-.709-2.413-1.643-3.022-.83-.538-1.942-.891-3.737-1.347-.555-.142-1.135-.457-1.315-.859-.475.48-.562 1.556-.478 2.185.097.729.948 1.929 2.23 1.976.782.031 1.771-.169 2.246-.27.818-.176 2.113.336 2.32 1.336M12.45 2.277c-2.173 0-4.05 1.433-4.043 3.912.007 2.085 1.684 3.201 2.282 3.999.904 1.201 1.393 2.623 1.444 4.799.04 1.731.038 3.441.047 4.047h.48c-.01-.634-.031-2.449-.006-4.1.032-2.177.54-3.545 1.444-4.746.603-.798 2.279-1.914 2.283-3.999.008-2.479-1.868-3.912-4.039-3.912"/>
                                    <path d="M13.001 19.03c0-.999-.011-3.67.013-3.999-.021-3.974 2.867-6.778 4.694-6.56 1.263.11 2.32.625 2.891 2.107.526 1.381.053 3.218-1.697 3.65-1.874.462-2.967.847-3.76 1.39-.969.666-1.705 1.937-1.728 3.414"/>
                                    <path d="M13.783 19.032c-.006-1.28.708-2.413 1.64-3.022.832-.538 1.946-.891 3.74-1.347.556-.142 1.136-.457 1.312-.859.478.48.564 1.556.48 2.185-.099.729-.948 1.929-2.227 1.976-.782.031-1.777-.169-2.25-.27-.814-.176-2.113.336-2.32 1.336"/>
                                    <path d="M54.39 1.778L63.269 9.85c-1.168-.948-2.593-1.646-4.412-1.646-4.304 0-6.053 3.209-6.053 5.469 0 1.678 1.099 4.153 4.929 4.153 1.607 0 3.893-1.119 4.55-1.627l-3.059 6.513c-1.458.29-1.937.471-3.171.51-6.855.204-9.625-4.007-9.623-8.31.005-5.688 5.062-12.606 13.446-12.606.514 0 1.142.177 1.68.374l.542-.694m17.026.174L80 22.566h-6.518l-.004-3.5h-4.445l-1.463 3.5h-7.069l7.39-14.57-1.666-.011 3.167-5.823zm-5.688 6.243-2.513 5.935h2.588"/>
                                    <path d="M42.315 2.16c3.228.019 5.052 1.771 5.052 4.302 0 2.334-1.924 4.4-4.036 5.467 2.174.8 2.363 2.761 2.363 4.15 0 3.353-3.365 6.486-7.74 6.486h-9.538l3.72-14.367-1.528-.01 3.125-6.027s5.957-.018 8.582 0m-3.167 8.274c.668 0 1.847-.17 2.141-1.462.323-1.402-.783-1.44-1.314-1.44l-1.896-.008-.662 2.91zm-2.681 3.605-.873 3.354h2.233c.878 0 2.075-.436 2.369-1.528.29-1.094-.547-1.826-1.423-1.826"/>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Disclaimer -->
            <div class="border-t border-slate-100 dark:border-slate-800 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 gap-4">
                <p>&copy; <?= date('Y') ?> <strong>ItemPedia</strong>. Toko Roblox Mandiri. Ditenagai oleh Flight PHP. 
                    <a href="/admin/login" class="text-slate-300 dark:text-slate-600 hover:text-slate-500 dark:hover:text-slate-400 transition ml-2 inline-block opacity-25 hover:opacity-100" title="Admin Portal">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                    </a>
                </p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 text-center sm:text-right">
                    Roblox adalah merek dagang terdaftar milik Roblox Corporation. ItemPedia beroperasi sebagai platform transaksi mandiri.
                </p>
            </div>
        </div>
    </footer>

    <!-- ================= MODAL LOGIN GOOGLE PEMBELI ================= -->
    <div id="googleLoginModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#0f172a] rounded-3xl w-full max-w-md shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden animate-in fade-in zoom-in-95">
            
            <!-- Modal Header -->
            <div class="p-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <div>
                        <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Pilih akun Google</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">untuk login pembeli di ItemPedia</p>
                    </div>
                </div>

                <button type="button" onclick="closeGoogleLoginModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Akun Cepat 1-Klik (Tanpa Password) -->
            <div class="p-6 space-y-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pilih akun yang tersedia:</p>
                
                <!-- Akun 1: Fahrul Gunawan -->
                <button type="button" 
                        onclick="loginWithGoogleAccount('Fahrul Gunawan', 'fahru.roblox@gmail.com', 'https://ui-avatars.com/api/?name=Fahrul+Gunawan&background=4285F4&color=fff&bold=true')" 
                        class="w-full p-3 rounded-2xl border-2 border-slate-200 hover:border-sky-400 hover:bg-sky-50/50 transition flex items-center gap-3.5 text-left group">
                    <img src="https://ui-avatars.com/api/?name=Fahrul+Gunawan&background=4285F4&color=fff&bold=true" 
                         alt="Fahrul Gunawan" 
                         class="w-10 h-10 rounded-full border border-slate-200 object-cover flex-shrink-0">
                    <div class="min-w-0 flex-grow">
                        <div class="text-xs font-black text-slate-900 group-hover:text-sky-600 transition truncate">Fahrul Gunawan</div>
                        <div class="text-[11px] text-slate-500 truncate">fahru.roblox@gmail.com</div>
                    </div>
                    <i class="fa-solid fa-arrow-right text-xs text-slate-300 group-hover:text-sky-500 transition"></i>
                </button>

                <!-- Akun 2: Pembeli Roblox -->
                <button type="button" 
                        onclick="loginWithGoogleAccount('Pembeli Roblox', 'buyer.roblox@gmail.com', 'https://ui-avatars.com/api/?name=Pembeli+Roblox&background=34A853&color=fff&bold=true')" 
                        class="w-full p-3 rounded-2xl border-2 border-slate-200 hover:border-sky-400 hover:bg-sky-50/50 transition flex items-center gap-3.5 text-left group">
                    <img src="https://ui-avatars.com/api/?name=Pembeli+Roblox&background=34A853&color=fff&bold=true" 
                         alt="Pembeli Roblox" 
                         class="w-10 h-10 rounded-full border border-slate-200 object-cover flex-shrink-0">
                    <div class="min-w-0 flex-grow">
                        <div class="text-xs font-black text-slate-900 group-hover:text-sky-600 transition truncate">Pembeli Roblox</div>
                        <div class="text-[11px] text-slate-500 truncate">buyer.roblox@gmail.com</div>
                    </div>
                    <i class="fa-solid fa-arrow-right text-xs text-slate-300 group-hover:text-sky-500 transition"></i>
                </button>

                <!-- Opsi Ketik Gmail Lainnya (Tanpa Password!) -->
                <div class="pt-2 border-t border-slate-100">
                    <button type="button" 
                            id="btnToggleCustomGmail" 
                            onclick="toggleCustomGmailInput()" 
                            class="text-xs font-black text-sky-600 hover:text-sky-700 flex items-center gap-2 py-1">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Gunakan akun Google / Gmail lainnya</span>
                    </button>

                    <div id="customGmailForm" class="hidden mt-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2.5">
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600">
                            Masukkan Akun Gmail Kamu:
                        </label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="customGmailInput" 
                                   placeholder="namaanda@gmail.com" 
                                   class="w-full px-3 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:border-sky-500">
                            <button type="button" 
                                    onclick="submitCustomGmailLogin()" 
                                    class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white font-black text-xs rounded-xl shadow transition flex-shrink-0">
                                Masuk
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i> Langsung masuk 1-klik otomatis tanpa perlu memasukkan password manual.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 text-[10px] text-slate-400 text-center leading-relaxed">
                Untuk melanjutkan, Google akan memverifikasi nama, email, dan foto profil Anda ke ItemPedia secara aman.
            </div>

        </div>
    </div>

    <!-- ================= DRAWER KERANJANG BELANJA (GLOBAL SLIDE-OVER) ================= -->
    <div id="cartDrawerBackdrop" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[70] hidden transition-opacity duration-300 opacity-0 cursor-pointer" onclick="closeCartDrawer()"></div>

    <div id="cartDrawer" class="fixed top-0 right-0 bottom-0 w-full sm:w-[460px] bg-white dark:bg-[#0c1e33] z-[80] shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out border-l border-transparent dark:border-slate-800">
        <!-- Header Drawer -->
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-white/95 dark:bg-[#0c1e33]/95 backdrop-blur-sm sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-100 dark:bg-sky-950/70 text-sky-600 dark:text-sky-400 flex items-center justify-center text-lg shadow-xs border border-sky-200/50 dark:border-sky-800/50">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white text-base flex items-center gap-2">
                        <span>Keranjang Belanja</span>
                        <span id="cartDrawerHeaderCount" class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-sky-100 dark:bg-sky-950/80 text-sky-700 dark:text-sky-300 border border-sky-300 dark:border-sky-700">0 Item</span>
                    </h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-400 font-medium">Checkout banyak item sekaligus via 1 QRIS</p>
                </div>
            </div>
            <button type="button" onclick="closeCartDrawer()" class="w-9 h-9 rounded-2xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition active:scale-90 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Body: Cart Items & Form Checkout -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4" id="cartDrawerContent">
            <!-- Rendered by JS -->
        </div>

        <!-- Footer: Subtotal & Checkout Button -->
        <div id="cartDrawerFooter" class="p-4 sm:p-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/95 dark:bg-[#0f172a]/95 backdrop-blur-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Total Pembayaran:</span>
                <div class="text-right">
                    <div id="cartDrawerDiscountRow" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hidden">Diskon: -Rp 0</div>
                    <span id="cartDrawerTotalPrice" class="text-xl font-black text-sky-600 dark:text-sky-400">Rp 0</span>
                </div>
            </div>
            <button type="button" 
                    id="btnCartCheckout" 
                    onclick="submitCartCheckout()" 
                    class="btn-shimmer w-full py-3.5 bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 active:scale-98 text-white font-black text-sm rounded-2xl shadow-lg shadow-sky-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-bolt text-sky-200"></i>
                <span>Checkout &amp; Bayar QRIS</span>
            </button>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastNotificationContainer" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2.5 pointer-events-none max-w-sm"></div>

    <script>
    // ================= GLOBAL SHOPPING CART LOGIC =================
    const CART_STORAGE_KEY = 'itempedia_shopping_cart';

    function getCart() {
        try {
            const raw = localStorage.getItem(CART_STORAGE_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function saveCart(cart) {
        try {
            localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {}
        updateNavCartBadge();
    }

    function updateNavCartBadge() {
        const cart = getCart();
        const count = cart.reduce((total, item) => total + (parseInt(item.qty) || 1), 0);
        const badge = document.getElementById('navCartCount');
        const headerCount = document.getElementById('cartDrawerHeaderCount');

        if (badge) {
            if (count > 0) {
                badge.innerText = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
        if (headerCount) {
            headerCount.innerText = count + ' Item';
        }
    }

    function showCartToast(title, subtitle = 'Berhasil ditambahkan ke keranjang') {
        const container = document.getElementById('toastNotificationContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'pointer-events-auto bg-slate-900 text-white p-3.5 sm:p-4 rounded-2xl shadow-2xl border border-slate-700 flex items-center justify-between gap-3 transform translate-y-4 opacity-0 transition-all duration-300';
        toast.innerHTML = `
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-black text-white truncate">${escapeHtml(title)}</div>
                    <div class="text-[11px] text-slate-300 font-medium">${escapeHtml(subtitle)}</div>
                </div>
            </div>
            <button type="button" onclick="openCartDrawer()" class="px-3 py-1.5 bg-sky-500 hover:bg-sky-400 active:scale-95 text-white text-[11px] font-black rounded-xl transition flex-shrink-0 shadow-xs">
                Lihat
            </button>
        `;

        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 300);
        }, 3200);
    }

    function addToCart(product, qty = 1, openDrawer = false) {
        if (!product || !product.id) return;
        const cart = getCart();
        const existingIdx = cart.findIndex(item => item.id == product.id);
        const maxStock = parseInt(product.stock) || 10;
        const addQty = parseInt(qty) || 1;

        if (maxStock <= 0) {
            showCartToast(product.name, 'Stok produk ini sedang habis');
            return;
        }

        if (existingIdx > -1) {
            let newQty = cart[existingIdx].qty + addQty;
            if (newQty > maxStock) newQty = maxStock;
            cart[existingIdx].qty = newQty;
            cart[existingIdx].stock = maxStock;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                game: product.game || 'Roblox',
                price: parseInt(product.price) || 0,
                price_original: parseInt(product.price_original) || 0,
                image_url: product.image_url || '',
                stock: maxStock,
                sub_category: product.sub_category || 'Item',
                qty: Math.min(addQty, maxStock)
            });
        }

        saveCart(cart);
        showCartToast(product.name, `${addQty}x ditambahkan ke keranjang`);

        if (openDrawer) {
            openCartDrawer();
        }
    }

    function updateCartItemQty(productId, delta) {
        const cart = getCart();
        const item = cart.find(i => i.id == productId);
        if (!item) return;

        item.qty += delta;
        if (item.qty < 1) item.qty = 1;
        if (item.qty > item.stock) item.qty = item.stock;

        saveCart(cart);
        renderCartDrawer();
    }

    function removeFromCart(productId) {
        let cart = getCart();
        cart = cart.filter(i => i.id != productId);
        saveCart(cart);
        renderCartDrawer();
    }

    function clearCart() {
        saveCart([]);
        renderCartDrawer();
    }

    function openCartDrawer() {
        const backdrop = document.getElementById('cartDrawerBackdrop');
        const drawer = document.getElementById('cartDrawer');
        const fab = document.getElementById('floatingActionButtons');
        if (!backdrop || !drawer) return;

        if (fab) {
            fab.classList.add('opacity-0', 'pointer-events-none');
        }

        backdrop.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            drawer.classList.remove('translate-x-full');
            drawer.classList.add('translate-x-0');
        }, 10);

        renderCartDrawer();
    }

    function closeCartDrawer() {
        const backdrop = document.getElementById('cartDrawerBackdrop');
        const drawer = document.getElementById('cartDrawer');
        const fab = document.getElementById('floatingActionButtons');
        if (!backdrop || !drawer) return;

        if (fab) {
            fab.classList.remove('opacity-0', 'pointer-events-none');
        }

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        drawer.classList.remove('translate-x-0');
        drawer.classList.add('translate-x-full');

        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 300);
    }

    let cartAppliedRedeem = null;

    function renderCartDrawer() {
        const container = document.getElementById('cartDrawerContent');
        const footer = document.getElementById('cartDrawerFooter');
        const totalPriceEl = document.getElementById('cartDrawerTotalPrice');
        const discountRow = document.getElementById('cartDrawerDiscountRow');
        if (!container) return;

        const cart = getCart();

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="py-16 text-center space-y-4">
                    <div class="w-20 h-20 rounded-3xl bg-sky-50 dark:bg-slate-800/80 border-2 border-sky-100 dark:border-slate-700 text-sky-400 dark:text-sky-300 flex items-center justify-center text-3xl mx-auto shadow-xs">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-black text-slate-800 dark:text-white text-base">Keranjang Belanjamu Kosong</h4>
                        <p class="text-xs text-slate-400 dark:text-slate-400 max-w-xs mx-auto">Yuk cari item, pet, atau akun Roblox favoritmu dan masukkan ke keranjang!</p>
                    </div>
                    <button type="button" onclick="closeCartDrawer(); window.location.href='/#katalog';" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-500 hover:bg-sky-600 active:scale-95 text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 transition cursor-pointer">
                        <i class="fa-solid fa-gamepad"></i>
                        <span>Mulai Belanja</span>
                    </button>
                </div>
            `;
            if (footer) footer.classList.add('hidden');
            return;
        }

        if (footer) footer.classList.remove('hidden');

        let subtotal = 0;
        let itemsHtml = '<div class="space-y-3">';

        cart.forEach(item => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;

            itemsHtml += `
                <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 rounded-2xl flex items-center gap-3 relative group hover:border-sky-300 dark:hover:border-sky-500 transition">
                    <img src="${escapeHtml(item.image_url || 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500')}" 
                         alt="" 
                         class="w-16 h-16 rounded-xl object-cover bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex-shrink-0"
                         onerror="this.src='https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500'">
                    
                    <div class="flex-1 min-w-0 pr-6">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[10px] font-black uppercase tracking-wider text-sky-700 dark:text-sky-300 bg-sky-100 dark:bg-sky-950/80 border border-sky-200 dark:border-sky-800 px-1.5 py-0.5 rounded">${escapeHtml(item.game || 'Roblox')}</span>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-400">${escapeHtml(item.sub_category || 'Item')}</span>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-xs sm:text-[13px] leading-snug truncate mt-0.5" title="${escapeHtml(item.name)}">
                            ${escapeHtml(item.name)}
                        </h4>
                        <div class="text-xs font-black text-orange-500 mt-1">
                            Rp ${item.price.toLocaleString('id-ID')}
                        </div>
                    </div>

                    <!-- Delete Button -->
                    <button type="button" 
                            onclick="removeFromCart(${item.id})" 
                            class="absolute top-3 right-3 text-slate-400 hover:text-rose-500 transition p-1 cursor-pointer"
                            title="Hapus dari keranjang">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>

                    <!-- Quantity Buttons -->
                    <div class="absolute bottom-3 right-3 flex items-center border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 rounded-xl overflow-hidden shadow-2xs">
                        <button type="button" onclick="updateCartItemQty(${item.id}, -1)" class="w-6 h-6 flex items-center justify-center text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 active:bg-slate-200 text-xs font-bold transition cursor-pointer">
                            <i class="fa-solid fa-minus text-[10px]"></i>
                        </button>
                        <span class="w-7 text-center text-xs font-black text-slate-800 dark:text-white">${item.qty}</span>
                        <button type="button" onclick="updateCartItemQty(${item.id}, 1)" class="w-6 h-6 flex items-center justify-center text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 active:bg-slate-200 text-xs font-bold transition cursor-pointer">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        itemsHtml += '</div>';

        // Hitung diskon jika ada redeem code aktif
        let discount = 0;
        if (cartAppliedRedeem && cartAppliedRedeem.discount_percent > 0) {
            discount = Math.round(cart[0].price * (cartAppliedRedeem.discount_percent / 100));
        }
        const finalTotal = Math.max(0, subtotal - discount);

        if (discount > 0 && discountRow) {
            discountRow.innerText = `Diskon: -Rp ${discount.toLocaleString('id-ID')} (${cartAppliedRedeem.discount_percent}%)`;
            discountRow.classList.remove('hidden');
        } else if (discountRow) {
            discountRow.classList.add('hidden');
        }

        if (totalPriceEl) {
            totalPriceEl.innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');
        }

        // Form Checkout di dalam Drawer
        const formHtml = `
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-user-check text-sky-500"></i> Informasi Pembeli
                    </span>
                    <button type="button" onclick="clearCart()" class="text-[11px] font-bold text-rose-500 hover:text-rose-400 transition cursor-pointer">
                        Kosongkan Keranjang
                    </button>
                </div>

                <!-- Input Roblox Username -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        Username Roblox Kamu <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="cartRobloxUsername" 
                               placeholder="Contoh: fahru_sultan" 
                               class="w-full pl-9 pr-24 py-2.5 bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-200 dark:border-slate-700 focus:border-sky-500 dark:focus:border-sky-400 focus:bg-white dark:focus:bg-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-white outline-none transition"
                               onblur="checkCartRobloxAvatar()">
                        <i class="fa-solid fa-at absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <button type="button" 
                                onclick="checkCartRobloxAvatar()" 
                                id="btnCheckCartAvatar" 
                                class="absolute right-1.5 top-1/2 -translate-y-1/2 px-2.5 py-1 bg-sky-100 dark:bg-sky-900/60 hover:bg-sky-200 dark:hover:bg-sky-800 text-sky-700 dark:text-sky-300 text-[11px] font-bold rounded-lg transition cursor-pointer">
                            Cek Avatar
                        </button>
                    </div>

                    <!-- Box Preview Avatar -->
                    <div id="cartRobloxAvatarPreview" class="hidden p-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center gap-3">
                        <img id="cartAvatarImg" src="" alt="Roblox Avatar" class="w-9 h-9 rounded-full border border-emerald-400 bg-white object-cover">
                        <div class="min-w-0">
                            <div class="text-xs font-black text-slate-900 dark:text-white flex items-center gap-1">
                                <span id="cartAvatarName">Username</span>
                                <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                            </div>
                            <span class="text-[10px] text-emerald-700 dark:text-emerald-400 font-bold">Avatar Roblox Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <!-- Input Kode Promo / Redeem -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        Kode Diskon / Promo
                    </label>
                    <div class="flex gap-2">
                        <input type="text" 
                               id="cartRedeemCodeInput" 
                               placeholder="Punya voucher?" 
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-200 dark:border-slate-700 focus:border-sky-500 dark:focus:border-sky-400 focus:bg-white dark:focus:bg-slate-800 rounded-xl text-xs font-bold uppercase text-slate-900 dark:text-white outline-none transition">
                        <button type="button" 
                                id="btnApplyCartRedeem" 
                                onclick="applyCartRedeemCode()" 
                                class="px-4 py-2 bg-slate-800 dark:bg-sky-600 hover:bg-slate-900 dark:hover:bg-sky-500 text-white font-bold text-xs rounded-xl transition flex-shrink-0 cursor-pointer">
                            Gunakan
                        </button>
                    </div>
                    <div id="cartRedeemStatusMsg" class="text-[11px] font-bold hidden"></div>
                </div>

                <!-- Input Catatan Pesanan -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        Catatan Pesanan (Opsional)
                    </label>
                    <textarea id="cartNote" rows="2" placeholder="Tulis pesan khusus untuk penjual jika ada..." class="w-full p-2.5 bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-200 dark:border-slate-700 focus:border-sky-500 dark:focus:border-sky-400 focus:bg-white dark:focus:bg-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-white outline-none transition resize-none"></textarea>
                </div>
            </div>
        `;

        container.innerHTML = itemsHtml + formHtml;
    }

    let cartAvatarUrl = '';

    function checkCartRobloxAvatar() {
        const input = document.getElementById('cartRobloxUsername');
        if (!input) return;
        const username = input.value.trim();
        if (!username) return;

        const btn = document.getElementById('btnCheckCartAvatar');
        const previewBox = document.getElementById('cartRobloxAvatarPreview');
        const img = document.getElementById('cartAvatarImg');
        const nameEl = document.getElementById('cartAvatarName');

        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        fetch(`/api/roblox-avatar?username=${encodeURIComponent(username)}`)
            .then(res => res.json())
            .then(data => {
                if (btn) btn.innerText = 'Cek Avatar';
                if (data.success) {
                    cartAvatarUrl = data.avatarUrl;
                    if (img) img.src = data.avatarUrl;
                    if (nameEl) nameEl.innerText = '@' + (data.displayName || data.username);
                    if (previewBox) previewBox.classList.remove('hidden');
                } else {
                    cartAvatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(username)}&background=38bdf8&color=fff`;
                    if (img) img.src = cartAvatarUrl;
                    if (nameEl) nameEl.innerText = '@' + username;
                    if (previewBox) previewBox.classList.remove('hidden');
                }
            })
            .catch(() => {
                if (btn) btn.innerText = 'Cek Avatar';
            });
    }

    function applyCartRedeemCode() {
        const input = document.getElementById('cartRedeemCodeInput');
        const statusEl = document.getElementById('cartRedeemStatusMsg');
        const btn = document.getElementById('btnApplyCartRedeem');
        if (!input || !statusEl) return;

        const code = input.value.trim().toUpperCase();
        if (!code) return;

        const cart = getCart();
        if (cart.length === 0) return;

        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        fetch('/api/redeem-code/check', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `code=${encodeURIComponent(code)}&product_id=${cart[0].id}&quantity=1`
        })
        .then(res => res.json())
        .then(data => {
            if (btn) btn.innerText = 'Gunakan';
            if (data.success) {
                cartAppliedRedeem = {
                    code: data.code,
                    discount_percent: data.discount_percent
                };
                statusEl.className = 'text-[11px] font-bold text-emerald-600 block';
                statusEl.innerText = `✅ Diskon ${data.discount_percent}% berhasil digunakan!`;
                renderCartDrawer();
            } else {
                cartAppliedRedeem = null;
                statusEl.className = 'text-[11px] font-bold text-rose-600 block';
                statusEl.innerText = `❌ ${data.message || 'Kode promo tidak valid'}`;
                renderCartDrawer();
            }
        })
        .catch(() => {
            if (btn) btn.innerText = 'Gunakan';
        });
    }

    function submitCartCheckout() {
        const cart = getCart();
        if (cart.length === 0) {
            alert('Keranjang belanja kamu masih kosong!');
            return;
        }

        const usernameInput = document.getElementById('cartRobloxUsername');
        const username = usernameInput ? usernameInput.value.trim() : '';

        if (!username) {
            alert('Silakan masukkan Username Roblox kamu terlebih dahulu!');
            if (usernameInput) usernameInput.focus();
            return;
        }

        const noteInput = document.getElementById('cartNote');
        const btn = document.getElementById('btnCartCheckout');

        const note = noteInput ? noteInput.value.trim() : '';
        const redeemCode = cartAppliedRedeem ? cartAppliedRedeem.code : '';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses Pesanan...';
        }

        const formData = new FormData();
        formData.append('is_ajax', '1');
        formData.append('roblox_username', username);
        formData.append('roblox_avatar', cartAvatarUrl);
        formData.append('whatsapp', '-');
        formData.append('note', note);
        formData.append('redeem_code', redeemCode);
        formData.append('cart_items', JSON.stringify(cart));

        fetch('/order/create', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect_url) {
                // Bersihkan keranjang belanja
                saveCart([]);
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'Gagal memproses pesanan keranjang. Silakan coba lagi.');
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-bolt text-sky-200"></i><span>Checkout &amp; Bayar QRIS</span>';
                }
            }
        })
        .catch(err => {
            alert('Terjadi kendala jaringan saat checkout. Silakan coba kembali.');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-bolt text-sky-200"></i><span>Checkout &amp; Bayar QRIS</span>';
            }
        });
    }

    // Update cart badge saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        updateNavCartBadge();

        // Buka otomatis jika ada parameter open_cart=1
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('open_cart') === '1') {
            setTimeout(openCartDrawer, 300);
        }
    });

    function openGoogleLoginModal() {
        document.getElementById('googleLoginModal').classList.remove('hidden');
    }

    function closeGoogleLoginModal() {
        document.getElementById('googleLoginModal').classList.add('hidden');
    }

    function toggleCustomGmailInput() {
        const box = document.getElementById('customGmailForm');
        box.classList.toggle('hidden');
        if (!box.classList.contains('hidden')) {
            document.getElementById('customGmailInput').focus();
        }
    }

    function toggleBuyerDropdown() {
        const menu = document.getElementById('buyerDropdownMenu');
        if (menu) menu.classList.toggle('hidden');
    }

    window.addEventListener('click', (e) => {
        const wrap = document.getElementById('buyerProfileDropdownWrap');
        const menu = document.getElementById('buyerDropdownMenu');
        if (wrap && menu && !wrap.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    async function loginWithGoogleAccount(name, email, avatar) {
        try {
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('avatar', avatar || '');

            const res = await fetch('/auth/google', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                closeGoogleLoginModal();
                window.location.reload();
            } else {
                alert(data.message || 'Login gagal');
            }
        } catch (err) {
            console.error('Google login error:', err);
            alert('Terjadi kesalahan saat masuk dengan Google.');
        }
    }

    function submitCustomGmailLogin() {
        const input = document.getElementById('customGmailInput');
        const val = input.value.trim();
        if (!val) {
            alert('Silakan masukkan alamat Gmail kamu');
            input.focus();
            return;
        }
        const email = val.includes('@') ? val : (val + '@gmail.com');
        const parts = email.split('@')[0];
        const name = parts.replace(/[._-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        loginWithGoogleAccount(name, email, '');
    }
    </script>

    <!-- ================= FLOATING ACTION BUTTONS (POJOK KANAN BAWAH) ================= -->
    <div id="floatingActionButtons" class="fixed bottom-4 right-3.5 sm:bottom-6 sm:right-6 z-40 flex flex-col items-end gap-2.5 sm:gap-3.5 transition-all duration-300">
        
        <!-- 1. Tombol Bulat WhatsApp (Di Atas Tombol Chat AI) -->
        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_admin'] ?? '6281234567890') ?>?text=Halo%20Admin%20ItemPedia,%20mau%20tanya%20seputar%20produk%20Roblox" 
           target="_blank" 
           rel="noopener noreferrer"
           title="Hubungi WhatsApp Seller"
           class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-[#25D366] hover:bg-[#20ba59] text-white flex items-center justify-center shadow-lg shadow-emerald-500/35 hover:scale-110 active:scale-95 transition-all duration-200 group relative">
            
            <!-- Tooltip Hover -->
            <span class="absolute right-16 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl bg-slate-950/90 text-white text-xs font-black whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-md hidden sm:block">
                WhatsApp Seller
            </span>

            <i class="fa-brands fa-whatsapp text-xl sm:text-3xl"></i>
        </a>

        <!-- 2. Tombol Bulat Chat Bot AI (Di Bawah) -->
        <button type="button" 
                id="btnOpenAiChat"
                onclick="toggleAiChatWidget()" 
                title="Tanya AI ItemPedia"
                class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-gradient-to-tr from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white flex items-center justify-center shadow-xl shadow-sky-500/40 hover:scale-110 active:scale-95 transition-all duration-200 group relative cursor-pointer">
            
            <!-- Status Dot AI Aktif -->
            <span class="absolute top-0 right-0 w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full bg-emerald-400 border-2 border-white animate-pulse"></span>

            <!-- Tooltip Hover -->
            <span class="absolute right-16 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl bg-slate-950/90 text-white text-xs font-black whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-md hidden sm:block">
                Tanya AI ItemPedia
            </span>

            <i class="fa-solid fa-robot text-lg sm:text-2xl" id="aiBtnIcon"></i>
        </button>

    </div>

    <!-- ================= WIDGET POPUP CHAT BOT AI ITEMPEDIA ================= -->
    <div id="aiChatWidget" class="hidden fixed bottom-18 right-3 sm:right-6 sm:bottom-24 z-50 w-[calc(100vw-1.5rem)] sm:w-[390px] h-[480px] sm:h-[520px] max-h-[78vh] bg-white rounded-2xl sm:rounded-3xl shadow-2xl border-2 border-sky-100 flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-5 duration-200">
        
        <!-- Header Bot AI -->
        <div class="bg-gradient-to-r from-sky-600 via-blue-600 to-indigo-600 text-white p-4 flex items-center justify-between shadow-md flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative w-10 h-10 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center text-lg border border-white/20 shadow-inner">
                    <i class="fa-solid fa-robot text-sky-200"></i>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-sky-600 animate-pulse"></span>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <h4 class="font-black text-sm text-white leading-tight">ItemPedia AI</h4>
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold bg-white/20 text-white">Bot 24/7</span>
                    </div>
                    <p class="text-[11px] text-sky-100 font-medium">Asisten Cerdas Khusus Toko ItemPedia</p>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <button type="button" 
                        onclick="resetAiChat()" 
                        title="Reset Percakapan"
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                </button>
                <button type="button" 
                        onclick="toggleAiChatWidget()" 
                        title="Tutup Chat AI"
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Chat Area (Scrollable) -->
        <div id="aiChatMessages" class="p-4 space-y-3.5 overflow-y-auto flex-grow bg-slate-50/50 text-xs scrollbar-none">
            
            <!-- AI Welcome Bubble -->
            <div class="flex items-start gap-2.5 max-w-[90%]">
                <div class="w-7 h-7 rounded-xl bg-sky-500 text-white flex items-center justify-center text-xs flex-shrink-0 mt-0.5 shadow-sm">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="space-y-1">
                    <div class="p-3.5 rounded-2xl rounded-tl-sm bg-white border border-slate-200/90 text-slate-800 leading-relaxed shadow-sm font-medium">
                        Halo! 👋 Saya <strong>ItemPedia AI Assistant</strong>.<br>
                        Ada yang bisa saya bantu seputar belanja item game Roblox, jam buka toko, cara pembayaran QRIS, atau lacak pesanan?
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold block">Baru saja</span>
                </div>
            </div>

            <!-- Quick Suggestions Chips -->
            <div id="aiQuickChips" class="space-y-1.5 pt-1">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1">
                    <i class="fa-solid fa-lightbulb text-amber-500"></i> Rekomendasi Pertanyaan:
                </p>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="askAiQuestion('Jam operasional toko jam berapa?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        ⏰ Jam operasional toko?
                    </button>
                    <button type="button" onclick="askAiQuestion('Bagaimana cara bayar lewat QRIS?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        💳 Cara bayar via QRIS?
                    </button>
                    <button type="button" onclick="askAiQuestion('Bagaimana cara trade item Roblox di sini?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        🎮 Cara trade item Roblox?
                    </button>
                    <button type="button" onclick="askAiQuestion('Apakah transaksi di ItemPedia aman & bergaransi?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        🛡️ Apakah belanja di sini aman?
                    </button>
                    <button type="button" onclick="askAiQuestion('Game apa saja yang tersedia di ItemPedia?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        🛒 Game apa saja yang tersedia?
                    </button>
                </div>
            </div>

            <!-- Typing Indicator (Hidden by Default) -->
            <div id="aiTypingIndicator" class="hidden flex items-center gap-2 max-w-[80%]">
                <div class="w-7 h-7 rounded-xl bg-sky-500 text-white flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="p-3 bg-white border border-slate-200 rounded-2xl rounded-tl-sm shadow-sm flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-bounce [animation-delay:0.4s]"></span>
                </div>
            </div>

        </div>

        <!-- Input Form AI -->
        <div class="p-3.5 bg-white border-t border-slate-200 flex-shrink-0">
            <form id="aiChatForm" onsubmit="handleSendAiMessage(event)" class="flex gap-2">
                <input type="text" 
                       id="aiChatInput" 
                       placeholder="Tanya seputar toko ItemPedia..."
                       autocomplete="off"
                       class="flex-grow px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:bg-white transition">
                <button type="submit" 
                        id="btnSendAi"
                        class="px-4 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-black text-xs rounded-2xl shadow-md shadow-sky-300 transition flex items-center justify-center gap-1 active:scale-95 flex-shrink-0">
                    <span>Kirim</span>
                    <i class="fa-solid fa-paper-plane text-[10px]"></i>
                </button>
            </form>
            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-2 px-1">
                <span>🤖 AI Asisten Toko ItemPedia</span>
                <span>Khusus Info Toko & Pesanan</span>
            </div>
        </div>

    </div>

    <script>
    // ================= SCRIPT CHAT BOT AI ITEMPEDIA =================
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    let isAiChatOpen = false;

    function toggleAiChatWidget() {
        const widget = document.getElementById('aiChatWidget');
        const icon = document.getElementById('aiBtnIcon');
        isAiChatOpen = !isAiChatOpen;

        if (isAiChatOpen) {
            widget.classList.remove('hidden');
            if (icon) icon.className = 'fa-solid fa-xmark text-xl sm:text-2xl';
            document.getElementById('aiChatInput').focus();
            scrollAiChatToBottom();
        } else {
            widget.classList.add('hidden');
            if (icon) icon.className = 'fa-solid fa-robot text-xl sm:text-2xl';
        }
    }

    function scrollAiChatToBottom() {
        const container = document.getElementById('aiChatMessages');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function askAiQuestion(questionText) {
        document.getElementById('aiChatInput').value = questionText;
        handleSendAiMessage();
    }

    function resetAiChat() {
        const container = document.getElementById('aiChatMessages');
        const chipsHtml = `
            <div class="flex items-start gap-2.5 max-w-[90%]">
                <div class="w-7 h-7 rounded-xl bg-sky-500 text-white flex items-center justify-center text-xs flex-shrink-0 mt-0.5 shadow-sm">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="space-y-1">
                    <div class="p-3.5 rounded-2xl rounded-tl-sm bg-white border border-slate-200/90 text-slate-800 leading-relaxed shadow-sm font-medium">
                        Halo! 👋 Percakapan telah direset. Silakan tanyakan hal apa pun seputar transaksi dan produk di ItemPedia!
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold block">Baru saja</span>
                </div>
            </div>
            <div id="aiQuickChips" class="space-y-1.5 pt-1">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1">
                    <i class="fa-solid fa-lightbulb text-amber-500"></i> Rekomendasi Pertanyaan:
                </p>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="askAiQuestion('Jam operasional toko jam berapa?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        ⏰ Jam operasional toko?
                    </button>
                    <button type="button" onclick="askAiQuestion('Bagaimana cara bayar lewat QRIS?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        💳 Cara bayar via QRIS?
                    </button>
                    <button type="button" onclick="askAiQuestion('Bagaimana cara trade item Roblox di sini?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        🎮 Cara trade item Roblox?
                    </button>
                    <button type="button" onclick="askAiQuestion('Apakah transaksi di ItemPedia aman & bergaransi?')" class="px-2.5 py-1 bg-white hover:bg-sky-50 hover:border-sky-400 border border-slate-200 text-slate-700 text-[11px] font-bold rounded-xl transition text-left shadow-2xs">
                        🛡️ Apakah belanja di sini aman?
                    </button>
                </div>
            </div>
            <div id="aiTypingIndicator" class="hidden flex items-center gap-2 max-w-[80%]">
                <div class="w-7 h-7 rounded-xl bg-sky-500 text-white flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="p-3 bg-white border border-slate-200 rounded-2xl rounded-tl-sm shadow-sm flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-bounce [animation-delay:0.4s]"></span>
                </div>
            </div>
        `;
        container.innerHTML = chipsHtml;
    }

    // Audio chime untuk balasan AI menggunakan Web Audio API synthesizer
    function playAiChime() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const now = ctx.currentTime;

            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(659.25, now); // E5
            osc.frequency.exponentialRampToValueAtTime(880, now + 0.15); // A5
            gain.gain.setValueAtTime(0.12, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.35);
        } catch(e) {}
    }

    // Helper untuk memformat dan membersihkan teks respons AI dari tanda asterisk (***, **, *)
    function formatAiResponse(text) {
        if (!text) return '';
        // Ubah ***teks*** menjadi <strong><em>teks</em></strong>
        let out = text.replace(/\*\*\*(.*?)\*\*\*/g, '<strong><em>$1</em></strong>');
        // Ubah **teks** menjadi <strong>teks</strong>
        out = out.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Ubah *teks* menjadi <em>teks</em>
        out = out.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // Hapus semua sisa karakter asterisk agar tidak ada tanda bintang yang tampil di chat
        out = out.replace(/\*/g, '');
        return out;
    }

    // Knowledge Base Engine Khusus ItemPedia
    function generateItemPediaAiResponse(query) {
        const q = query.toLowerCase();

        // 1. Jam Buka / Operasional (Dinamis Sesuai Zona Waktu Lokal)
        if (q.includes('jam') || q.includes('buka') || q.includes('operasional') || q.includes('tutup') || q.includes('kapan')) {
            const local = detectLocalStoreHours();
            let localExtra = '';
            if (local.tzCode && local.tzCode !== 'WIB') {
                localExtra = `<br><span class="inline-block mt-2 px-3 py-1.5 bg-sky-50 text-sky-800 rounded-xl text-xs font-bold border border-sky-200">📍 <strong>Menyesuaikan zona waktu Anda (${local.tzCode})</strong>: Buka pukul <strong>${local.openTime} ${local.tzCode} - ${local.closeTime} ${local.tzCode}</strong></span>`;
            }
            return formatAiResponse(`⏰ <strong>Jam Operasional Toko ItemPedia</strong>:<br>
            Kami buka setiap hari <strong>Senin - Minggu pukul 06:00 WIB - 20:00 WIB</strong>!${localExtra}<br><br>
            Selama jam operasional ini, seller standby untuk memproses pesanan dan melakukan trade item maupun pengiriman data akun secara kilat.`);
        }

        // 2. Pembayaran & QRIS
        if (q.includes('bayar') || q.includes('pembayaran') || q.includes('qris') || q.includes('transfer') || q.includes('dana') || q.includes('gopay') || q.includes('ovo') || q.includes('bca') || q.includes('bank')) {
            return formatAiResponse(`💳 <strong>Metode Pembayaran di ItemPedia</strong>:<br>
            ItemPedia menggunakan sistem <strong>QRIS Otomatis Real-time</strong>.<br><br>
            • Mendukung semua e-wallet: <strong>GoPay, DANA, OVO, ShopeePay, LinkAja</strong>.<br>
            • Mendukung seluruh mobile banking: <strong>BCA, Mandiri, BRI, BNI, Seabank</strong> dll.<br>
            • Pembayaran diverifikasi otomatis seketika tanpa perlu upload bukti transfer!`);
        }

        // 3. Cara Trade Item Roblox
        if (q.includes('trade') || q.includes('kirim item') || q.includes('ambil') || q.includes('gimana cara beli') || q.includes('cara belanja') || q.includes('cara beli')) {
            return formatAiResponse(`🎮 <strong>Panduan Cara Beli & Trade Item</strong>:<br>
            1. Pilih item atau pet game Roblox di menu <strong>Katalog Produk</strong>.<br>
            2. Masukkan <strong>Username Roblox</strong> kamu dan cek avatarnya agar tepat sasaran.<br>
            3. Klik <strong>Beli Sekarang</strong> lalu scan QRIS yang tampil.<br>
            4. Setelah bayar, halaman invoice akan membuka <strong>Ruang Live Chat</strong>.<br>
            5. Kamu bisa kirim link private server di chat tersebut atau standby join game untuk serah terima item dengan seller!`);
        }

        // 4. Produk Akun Game (Polosan / Sultan)
        if (q.includes('akun') || q.includes('password') || q.includes('polosan') || q.includes('sultan') || q.includes('login akun')) {
            return formatAiResponse(`🔑 <strong>Sistem Pembelian Akun Roblox</strong>:<br>
            • Untuk pembelian Akun Roblox, data <strong>Username & Password</strong> akan otomatis tampil di halaman invoice begitu pembayaran terverifikasi.<br>
            • Seluruh akun dijamin <strong>100% Polosan Asli</strong>, tanpa sangkutan email pihak ketiga, dan bergaransi anti hack-back!`);
        }

        // 5. Keamanan, Garansi, & Legalitas
        if (q.includes('aman') || q.includes('garansi') || q.includes('penipuan') || q.includes('percaya') || q.includes('legal') || q.includes('hack')) {
            return formatAiResponse(`🛡️ <strong>Garansi Keamanan ItemPedia</strong>:<br>
            • <strong>100% Anti Hack-Back & Legal</strong>: Seluruh item didapatkan murni dari gameplay resmi tanpa duplikasi/cheat.<br>
            • Telah melayani lebih dari <strong>2.400+ transaksi sukses</strong> dengan rata-rata ulasan bintang 4.9.<br>
            • Tersedia perlindungan garansi jika terjadi kendala pada saat pengiriman.`);
        }

        // 6. Lacak Pesanan / Cek Status
        if (q.includes('lacak') || q.includes('invoice') || q.includes('status') || q.includes('pesanan saya') || q.includes('cek pesanan')) {
            return formatAiResponse(`📦 <strong>Cara Lacak Pesanan</strong>:<br>
            • Kunjungi menu <strong><a href="/lacak" class="text-sky-600 underline font-bold">Lacak Pesanan</a></strong> di atas, lalu masukkan nomor invoice (contoh: <code>ITP-2026...</code>) atau username Roblox kamu.<br>
            • Jika kamu sudah <strong>Masuk dengan Google</strong>, semua riwayat pesananmu langsung tercatat di halaman <strong><a href="/pesanan-saya" class="text-sky-600 underline font-bold">Pesanan Saya</a></strong>!`);
        }

        // 7. Daftar Game & Produk
        if (q.includes('game') || q.includes('produk') || q.includes('jual apa') || q.includes('katalog') || q.includes('zoo') || q.includes('tree') || q.includes('catch')) {
            return formatAiResponse(`🛒 <strong>Katalog Game Roblox di ItemPedia</strong>:<br>
            • 🌲 <strong>Chop Your Tree</strong>: Mythic Void Axe, Nuclear Sprinkler, Akun Sultan.<br>
            • 🐾 <strong>Build A Zoo</strong>: Mucy ($2.7M/s), Chomp, Secret Dragon, Conveyor Egg Bundle.<br>
            • 🐎 <strong>Catch and Tame</strong>: Master Lasso Tier 5, Rideable Gryphon, Akun Sultan.<br><br>
            Silakan pilih logo game di halaman beranda untuk melihat daftar harga dan stoknya!`);
        }

        // 8. Hubungi Manusia / WhatsApp
        if (q.includes('whatsapp') || q.includes('wa') || q.includes('admin') || q.includes('manusia') || q.includes('kontak') || q.includes('hubungi') || q.includes('bantuan')) {
            return formatAiResponse(`💬 <strong>Hubungi Penjual Langsung</strong>:<br>
            Jika kamu butuh berbicara langsung dengan admin seller manusia, silakan klik <strong>tombol WhatsApp hijau bulat di atas tombol saya ini</strong>, atau gunakan fitur <strong>Live Chat In-App</strong> pada halaman invoice pesananmu ya!`);
        }

        // Default: Fallback ramah khusus ItemPedia
        const localInfo = detectLocalStoreHours();
        const displayJam = (localInfo.tzCode && localInfo.tzCode !== 'WIB')
            ? `${localInfo.openTime} - ${localInfo.closeTime} ${localInfo.tzCode} (06:00 - 20:00 WIB)`
            : '06:00 - 20:00 WIB';

        return formatAiResponse(`Saya adalah asisten AI khusus toko <strong>ItemPedia</strong> 🤖<br><br>
        Saya bisa membantu menjawab hal-hal seputar:<br>
        • ⏰ <strong>Jam operasional toko</strong> (${displayJam})<br>
        • 💳 <strong>Pembayaran QRIS instan</strong><br>
        • 🎮 <strong>Cara trade item & terima akun Roblox</strong><br>
        • 🛡️ <strong>Garansi keamanan belanja</strong><br>
        • 📦 <strong>Cara melacak pesanan invoice</strong><br><br>
        Silakan tanyakan hal di atas terkait toko ItemPedia ya!`);
    }

    function handleSendAiMessage(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('aiChatInput');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        const container = document.getElementById('aiChatMessages');
        const typing = document.getElementById('aiTypingIndicator');

        // Sembunyikan quick chips jika masih ada
        const chips = document.getElementById('aiQuickChips');
        if (chips) chips.classList.add('hidden');

        // 1. Tampilkan Bubble User
        const userBubble = `
            <div class="flex items-start justify-end gap-2.5 max-w-[85%] ml-auto animate-in fade-in">
                <div class="space-y-1 text-right">
                    <div class="p-3.5 rounded-2xl rounded-tr-sm bg-gradient-to-r from-sky-500 to-blue-600 text-white leading-relaxed shadow-sm font-medium text-left">
                        ${escapeHtml(text)}
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold block text-right">Kamu</span>
                </div>
            </div>
        `;
        if (typing) {
            typing.insertAdjacentHTML('beforebegin', userBubble);
        } else {
            container.insertAdjacentHTML('beforeend', userBubble);
        }

        // Tampilkan indikator mengetik
        if (typing) {
            typing.classList.remove('hidden');
        }
        scrollAiChatToBottom();

        // 2. Simulasi AI Reasoning (450ms)
        setTimeout(() => {
            if (typing) {
                typing.classList.add('hidden');
            }

            const rawResponse = generateItemPediaAiResponse(text);
            const aiResponseHtml = formatAiResponse(rawResponse);

            const aiBubble = `
                <div class="flex items-start gap-2.5 max-w-[90%] animate-in fade-in">
                    <div class="w-7 h-7 rounded-xl bg-sky-500 text-white flex items-center justify-center text-xs flex-shrink-0 mt-0.5 shadow-sm">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div class="space-y-1">
                        <div class="p-3.5 rounded-2xl rounded-tl-sm bg-white border border-slate-200/90 text-slate-800 leading-relaxed shadow-sm font-medium">
                            ${aiResponseHtml}
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold block">ItemPedia AI</span>
                    </div>
                </div>
            `;

            if (typing) {
                typing.insertAdjacentHTML('beforebegin', aiBubble);
            } else {
                container.insertAdjacentHTML('beforeend', aiBubble);
            }

            playAiChime();
            scrollAiChatToBottom();
        }, 500);
    }

    // ================= ANIMASI SCROLLDOWN NAVIGASI HALUS =================
    function smoothScrollToElement(targetEl, offset) {
        if (!targetEl) return;
        const headerEl = document.querySelector('header');
        const headerHeight = headerEl ? headerEl.offsetHeight : 80;
        const effectiveOffset = (typeof offset === 'number') ? offset : (headerHeight + 14);
        
        const targetRect = targetEl.getBoundingClientRect();
        const targetTop = targetRect.top + window.pageYOffset - effectiveOffset;

        window.scrollTo({
            top: Math.max(0, targetTop),
            behavior: 'smooth'
        });

        // Efek glowing halo pada section target untuk feedback visual yang memuaskan
        targetEl.classList.remove('scroll-target-glow');
        void targetEl.offsetWidth; // trigger reflow
        targetEl.classList.add('scroll-target-glow');
        setTimeout(() => {
            targetEl.classList.remove('scroll-target-glow');
        }, 1600);
    }

    // Intercept semua klik pada link navigasi in-page (#katalog, /#katalog, dll)
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href) return;

        let hash = null;
        if (href.startsWith('#') && href.length > 1) {
            hash = href;
        } else if (href.startsWith('/#') && (window.location.pathname === '/' || window.location.pathname === '')) {
            hash = href.substring(1); // menjadi #katalog
        } else if (href.includes('#')) {
            try {
                const targetUrl = new URL(link.href, window.location.origin);
                if (targetUrl.pathname === window.location.pathname && targetUrl.hash) {
                    hash = targetUrl.hash;
                }
            } catch(err) {}
        }

        if (hash) {
            const targetEl = document.querySelector(hash);
            if (targetEl) {
                e.preventDefault();
                smoothScrollToElement(targetEl);
                if (window.history && window.history.pushState) {
                    window.history.pushState(null, null, hash);
                } else {
                    window.location.hash = hash;
                }
            }
        }
    });

    // Jalankan animasi scroll halus jika halaman dibuka langsung dengan hash URL
    window.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash) {
            const targetEl = document.querySelector(window.location.hash);
            if (targetEl) {
                setTimeout(function() {
                    smoothScrollToElement(targetEl);
                }, 200);
            }
        }
        updateSiteThemeIcon();
    });

    // Theme Toggle Functionality (Dark / Light Mode)
    function toggleSiteTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('itempedia_theme', isDark ? 'dark' : 'light');
        updateSiteThemeIcon();
    }

    function updateSiteThemeIcon() {
        const icon = document.getElementById('themeToggleIcon');
        const label = document.getElementById('themeToggleLabel');
        if (!icon) return;
        if (document.documentElement.classList.contains('dark')) {
            icon.className = 'fa-solid fa-sun text-amber-400 text-sm sm:text-base transition-transform rotate-0 duration-300';
            if (label) label.innerText = 'Terang';
        } else {
            icon.className = 'fa-solid fa-moon text-sky-500 text-sm sm:text-base transition-transform -rotate-12 duration-300';
            if (label) label.innerText = 'Gelap';
        }
    }
    </script>
    <?php include __DIR__ . '/components/custom_confirm.php'; ?>
</body>
</html>
