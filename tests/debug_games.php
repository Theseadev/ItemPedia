<?php
require_once __DIR__ . '/../app/Config/Database.php';
$db = \App\Config\Database::getConnection();

echo "=== GAMES TABLE ===\n";
print_r($db->query("SELECT * FROM games")->fetchAll(PDO::FETCH_ASSOC));

echo "=== CATEGORIES TABLE ===\n";
print_r($db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC));

echo "=== PRODUCTS (game & category) ===\n";
print_r($db->query("SELECT id, name, game, category_id, sub_category FROM products")->fetchAll(PDO::FETCH_ASSOC));
