<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Seller Center - ItemPedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/svg+xml" href="/images/logo-icon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f0f2f5] text-slate-800 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white border border-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xs p-2">
                <img src="/images/logo-icon.png" srcset="/images/logo-icon@2x.png 2x" alt="ItemPedia" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Seller Center <span class="text-blue-600">ItemPedia</span></h1>
            <p class="text-xs text-slate-500 mt-1">Masuk untuk mengelola pesanan & dagangan toko Roblox</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xl">
            <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2.5 animate-in fade-in">
                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <form action="/admin/login" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" 
                           name="username" 
                           required 
                           value="admin"
                           placeholder="admin"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" 
                           name="password" 
                           required 
                           value="admin123"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition">
                </div>

                <div class="p-3.5 bg-slate-50 rounded-2xl text-[11px] text-slate-600 border border-slate-200/80">
                    <div class="flex items-center gap-1.5 font-bold text-slate-800 mb-0.5">
                        <i class="fa-solid fa-key text-amber-500"></i>
                        <span>Akun Default:</span>
                    </div>
                    Username: <code class="text-blue-600 font-bold">admin</code> &bull; Password: <code class="text-blue-600 font-bold">admin123</code>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm rounded-xl shadow-xs transition flex items-center justify-center gap-2 active:scale-95">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk ke Seller Center</span>
                </button>
            </form>

            <div class="mt-6 text-center pt-4 border-t border-slate-100">
                <a href="/" class="text-xs text-slate-500 hover:text-blue-600 font-bold transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Halaman Toko</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
