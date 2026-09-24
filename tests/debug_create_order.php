<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';

use App\Config\Database;
use App\Controllers\OrderController;

$db = Database::getConnection();
$prod = $db->query("SELECT id, name, stock FROM products WHERE stock > 5 AND is_active = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);

echo "Testing product ID: " . $prod['id'] . " (Stock: " . $prod['stock'] . ")\n";

// Set request data
Flight::request()->data->product_id = $prod['id'];
Flight::request()->data->roblox_username = 'QATestPlayer';
Flight::request()->data->roblox_avatar = 'https://ui-avatars.com/api/?name=QATestPlayer';
Flight::request()->data->whatsapp = '081234567890';
Flight::request()->data->quantity = 1;
Flight::request()->data->note = 'Tolong fast response!';
Flight::request()->data->redeem_code = 'TESTDISC20';

try {
    OrderController::createOrder();
} catch (\Throwable $e) {
    echo "CAUGHT EXCEPTION: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
