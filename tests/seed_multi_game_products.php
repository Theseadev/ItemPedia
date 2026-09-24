<?php
require_once __DIR__ . '/../app/Config/Database.php';
$db = \App\Config\Database::getConnection();

// Seed produk untuk Chop Your Tree
$checkCyt = $db->query("SELECT COUNT(*) FROM products WHERE game = 'Chop Your Tree'")->fetchColumn();
if ((int)$checkCyt === 0) {
    $stmt = $db->prepare("INSERT INTO products (category_id, game, name, slug, price, price_original, description, image_url, badge, stock, sub_category, rating, total_sold) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        1,
        'Chop Your Tree',
        'Golden Axe Tier X ($500K/s) (Legendary)',
        'cyt-golden-axe-tier-x',
        45000,
        60000,
        'Kapak emas legendaris tier tertinggi dengan kecepatan potong pohon ultra-cepat.',
        'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=500&auto=format&fit=crop&q=60',
        'Legendary Item',
        5,
        'Item',
        5.0,
        18
    ]);

    $stmt->execute([
        1,
        'Chop Your Tree',
        'Woodpecker Pet 10x Boost (Mythic)',
        'cyt-woodpecker-pet-10x',
        30000,
        45000,
        'Pet burung pematuk kayu dengan pasif boost damage 10x lipat ke semua pohon.',
        'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500&auto=format&fit=crop&q=60',
        'Mythic Pet',
        8,
        'Pet',
        4.9,
        24
    ]);

    $stmt->execute([
        2,
        'Chop Your Tree',
        'Akun Chop Your Tree (Max Rebirth + All Axes)',
        'cyt-akun-max-rebirth',
        120000,
        175000,
        'Akun siap pakai dengan Max Rebirth level 50, inventori full kapak emas dan 10M Coins.',
        'https://images.unsplash.com/photo-1563089145-599997674d42?w=500&auto=format&fit=crop&q=60',
        'Sultan Akun',
        2,
        'Akun',
        5.0,
        7
    ]);
    echo "Seeded Chop Your Tree products.\n";
}

// Seed produk untuk Catch and Tame
$checkCnt = $db->query("SELECT COUNT(*) FROM products WHERE game = 'Catch and Tame'")->fetchColumn();
if ((int)$checkCnt === 0) {
    $stmt = $db->prepare("INSERT INTO products (category_id, game, name, slug, price, price_original, description, image_url, badge, stock, sub_category, rating, total_sold) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        1,
        'Catch and Tame',
        'Mythical Phoenix Tamer (Fly + 99% Catch)',
        'cnt-mythical-phoenix-tamer',
        60000,
        85000,
        'Monster burung api legendaris langka. Menjinakkan monster lain dengan sukses 99%.',
        'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=500&auto=format&fit=crop&q=60',
        'Mythic Beast',
        4,
        'Pet',
        5.0,
        15
    ]);

    $stmt->execute([
        1,
        'Catch and Tame',
        'Golden Magic Lasso (Infinite Durability)',
        'cnt-golden-magic-lasso',
        35000,
        50000,
        'Tali lasso emas sakti tahan banting tanpa batas durabilitas untuk taming monster tier tinggi.',
        'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=500&auto=format&fit=crop&q=60',
        'Best Seller',
        10,
        'Item',
        4.9,
        32
    ]);

    $stmt->execute([
        2,
        'Catch and Tame',
        'Akun Catch & Tame VIP (Full Legendaries)',
        'cnt-akun-vip-full-legendaries',
        150000,
        200000,
        'Akun VIP Sultan Catch and Tame level 80 dengan 15 monster mythical dan rank top leaderboard.',
        'https://images.unsplash.com/photo-1563089145-599997674d42?w=500&auto=format&fit=crop&q=60',
        'VIP Sultan',
        1,
        'Akun',
        5.0,
        9
    ]);
    echo "Seeded Catch and Tame products.\n";
}

echo "Done seeding.\n";
