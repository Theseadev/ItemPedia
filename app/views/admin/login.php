<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Seller Center - ItemPedia</title>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        };
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
<body class="bg-[#f0f2f5] dark:bg-[#081220] text-slate-800 dark:text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased transition-colors duration-200">
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white dark:bg-[#0c1e33] border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xs p-2">
                <img src="/images/logo-icon.png" srcset="/images/logo-icon@2x.png 2x" alt="ItemPedia" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Seller Center <span class="text-sky-500">ItemPedia</span></h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Masuk untuk mengelola pesanan & dagangan toko Roblox</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-[#0c1e33] rounded-3xl border border-slate-200/90 dark:border-slate-800 p-6 sm:p-8 shadow-xl">
            <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/80 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2.5 animate-in fade-in">
                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <form action="/admin/login" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" 
                           name="username" 
                           required 
                           value="admin"
                           placeholder="admin"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-slate-900 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" 
                           name="password" 
                           required 
                           value="admin123"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-sky-500 focus:bg-white dark:focus:bg-slate-900 transition">
                </div>

                <div class="p-3.5 bg-slate-50 dark:bg-slate-900/70 rounded-2xl text-[11px] text-slate-600 dark:text-slate-400 border border-slate-200/80 dark:border-slate-800">
                    <div class="flex items-center gap-1.5 font-bold text-slate-800 dark:text-slate-200 mb-0.5">
                        <i class="fa-solid fa-key text-amber-500"></i>
                        <span>Akun Default:</span>
                    </div>
                    Username: <code class="text-sky-600 dark:text-sky-400 font-bold">admin</code> &bull; Password: <code class="text-sky-600 dark:text-sky-400 font-bold">admin123</code>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-sky-500 hover:bg-sky-600 text-white font-extrabold text-sm rounded-xl shadow-xs transition flex items-center justify-center gap-2 active:scale-95">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk ke Seller Center</span>
                </button>
            </form>

            <div class="mt-6 text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="/" class="text-xs text-slate-500 dark:text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 font-bold transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Halaman Toko</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
