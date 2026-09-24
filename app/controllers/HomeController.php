<?php

namespace App\Controllers;

use Flight;
use App\Config\Database;
use PDO;

class HomeController
{
    public static function index(): void
    {
        $db = Database::getConnection();

        // Ambil kategori
        $categories = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();

        // Daftar Game Roblox Dinamis dari Database (CMS Kategori)
        $dbGames = $db->query("SELECT * FROM games WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();
        $games = [
            [
                'id' => 'all',
                'name' => 'Semua Game',
                'logo' => 'https://images.rbxcdn.com/13c3b0eb81ff7d05777741d7c48f88a9.png',
                'icon' => 'fa-solid fa-cubes'
            ]
        ];
        foreach ($dbGames as $dg) {
            $games[] = [
                'id' => $dg['name'],
                'name' => $dg['name'],
                'logo' => $dg['logo_url'] ?: 'https://images.rbxcdn.com/13c3b0eb81ff7d05777741d7c48f88a9.png',
                'icon' => $dg['icon'] ?: 'fa-solid fa-gamepad',
                'description' => $dg['description'] ?? ''
            ];
        }

        // Filter kategori, game & pencarian
        $catFilter = strtolower(trim(Flight::request()->query->kategori ?? 'semua'));
        $gameFilter = Flight::request()->query->game ?? 'all';
        $searchQuery = trim(Flight::request()->query->q ?? '');

        // Ambil semua produk aktif agar switching game & kategori dapat berjalan instan tanpa delay
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.is_active = 1";
        $params = [];

        if (!empty($searchQuery)) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.game LIKE ? OR p.sub_category LIKE ? OR c.name LIKE ?)";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        // Petakan kategori khusus per-game (game-specific categories)
        // Sumber: 1) Kolom categories di tabel games (CMS Kategori), 2) sub_category dari produk aktif game tsb
        $gameCategoriesMap = [];
        $prodSubCatsByGame = [];
        $allActiveSubCats = [];

        foreach ($products as $p) {
            $gKey = strtolower($p['game'] ?? 'build a zoo');
            $sc = trim($p['sub_category'] ?? '');
            if (!empty($sc)) {
                $prodSubCatsByGame[$gKey][] = $sc;
                $allActiveSubCats[] = $sc;
            }
        }

        // 1. Kategori per masing-masing Game terdaftar
        foreach ($dbGames as $dg) {
            $gNameLower = strtolower($dg['name']);
            $gSlugLower = strtolower($dg['slug']);
            
            $seenSlugs = ['semua' => true];
            $tabs = [
                ['slug' => 'semua', 'name' => 'Semua']
            ];

            // Ambil kategori dari konfigurasi game di database
            $rawGameCats = array_filter(array_map('trim', explode(',', $dg['categories'] ?? '')));
            foreach ($rawGameCats as $cName) {
                if (empty($cName)) continue;
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $cName));
                if (!isset($seenSlugs[$slug])) {
                    $tabs[] = ['slug' => $slug, 'name' => $cName];
                    $seenSlugs[$slug] = true;
                }
            }

