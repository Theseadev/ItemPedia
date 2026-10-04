<?php

$rootDir = dirname(__DIR__);

if (file_exists($rootDir . '/vendor/autoload.php')) {
    require_once $rootDir . '/vendor/autoload.php';
}

// Prepend PSR-4 Autoloader for App\ namespace
spl_autoload_register(function ($class) use ($rootDir) {
    $prefix = 'App\\';
    $baseDir = $rootDir . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}, true, true);

use App\Controllers\HomeController;
use App\Controllers\OrderController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;

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

// Routing Admin
Flight::route('GET|POST /admin/login', [AdminController::class, 'login']);
Flight::route('GET /admin/logout', [AdminController::class, 'logout']);
Flight::route('GET /admin', [AdminController::class, 'dashboard']);
Flight::route('GET /admin/orders', [AdminController::class, 'orders']);
Flight::route('POST /admin/orders/update', [AdminController::class, 'updateOrderStatus']);
Flight::route('GET /admin/reviews', [AdminController::class, 'reviews']);
Flight::route('GET /admin/products', [AdminController::class, 'products']);
Flight::route('GET /admin/products/create', [AdminController::class, 'createProduct']);
Flight::route('POST /admin/products/quick-stock', [AdminController::class, 'quickUpdateStock']);
Flight::route('POST /admin/products/add', [AdminController::class, 'addProduct']);
Flight::route('POST /admin/products/update', [AdminController::class, 'updateProduct']);
Flight::route('POST /admin/products/delete', [AdminController::class, 'deleteProduct']);
Flight::route('GET /admin/categories', [AdminController::class, 'categories']);
Flight::route('POST /admin/categories/add', [AdminController::class, 'addCategory']);
Flight::route('POST /admin/categories/update', [AdminController::class, 'updateCategory']);
Flight::route('POST /admin/categories/delete', [AdminController::class, 'deleteCategory']);
Flight::route('POST /admin/categories/add-custom-category', [AdminController::class, 'addCustomCategory']);
Flight::route('POST /admin/categories/update-custom-category', [AdminController::class, 'updateCustomCategory']);
Flight::route('POST /admin/categories/delete-custom-category', [AdminController::class, 'deleteCustomCategory']);
Flight::route('GET /admin/pages', [AdminController::class, 'pages']);
Flight::route('POST /admin/pages/update', [AdminController::class, 'savePageContent']);
Flight::route('POST /admin/faqs/add', [AdminController::class, 'addFaq']);
Flight::route('POST /admin/faqs/update', [AdminController::class, 'updateFaq']);
Flight::route('POST /admin/faqs/delete', [AdminController::class, 'deleteFaq']);
Flight::route('GET /admin/redeem-codes', [AdminController::class, 'redeemCodes']);
Flight::route('POST /admin/redeem-codes/add', [AdminController::class, 'addRedeemCode']);
Flight::route('POST /admin/redeem-codes/update', [AdminController::class, 'updateRedeemCode']);
Flight::route('POST /admin/redeem-codes/delete', [AdminController::class, 'deleteRedeemCode']);
Flight::route('POST /admin/redeem-codes/toggle', [AdminController::class, 'toggleRedeemCode']);
Flight::route('POST /admin/settings', [AdminController::class, 'updateSettings']);

// Routing Modul Pembaruan Sistem (Upgrade via GitHub)
Flight::route('GET /admin/upgrade', [AdminController::class, 'upgradeView']);
Flight::route('POST /api/admin/upgrade/check', [AdminController::class, 'checkUpdate']);
Flight::route('POST /api/admin/upgrade/execute', [AdminController::class, 'executeUpgrade']);

// 404 Handler
Flight::map('notFound', function () {
    Flight::redirect('/?error=Halaman+tidak+ditemukan');
});

// Jalankan Flight PHP
Flight::start();
