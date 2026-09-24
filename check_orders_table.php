<?php
require 'app/config/database.php';
$db = App\Config\Database::getConnection();
$cols = $db->query("SHOW COLUMNS FROM orders")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['Field'] . " | " . $c['Type'] . "\n";
}
