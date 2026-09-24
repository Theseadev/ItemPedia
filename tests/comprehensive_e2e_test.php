<?php
/**
 * Comprehensive Automated End-to-End Test Suite for ItemPedia
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$baseUrl = 'http://127.0.0.1:8000';
$results = [];

function runTest($name, $fn) {
    global $results;
    try {
        $msg = $fn();
        $results[] = ['name' => $name, 'status' => 'PASS', 'message' => $msg ?: 'OK'];
        echo "✅ [PASS] {$name}: " . ($msg ?: 'OK') . "\n";
    } catch (\Throwable $e) {
        $results[] = ['name' => $name, 'status' => 'FAIL', 'message' => $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()];
        echo "❌ [FAIL] {$name}: " . $e->getMessage() . "\n";
    }
}

function httpReq($url, $method = 'GET', $data = [], $cookies = [], $followRedirect = false) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followRedirect);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if (is_array($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }

    if (!empty($cookies)) {
        $cookieStr = '';
        foreach ($cookies as $k => $v) {
            $cookieStr .= "{$k}={$v}; ";
        }
        curl_setopt($ch, CURLOPT_COOKIE, trim($cookieStr));
    }

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($raw, 0, $headerSize);
    $body = substr($raw, $headerSize);
    curl_close($ch);

    // Extract Set-Cookie
    $newCookies = $cookies; // preserve existing cookies
    if (preg_match_all('/^Set-Cookie:\s*([^;]+)/mi', $headers, $matches)) {
        foreach ($matches[1] as $c) {
            [$ck, $cv] = explode('=', $c, 2);
            $newCookies[trim($ck)] = trim($cv);
        }
    }

    // Extract Location
    $location = null;
    if (preg_match('/^Location:\s*(.+)$/mi', $headers, $mLoc)) {
        $location = trim($mLoc[1]);
    }

    return [
        'code' => $httpCode,
        'headers' => $headers,
        'body' => $body,
        'cookies' => $newCookies,
        'location' => $location
    ];
}

function isRedirect($code) {
    return in_array($code, [301, 302, 303, 307, 308]);
}

echo "=== STARTING ITEMPEDIA COMPREHENSIVE QA TEST SUITE ===\n\n";

// ----------------------------------------------------
// TEST 1: Homepage Rendering & Health
// ----------------------------------------------------
runTest("1. Homepage Rendering (GET /)", function() use ($baseUrl) {
    $res = httpReq($baseUrl . '/');
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    if (!str_contains($res['body'], 'ItemPedia')) {
        throw new Exception("Homepage missing brand 'ItemPedia'");
    }
    if (str_contains($res['body'], 'FLASH SALE TERBATAS')) {
        throw new Exception("Flash sale bar still present (should be removed)");
    }
    if (str_contains($res['body'], 'Senin - Minggu • 07:00 WITA')) {
        throw new Exception("Operating hours bar still present (should be removed)");
    }
    if (!str_contains($res['body'], 'gameCategoriesMap')) {
        throw new Exception("Homepage missing gameCategoriesMap script");
    }
    return "Homepage rendered cleanly without removed bars (HTTP 200)";
});

// ----------------------------------------------------
// TEST 1b: Dynamic Game Categories Separation
// ----------------------------------------------------
runTest("1b. Dynamic Game Categories Check", function() use ($baseUrl) {
    // 1. Check Build A Zoo categories
    $resBaz = httpReq($baseUrl . '/?game=' . urlencode('Build A Zoo'));
    if ($resBaz['code'] !== 200) {
        throw new Exception("Expected HTTP 200 for Build A Zoo, got " . $resBaz['code']);
    }
    if (!str_contains($resBaz['body'], 'Pet') || !str_contains($resBaz['body'], 'Egg')) {
        throw new Exception("Build A Zoo should contain Pet and Egg categories");
    }

    // 2. Check Chop Your Tree categories
    $resTree = httpReq($baseUrl . '/?game=' . urlencode('Chop Your Tree'));
    if ($resTree['code'] !== 200) {
        throw new Exception("Expected HTTP 200 for Chop Your Tree, got " . $resTree['code']);
    }
    if (!str_contains($resTree['body'], 'Axe') || !str_contains($resTree['body'], 'Booster')) {
        throw new Exception("Chop Your Tree should contain Axe and Booster categories");
    }

    // 3. Check Catch and Tame categories
    $resTame = httpReq($baseUrl . '/?game=' . urlencode('Catch and Tame'));
    if ($resTame['code'] !== 200) {
        throw new Exception("Expected HTTP 200 for Catch and Tame, got " . $resTame['code']);
    }
    if (!str_contains($resTame['body'], 'Monster') || !str_contains($resTame['body'], 'Lasso')) {
        throw new Exception("Catch and Tame should contain Monster and Lasso categories");
    }

    return "Each game correctly provides its distinct, custom categories";
});

// ----------------------------------------------------
// TEST 2: Lacak Pesanan Page
// ----------------------------------------------------
runTest("2. Lacak Pesanan (GET /lacak)", function() use ($baseUrl) {
    $res = httpReq($baseUrl . '/lacak');
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    if (!str_contains($res['body'], 'Lacak Pesanan')) {
        throw new Exception("Missing 'Lacak Pesanan' heading");
    }
    return "Lacak page loads cleanly (HTTP 200)";
});

// ----------------------------------------------------
// TEST 3: Roblox Avatar Checker API
// ----------------------------------------------------
runTest("3. API Roblox Avatar Checker (GET /api/roblox-avatar)", function() use ($baseUrl) {
    $res = httpReq($baseUrl . '/api/roblox-avatar?username=Roblox');
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    $json = json_decode($res['body'], true);
    if (empty($json['success']) || empty($json['avatarUrl'])) {
        throw new Exception("Invalid JSON avatar response: " . $res['body']);
    }
    return "Roblox Avatar API works (Avatar URL: " . substr($json['avatarUrl'], 0, 40) . "...)";
});

// ----------------------------------------------------
// TEST 4: Redeem Code Validation API
// ----------------------------------------------------
runTest("4. API Redeem Code Check (POST /api/redeem-code/check)", function() use ($baseUrl) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();

    // Create a test redeem code
    $db->prepare("REPLACE INTO redeem_codes (code, discount_percent, product_id, max_uses, used_count, is_active) VALUES ('TESTDISC20', 20, NULL, 100, 0, 1)")->execute();
    
    $prod = $db->query("SELECT id, price FROM products WHERE is_active = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if (!$prod) {
        throw new Exception("No product found to test redeem code");
    }

    $res = httpReq($baseUrl . '/api/redeem-code/check', 'POST', [
        'code' => 'TESTDISC20',
        'product_id' => $prod['id'],
        'quantity' => 1
    ]);
    
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code'] . " - " . $res['body']);
    }
    $json = json_decode($res['body'], true);
    if (empty($json['success']) || (int)$json['discount_percent'] !== 20) {
        throw new Exception("Redeem discount mismatch: " . $res['body']);
    }

    return "Redeem Code API correctly computes 20% discount (Discount: " . $json['formatted_discount'] . ")";
});

// ----------------------------------------------------
// TEST 5: Google Sign-In API & Pesanan Saya
// ----------------------------------------------------
$buyerSessionCookies = [];
runTest("5. Buyer 1-Click Google Sign-In (POST /auth/google)", function() use ($baseUrl, &$buyerSessionCookies) {
    $res = httpReq($baseUrl . '/auth/google', 'POST', [
        'email' => 'qa.tester.itempedia@gmail.com',
        'name' => 'QA Tester Pro',
        'google_id' => 'goog_qa_test_999',
        'avatar' => 'https://ui-avatars.com/api/?name=QA+Tester&background=06b6d4&color=fff'
    ]);

    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    $json = json_decode($res['body'], true);
    if (empty($json['success']) || empty($json['user']['email'])) {
        throw new Exception("Invalid auth response: " . $res['body']);
    }
    $buyerSessionCookies = $res['cookies'];
    return "Google login creates buyer account & session successfully";
});

runTest("6. Buyer My Orders Page (GET /pesanan-saya)", function() use ($baseUrl, &$buyerSessionCookies) {
    $res = httpReq($baseUrl . '/pesanan-saya', 'GET', [], $buyerSessionCookies);
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200 for logged in buyer, got " . $res['code']);
    }
    if (!str_contains($res['body'], 'Pesanan Saya')) {
        throw new Exception("Missing 'Pesanan Saya' text in view");
    }
    return "Pesanan Saya page renders authenticated orders (HTTP 200)";
});

// ----------------------------------------------------
// TEST 6b: Shopping Cart Validation API & Multi-Item Checkout
// ----------------------------------------------------
runTest("6b. Shopping Cart API Check (POST /api/cart/check)", function() use ($baseUrl) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();
    $db->exec("UPDATE products SET stock = 10 WHERE is_active = 1 AND stock < 5");
    $prods = $db->query("SELECT id, name, price, stock FROM products WHERE is_active = 1 LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
    if (count($prods) < 2) {
        throw new Exception("Need at least 2 products for cart test");
    }

    $cartData = [
        ['id' => $prods[0]['id'], 'qty' => 2],
        ['id' => $prods[1]['id'], 'qty' => 1]
    ];

    $res = httpReq($baseUrl . '/api/cart/check', 'POST', [
        'items' => json_encode($cartData)
    ]);

    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    $json = json_decode($res['body'], true);
    if (empty($json['success']) || count($json['items']) !== 2) {
        throw new Exception("Invalid cart check response: " . $res['body']);
    }
    $expectedSubtotal = ($prods[0]['price'] * 2) + ($prods[1]['price'] * 1);
    if ($json['subtotal'] !== $expectedSubtotal) {
        throw new Exception("Subtotal mismatch. Expected {$expectedSubtotal}, got {$json['subtotal']}");
    }
    return "Cart check API successfully validates 2 items with subtotal Rp " . number_format($json['subtotal'], 0, ',', '.');
});

runTest("6c. Multi-Item Cart Checkout (POST /order/create with cart_items)", function() use ($baseUrl, $buyerSessionCookies) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();
    $prods = $db->query("SELECT id, name, price, stock FROM products WHERE is_active = 1 LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);

    $cartData = [
        ['id' => $prods[0]['id'], 'qty' => 2],
        ['id' => $prods[1]['id'], 'qty' => 1]
    ];

    $res = httpReq($baseUrl . '/order/create', 'POST', [
        'is_ajax' => '1',
        'roblox_username' => 'QACartBuyer',
        'whatsapp' => '081298765432',
        'note' => 'Pesanan keranjang belanja',
        'cart_items' => json_encode($cartData)
    ], $buyerSessionCookies);

    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200 JSON, got " . $res['code'] . " - " . $res['body']);
    }
    $json = json_decode($res['body'], true);
    if (empty($json['success']) || empty($json['invoice'])) {
        throw new Exception("Cart checkout failed: " . $res['body']);
    }

    $cartInv = $json['invoice'];
    // Verify in database that items_json is populated
    $order = $db->query("SELECT * FROM orders WHERE invoice_number = '{$cartInv}'")->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        throw new Exception("Order not found in database for {$cartInv}");
    }
    if (empty($order['items_json'])) {
        throw new Exception("items_json is empty for cart order {$cartInv}");
    }
    $savedItems = json_decode($order['items_json'], true);
    if (count($savedItems) !== 2) {
        throw new Exception("Expected 2 saved items in items_json, got " . count($savedItems));
    }

    return "Cart checkout successfully created multi-item order: {$cartInv}";
});

// ----------------------------------------------------
// TEST 7: Order Creation, Checkout & Invoice Generation
// ----------------------------------------------------
$createdInvoice = null;
runTest("7. Order Checkout Flow (POST /order/create)", function() use ($baseUrl, &$createdInvoice, $buyerSessionCookies) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();
    $prod = $db->query("SELECT id, name, stock FROM products WHERE is_active = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if (!$prod) {
        throw new Exception("No product available");
    }
    if ((int)$prod['stock'] < 10) {
        $db->prepare("UPDATE products SET stock = 50 WHERE id = ?")->execute([$prod['id']]);
        $prod['stock'] = 50;
    }

    $initialStock = (int)$prod['stock'];

    $res = httpReq($baseUrl . '/order/create', 'POST', [
        'product_id' => $prod['id'],
        'roblox_username' => 'QATestPlayer',
        'roblox_avatar' => 'https://ui-avatars.com/api/?name=QATestPlayer&background=06b6d4&color=fff',
        'whatsapp' => '081234567890',
        'quantity' => 1,
        'note' => 'Tolong fast response ya min!',
        'redeem_code' => 'TESTDISC20'
    ], $buyerSessionCookies);

    if (!isRedirect($res['code']) || empty($res['location'])) {
        throw new Exception("Expected redirect to invoice, got HTTP {$res['code']}, loc: " . ($res['location'] ?? 'none'));
    }

    if (str_contains($res['location'], 'error=')) {
        throw new Exception("Order creation failed with error: " . $res['location']);
    }

    preg_match('/\/order\/([^\s\?\/]+)/i', $res['location'], $mInv);
    if (empty($mInv[1])) {
        throw new Exception("Invalid redirect location: " . $res['location']);
    }
    $createdInvoice = trim($mInv[1]);

    // Check stock decrement
    $newStock = (int)$db->query("SELECT stock FROM products WHERE id = {$prod['id']}")->fetchColumn();
    if ($newStock !== ($initialStock - 1)) {
        throw new Exception("Stock not decremented properly (Before: {$initialStock}, After: {$newStock})");
    }

    return "Order created: {$createdInvoice}, stock reduced by 1";
});

// ----------------------------------------------------
// TEST 8: Order Detail / QRIS Invoice Page & Link Rendering
// ----------------------------------------------------
runTest("8. Order Detail Page (GET /order/{invoice})", function() use ($baseUrl, &$createdInvoice) {
    $cleanInv = trim($createdInvoice);
    $res = httpReq($baseUrl . '/order/' . $cleanInv);
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code'] . " for invoice '{$cleanInv}'");
    }
    if (!str_contains($res['body'], $cleanInv)) {
        throw new Exception("Invoice number missing from page");
    }
    if (!str_contains($res['body'], 'QRIS') && !str_contains($res['body'], 'Pembayaran')) {
        throw new Exception("QRIS / Payment section missing");
    }
    return "Order detail page rendered properly with QRIS and invoice status (HTTP 200)";
});

// ----------------------------------------------------
// ----------------------------------------------------
// TEST 9: QRIS Payment Simulation
// ----------------------------------------------------
runTest("9. Payment Simulation (GET /order/{invoice}/simulate)", function() use ($baseUrl, &$createdInvoice) {
    $res = httpReq($baseUrl . "/order/{$createdInvoice}/simulate");
    if (!isRedirect($res['code'])) {
        throw new Exception("Expected redirect after payment, got " . $res['code']);
    }

    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();
    $status = $db->query("SELECT status FROM orders WHERE invoice_number = '{$createdInvoice}'")->fetchColumn();
    if ($status !== 'PAID') {
        throw new Exception("Expected order status 'PAID', got '{$status}'");
    }

    return "Payment simulated successfully (Status: PAID)";
});

// ----------------------------------------------------
// TEST 10: Live Chat API & Roblox Auto-Links on Paid Order
// ----------------------------------------------------
runTest("10. Live Chat Sending & Roblox Auto-Link (POST /api/chat/{invoice}/send)", function() use ($baseUrl, &$createdInvoice) {
    // 1. Send Roblox Private Server link from Buyer
    $resBuyer = httpReq($baseUrl . "/api/chat/{$createdInvoice}/send", 'POST', [
        'message' => 'Ini link private server saya: https://www.roblox.com/games/share?code=qa99testcode&type=Server',
        'sender' => 'buyer',
        'sender_name' => 'QATestPlayer'
    ]);
    if ($resBuyer['code'] !== 200) {
        throw new Exception("Buyer chat send failed: HTTP " . $resBuyer['code']);
    }

    // 2. Send Seller reply
    $resSeller = httpReq($baseUrl . "/api/chat/{$createdInvoice}/send", 'POST', [
        'message' => 'Siap kak, langsung join ke server ya!',
        'sender' => 'seller',
        'sender_name' => 'Seller ItemPedia'
    ]);
    if ($resSeller['code'] !== 200) {
        throw new Exception("Seller chat send failed: HTTP " . $resSeller['code']);
    }

    // 3. Fetch messages via API
    $resGet = httpReq($baseUrl . "/api/chat/{$createdInvoice}?role=seller");
    $json = json_decode($resGet['body'], true);
    if (empty($json['messages']) || count($json['messages']) < 2) {
        throw new Exception("Messages count mismatch: " . $resGet['body']);
    }

    // 4. Verify Invoice Page HTML has auto-link & 1-click Roblox button
    $resPage = httpReq($baseUrl . "/order/{$createdInvoice}");
    if (!str_contains($resPage['body'], 'Buka / Join Server Roblox')) {
        throw new Exception("1-Click Roblox Join Server button missing in order chat HTML");
    }

    return "Live Chat auto-detects URLs & generates 1-Click Roblox Join Server button correctly";
});

// ----------------------------------------------------
// TEST 11: Submit Review for Paid Order
// ----------------------------------------------------
runTest("11. Submit Review for Paid Order (POST /order/{invoice}/review)", function() use ($baseUrl, &$createdInvoice) {
    $res = httpReq($baseUrl . "/order/{$createdInvoice}/review", 'POST', [
        'rating' => 5,
        'comment' => 'Mantap sekali, seller ramah dan proses instan!'
    ]);
    if (!isRedirect($res['code'])) {
        throw new Exception("Expected redirect after review, got " . $res['code']);
    }

    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();
    $revCount = (int)$db->query("SELECT COUNT(*) FROM reviews WHERE comment LIKE '%Mantap sekali%'")->fetchColumn();
    if ($revCount === 0) {
        throw new Exception("Review not inserted into database");
    }

    return "Review submitted & verified in database (5 Stars)";
});

// ----------------------------------------------------
// TEST 12: Admin Authentication & Session
// ----------------------------------------------------
$adminCookies = [];
runTest("12. Admin Login Flow (POST /admin/login)", function() use ($baseUrl, &$adminCookies) {
    $res = httpReq($baseUrl . '/admin/login', 'POST', [
        'username' => 'admin',
        'password' => 'admin123'
    ]);

    if (!isRedirect($res['code']) || empty($res['location'])) {
        throw new Exception("Admin login failed. HTTP {$res['code']}, loc: " . ($res['location'] ?? 'none'));
    }
    $adminCookies = $res['cookies'];
    return "Admin login successful with credentials admin:admin123";
});

// ----------------------------------------------------
// TEST 13: Admin Dashboard & Cleaned View
// ----------------------------------------------------
runTest("13. Admin Dashboard View (GET /admin)", function() use ($baseUrl, &$adminCookies) {
    $res = httpReq($baseUrl . '/admin', 'GET', [], $adminCookies);
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    if (str_contains($res['body'], 'Informasi Fitur Penjual')) {
        throw new Exception("'Informasi Fitur Penjual' still visible (should be removed)");
    }
    if (str_contains($res['body'], 'Pengumuman')) {
        throw new Exception("'Pengumuman' still visible (should be removed)");
    }
    if (str_contains($res['body'], 'Edukasi Penjual')) {
        throw new Exception("'Edukasi Penjual' still visible (should be removed)");
    }
    if (str_contains($res['body'], 'Keuangan Toko')) {
        throw new Exception("'Keuangan Toko' still visible (should be removed)");
    }
    if (!str_contains($res['body'], 'Aktifitas Penting') || !str_contains($res['body'], 'Performa Toko')) {
        throw new Exception("Core dashboard cards missing");
    }
    return "Admin dashboard renders clean full-width layout without removed cards";
});

// ----------------------------------------------------
// TEST 14: Admin Orders Management & Status Update
// ----------------------------------------------------
runTest("14. Admin Orders List & Status Update (GET & POST /admin/orders)", function() use ($baseUrl, &$adminCookies, &$createdInvoice) {
    // 1. Fetch Orders List
    $resList = httpReq($baseUrl . '/admin/orders?status=ALL', 'GET', [], $adminCookies);
    if ($resList['code'] !== 200) {
        throw new Exception("Admin orders list returned HTTP " . $resList['code']);
    }

    // 2. Update Order Status to SUCCESS
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();
    if (!empty($createdInvoice)) {
        $order = $db->query("SELECT id FROM orders WHERE invoice_number = '{$createdInvoice}'")->fetch(PDO::FETCH_ASSOC);
    } else {
        $order = $db->query("SELECT id FROM orders ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }
    if (!$order) {
        throw new Exception("No order found to update status");
    }

    $resUpdate = httpReq($baseUrl . '/admin/orders/update', 'POST', [
        'order_id' => $order['id'],
        'status' => 'SUCCESS',
        'account_data' => 'Roblox Trade Complete Verified QA'
    ], $adminCookies);

    if (!isRedirect($resUpdate['code'])) {
        throw new Exception("Order update failed: HTTP " . $resUpdate['code']);
    }

    $newStatus = $db->query("SELECT status FROM orders WHERE id = {$order['id']}")->fetchColumn();
    if ($newStatus !== 'SUCCESS') {
        throw new Exception("Status not updated to SUCCESS in DB (got {$newStatus})");
    }

    return "Admin order management & status update to SUCCESS verified";
});

// ----------------------------------------------------
// TEST 15: Admin Products CRUD & Quick Stock
// ----------------------------------------------------
$createdProductId = null;
runTest("15. Admin Products CRUD (Add, Update, Quick-Stock, Delete)", function() use ($baseUrl, &$adminCookies, &$createdProductId) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();

    // 1. Add Product
    $prodName = 'QA Test Pet Super Rare ' . rand(100, 999);
    $resAdd = httpReq($baseUrl . '/admin/products/add', 'POST', [
        'name' => $prodName,
        'game' => 'Build A Zoo',
        'category_id' => 1,
        'sub_category' => 'Pet',
        'price' => '150000',
        'price_original' => '250000',
        'description' => 'Pet langka hasil automated testing',
        'stock' => 10,
        'badge' => 'Instant'
    ], $adminCookies);

    if (!isRedirect($resAdd['code'])) {
        throw new Exception("Add product failed: HTTP " . $resAdd['code']);
    }

    $prod = $db->query("SELECT * FROM products WHERE name = '{$prodName}'")->fetch(PDO::FETCH_ASSOC);
    if (!$prod) {
        throw new Exception("Added product not found in DB");
    }
    $createdProductId = (int)$prod['id'];

    // 2. Quick Stock Update
    $resStock = httpReq($baseUrl . '/admin/products/quick-stock', 'POST', [
        'product_id' => $createdProductId,
        'stock' => 25
    ], $adminCookies);
    if (!isRedirect($resStock['code'])) {
        throw new Exception("Quick stock update failed: HTTP " . $resStock['code']);
    }
    $stockCheck = (int)$db->query("SELECT stock FROM products WHERE id = {$createdProductId}")->fetchColumn();
    if ($stockCheck !== 25) {
        throw new Exception("Stock not updated to 25 (got {$stockCheck})");
    }

    // 3. Edit Product
    $resEdit = httpReq($baseUrl . '/admin/products/update', 'POST', [
        'id' => $createdProductId,
        'name' => $prodName . ' (UPDATED)',
        'game' => 'Build A Zoo',
        'category_id' => 1,
        'sub_category' => 'Pet',
        'price' => '175000',
        'price_original' => '300000',
        'description' => 'Updated deskripsi QA',
        'stock' => 20,
        'badge' => 'Hot'
    ], $adminCookies);
    if (!isRedirect($resEdit['code'])) {
        throw new Exception("Edit product failed: HTTP " . $resEdit['code']);
    }

    // 4. Delete Product
    $resDel = httpReq($baseUrl . '/admin/products/delete', 'POST', [
        'id' => $createdProductId
    ], $adminCookies);
    if (!isRedirect($resDel['code'])) {
        throw new Exception("Delete product failed: HTTP " . $resDel['code']);
    }
    $delCheck = $db->query("SELECT COUNT(*) FROM products WHERE id = {$createdProductId}")->fetchColumn();
    if ((int)$delCheck !== 0) {
        throw new Exception("Product was not deleted from DB");
    }

    return "Product Create, Quick-Stock, Update, and Delete CRUD all passed";
});

// ----------------------------------------------------
// TEST 16: Admin Categories CMS CRUD
// ----------------------------------------------------
runTest("16. Admin Category & Games CMS (Add, Update, Delete)", function() use ($baseUrl, &$adminCookies) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();

    $catName = 'QA Test Game ' . rand(100, 999);
    $catSlug = 'qa-test-game-' . rand(100, 999);

    // 1. Add Category
    $resAdd = httpReq($baseUrl . '/admin/categories/add', 'POST', [
        'name' => $catName,
        'slug' => $catSlug,
        'icon' => 'fa-solid fa-gamepad',
        'description' => 'Category created via QA test',
        'sort_order' => 99,
        'is_active' => '1'
    ], $adminCookies);
    if (!isRedirect($resAdd['code'])) {
        throw new Exception("Add category failed: HTTP " . $resAdd['code']);
    }

    $game = $db->query("SELECT * FROM games WHERE slug = '{$catSlug}'")->fetch(PDO::FETCH_ASSOC);
    if (!$game) {
        throw new Exception("Added game category not found in DB");
    }

    // 2. Update Category
    $resUpd = httpReq($baseUrl . '/admin/categories/update', 'POST', [
        'id' => $game['id'],
        'name' => $catName . ' (Edited)',
        'slug' => $catSlug,
        'icon' => 'fa-solid fa-trophy',
        'description' => 'Updated QA desc',
        'sort_order' => 10,
        'is_active' => '1'
    ], $adminCookies);
    if (!isRedirect($resUpd['code'])) {
        throw new Exception("Update category failed: HTTP " . $resUpd['code']);
    }

    // 3. Delete Category
    $resDel = httpReq($baseUrl . '/admin/categories/delete', 'POST', [
        'id' => $game['id']
    ], $adminCookies);
    if (!isRedirect($resDel['code'])) {
        throw new Exception("Delete category failed: HTTP " . $resDel['code']);
    }

    return "Category / Game CMS Create, Update, Delete CRUD all passed";
});

// ----------------------------------------------------
// TEST 17: Admin Pages CMS & FAQs CRUD
// ----------------------------------------------------
runTest("17. Admin Pages CMS & FAQ Management (Add, Update, Delete)", function() use ($baseUrl, &$adminCookies) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();

    // 1. Save Page Content
    $resPage = httpReq($baseUrl . '/admin/pages/update', 'POST', [
        'hero_title' => 'Toko Item Roblox Terpercaya',
        'hero_subtitle' => 'Beli item, pet, dan akun Roblox termurah se-Indonesia'
    ], $adminCookies);
    if (!isRedirect($resPage['code'])) {
        throw new Exception("Save page content failed: HTTP " . $resPage['code']);
    }

    // 2. Add FAQ
    $faqQ = 'Apakah transaksi di ItemPedia bergaransi? ' . rand(100, 999);
    $resFaqAdd = httpReq($baseUrl . '/admin/faqs/add', 'POST', [
        'question' => $faqQ,
        'answer' => 'Ya, 100% bergaransi aman dan cepat.',
        'category' => 'Garansi',
        'sort_order' => 1
    ], $adminCookies);
    if (!isRedirect($resFaqAdd['code'])) {
        throw new Exception("Add FAQ failed: HTTP " . $resFaqAdd['code']);
    }

    $faq = $db->query("SELECT * FROM faqs WHERE question = '{$faqQ}'")->fetch(PDO::FETCH_ASSOC);
    if (!$faq) {
        throw new Exception("Added FAQ not found in DB");
    }

    // 3. Update FAQ
    $resFaqUpd = httpReq($baseUrl . '/admin/faqs/update', 'POST', [
        'id' => $faq['id'],
        'question' => $faqQ . ' (Updated)',
        'answer' => 'Jawaban telah diupdate.',
        'category' => 'Garansi',
        'sort_order' => 2
    ], $adminCookies);
    if (!isRedirect($resFaqUpd['code'])) {
        throw new Exception("Update FAQ failed: HTTP " . $resFaqUpd['code']);
    }

    // 4. Delete FAQ
    $resFaqDel = httpReq($baseUrl . '/admin/faqs/delete', 'POST', [
        'id' => $faq['id']
    ], $adminCookies);
    if (!isRedirect($resFaqDel['code'])) {
        throw new Exception("Delete FAQ failed: HTTP " . $resFaqDel['code']);
    }

    return "Pages CMS & FAQ Management (Add, Update, Delete) all passed";
});

// ----------------------------------------------------
// TEST 18: Admin Redeem Codes CRUD & Toggle
// ----------------------------------------------------
runTest("18. Admin Redeem Codes CRUD & Toggle", function() use ($baseUrl, &$adminCookies) {
    require_once __DIR__ . '/../app/config/database.php';
    $db = \App\Config\Database::getConnection();

    $codeName = 'QACODE' . rand(1000, 9999);

    // 1. Add Redeem Code
    $resAdd = httpReq($baseUrl . '/admin/redeem-codes/add', 'POST', [
        'code' => $codeName,
        'discount_percent' => 15,
        'product_id' => 0,
        'max_uses' => 50,
        'is_active' => '1'
    ], $adminCookies);
    if (!isRedirect($resAdd['code'])) {
        throw new Exception("Add redeem code failed: HTTP " . $resAdd['code']);
    }

    $code = $db->query("SELECT * FROM redeem_codes WHERE code = '{$codeName}'")->fetch(PDO::FETCH_ASSOC);
    if (!$code) {
        throw new Exception("Added redeem code not found in DB");
    }

    // 2. Toggle Active State
    $resToggle = httpReq($baseUrl . '/admin/redeem-codes/toggle', 'POST', [
        'id' => $code['id']
    ], $adminCookies);
    if (!isRedirect($resToggle['code'])) {
        throw new Exception("Toggle redeem code failed: HTTP " . $resToggle['code']);
    }
    $stateCheck = (int)$db->query("SELECT is_active FROM redeem_codes WHERE id = {$code['id']}")->fetchColumn();
    if ($stateCheck !== 0) {
        throw new Exception("Expected state 0 after toggle, got {$stateCheck}");
    }

    // 3. Edit Redeem Code
    $resEdit = httpReq($baseUrl . '/admin/redeem-codes/update', 'POST', [
        'id' => $code['id'],
        'code' => $codeName,
        'discount_percent' => 25,
        'product_id' => 0,
        'max_uses' => 100,
        'is_active' => '1'
    ], $adminCookies);
    if (!isRedirect($resEdit['code'])) {
        throw new Exception("Edit redeem code failed: HTTP " . $resEdit['code']);
    }

    // 4. Delete Redeem Code
    $resDel = httpReq($baseUrl . '/admin/redeem-codes/delete', 'POST', [
        'id' => $code['id']
    ], $adminCookies);
    if (!isRedirect($resDel['code'])) {
        throw new Exception("Delete redeem code failed: HTTP " . $resDel['code']);
    }

    return "Redeem Codes CRUD & Toggle active state passed";
});

// ----------------------------------------------------
// TEST 19: Admin Chat Inbox API
// ----------------------------------------------------
runTest("19. Admin Chat Inbox API (GET /api/chat/inbox)", function() use ($baseUrl, &$adminCookies) {
    $res = httpReq($baseUrl . '/api/chat/inbox', 'GET', [], $adminCookies);
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    $json = json_decode($res['body'], true);
    if (empty($json['success']) || !is_array($json['conversations'])) {
        throw new Exception("Invalid chat inbox JSON response: " . $res['body']);
    }
    return "Admin chat inbox API returns " . count($json['conversations']) . " active conversation threads";
});

// ----------------------------------------------------
// TEST 20: Admin Reviews View
// ----------------------------------------------------
runTest("20. Admin Reviews Moderation View (GET /admin/reviews)", function() use ($baseUrl, &$adminCookies) {
    $res = httpReq($baseUrl . '/admin/reviews', 'GET', [], $adminCookies);
    if ($res['code'] !== 200) {
        throw new Exception("Expected HTTP 200, got " . $res['code']);
    }
    if (!str_contains($res['body'], 'Ulasan Pembeli')) {
        throw new Exception("Missing 'Ulasan Pembeli' heading in view");
    }
    return "Admin reviews moderation page renders properly (HTTP 200)";
});

echo "\n======================================================\n";
echo "SUMMARY RESULTS:\n";
$passCount = count(array_filter($results, fn($r) => $r['status'] === 'PASS'));
$failCount = count(array_filter($results, fn($r) => $r['status'] === 'FAIL'));
echo "TOTAL TESTS: " . count($results) . " | PASSED: {$passCount} | FAILED: {$failCount}\n";
echo "======================================================\n";
