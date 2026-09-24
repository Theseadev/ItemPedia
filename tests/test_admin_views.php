<?php

require dirname(__DIR__) . '/vendor/autoload.php';

session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';

echo "=== MEMULAI TEST SEMUA VIEW ADMIN ===\n";

Flight::set('flight.views.path', dirname(__DIR__) . '/app/views');

$db = \App\Config\Database::getConnection();
echo "[1] Database Connection: " . ($db ? "OK" : "FAIL") . "\n";

// Test 1: Dashboard
ob_start();
\App\Controllers\AdminController::dashboard();
$out = ob_get_clean();
$ok1 = (strlen($out) > 500 && strpos($out, 'Beranda Toko') !== false && strpos($out, 'Aktifitas Penting') !== false);
echo "[2] Beranda Toko (/admin) [Image 1]: " . ($ok1 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

// Test 2: Orders
ob_start();
\App\Controllers\AdminController::orders();
$out = ob_get_clean();
$ok2 = (strlen($out) > 500 && strpos($out, 'Riwayat Pesanan') !== false && strpos($out, 'Perlu Diproses') !== false);
echo "[3] Riwayat Pesanan (/admin/orders) [Image 2]: " . ($ok2 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

// Test 3: Reviews
ob_start();
\App\Controllers\AdminController::reviews();
$out = ob_get_clean();
$ok3 = (strlen($out) > 500 && strpos($out, 'Ulasan Pembeli') !== false && strpos($out, 'Rating Toko') !== false);
echo "[4] Ulasan Pembeli (/admin/reviews) [Image 3]: " . ($ok3 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

// Test 4: Create Product
ob_start();
\App\Controllers\AdminController::createProduct();
$out = ob_get_clean();
$ok4 = (strlen($out) > 500 && strpos($out, 'Buat Dagangan') !== false && strpos($out, 'Tipe Dagangan') !== false);
echo "[5] Buat Dagangan (/admin/products/create) [Image 4]: " . ($ok4 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

// Test 5: Products
ob_start();
\App\Controllers\AdminController::products();
$out = ob_get_clean();
$ok5 = (strlen($out) > 500 && strpos($out, 'Daganganku') !== false && strpos($out, 'Stok Habis') !== false);
echo "[6] Daganganku (/admin/products) [Image 5]: " . ($ok5 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

// Test 6: Categories
ob_start();
\App\Controllers\AdminController::categories();
$out = ob_get_clean();
$ok6 = (strlen($out) > 500 && strpos($out, 'Kategori') !== false);
echo "[7] Kategori & Game Roblox (/admin/categories): " . ($ok6 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

// Test 7: Pages
ob_start();
\App\Controllers\AdminController::pages();
$out = ob_get_clean();
$ok7 = (strlen($out) > 500 && strpos($out, 'Edit Laman') !== false);
echo "[8] Edit Laman & FAQ (/admin/pages): " . ($ok7 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

// Test 8: Redeem Codes
ob_start();
\App\Controllers\AdminController::redeemCodes();
$out = ob_get_clean();
$ok8 = (strlen($out) > 500 && strpos($out, 'Kode Redeem') !== false);
echo "[9] Kode Redeem (/admin/redeem-codes): " . ($ok8 ? "OK (" . strlen($out) . " bytes)" : "FAIL") . "\n";

echo "=== SEMUA TEST BERHASIL DIJALANKAN ===\n";
