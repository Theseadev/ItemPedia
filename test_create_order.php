<?php
require 'app/config/database.php';
$db = App\Config\Database::getConnection();

try {
    // Check tables
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(", ", $tables) . "\n";
    
    // Test simulate what createOrder does:
    $productId = 18;
    $robloxUsername = "test_player";
    $robloxAvatar = "https://ui-avatars.com/api/?name=test";
    $whatsapp = "-";
    $note = "test note";
    $buyerEmail = null;
    $appliedCode = null;
    $discountAmount = 0;
    $invoiceNumber = 'ITP-TEST-' . rand(1000, 9999);
    $totalPrice = 65000;
    $productDisplayName = "Test Product";
    $categoryName = "Pet";
    
    echo "Attempting INSERT into orders...\n";
    $insertStmt = $db->prepare("INSERT INTO orders 
        (invoice_number, product_id, product_name, category_name, price, roblox_username, roblox_avatar_url, whatsapp, note, status, payment_method, buyer_email, redeem_code, discount_amount) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', 'QRIS', ?, ?, ?)");

    $insertStmt->execute([
        $invoiceNumber,
        $productId,
        $productDisplayName,
        $categoryName,
        $totalPrice,
        $robloxUsername,
        $robloxAvatar,
        $whatsapp,
        $note,
        $buyerEmail,
        $appliedCode,
        $discountAmount
    ]);
    echo "Orders INSERT OK! ID: " . $db->lastInsertId() . "\n";

    echo "Attempting order_messages INSERT...\n";
    $msgStmt = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, 'buyer', ?, ?, 0, CURRENT_TIMESTAMP)");
    $msgStmt->execute([$invoiceNumber, $robloxUsername, $note]);
    echo "order_messages 1 OK!\n";

    $welcomeText = "Halo kak!";
    $botStmt = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, 'seller', 'Seller ItemPedia', ?, 0, CURRENT_TIMESTAMP)");
    $botStmt->execute([$invoiceNumber, $welcomeText]);
    echo "order_messages 2 OK!\n";

} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
