<?php
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Models/ChatMessage.php';
require_once __DIR__ . '/../app/Models/Order.php';

use App\Config\Database;
use App\Models\ChatMessage;

$db = Database::getConnection();
$stmt = $db->query("SELECT * FROM orders WHERE invoice_number LIKE '%JJI%' LIMIT 1");
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if ($order) {
    echo "Found Order: " . $order['invoice_number'] . "\n";
    $msg = ChatMessage::create([
        'order_id' => $order['id'],
        'invoice_number' => $order['invoice_number'],
        'sender' => 'buyer',
        'sender_name' => $order['roblox_username'] ?? 'Buyer',
        'message' => 'Kak tolong join server saya ya: https://www.roblox.com/games/share?code=usqjisz7&type=Server',
        'is_read' => 0
    ]);
    echo "Inserted Message ID: " . $msg['id'] . "\n";
    echo "Message Text: " . $msg['message'] . "\n";
} else {
    echo "No order found\n";
}
