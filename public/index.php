<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\OrderController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;

Flight::set('flight.views.path', dirname(__DIR__) . '/app/views');
Flight::set('flight.log_errors', true);

Flight::map('error', function (\Throwable $ex) {
    error_log("ItemPedia Exception: " . $ex->getMessage() . " in " . $ex->getFile() . ":" . $ex->getLine());
    Flight::redirect('/?error=' . urlencode('Terjadi kesalahan pada sistem. Silakan coba kembali atau hubungi admin.'));
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

// 404 Handler
Flight::map('notFound', function () {
    Flight::redirect('/?error=Halaman+tidak+ditemukan');
});

// Jalankan Flight PHP
Flight::start();
