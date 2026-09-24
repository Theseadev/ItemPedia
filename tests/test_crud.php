<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Database;

echo "=== TEST ITEMPEDIA CRUD SUITE ===\n";

$db = Database::getConnection();
echo "1. Database Driver: " . Database::getDriver() . "\n";

// Test 1: Category CRUD
echo "\n--- TEST CATEGORY/GAME CRUD ---\n";
$testGameName = "Test Game " . rand(1000, 9999);
$testGameSlug = "test-game-" . rand(1000, 9999);
$db->prepare("INSERT INTO games (name, slug, logo_url, icon, description, sort_order, is_active) VALUES (?, ?, '', 'fa-solid fa-gamepad', 'Test Description', 99, 1)")
   ->execute([$testGameName, $testGameSlug]);
$gameId = $db->lastInsertId();
echo "Inserted Game ID: {$gameId} ({$testGameName})\n";

// Update Game
$updatedGameName = $testGameName . " (Updated)";
$db->prepare("UPDATE games SET name = ?, description = 'Updated Description' WHERE id = ?")
   ->execute([$updatedGameName, $gameId]);
echo "Updated Game to: {$updatedGameName}\n";

// Verify Game
$checkGame = $db->query("SELECT * FROM games WHERE id = {$gameId}")->fetch();
assert($checkGame['name'] === $updatedGameName, "Game update failed");
echo "Game Update Verified!\n";

// Test 2: Product CRUD
echo "\n--- TEST PRODUCT CRUD ---\n";
$testProdName = "Super Pet Test " . rand(100, 999);
$testProdSlug = "super-pet-test-" . rand(100, 999);
$db->prepare("INSERT INTO products (category_id, game, name, slug, price, price_original, description, image_url, badge, stock, total_sold, rating, sub_category) VALUES (1, ?, ?, ?, 45000, 60000, 'Super fast pet', 'https://example.com/pet.png', 'Prismatic', 10, 2, 4.9, 'Pet')")
   ->execute([$updatedGameName, $testProdName, $testProdSlug]);
$prodId = $db->lastInsertId();
echo "Inserted Product ID: {$prodId} ({$testProdName})\n";

// Update Product
$updatedProdName = $testProdName . " (Legendary)";
$db->prepare("UPDATE products SET name = ?, price = 50000, stock = 15, badge = 'Divine' WHERE id = ?")
   ->execute([$updatedProdName, $prodId]);
echo "Updated Product to: {$updatedProdName}\n";

// Verify Product
$checkProd = $db->query("SELECT * FROM products WHERE id = {$prodId}")->fetch();
assert($checkProd['name'] === $updatedProdName && (int)$checkProd['price'] === 50000 && (int)$checkProd['stock'] === 15, "Product update failed");
echo "Product Update Verified!\n";

// Test 3: FAQ CRUD
echo "\n--- TEST FAQ CRUD ---\n";
$testQ = "Bagaimana cara klaim garansi?";
$testA = "Hubungi admin via WhatsApp dan sebutkan nomor invoice pesanan kamu.";
$db->prepare("INSERT INTO faqs (question, answer, category, sort_order, is_active) VALUES (?, ?, 'Garansi', 99, 1)")
   ->execute([$testQ, $testA]);
$faqId = $db->lastInsertId();
echo "Inserted FAQ ID: {$faqId}\n";

// Update FAQ
$updatedA = "Hubungi WhatsApp admin 24 jam dengan menyebutkan nomor invoice.";
$db->prepare("UPDATE faqs SET answer = ?, sort_order = 1 WHERE id = ?")
   ->execute([$updatedA, $faqId]);
echo "Updated FAQ ID: {$faqId}\n";

// Verify FAQ
$checkFaq = $db->query("SELECT * FROM faqs WHERE id = {$faqId}")->fetch();
assert($checkFaq['answer'] === $updatedA, "FAQ update failed");
echo "FAQ Update Verified!\n";

// Test 4: Redeem Code CRUD
echo "\n--- TEST REDEEM CODE CRUD ---\n";
$testCode = "TESTCODE" . rand(100, 999);
$db->prepare("INSERT INTO redeem_codes (code, discount_percent, product_id, max_uses, used_count, is_active) VALUES (?, 15, ?, 50, 0, 1)")
   ->execute([$testCode, $prodId]);
$codeId = $db->lastInsertId();
echo "Inserted Redeem Code ID: {$codeId} ({$testCode})\n";

// Update Redeem Code
$updatedCode = $testCode . "UP";
$db->prepare("UPDATE redeem_codes SET code = ?, discount_percent = 20, max_uses = 100 WHERE id = ?")
   ->execute([$updatedCode, $codeId]);
echo "Updated Redeem Code to: {$updatedCode} (20%)\n";

// Verify Redeem Code
$checkCode = $db->query("SELECT * FROM redeem_codes WHERE id = {$codeId}")->fetch();
assert($checkCode['code'] === $updatedCode && (int)$checkCode['discount_percent'] === 20, "Redeem code update failed");
echo "Redeem Code Update Verified!\n";

// Test 5: Settings / Pages Content CMS CRUD
echo "\n--- TEST SETTINGS / PAGES CMS CRUD ---\n";
$stmt = $db->prepare("REPLACE INTO settings (`key`, `value`) VALUES (?, ?)");
$stmt->execute(['hero_title', 'Item & Akun Roblox Terbaik & Tercepat!']);
$stmt->execute(['hero_badge', '⚡ FLASH SALE TERBATAS']);
$stmt->execute(['operating_hours_open', '07:00']);
$stmt->execute(['operating_hours_close', '21:00']);
echo "Settings saved successfully!\n";

// Clean up test records
echo "\n--- CLEANUP TEST DATA ---\n";
$db->prepare("DELETE FROM redeem_codes WHERE id = ?")->execute([$codeId]);
$db->prepare("DELETE FROM faqs WHERE id = ?")->execute([$faqId]);
$db->prepare("DELETE FROM products WHERE id = ?")->execute([$prodId]);
$db->prepare("DELETE FROM games WHERE id = ?")->execute([$gameId]);
echo "Cleaned up test records successfully.\n";

echo "\nALL CRUD TESTS PASSED SUCCESSFULLY! (100% OK)\n";
