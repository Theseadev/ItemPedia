<?php
require_once __DIR__ . '/../app/config/database.php';

use App\Config\Database;

$db = Database::getConnection();

// Query messages that have links or server share codes
$stmt = $db->query("SELECT * FROM order_messages WHERE message LIKE '%roblox.com%' OR message LIKE '%share?code=%' LIMIT 5");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total link messages in DB: " . count($messages) . "\n";
foreach ($messages as $m) {
    echo "ID: " . $m['id'] . " | Sender: " . $m['sender'] . " | Msg: " . $m['message'] . "\n";
}
