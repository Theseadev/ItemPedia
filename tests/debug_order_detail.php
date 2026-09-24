<?php
require_once __DIR__ . '/../app/config/database.php';

$db = \App\Config\Database::getConnection();
$order = $db->query("SELECT invoice_number FROM orders ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

$ch = curl_init('http://127.0.0.1:8000/order/' . $order['invoice_number']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Fetched Invoice: " . $order['invoice_number'] . "\n";
echo "HTTP Code: " . $code . "\n";
echo "HTML Length: " . strlen($html) . "\n";
echo "Contains 'Seller ItemPedia': " . (str_contains($html, 'Seller ItemPedia') ? "YES" : "NO") . "\n";
if (!str_contains($html, 'Seller ItemPedia')) {
    echo "First 500 chars of HTML:\n" . substr($html, 0, 500) . "\n";
}