            // Tambahkan juga kategori unik dari produk aktif game ini jika ada
            if (!empty($prodSubCatsByGame[$gNameLower])) {
                foreach ($prodSubCatsByGame[$gNameLower] as $cName) {
                    if (empty($cName)) continue;
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $cName));
                    if (!isset($seenSlugs[$slug])) {
                        $tabs[] = ['slug' => $slug, 'name' => $cName];
                        $seenSlugs[$slug] = true;
                    }
                }
            }

            // Default aman jika game sama sekali belum punya kategori
            if (count($tabs) === 1) {
                $tabs[] = ['slug' => 'item', 'name' => 'Item'];
                $tabs[] = ['slug' => 'akun', 'name' => 'Akun'];
            }

            $gameCategoriesMap[$gNameLower] = $tabs;
            $gameCategoriesMap[$gSlugLower] = $tabs;
        }

        // 2. Kategori untuk "Semua Game" (all)
        $allTabs = [
            ['slug' => 'semua', 'name' => 'Semua']
        ];
        $allSeenSlugs = ['semua' => true];

        // Masukkan kategori dari produk aktif terlebih dahulu
        $uniqueActiveSubCats = array_unique($allActiveSubCats);
        foreach ($uniqueActiveSubCats as $sc) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $sc));
            if (!isset($allSeenSlugs[$slug])) {
                $allTabs[] = ['slug' => $slug, 'name' => $sc];
                $allSeenSlugs[$slug] = true;
            }
        }

        // Masukkan kategori dari game-game aktif
        foreach ($dbGames as $dg) {
            $rawGameCats = array_filter(array_map('trim', explode(',', $dg['categories'] ?? '')));
            foreach ($rawGameCats as $cName) {
                if (empty($cName)) continue;
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $cName));
                if (!isset($allSeenSlugs[$slug])) {
                    $allTabs[] = ['slug' => $slug, 'name' => $cName];
                    $allSeenSlugs[$slug] = true;
                }
            }
        }

        if (count($allTabs) === 1) {
            $allTabs[] = ['slug' => 'item', 'name' => 'Item'];
            $allTabs[] = ['slug' => 'akun', 'name' => 'Akun'];
        }

        $gameCategoriesMap['all'] = $allTabs;

        // Tentukan tabs yang aktif untuk initial server-render
        $activeGameKey = strtolower($gameFilter);
        $categoryTabs = $gameCategoriesMap[$activeGameKey] ?? $gameCategoriesMap['all'];

        // Hitung produk per sub-kategori khusus Build A Zoo (backwards compatibility)
        $bazCounts = [
            'semua' => 0,
            'pet' => 0,
            'egg' => 0,
            'food' => 0,
            'item' => 0,
            'akun' => 0,
        ];
        $allBazProds = $db->query("SELECT sub_category, category_id FROM products WHERE game = 'Build A Zoo' AND is_active = 1")->fetchAll();
        $bazCounts['semua'] = count($allBazProds);
        foreach ($allBazProds as $bp) {
            $sc = strtolower($bp['sub_category'] ?? '');
            if ($sc === 'pet' || (empty($sc) && (int)$bp['category_id'] === 1)) {
                $bazCounts['pet']++;
            } elseif ($sc === 'akun' || (empty($sc) && (int)$bp['category_id'] === 2)) {
                $bazCounts['akun']++;
            } elseif (isset($bazCounts[$sc])) {
                $bazCounts[$sc]++;
            }
        }

        // Ambil ulasan nyata pembeli dari database
        $allReviews = $db->query("SELECT r.*, p.name as product_name, p.game 
                               FROM reviews r 
                               JOIN products p ON r.product_id = p.id 
                               ORDER BY r.id DESC")->fetchAll();
        $reviews = array_slice($allReviews, 0, 6);

        // Petakan ulasan per product_id untuk pop-up detail produk
        $reviewsByProduct = [];
        foreach ($allReviews as $rev) {
            $reviewsByProduct[$rev['product_id']][] = [
                'roblox_username' => $rev['roblox_username'],
                'roblox_avatar_url' => $rev['roblox_avatar_url'],
                'rating' => (float)$rev['rating'],
                'comment' => $rev['comment'],
                'created_at' => $rev['created_at']
            ];
        }

        foreach ($products as &$prod) {
            $prod['reviews'] = $reviewsByProduct[$prod['id']] ?? [];
        }
        unset($prod);

        // Ambil settings
        $settingsRaw = $db->query("SELECT * FROM settings")->fetchAll();
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }

        // Ambil transaksi pembelian terbaru untuk Live Pembelian Feed
        $rawOrders = $db->query("SELECT o.id, o.invoice_number, o.product_name, o.price, o.roblox_username, o.roblox_avatar_url, o.status, o.created_at, p.image_url as product_image, p.game 
                                FROM orders o 
                                LEFT JOIN products p ON o.product_id = p.id 
                                ORDER BY o.id DESC 
                                LIMIT 15")->fetchAll();

        $recentPurchases = [];
        $sampleBuyers = [
            ['u' => 'Reyhan_Gamer', 'p' => 'Mucy ($2.753M/s)', 'g' => 'Build A Zoo', 'pr' => 45000, 't' => 'Baru saja'],
            ['u' => 'BintangRoblox', 'p' => 'Chomp ($1.617M/s)', 'g' => 'Build A Zoo', 'pr' => 28000, 't' => '1 mnt lalu'],
            ['u' => 'Dimas_Blox', 'p' => 'Akun Polosan Level 1500+', 'g' => 'Build A Zoo', 'pr' => 65000, 't' => '2 mnt lalu'],
            ['u' => 'Naufal_Pro99', 'p' => 'Golden Axe Level Max', 'g' => 'Chop Your Tree', 'pr' => 35000, 't' => '4 mnt lalu'],
            ['u' => 'Fadhil_Hunter', 'p' => 'Mythical Beast Dragon', 'g' => 'Catch and Tame', 'pr' => 85000, 't' => '5 mnt lalu'],
            ['u' => 'Aldo_Kenz', 'p' => '10x Lucky Egg Bundle', 'g' => 'Build A Zoo', 'pr' => 50000, 't' => '8 mnt lalu'],
        ];

        foreach ($rawOrders as $idx => $ord) {
            $u = $ord['roblox_username'] ?: 'Buyer_' . substr($ord['invoice_number'], -4);
            $uMasked = strlen($u) > 3 ? substr($u, 0, 2) . '***' . substr($u, -1) : $u;
            
            $recentPurchases[] = [
                'username' => $uMasked,
                'avatar_url' => $ord['roblox_avatar_url'] ?: 'https://images.rbxcdn.com/13c3b0eb81ff7d05777741d7c48f88a9.png',
                'product_name' => $ord['product_name'] ?: 'Item Roblox',
                'game' => $ord['game'] ?: 'Roblox',
                'price' => (int)$ord['price'],
                'time_ago' => ($idx === 0) ? 'Baru saja' : (($idx * 2) . ' mnt lalu')
            ];
        }

        if (count($recentPurchases) < 6) {
            foreach ($sampleBuyers as $sb) {
                $u = $sb['u'];
                $uMasked = substr($u, 0, 2) . '***' . substr($u, -2);
                $recentPurchases[] = [
                    'username' => $uMasked,
                    'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($sb['u']) . '&background=0ea5e9&color=fff',
                    'product_name' => $sb['p'],
                    'game' => $sb['g'],
                    'price' => $sb['pr'],
                    'time_ago' => $sb['t']
                ];
            }
        }

        // Ambil FAQs dari Database
        $faqs = $db->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();

        Flight::render('home', [
            'categories' => $categories,
            'categoryTabs' => $categoryTabs,
            'gameCategoriesMap' => $gameCategoriesMap,
            'games' => $games,
            'products' => $products,
            'reviews' => $reviews,
            'recentPurchases' => $recentPurchases,
            'faqs' => $faqs,
            'activeCategory' => $catFilter,
            'activeGame' => $gameFilter,
            'searchQuery' => $searchQuery,
            'settings' => $settings,
            'bazCounts' => $bazCounts
        ]);
    }
}
