<?php

$cookieFile = __DIR__ . '/cookie.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

function makeReq($url, $postData = null) {
    global $cookieFile;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($postData !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return ['code' => $code, 'html' => $res, 'url' => $effectiveUrl];
}

echo "1. Attempting Login...\n";
$loginRes = makeReq('http://127.0.0.1:8000/admin/login', [
    'username' => 'admin',
    'password' => 'admin123'
]);

echo "Login Response URL: {$loginRes['url']} (HTTP {$loginRes['code']})\n";

$routes = [
    '/admin' => 'Riwayat Pesanan',
    '/admin?status=NEED_PROCESS' => 'Perlu Diproses',
    '/admin/products' => 'Daganganku',
    '/admin/categories' => 'Kategori & Game Roblox',
    '/admin/pages' => 'Edit Laman & Konten Toko',
    '/admin/redeem-codes' => 'Kode Promo & Voucher',
];

foreach ($routes as $route => $expectedText) {
    $res = makeReq("http://127.0.0.1:8000{$route}");
    if ($res['code'] === 200 && strpos($res['html'], $expectedText) !== false) {
        echo "[PASS] Route {$route} (HTTP 200, found '{$expectedText}')\n";
    } else {
        echo "[FAIL] Route {$route} (HTTP {$res['code']}, url: {$res['url']})\n";
        echo "HTML Snippet: " . substr(strip_tags($res['html']), 0, 150) . "\n";
    }
}

if (file_exists($cookieFile)) unlink($cookieFile);
echo "\nTesting /admin/login without session:\n";
$loginPage = makeReq('http://127.0.0.1:8000/admin/login');
if ($loginPage['code'] === 200 && strpos($loginPage['html'], 'Seller Center') !== false) {
    echo "[PASS] Route /admin/login (HTTP 200, found 'Seller Center')\n";
} else {
    echo "[FAIL] Route /admin/login (HTTP {$loginPage['code']})\n";
}
