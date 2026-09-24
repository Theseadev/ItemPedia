<?php

require dirname(__DIR__) . '/vendor/autoload.php';

session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';

echo "=== TEST CHAT INBOX & LIVE CHAT DOCK ===\n";

$db = \App\Config\Database::getConnection();

// 1. Test getChatInbox
ob_start();
\App\Controllers\OrderController::getChatInbox();
$inboxJson = ob_get_clean();
$inboxData = json_decode($inboxJson, true);

echo "[1] Get Chat Inbox API: ";
if ($inboxData && !empty($inboxData['conversations'])) {
    echo "OK (" . count($inboxData['conversations']) . " conversations found)\n";
    foreach ($inboxData['conversations'] as $c) {
        echo "   - [" . $c['invoice_number'] . "] " . $c['roblox_username'] . ": " . $c['last_message'] . " (" . $c['time_formatted'] . ", unread: " . $c['unread_count'] . ")\n";
    }
} else {
    echo "FAIL\n";
    print_r($inboxJson);
}

// 2. Test getChatMessages for first conversation
$firstInvoice = $inboxData['conversations'][0]['invoice_number'] ?? 'ITP-20260923-JJI01';
ob_start();
\App\Controllers\OrderController::getChatMessages($firstInvoice);
$msgJson = ob_get_clean();
$msgData = json_decode($msgJson, true);

echo "[2] Get Chat Messages for {$firstInvoice}: ";
if ($msgData && $msgData['success'] && isset($msgData['order'])) {
    echo "OK (" . count($msgData['messages']) . " messages found)\n";
    echo "   - Order Product: " . $msgData['order']['product_name'] . " (Rp " . number_format($msgData['order']['price'], 0, ',', '.') . ")\n";
    foreach ($msgData['messages'] as $m) {
        echo "     • " . $m['sender_name'] . " (" . $m['sender'] . "): " . $m['message'] . " [" . $m['time_formatted'] . "]\n";
    }
} else {
    echo "FAIL\n";
    print_r($msgJson);
}

echo "=== SEMUA TEST CHAT BERHASIL ===\n";
