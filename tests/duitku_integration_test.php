<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/services/DuitkuService.php';

use App\Services\DuitkuService;
use App\Config\Database;

echo "=== DUITKU PAYMENT GATEWAY INTEGRATION TEST ===\n";

// 1. Get Config
$config = DuitkuService::getConfig();
echo "1. Duitku Config Check:\n";
echo "   - Enabled: " . ($config['enabled'] ? 'Yes' : 'No') . "\n";
echo "   - Environment: " . $config['env'] . "\n";
echo "   - Merchant Code: " . $config['merchant_code'] . "\n";
echo "   - Passport URL: " . $config['passport_url'] . "\n";
assert(!empty($config['merchant_code']), "Merchant code must be configured");

// 2. Channels List
$channels = DuitkuService::getPaymentChannels();
echo "2. Payment Channels Available: " . count($channels) . " channels\n";
foreach ($channels as $code => $channel) {
    echo "   - [$code] {$channel['name']} ({$channel['group']})\n";
}
assert(count($channels) >= 5, "At least 5 payment channels should be defined");

// 3. Create a test order in database first
$db = Database::getConnection();
$testInvoice = 'ITP-TEST-' . strtoupper(substr(md5(uniqid()), 0, 6));
$db->prepare("INSERT INTO orders (invoice_number, product_id, product_name, category_name, price, roblox_username, whatsapp, status, payment_method, created_at) VALUES (?, 1, ?, 'Build A Zoo', ?, ?, ?, 'PENDING', 'QRIS', CURRENT_TIMESTAMP)")
   ->execute([$testInvoice, 'Duitku Test Roblox Item', 50000, 'RobloxTester', '081234567890']);

$stmt = $db->prepare("SELECT * FROM orders WHERE invoice_number = ?");
$stmt->execute([$testInvoice]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// 4. Test createPayment
$payment = DuitkuService::createPayment($order, 'LQ');
echo "3. Payment Creation Result:\n";
echo "   - Success: " . ($payment['success'] ? 'Yes' : 'No') . "\n";
echo "   - Reference: " . $payment['reference'] . "\n";
echo "   - QR String: " . substr($payment['qr_string'] ?? '', 0, 30) . "...\n";
assert($payment['success'] === true, "Payment creation should be successful");
assert(!empty($payment['reference']), "Payment reference must exist");

// 5. Test Callback Handling (Webhook IPN)
$callbackPayload = [
    'merchantCode' => $config['merchant_code'],
    'amount' => '50000',
    'merchantOrderId' => $testInvoice,
    'signature' => md5($config['merchant_code'] . '50000' . $testInvoice . $config['api_key']),
    'resultCode' => '00',
    'reference' => $payment['reference']
];

$callbackResult = DuitkuService::handleCallback($callbackPayload);
echo "4. Webhook Callback Processing Result:\n";
echo "   - Success: " . ($callbackResult['success'] ? 'Yes' : 'No') . "\n";
echo "   - Message: " . $callbackResult['message'] . "\n";
echo "   - HTTP Code: " . $callbackResult['code'] . "\n";
assert($callbackResult['success'] === true, "Callback should succeed");
assert($callbackResult['code'] === 200, "Callback code should be 200");

// Check order status updated to PAID
$stmt = $db->prepare("SELECT status, payment_reference FROM orders WHERE invoice_number = ?");
$stmt->execute([$testInvoice]);
$updatedOrder = $stmt->fetch(PDO::FETCH_ASSOC);
echo "5. Database Order Verification after Webhook:\n";
echo "   - Status: " . $updatedOrder['status'] . "\n";
echo "   - Payment Reference: " . $updatedOrder['payment_reference'] . "\n";
assert($updatedOrder['status'] === 'PAID', "Order status must be updated to PAID");

// Cleanup test order
$db->prepare("DELETE FROM orders WHERE invoice_number = ?")->execute([$testInvoice]);
$db->prepare("DELETE FROM order_messages WHERE invoice_number = ?")->execute([$testInvoice]);

echo "\n======================================================\n";
echo "🎉 ALL DUITKU SERVICE & WEBHOOK TESTS PASSED 100%!\n";
echo "======================================================\n";
