<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;
    private static string $driver = 'sqlite';

    /**
     * Parse file .env sederhana
     */
    private static function loadEnv(): array
    {
        $envFile = dirname(__DIR__, 2) . '/.env';
        $config = [];

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                if (str_contains($line, '=')) {
                    [$key, $val] = explode('=', $line, 2);
                    $config[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
                }
            }
        }

        return $config;
    }

    public static function getConnection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $env = self::loadEnv();
        $preferDriver = strtolower($env['DB_CONNECTION'] ?? 'sqlite');

        // 1. Coba koneksi MySQL terlebih dahulu jika dikonfigurasi / Laragon aktif
        if ($preferDriver === 'mysql') {
            $host = $env['DB_HOST'] ?? '127.0.0.1';
            $port = (int)($env['DB_PORT'] ?? 3306);
            $database = $env['DB_DATABASE'] ?? 'itempedia';
            $username = $env['DB_USERNAME'] ?? 'root';
            $password = $env['DB_PASSWORD'] ?? '';

            // Cek port cepat (0.1 detik) agar tidak blocking/delay jika MySQL offline
            $socket = @fsockopen($host, $port, $errno, $errstr, 0.1);
            if ($socket) {
                fclose($socket);
                try {
                    // Pastikan server MySQL reachable & auto-create database jika belum ada
                    $initPdo = new PDO("mysql:host={$host};port={$port}", $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 1
                    ]);
                    $initPdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                    // Koneksi ke database target
                    self::$pdo = new PDO("mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4", $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);

                    self::$driver = 'mysql';
                    self::initMysqlSchema();
                    return self::$pdo;
                } catch (\Exception $e) {
                    error_log("ItemPedia MySQL fallback to SQLite: " . $e->getMessage());
                }
            }
        }

        // 2. Fallback ke SQLite jika MySQL tidak aktif atau driver diset sqlite
        $dbDir = dirname(__DIR__, 2) . '/database';
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0777, true);
        }

        $dbPath = $dbDir . '/itempedia.sqlite';
        try {
            self::$pdo = new PDO("sqlite:" . $dbPath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            self::$driver = 'sqlite';
            self::initSqliteSchema();
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }

        return self::$pdo;
    }

    public static function getDriver(): string
    {
        return self::$driver;
    }

    /**
     * Inisialisasi Skema MySQL (Laragon)
     */
    private static function initMysqlSchema(): void
    {
        $db = self::$pdo;

        // Cek apakah tabel utama sudah ada untuk menghindari DDL overhead pada setiap request
        $checkTable = $db->query("SHOW TABLES LIKE 'products'")->fetchColumn();
        if ($checkTable) {
            return;
        }

        $db->exec("CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            icon VARCHAR(100) DEFAULT 'package',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->exec("CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT NOT NULL,
            game VARCHAR(100) DEFAULT 'Blox Fruits',
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            price INT NOT NULL,
            price_original INT DEFAULT 0,
            description TEXT,
            image_url TEXT,
            badge VARCHAR(100) DEFAULT 'Terlaris',
            stock INT DEFAULT 10,
            is_active INT DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_category (category_id),
            INDEX idx_game (game)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->exec("CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            invoice_number VARCHAR(100) UNIQUE NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            category_name VARCHAR(255) NOT NULL,
            price INT NOT NULL,
            roblox_username VARCHAR(255) NOT NULL,
            roblox_avatar_url TEXT,
            whatsapp VARCHAR(50) NOT NULL,
            note TEXT,
            status VARCHAR(50) DEFAULT 'PENDING',
            payment_method VARCHAR(50) DEFAULT 'QRIS',
            account_data TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_product (product_id),
            INDEX idx_invoice (invoice_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->exec("CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->exec("CREATE TABLE IF NOT EXISTS settings (
            `key` VARCHAR(100) PRIMARY KEY,
            `value` TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Tabel Reviews (Ulasan Pembeli Real)
        $db->exec("CREATE TABLE IF NOT EXISTS reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            order_id INT NULL,
            roblox_username VARCHAR(255) NOT NULL,
            roblox_avatar_url TEXT NULL,
            rating INT DEFAULT 5,
            comment TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_product_review (product_id),
            INDEX idx_order_review (order_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Tabel Chat Pesanan (Live Chat Antara Pembeli & Penjual)
        $db->exec("CREATE TABLE IF NOT EXISTS order_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            invoice_number VARCHAR(100) NOT NULL,
            sender VARCHAR(20) NOT NULL,
            sender_name VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            is_read INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_order_msg (invoice_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Tabel Akun Pembeli (Login dengan Google)
        $db->exec("CREATE TABLE IF NOT EXISTS buyers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            google_id VARCHAR(255),
            email VARCHAR(255) UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            avatar_url TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_buyer_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Tabel Kode Redeem (Diskon Persen Khusus 1 Produk)
        $db->exec("CREATE TABLE IF NOT EXISTS redeem_codes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(100) UNIQUE NOT NULL,
            discount_percent INT NOT NULL,
            product_id INT NULL,
            max_uses INT DEFAULT 0,
            used_count INT DEFAULT 0,
            is_active INT DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_redeem_code (code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Tabel Games Roblox (CMS Kategori Game)
        $db->exec("CREATE TABLE IF NOT EXISTS games (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) UNIQUE NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            logo_url TEXT,
            icon VARCHAR(100) DEFAULT 'fa-solid fa-gamepad',
            description TEXT,
            is_active INT DEFAULT 1,
            sort_order INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_game_slug (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Tabel FAQs (CMS Tanya Jawab)
        $db->exec("CREATE TABLE IF NOT EXISTS faqs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question TEXT NOT NULL,
            answer TEXT NOT NULL,
            category VARCHAR(100) DEFAULT 'Umum',
            sort_order INT DEFAULT 0,
            is_active INT DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        try {
            $db->exec("ALTER TABLE orders ADD COLUMN buyer_email VARCHAR(255)");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE orders ADD COLUMN redeem_code VARCHAR(100)");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE orders ADD COLUMN discount_amount INT DEFAULT 0");
        } catch (\Exception $e) {}

        try {
            $db->exec("ALTER TABLE products ADD COLUMN rating DECIMAL(3,1) DEFAULT 4.9");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE products ADD COLUMN total_sold INT DEFAULT 0");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE products ADD COLUMN sub_category VARCHAR(50) DEFAULT 'Pet'");
        } catch (\Exception $e) {}

        self::seedDefaultData();
    }

    /**
     * Inisialisasi Skema SQLite
     */
    private static function initSqliteSchema(): void
    {
        $db = self::$pdo;

        // Optimasi SQLite WAL & In-Memory Cache untuk kecepatan instan
        $db->exec("PRAGMA journal_mode = WAL;");
        $db->exec("PRAGMA synchronous = NORMAL;");
        $db->exec("PRAGMA temp_store = MEMORY;");
        $db->exec("PRAGMA cache_size = -64000;");

        // Pastikan kolom categories ada di tabel games
        try {
            $cols = $db->query("PRAGMA table_info(games)")->fetchAll(PDO::FETCH_ASSOC);
            $hasCat = false;
            foreach ($cols as $c) {
                if ($c['name'] === 'categories') { $hasCat = true; break; }
            }
            if (!$hasCat && !empty($cols)) {
                $db->exec("ALTER TABLE games ADD COLUMN categories TEXT DEFAULT 'Pet, Gems, Item, Akun'");
            }
        } catch (\Exception $e) {}

        // Pastikan kolom items_json ada di tabel orders
        try {
            $orderCols = $db->query("PRAGMA table_info(orders)")->fetchAll(PDO::FETCH_ASSOC);
            $hasItems = false;
            foreach ($orderCols as $c) {
                if ($c['name'] === 'items_json') { $hasItems = true; break; }
            }
            if (!$hasItems && !empty($orderCols)) {
                $db->exec("ALTER TABLE orders ADD COLUMN items_json TEXT DEFAULT NULL");
            }
        } catch (\Exception $e) {}

        // Cek apakah tabel utama sudah ada untuk menghindari DDL overhead pada setiap request
        $checkTable = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='products'")->fetchColumn();
        if ($checkTable) {
            return; // Skema sudah siap! Langsung return dalam < 1ms
        }

        $db->exec("CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            icon TEXT DEFAULT 'package',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            game TEXT DEFAULT 'Blox Fruits',
            name TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            price INTEGER NOT NULL,
            price_original INTEGER DEFAULT 0,
            description TEXT,
            image_url TEXT,
            badge TEXT DEFAULT 'Terlaris',
            stock INTEGER DEFAULT 10,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )");

        try {
            $db->exec("ALTER TABLE products ADD COLUMN game TEXT DEFAULT 'Blox Fruits'");
        } catch (\Exception $e) {}

        $db->exec("CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            invoice_number TEXT UNIQUE NOT NULL,
            product_id INTEGER NOT NULL,
            product_name TEXT NOT NULL,
            category_name TEXT NOT NULL,
            price INTEGER NOT NULL,
            roblox_username TEXT NOT NULL,
            roblox_avatar_url TEXT,
            whatsapp TEXT NOT NULL,
            note TEXT,
            status TEXT DEFAULT 'PENDING',
            payment_method TEXT DEFAULT 'QRIS',
            account_data TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id)
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            order_id INTEGER,
            roblox_username TEXT NOT NULL,
            roblox_avatar_url TEXT,
            rating INTEGER DEFAULT 5,
            comment TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id)
        )");

        // Tabel Chat Pesanan (Live Chat Antara Pembeli & Penjual)
        $db->exec("CREATE TABLE IF NOT EXISTS order_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            invoice_number TEXT NOT NULL,
            sender TEXT NOT NULL,
            sender_name TEXT NOT NULL,
            message TEXT NOT NULL,
            is_read INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabel Akun Pembeli (Login dengan Google)
        $db->exec("CREATE TABLE IF NOT EXISTS buyers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            google_id TEXT,
            email TEXT UNIQUE NOT NULL,
            name TEXT NOT NULL,
            avatar_url TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabel Kode Redeem (Diskon Persen Khusus 1 Produk)
        $db->exec("CREATE TABLE IF NOT EXISTS redeem_codes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code TEXT UNIQUE NOT NULL,
            discount_percent INTEGER NOT NULL,
            product_id INTEGER,
            max_uses INTEGER DEFAULT 0,
            used_count INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabel Games Roblox (CMS Kategori Game)
        $db->exec("CREATE TABLE IF NOT EXISTS games (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            logo_url TEXT,
            icon TEXT DEFAULT 'fa-solid fa-gamepad',
            description TEXT,
            is_active INTEGER DEFAULT 1,
            sort_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabel FAQs (CMS Tanya Jawab)
        $db->exec("CREATE TABLE IF NOT EXISTS faqs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            answer TEXT NOT NULL,
            category TEXT DEFAULT 'Umum',
            sort_order INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        try {
            $db->exec("ALTER TABLE orders ADD COLUMN buyer_email TEXT");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE orders ADD COLUMN redeem_code TEXT");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE orders ADD COLUMN discount_amount INTEGER DEFAULT 0");
        } catch (\Exception $e) {}

        try {
            $db->exec("ALTER TABLE products ADD COLUMN rating REAL DEFAULT 4.9");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE products ADD COLUMN total_sold INTEGER DEFAULT 0");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE products ADD COLUMN sub_category TEXT DEFAULT 'Pet'");
        } catch (\Exception $e) {}
        try {
            $db->exec("ALTER TABLE games ADD COLUMN categories TEXT DEFAULT 'Pet, Gems, Item, Akun'");
        } catch (\Exception $e) {}

        self::seedDefaultData();
    }

    /**
     * Seeder data aman: mengecek ketersediaan data via SLUG agar tidak memicu UNIQUE constraint error
     */
    private static function seedDefaultData(): void
    {
        $db = self::$pdo;

        try {
            // Cek apakah database sudah pernah diinisialisasi sebelumnya
            $stmtCheckInit = $db->query("SELECT value FROM settings WHERE `key` = 'database_initialized'");
            $isInitialized = $stmtCheckInit ? $stmtCheckInit->fetchColumn() : null;
            if ($isInitialized === '1') {
                return; // Database sudah terinisialisasi. Jangan inject data default lagi saat admin menghapus data!
            }

            // Jika database sudah memiliki data admin atau kategori, tandai sebagai initialized agar tidak inject ulang
            $adminCount = (int)$db->query("SELECT COUNT(*) FROM admins")->fetchColumn();
            if ($adminCount > 0) {
                if (self::$driver === 'mysql') {
                    $db->exec("INSERT INTO settings (`key`, `value`) VALUES ('database_initialized', '1') ON DUPLICATE KEY UPDATE `value`='1'");
                } else {
                    $db->exec("INSERT OR REPLACE INTO settings (`key`, `value`) VALUES ('database_initialized', '1')");
                }
                return;
            }

            // 1. Kategori Default
            $categories = [
                ['id' => 1, 'name' => 'Item Roblox', 'slug' => 'item-game', 'icon' => 'cube'],
                ['id' => 2, 'name' => 'Akun Roblox', 'slug' => 'akun-game', 'icon' => 'user-shield'],
            ];

            foreach ($categories as $cat) {
                $check = $db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
                $check->execute([$cat['slug']]);
                if ((int)$check->fetchColumn() === 0) {
                    $stmt = $db->prepare("INSERT INTO categories (id, name, slug, icon) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$cat['id'], $cat['name'], $cat['slug'], $cat['icon']]);
                }
            }

            // 2. Admin Default (admin / admin123)
            $checkAdmin = $db->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
            $checkAdmin->execute(['admin']);
            if ((int)$checkAdmin->fetchColumn() === 0) {
                $stmt = $db->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
                $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);
            }

            // 3. Produk Default Pilihan (Build A Zoo)
            $products = [
                [
                    'category_slug' => 'item-game',
                    'game' => 'Build A Zoo',
                    'name' => 'Mucy ($2.753M/s) (Prismatic) (Stellar)',
                    'slug' => 'baz-mucy-prismatic-stellar',
                    'price' => 65000,
                    'price_original' => 85000,
                    'description' => 'Hewan langka Mucy 179 kg dengan income tertinggi $2.753M/detik. Prismatic Stellar tier, siap trade via server privat.',
                    'image_url' => '/uploads/products/mucy-prismatic-stellar.png',
                    'badge' => 'Prismatic Stellar',
                    'stock' => 3
                ],
                [
                    'category_slug' => 'item-game',
                    'game' => 'Build A Zoo',
                    'name' => 'Chomp ($1.617M/s) (Prismatic) (Stellar)',
                    'slug' => 'baz-chomp-prismatic-stellar',
                    'price' => 50000,
                    'price_original' => 70000,
                    'description' => 'Chomp raksasa berat 2.5 ton dengan income masif $1.617M/detik. Prismatic Stellar tier, garansi pengiriman kilat 3-5 menit.',
                    'image_url' => '/uploads/products/chomp-prismatic-stellar.png',
                    'badge' => 'Prismatic Stellar',
                    'stock' => 4
                ],
                [
                    'category_slug' => 'item-game',
                    'game' => 'Build A Zoo',
                    'name' => 'Crystalline ($525.277/s) (Divine) (Jurassic)',
                    'slug' => 'baz-crystalline-divine-jurassic',
                    'price' => 35000,
                    'price_original' => 45000,
                    'description' => 'Dinosaurus kristal tier Divine Jurassic dengan income stabil $525.277/detik. Hewan petarung & booster income terbaik.',
                    'image_url' => '/uploads/products/crystalline-divine-jurassic.png',
                    'badge' => 'Divine Jurassic',
                    'stock' => 5
                ],
                [
                    'category_slug' => 'item-game',
                    'game' => 'Build A Zoo',
                    'name' => 'Aurefang ($438.300/s) (Prismatic) (Snow)',
                    'slug' => 'baz-aurefang-prismatic-snow',
                    'price' => 30000,
                    'price_original' => 40000,
                    'description' => 'Naga es Aurefang Prismatic dengan mutasi Snow langka. Menghasilkan income $438.300/detik untuk kebun binatangmu.',
                    'image_url' => '/uploads/products/aurefang-prismatic-snow.png',
                    'badge' => 'Prismatic Snow',
                    'stock' => 5
                ],
                [
                    'category_slug' => 'item-game',
                    'game' => 'Build A Zoo',
                    'name' => 'Glazyn ($33.605/s) (Prismatic) Egg Event',
                    'slug' => 'baz-glazyn-prismatic-egg-event',
                    'price' => 20000,
                    'price_original' => 30000,
                    'description' => 'Hewan eksklusif Glazyn Prismatic dari limited Egg Event. Desain sayap bercahaya, sangat cocok untuk kolektor.',
                    'image_url' => '/uploads/products/glazyn-prismatic-egg-event.png',
                    'badge' => 'Egg Event',
                    'stock' => 8
                ],
                [
                    'category_slug' => 'akun-game',
                    'game' => 'Build A Zoo',
                    'name' => 'Akun Sultan Build A Zoo (Max Habitat + Billionaire Cash)',
                    'slug' => 'akun-build-a-zoo-sultan-billionaire',
                    'price' => 130000,
                    'price_original' => 175000,
                    'description' => 'Akun sultan Build A Zoo dengan seluruh plot dan conveyor level maksimal, koleksi hewan lengkap, saldo miliaran cash. Akun polosan siap bind emailmu.',
                    'image_url' => '/uploads/products/mucy-prismatic-stellar.png',
                    'badge' => 'Akun Sultan',
                    'stock' => 2
                ]
            ];

            $catMap = [];
            $allCats = $db->query("SELECT id, slug FROM categories")->fetchAll();
            foreach ($allCats as $c) {
                $catMap[$c['slug']] = $c['id'];
            }

            foreach ($products as $p) {
                $checkProd = $db->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
                $checkProd->execute([$p['slug']]);
                if ((int)$checkProd->fetchColumn() === 0) {
                    $catId = $catMap[$p['category_slug']] ?? 1;
                    $stmtProd = $db->prepare("INSERT INTO products (category_id, game, name, slug, price, price_original, description, image_url, badge, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmtProd->execute([
                        $catId,
                        $p['game'],
                        $p['name'],
                        $p['slug'],
                        $p['price'],
                        $p['price_original'],
                        $p['description'],
                        $p['image_url'],
                        $p['badge'],
                        $p['stock']
                    ]);
                }
            }
            // Seed Reviews jika tabel ulasan kosong
            $checkReviews = $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
            if ((int)$checkReviews === 0) {
                $prods = $db->query("SELECT id, name FROM products ORDER BY id ASC LIMIT 6")->fetchAll();
                if (!empty($prods)) {
                    $defaultReviews = [
                        [
                            'product_id' => $prods[0]['id'],
                            'roblox_username' => 'Fahrl_05',
                            'roblox_avatar_url' => 'https://tr.rbxcdn.com/30DAY-AvatarHeadshot-8447B66F51DB9D527E8186331A9945F1-Png/150/150/AvatarHeadshot/Png/noFilter',
                            'rating' => 5,
                            'comment' => 'Mucy 179 kg gokil banget income $2.753M/s langsung masuk pasifnya kenceng parah. Trade private server cuma 3 menit beres, fast delivery beneran!'
                        ],
                        [
                            'product_id' => $prods[1]['id'] ?? $prods[0]['id'],
                            'roblox_username' => 'Zentox_RBLX',
                            'roblox_avatar_url' => 'https://ui-avatars.com/api/?name=Zentox&background=38bdf8&color=fff',
                            'rating' => 5,
                            'comment' => 'Chomp 2.5 ton gemuk banget bro haha, penjual responsif dan ramah banget di WhatsApp. Trusted seller, pasti langganan lagi.'
                        ],
                        [
                            'product_id' => $prods[2]['id'] ?? $prods[0]['id'],
                            'roblox_username' => 'DinoKing99',
                            'roblox_avatar_url' => 'https://ui-avatars.com/api/?name=Dino&background=10b981&color=fff',
                            'rating' => 5,
                            'comment' => 'Dino Crystalline mantap tier Divine Jurassic beneran sesuai deskripsi. Makasih banyak bonus pot-nya gan!'
                        ],
                        [
                            'product_id' => $prods[3]['id'] ?? $prods[0]['id'],
                            'roblox_username' => 'SnowHunter',
                            'roblox_avatar_url' => 'https://ui-avatars.com/api/?name=Snow&background=f59e0b&color=fff',
                            'rating' => 5,
                            'comment' => 'Aurefang Snow mutasi mantap banget efek es-nya keren parah. Penjual fast respon, recommended seller!'
                        ],
                        [
                            'product_id' => $prods[4]['id'] ?? $prods[0]['id'],
                            'roblox_username' => 'GlazynCollector',
                            'roblox_avatar_url' => 'https://ui-avatars.com/api/?name=Glazyn&background=8b5cf6&color=fff',
                            'rating' => 5,
                            'comment' => 'Glazyn sayap bercahaya dari limited event langka beneran dapat. Proses trade kilat aman 100%!'
                        ],
                        [
                            'product_id' => $prods[5]['id'] ?? $prods[0]['id'],
                            'roblox_username' => 'Kenzo_Gamer',
                            'roblox_avatar_url' => 'https://ui-avatars.com/api/?name=Kenzo&background=06b6d4&color=fff',
                            'rating' => 5,
                            'comment' => 'Akun polosan 100% aman langsung saya bind ke email pribadi. Saldo miliaran cash & plot lengkap, puas banget belanja di ItemPedia!'
                        ]
                    ];

                    $insRev = $db->prepare("INSERT INTO reviews (product_id, roblox_username, roblox_avatar_url, rating, comment) VALUES (?, ?, ?, ?, ?)");
                    foreach ($defaultReviews as $dr) {
                        $insRev->execute([
                            $dr['product_id'],
                            $dr['roblox_username'],
                            $dr['roblox_avatar_url'],
                            $dr['rating'],
                            $dr['comment']
                        ]);
                    }
                }
            }

            // 5. Seed Kode Redeem Default jika kosong
            $checkCodes = $db->query("SELECT COUNT(*) FROM redeem_codes")->fetchColumn();
            if ((int)$checkCodes === 0) {
                $sampleCodes = [
                    ['code' => 'ITEMHEMAT10', 'discount_percent' => 10, 'product_id' => null, 'max_uses' => 100],
                    ['code' => 'ROBLOX20', 'discount_percent' => 20, 'product_id' => null, 'max_uses' => 50],
                ];
                $insCode = $db->prepare("INSERT INTO redeem_codes (code, discount_percent, product_id, max_uses, used_count, is_active) VALUES (?, ?, ?, ?, 0, 1)");
                foreach ($sampleCodes as $sc) {
                    $insCode->execute([$sc['code'], $sc['discount_percent'], $sc['product_id'], $sc['max_uses']]);
                }
            }

            // 6. Seed Games Default (CMS Kategori Game)
            $defaultGames = [
                [
                    'name' => 'Build A Zoo',
                    'slug' => 'build-a-zoo',
                    'logo_url' => 'https://tr.rbxcdn.com/180DAY-2543fb928e006898a9d8d103d7cdacb9/150/150/Image/Png/noFilter',
                    'icon' => 'fa-solid fa-hippo',
                    'description' => 'Hewan langka Prismatic, Divine, Mutasi Stellar & Snow dengan income $/s tertinggi.',
                    'sort_order' => 1
                ],
                [
                    'name' => 'Chop Your Tree',
                    'slug' => 'chop-your-tree',
                    'logo_url' => 'https://tr.rbxcdn.com/180DAY-583aadc048a6f9dd057f5a4ccd6f4a58/150/150/Image/Png/noFilter',
                    'icon' => 'fa-solid fa-tree',
                    'description' => 'Axes legendaris, booster kecepatan potong, dan pet pemotong kayu super cepat.',
                    'sort_order' => 2
                ],
                [
                    'name' => 'Catch and Tame',
                    'slug' => 'catch-and-tame',
                    'logo_url' => 'https://tr.rbxcdn.com/180DAY-11c9aa2e3597fe112c004ab2171bf298/150/150/Image/Png/noFilter',
                    'icon' => 'fa-solid fa-horse',
                    'description' => 'Monster langka mythical, lasso sakti, dan booster taming instan.',
                    'sort_order' => 3
                ],
                [
                    'name' => 'Pet Simulator 99',
                    'slug' => 'pet-simulator-99',
                    'logo_url' => 'https://tr.rbxcdn.com/180DAY-e8e63b6ca1fa89b2db971c26b6fe29db/150/150/Image/Png/noFilter',
                    'icon' => 'fa-solid fa-cat',
                    'description' => 'Huge Pets, Titanic Pets, Gems jutaan, dan Exclusive Enchants.',
                    'sort_order' => 4
                ],
                [
                    'name' => 'Blox Fruits',
                    'slug' => 'blox-fruits',
                    'logo_url' => 'https://tr.rbxcdn.com/180DAY-4f4c2c56a16c7cf2547b30c4fc5fc13b/150/150/Image/Png/noFilter',
                    'icon' => 'fa-solid fa-skull-crossbones',
                    'description' => 'Perm Fruits (Kitsune, Dragon, Leopard), Gamepass, Beli & Fragment.',
                    'sort_order' => 5
                ],
                [
                    'name' => 'Toilet Tower Defense',
                    'slug' => 'toilet-tower-defense',
                    'logo_url' => 'https://tr.rbxcdn.com/180DAY-925761eb1bb5d2eebf0d486d34e892c5/150/150/Image/Png/noFilter',
                    'icon' => 'fa-solid fa-shield-halved',
                    'description' => 'Unit Godly, Mythic, Gems, Crates, dan Exclusive Event Units.',
                    'sort_order' => 6
                ]
            ];

            foreach ($defaultGames as $dg) {
                $checkGame = $db->prepare("SELECT COUNT(*) FROM games WHERE slug = ? OR name = ?");
                $checkGame->execute([$dg['slug'], $dg['name']]);
                if ((int)$checkGame->fetchColumn() === 0) {
                    $insGame = $db->prepare("INSERT INTO games (name, slug, logo_url, icon, description, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
                    $insGame->execute([$dg['name'], $dg['slug'], $dg['logo_url'], $dg['icon'], $dg['description'], $dg['sort_order']]);
                }
            }

            // 7. Seed Settings Default (CMS Konten Laman)
            $defaultSettings = [
                'site_name' => 'ItemPedia',
                'site_tagline' => 'Pusat Jual Beli Item & Akun Game Roblox Terpercaya',
                'hero_badge' => '⚡ FLASH PROMO SPESIAL HARI INI',
                'hero_title' => 'Item & Akun Roblox Impianmu, Dikirim Hitungan Menit.',
                'hero_subtitle' => 'Pusat marketplace item game Build A Zoo, Chop Your Tree, Catch and Tame, Pet Sim 99, & Blox Fruits terlengkap dengan sistem otomatis dan garansi 100% aman anti-banned.',
                'announcement' => '🎉 Diskon Spesial Minggu Ini! Gunakan Kode Promo: ITEMHEMAT10 untuk potongan 10% setiap pembelian item Build A Zoo! ⚡ Pengiriman Kilat 3-5 Menit via Trade Server Privat.',
                'whatsapp_admin' => '6281234567890',
                'whatsapp_display' => '+62 812-3456-7890',
                'operating_hours_open' => '07:00',
                'operating_hours_close' => '21:00',
                'operating_timezone' => 'WITA',
                'step1_title' => 'Pilih Item / Akun Game',
                'step1_desc' => 'Cari hewan Prismatic, akun Sultan, atau item game favoritmu sesuai kebutuhan.',
                'step2_title' => 'Isi Data & Bayar QRIS',
                'step2_desc' => 'Masukkan username Roblox & nomor WhatsApp. Scan QRIS instan dari semua e-wallet & m-banking.',
                'step3_title' => 'Trade Langsung di Server',
                'step3_desc' => 'Admin join server privatmu atau kirim via trade instan dalam 3-5 menit beres!',
            ];

            $stmtSetting = $db->prepare("INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)");
            if (self::$driver === 'mysql') {
                $stmtSetting = $db->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `key`=`key`");
            }
            foreach ($defaultSettings as $sk => $sv) {
                $stmtSetting->execute([$sk, $sv]);
            }

            // 8. Seed FAQs Default
            $checkFaqs = $db->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
            if ((int)$checkFaqs === 0) {
                $defaultFaqs = [
                    [
                        'question' => 'Bagaimana cara kerja pengiriman item game?',
                        'answer' => 'Setelah pembayaran terverifikasi otomatis via QRIS, admin kami akan langsung menghubungi nomor WhatsApp Anda atau join ke Private Server Roblox sesuai username yang Anda cantumkan saat checkout. Proses trade rata-rata hanya butuh 3-5 menit.',
                        'category' => 'Pengiriman',
                        'sort_order' => 1
                    ],
                    [
                        'question' => 'Apakah belanja di ItemPedia aman dari banned?',
                        'answer' => '100% Aman! Seluruh item dan akun diperoleh secara legal melalui in-game grinding, trading resmi, dan event resmi pengembang Roblox. Tanpa exploit, dupe, atau cheat.',
                        'category' => 'Keamanan',
                        'sort_order' => 2
                    ],
                    [
                        'question' => 'Metode pembayaran apa saja yang didukung?',
                        'answer' => 'Kami mendukung pembayaran otomatis QRIS 24 Jam yang dapat di-scan dari DANA, GoPay, OVO, ShopeePay, LinkAja, BCA Mobile, Mandiri Livin, BRImo, Seabank, Bank Jago, dan seluruh aplikasi m-banking berstandar QRIS.',
                        'category' => 'Pembayaran',
                        'sort_order' => 3
                    ],
                    [
                        'question' => 'Bagaimana jika pesanan saya mengalami kendala atau seller belum join?',
                        'answer' => 'Tim admin ItemPedia siap membantu melalui fitur Live Chat di halaman pesanan atau WhatsApp resmi setiap hari pukul 07:00 - 21:00 WITA. Garansi uang kembali 100% jika item gagal terkirim.',
                        'category' => 'Garansi & Bantuan',
                        'sort_order' => 4
                    ]
                ];

                $insFaq = $db->prepare("INSERT INTO faqs (question, answer, category, sort_order, is_active) VALUES (?, ?, ?, ?, 1)");
                foreach ($defaultFaqs as $df) {
                    $insFaq->execute([$df['question'], $df['answer'], $df['category'], $df['sort_order']]);
                }
            }

            // Tandai bahwa database sudah selesai diinisialisasi untuk pertama kali
            if (self::$driver === 'mysql') {
                $db->exec("INSERT INTO settings (`key`, `value`) VALUES ('database_initialized', '1') ON DUPLICATE KEY UPDATE `value`='1'");
            } else {
                $db->exec("INSERT OR REPLACE INTO settings (`key`, `value`) VALUES ('database_initialized', '1')");
            }
        } catch (\Exception $e) {
            // Log silent jika terjadi kendala minor seeder
            error_log("Seed warning: " . $e->getMessage());
        }
    }
}
