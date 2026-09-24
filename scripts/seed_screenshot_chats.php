<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$db = \App\Config\Database::getConnection();

$samples = [
    [
        'invoice' => 'ITP-20260923-JJI01',
        'user' => 'Jajie',
        'product' => 'STELLAR AURELIA EGG | BUILD A ZOO',
        'price' => 200000,
        'status' => 'SUCCESS',
        'avatar' => 'https://ui-avatars.com/api/?name=Jajie&background=06b6d4&color=fff&bold=true',
        'messages' => [
            ['seller', 'Seller ItemPedia', 'https://www.roblox.com/games/share?code=3fb049a07853207a5ac8b8&type=Server', '08:43', 1],
            ['buyer', 'Jajie', 'Ok skip lg masuk', '08:44', 1],
            ['seller', 'Seller ItemPedia', ':v', '08:45', 1],
            ['seller', 'Seller ItemPedia', 'ntar mimin ganti akun 200 dulu', '08:46', 1],
            ['buyer', 'Jajie', 'Makasih bro', '09:13', 1]
        ]
    ],
    [
        'invoice' => 'ITP-20260923-FRO02',
        'user' => 'Fero',
        'product' => 'Mucy ($2.753M/s) (Prismatic) (Stellar)',
        'price' => 65000,
        'status' => 'PAID',
        'avatar' => 'https://ui-avatars.com/api/?name=Fero&background=06b6d4&color=fff&bold=true',
        'messages' => [
            ['buyer', 'Fero', 'Halo min, orderan saya sudah masuk belum?', '14:50', 1],
            ['seller', 'Seller ItemPedia', 'Sudah kak, sedang disiapkan ya!', '14:55', 1],
            ['buyer', 'Fero', 'Nanti jam 3:30 ya min', '15:03', 0],
            ['buyer', 'Fero', 'Soalnya lagi di jalan nih', '15:03', 0],
            ['buyer', 'Fero', 'Bisa dihold dulu kan ya?', '15:04', 0],
            ['buyer', 'Fero', 'Nanti kalau standby aku kabarin', '15:05', 0]
        ]
    ],
    [
        'invoice' => 'ITP-20260923-ZHL03',
        'user' => 'Zhong Li',
        'product' => 'Chomp ($1.617M/s) (Prismatic) (Stellar)',
        'price' => 50000,
        'status' => 'PAID',
        'avatar' => 'https://ui-avatars.com/api/?name=Zhong+Li&background=06b6d4&color=fff&bold=true',
        'messages' => [
            ['buyer', 'Zhong Li', 'are u online?', '09:06', 0]
        ]
    ],
    [
        'invoice' => 'ITP-20260922-MHR04',
        'user' => 'Maharani Aliya',
        'product' => 'Akun Sultan Build A Zoo (Max Habitat)',
        'price' => 130000,
        'status' => 'SUCCESS',
        'avatar' => 'https://ui-avatars.com/api/?name=Maharani+Aliya&background=06b6d4&color=fff&bold=true',
        'messages' => [
            ['seller', 'Seller ItemPedia', 'Data akun sudah dikirimkan di invoice ya kak!', '22 Sep 10:00', 1],
            ['buyer', 'Maharani Aliya', 'join', '22 Sep 10:15', 1]
        ]
    ],
    [
        'invoice' => 'ITP-20260922-MDW05',
        'user' => 'Made Wistara',
        'product' => 'Crystalline ($525.277/s) (Divine) (Jurassic)',
        'price' => 35000,
        'status' => 'SUCCESS',
        'avatar' => 'https://ui-avatars.com/api/?name=Made+Wistara&background=06b6d4&color=fff&bold=true',
        'messages' => [
            ['buyer', 'Made Wistara', 'https://www.roblox.com/share?code=abc', '22 Sep 08:30', 1]
        ]
    ]
];

foreach ($samples as $s) {
    $check = $db->prepare("SELECT COUNT(*) FROM orders WHERE invoice_number = ?");
    $check->execute([$s['invoice']]);
    if ((int)$check->fetchColumn() === 0) {
        $insOrder = $db->prepare("INSERT INTO orders (invoice_number, product_id, product_name, category_name, price, roblox_username, roblox_avatar_url, whatsapp, status, payment_method, created_at) VALUES (?, 1, ?, 'Build A Zoo', ?, ?, ?, '-', ?, 'QRIS', CURRENT_TIMESTAMP)");
        $insOrder->execute([$s['invoice'], $s['product'], $s['price'], $s['user'], $s['avatar'], $s['status']]);

        $insMsg = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        foreach ($s['messages'] as $m) {
            $insMsg->execute([$s['invoice'], $m[0], $m[1], $m[2], $m[4]]);
        }
    }
}

echo "Demo chats seeded successfully!\n";
