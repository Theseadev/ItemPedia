<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Database;

$db = Database::getConnection();
$db->prepare("DELETE FROM settings WHERE `key` IN ('hero_badge', 'announcement')")->execute();
echo "Settings cleaned up successfully.\n";
