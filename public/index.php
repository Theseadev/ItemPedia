<?php

$rootDir = dirname(__DIR__);

// Ensure base path resolves to root '/' in serverless / proxy environments
if (isset($_SERVER['SCRIPT_NAME'])) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}
if (isset($_SERVER['PHP_SELF'])) {
    $_SERVER['PHP_SELF'] = '/index.php';
}

if (file_exists($rootDir . '/vendor/autoload.php')) {
    require_once $rootDir . '/vendor/autoload.php';
}

// Case-safe PSR-4 Autoloader for App\ namespace (handles Linux/Vercel case-sensitive filesystems)
spl_autoload_register(function ($class) use ($rootDir) {
    $prefix = 'App\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $parts = explode('\\', $relativeClass);
    $className = array_pop($parts);
    
    $subDirLower = !empty($parts) ? implode('/', array_map('strtolower', $parts)) . '/' : '';
    $subDirExact = !empty($parts) ? implode('/', $parts) . '/' : '';

    $candidates = [
        $rootDir . '/app/' . $subDirLower . $className . '.php',
        $rootDir . '/app/' . $subDirLower . strtolower($className) . '.php',
        $rootDir . '/app/' . $subDirExact . $className . '.php',
        $rootDir . '/app/' . $subDirExact . strtolower($className) . '.php',
        $rootDir . '/app/' . str_replace('\\', '/', $relativeClass) . '.php',
        $rootDir . '/app/' . strtolower(str_replace('\\', '/', $relativeClass)) . '.php'
    ];

    foreach ($candidates as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}, true, true);

// Explicitly require core classes for 100% fail-proof execution on Serverless / Linux
require_once $rootDir . '/app/config/database.php';
require_once $rootDir . '/app/services/UpgradeService.php';
require_once $rootDir . '/app/services/DuitkuService.php';
require_once $rootDir . '/app/services/CacheService.php';
require_once $rootDir . '/app/controllers/HomeController.php';
require_once $rootDir . '/app/controllers/OrderController.php';
require_once $rootDir . '/app/controllers/AdminController.php';
require_once $rootDir . '/app/controllers/AuthController.php';

use App\Controllers\HomeController;
use App\Controllers\OrderController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Services\DuitkuService;
use App\Services\CacheService;

Flight::set('flight.base_url', '/');
Flight::path($rootDir . '/app');
Flight::path($rootDir . '/app/controllers');
Flight::path($rootDir . '/app/config');
Flight::path($rootDir . '/app/services');
Flight::set('flight.views.path', $rootDir . '/app/views');
Flight::set('flight.log_errors', true);

Flight::map('error', function (\Throwable $ex) {
    error_log("ItemPedia Exception: " . $ex->getMessage() . " in " . $ex->getFile() . ":" . $ex->getLine());
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>ItemPedia - System Notice</title><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-slate-900 text-white flex items-center justify-center min-h-screen p-4">';
    echo '<div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-3xl p-6 shadow-2xl text-center space-y-4">';
    echo '<div class="w-14 h-14 mx-auto rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-bold">⚠️</div>';
    echo '<h1 class="text-xl font-black text-white">ItemPedia Sedang Menyiapkan Server</h1>';
    echo '<p class="text-xs text-slate-300 leading-relaxed bg-slate-900/80 p-3.5 rounded-2xl border border-slate-700 text-left font-mono break-words">' . htmlspecialchars($ex->getMessage()) . '</p>';
    echo '<a href="/" class="inline-block px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs shadow-lg transition">Muat Ulang Halaman</a>';
    echo '</div></body></html>';
});

// Routing Halaman Utama & Katalog
Flight::route('GET /', [HomeController::class, 'index']);

// Routing Autentikasi Google Pembeli (1-Click Google Sign-In)
Flight::route('POST /auth/google', [AuthController::class, 'loginGoogle']);
Flight::route('GET /auth/logout', [AuthController::class, 'logout']);
Flight::route('GET /pesanan-saya', [AuthController::class, 'myOrders']);

// Routing API Roblox Avatar Checker, Kode Redeem & Keranjang
Flight::route('GET /api/roblox-avatar', [OrderController::class, 'checkRoblox']);
Flight::route('POST /api/redeem-code/check', [OrderController::class, 'checkRedeemCode']);
Flight::route('POST /api/cart/check', [OrderController::class, 'checkCart']);

// Routing Integrasi Payment Gateway Duitku (Sandbox & Production)
Flight::route('POST /api/duitku/callback', [OrderController::class, 'duitkuCallback']);
Flight::route('POST /api/duitku/create', [OrderController::class, 'createDuitkuPayment']);
Flight::route('GET /api/duitku/channels', [OrderController::class, 'getDuitkuChannels']);

// Routing Pemesanan & Invoice
Flight::route('POST /order/create', [OrderController::class, 'createOrder']);
Flight::route('POST /order/cart-checkout', [OrderController::class, 'createOrder']);
Flight::route('GET /order/@invoice/simulate', [OrderController::class, 'simulatePayment']);
Flight::route('POST /order/@invoice/review', [OrderController::class, 'submitReview']);
Flight::route('GET /order/@invoice', [OrderController::class, 'showOrder']);
Flight::route('GET /lacak', [OrderController::class, 'lacak']);
Flight::route('GET /keranjang', function() {
    // Arahkan ke homepage dan buka drawer keranjang secara instan
    Flight::redirect('/?open_cart=1');
});

// Routing Live Chat In-App
Flight::route('GET /api/chat/inbox', [OrderController::class, 'getChatInbox']);
Flight::route('GET /api/chat/@invoice', [OrderController::class, 'getChatMessages']);
Flight::route('POST /api/chat/@invoice/send', [OrderController::class, 'sendChatMessage']);

// Routing Admin (Secret Portal: /Banjar)
Flight::route('GET|POST /Banjar/login', [AdminController::class, 'login']);
Flight::route('GET|POST /banjar/login', [AdminController::class, 'login']);
Flight::route('GET /Banjar/logout', [AdminController::class, 'logout']);
Flight::route('GET /banjar/logout', [AdminController::class, 'logout']);
Flight::route('GET /Banjar', [AdminController::class, 'dashboard']);
Flight::route('GET /banjar', [AdminController::class, 'dashboard']);
Flight::route('GET /Banjar/orders', [AdminController::class, 'orders']);
Flight::route('GET /banjar/orders', [AdminController::class, 'orders']);
Flight::route('POST /Banjar/orders/update', [AdminController::class, 'updateOrderStatus']);
Flight::route('POST /banjar/orders/update', [AdminController::class, 'updateOrderStatus']);
Flight::route('GET /Banjar/reviews', [AdminController::class, 'reviews']);
Flight::route('GET /banjar/reviews', [AdminController::class, 'reviews']);
Flight::route('GET /Banjar/products', [AdminController::class, 'products']);
Flight::route('GET /banjar/products', [AdminController::class, 'products']);
Flight::route('GET /Banjar/products/create', [AdminController::class, 'createProduct']);
Flight::route('GET /banjar/products/create', [AdminController::class, 'createProduct']);
Flight::route('POST /Banjar/products/quick-stock', [AdminController::class, 'quickUpdateStock']);
Flight::route('POST /banjar/products/quick-stock', [AdminController::class, 'quickUpdateStock']);
Flight::route('POST /Banjar/products/add', [AdminController::class, 'addProduct']);
Flight::route('POST /banjar/products/add', [AdminController::class, 'addProduct']);
Flight::route('POST /Banjar/products/update', [AdminController::class, 'updateProduct']);
Flight::route('POST /banjar/products/update', [AdminController::class, 'updateProduct']);
Flight::route('POST /Banjar/products/delete', [AdminController::class, 'deleteProduct']);
Flight::route('POST /banjar/products/delete', [AdminController::class, 'deleteProduct']);
Flight::route('GET /Banjar/categories', [AdminController::class, 'categories']);
Flight::route('GET /banjar/categories', [AdminController::class, 'categories']);
Flight::route('POST /Banjar/categories/add', [AdminController::class, 'addCategory']);
Flight::route('POST /banjar/categories/add', [AdminController::class, 'addCategory']);
Flight::route('POST /Banjar/categories/update', [AdminController::class, 'updateCategory']);
Flight::route('POST /banjar/categories/update', [AdminController::class, 'updateCategory']);
Flight::route('POST /Banjar/categories/delete', [AdminController::class, 'deleteCategory']);
Flight::route('POST /banjar/categories/delete', [AdminController::class, 'deleteCategory']);
Flight::route('POST /Banjar/categories/add-custom-category', [AdminController::class, 'addCustomCategory']);
Flight::route('POST /banjar/categories/add-custom-category', [AdminController::class, 'addCustomCategory']);
Flight::route('POST /Banjar/categories/update-custom-category', [AdminController::class, 'updateCustomCategory']);
Flight::route('POST /banjar/categories/update-custom-category', [AdminController::class, 'updateCustomCategory']);
Flight::route('POST /Banjar/categories/delete-custom-category', [AdminController::class, 'deleteCustomCategory']);
Flight::route('POST /banjar/categories/delete-custom-category', [AdminController::class, 'deleteCustomCategory']);
Flight::route('GET /Banjar/pages', [AdminController::class, 'pages']);
Flight::route('GET /banjar/pages', [AdminController::class, 'pages']);
Flight::route('POST /Banjar/pages/update', [AdminController::class, 'savePageContent']);
Flight::route('POST /banjar/pages/update', [AdminController::class, 'savePageContent']);
Flight::route('POST /Banjar/faqs/add', [AdminController::class, 'addFaq']);
Flight::route('POST /banjar/faqs/add', [AdminController::class, 'addFaq']);
Flight::route('POST /Banjar/faqs/update', [AdminController::class, 'updateFaq']);
Flight::route('POST /banjar/faqs/update', [AdminController::class, 'updateFaq']);
Flight::route('POST /Banjar/faqs/delete', [AdminController::class, 'deleteFaq']);
Flight::route('POST /banjar/faqs/delete', [AdminController::class, 'deleteFaq']);
Flight::route('GET /Banjar/redeem-codes', [AdminController::class, 'redeemCodes']);
Flight::route('GET /banjar/redeem-codes', [AdminController::class, 'redeemCodes']);
Flight::route('POST /Banjar/redeem-codes/add', [AdminController::class, 'addRedeemCode']);
Flight::route('POST /banjar/redeem-codes/add', [AdminController::class, 'addRedeemCode']);
Flight::route('POST /Banjar/redeem-codes/update', [AdminController::class, 'updateRedeemCode']);
Flight::route('POST /banjar/redeem-codes/update', [AdminController::class, 'updateRedeemCode']);
Flight::route('POST /Banjar/redeem-codes/delete', [AdminController::class, 'deleteRedeemCode']);
Flight::route('POST /banjar/redeem-codes/delete', [AdminController::class, 'deleteRedeemCode']);
Flight::route('POST /Banjar/redeem-codes/toggle', [AdminController::class, 'toggleRedeemCode']);
Flight::route('POST /banjar/redeem-codes/toggle', [AdminController::class, 'toggleRedeemCode']);
Flight::route('POST /Banjar/settings', [AdminController::class, 'updateSettings']);
Flight::route('POST /banjar/settings', [AdminController::class, 'updateSettings']);

// Routing Manajemen Cache Aplikasi (Save & Clear Cache)
Flight::route('POST /Banjar/cache/save', [AdminController::class, 'saveCache']);
Flight::route('POST /banjar/cache/save', [AdminController::class, 'saveCache']);
Flight::route('POST /Banjar/cache/clear', [AdminController::class, 'clearCache']);
Flight::route('POST /banjar/cache/clear', [AdminController::class, 'clearCache']);
Flight::route('GET /Banjar/cache/stats', [AdminController::class, 'getCacheStats']);
Flight::route('GET /banjar/cache/stats', [AdminController::class, 'getCacheStats']);

// Routing Modul Pembaruan Sistem (Upgrade via GitHub)
Flight::route('GET /Banjar/upgrade', [AdminController::class, 'upgradeView']);
Flight::route('GET /banjar/upgrade', [AdminController::class, 'upgradeView']);
Flight::route('POST /api/Banjar/upgrade/check', [AdminController::class, 'checkUpdate']);
Flight::route('POST /api/banjar/upgrade/check', [AdminController::class, 'checkUpdate']);
Flight::route('POST /api/Banjar/upgrade/execute', [AdminController::class, 'executeUpgrade']);
Flight::route('POST /api/banjar/upgrade/execute', [AdminController::class, 'executeUpgrade']);

// 404 Handler
Flight::map('notFound', function () {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>404 - Halaman Tidak Ditemukan | ItemPedia</title><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-slate-900 text-white flex items-center justify-center min-h-screen p-4">';
    echo '<div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-3xl p-6 shadow-2xl text-center space-y-4">';
    echo '<div class="w-14 h-14 mx-auto rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-2xl font-bold">🔍</div>';
    echo '<h1 class="text-xl font-black text-white">404 - Halaman Tidak Ditemukan</h1>';
    echo '<p class="text-xs text-slate-400">Halaman yang Anda cari tidak tersedia atau URL yang diminta tidak ditemukan.</p>';
    echo '<a href="/" class="inline-block px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs shadow-lg transition">Kembali ke Beranda</a>';
    echo '</div></body></html>';
});

// Jalankan Flight PHP
Flight::start();
