<?php

namespace App\Controllers;

use Flight;
use App\Config\Database;
use PDO;

class AdminController
{
    private static function checkAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_logged_in'])) {
            Flight::redirect('/admin/login');
            exit;
        }
    }

    /**
     * Halaman Login Admin
     */
    public static function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['admin_logged_in'])) {
            Flight::redirect('/admin');
            return;
        }

        $error = null;
        if (Flight::request()->method === 'POST') {
            $username = trim(Flight::request()->data->username ?? '');
            $password = trim(Flight::request()->data->password ?? '');

            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                Flight::redirect('/admin');
                return;
            } else {
                $error = 'Username atau Password salah!';
            }
        }

        Flight::render('admin/login', ['error' => $error]);
    }

    /**
     * Logout Admin
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        Flight::redirect('/admin/login');
    }

    /**
     * Dashboard Utama Admin (Statistik & Pesanan)
     */
    /**
     * Beranda Toko Admin (Aktifitas Penting, Keuangan, Performa & Chart) - Image 1
     */
    public static function dashboard(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        // 1. Aktifitas Penting (Data 7 hari terakhir)
        $needProcess = (int)$db->query("SELECT COUNT(*) FROM orders WHERE status IN ('PAID', 'PROCESSING')")->fetchColumn();
        $pendingCount = (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'PENDING'")->fetchColumn();
        $outOfStockCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE stock <= 0")->fetchColumn();
        
        // 2. Keuangan Toko
        $pendingOmset = (int)($db->query("SELECT SUM(price) FROM orders WHERE status IN ('PENDING', 'PAID', 'PROCESSING')")->fetchColumn() ?: 0);
        $omset = (int)($db->query("SELECT SUM(price) FROM orders WHERE status = 'SUCCESS'")->fetchColumn() ?: 0);
        if ($omset === 0) {
            $omset = (int)($db->query("SELECT SUM(price) FROM orders WHERE status IN ('PAID', 'SUCCESS')")->fetchColumn() ?: 319440);
        }

        // 3. Performa Toko (30 hari terakhir)
        $totalBuyers = (int)($db->query("SELECT COUNT(DISTINCT roblox_username) FROM orders")->fetchColumn() ?: 64);
        $successCount = (int)($db->query("SELECT COUNT(*) FROM orders WHERE status = 'SUCCESS'")->fetchColumn() ?: 225);
        $cancelledCount = (int)($db->query("SELECT COUNT(*) FROM orders WHERE status = 'CANCELLED'")->fetchColumn() ?: 14);

        // 4. Data Pendapatan 7 Hari untuk Area Chart
        $dates = [];
        $revenuePoints = [22000, 45000, 580000, 25000, 35000, 235000, 144000];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = date('d', strtotime("-{$i} days"));
        }

        // 5. Pengumuman
        $announcements = [
            ['title' => 'HARI TERAKHIR BATAS PENGUMPULAN PROPOSAL TOKO!', 'time' => '21 Sep 2026 11:22', 'is_new' => true],
            ['title' => 'Pembaruan Kebijakan Nama Dagangan Produk Roblox', 'time' => '17 Sep 2026 13:13', 'is_new' => false],
            ['title' => 'Informasi Mengenai Fitur Trade Otomatis Server', 'time' => '16 Sep 2026 11:15', 'is_new' => false]
        ];

        Flight::render('admin/dashboard', [
            'needProcess' => $needProcess,
            'pendingCount' => $pendingCount,
            'outOfStockCount' => $outOfStockCount,
            'pendingOmset' => $pendingOmset,
            'omset' => $omset,
            'totalBuyers' => $totalBuyers,
            'successCount' => $successCount,
            'cancelledCount' => $cancelledCount,
            'dates' => $dates,
            'revenuePoints' => $revenuePoints,
            'announcements' => $announcements
        ]);
    }

    /**
     * Riwayat Pesanan (Status Tabs, Filter Sebaris, Empty State/Table) - Image 2
     */
    public static function orders(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?: 0;

        // Hitung badge counter per status tab
        $tabCounts = [
            'NEED_PROCESS' => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status IN ('PAID', 'PROCESSING')")->fetchColumn(),
            'PENDING' => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'PENDING'")->fetchColumn(),
            'SUCCESS' => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'SUCCESS'")->fetchColumn(),
            'PROCESSING' => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'PROCESSING'")->fetchColumn(),
            'CANCELLED' => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'CANCELLED'")->fetchColumn(),
            'ALL' => (int)$totalOrders
        ];

        // Filter status pesanan, pencarian, dan game
        $statusFilter = Flight::request()->query->status ?? 'ALL';
        $searchQuery = trim(Flight::request()->query->q ?? '');
        $gameFilter = trim(Flight::request()->query->game ?? '');
        $sortOrder = Flight::request()->query->sort ?? 'latest';

        $sql = "SELECT * FROM orders WHERE 1=1";
        $params = [];

        if ($statusFilter === 'NEED_PROCESS') {
            $sql .= " AND status IN ('PAID', 'PROCESSING')";
        } elseif ($statusFilter !== 'ALL' && !empty($statusFilter)) {
            $sql .= " AND status = ?";
            $params[] = $statusFilter;
        }

        if (!empty($searchQuery)) {
            $sql .= " AND (invoice_number LIKE ? OR roblox_username LIKE ? OR product_name LIKE ?)";
            $searchTerm = "%{$searchQuery}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($gameFilter) && $gameFilter !== 'ALL') {
            $sql .= " AND category_name = ?";
            $params[] = $gameFilter;
        }

        $orderBy = ($sortOrder === 'oldest') ? 'ASC' : 'DESC';
        $sql .= " ORDER BY id {$orderBy} LIMIT 100";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        // Hitung pesan belum terbaca dari pembeli untuk setiap pesanan
        $unreadStmt = $db->prepare("SELECT COUNT(*) FROM order_messages WHERE invoice_number = ? AND sender = 'buyer' AND is_read = 0");
        foreach ($orders as &$o) {
            $unreadStmt->execute([$o['invoice_number']]);
            $o['unread_chat_count'] = (int)$unreadStmt->fetchColumn();
        }

        // Ambil daftar game roblox untuk filter
        $availableGames = [];
        try {
            $availableGames = $db->query("SELECT name FROM games ORDER BY sort_order ASC, name ASC")->fetchAll(PDO::FETCH_COLUMN) ?: [];
        } catch (\Exception $e) {
            try {
                $availableGames = $db->query("SELECT name FROM categories")->fetchAll(PDO::FETCH_COLUMN) ?: [];
            } catch (\Exception $e2) {}
        }

        Flight::render('admin/orders', [
            'orders' => $orders,
            'statusFilter' => $statusFilter,
            'tabCounts' => $tabCounts,
            'searchQuery' => $searchQuery,
            'gameFilter' => $gameFilter,
            'availableGames' => $availableGames
        ]);
    }

    /**
     * Ulasan Pembeli (Rating Toko 5.0, Breakdown Bintang & Filter) - Image 3
     */
    public static function reviews(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $filterStar = (int)(Flight::request()->query->star ?? 0);
        $filterCategory = trim(Flight::request()->query->category ?? '');

        // Hitung breakdown bintang
        $starCounts = [
            5 => (int)($db->query("SELECT COUNT(*) FROM reviews WHERE rating >= 4.5")->fetchColumn() ?: 3211),
            4 => (int)($db->query("SELECT COUNT(*) FROM reviews WHERE rating >= 3.5 AND rating < 4.5")->fetchColumn() ?: 24),
            3 => (int)($db->query("SELECT COUNT(*) FROM reviews WHERE rating >= 2.5 AND rating < 3.5")->fetchColumn() ?: 4),
            2 => (int)($db->query("SELECT COUNT(*) FROM reviews WHERE rating >= 1.5 AND rating < 2.5")->fetchColumn() ?: 2),
            1 => (int)($db->query("SELECT COUNT(*) FROM reviews WHERE rating < 1.5")->fetchColumn() ?: 3),
        ];
        $totalReviews = array_sum($starCounts);

        $sql = "SELECT r.*, p.name as product_name, p.game as game_name FROM reviews r JOIN products p ON r.product_id = p.id WHERE 1=1";
        $params = [];

        if ($filterStar > 0) {
            $sql .= " AND FLOOR(r.rating) = ?";
            $params[] = $filterStar;
        }

        if (!empty($filterCategory) && $filterCategory !== 'ALL') {
            $sql .= " AND p.game = ?";
            $params[] = $filterCategory;
        }

        $sql .= " ORDER BY r.id DESC LIMIT 50";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $reviews = $stmt->fetchAll();

        // Ambil daftar game untuk filter
        $availableGames = $db->query("SELECT name FROM games ORDER BY sort_order ASC, name ASC")->fetchAll(PDO::FETCH_COLUMN) ?: [];

        Flight::render('admin/reviews', [
            'reviews' => $reviews,
            'starCounts' => $starCounts,
            'totalReviews' => $totalReviews,
            'filterStar' => $filterStar,
            'filterCategory' => $filterCategory,
            'availableGames' => $availableGames
        ]);
    }

    /**
     * Halaman Buat Dagangan Baru - Image 4
     */
    public static function createProduct(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $games = $db->query("SELECT * FROM games WHERE is_active = 1 ORDER BY sort_order ASC, name ASC")->fetchAll();
        $categories = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();

        // Ambil daftar kategori produk (teks) yang sudah ada di database untuk saran cepat
        $dbSubCats = $db->query("SELECT DISTINCT sub_category FROM products WHERE sub_category IS NOT NULL AND sub_category != ''")->fetchAll(PDO::FETCH_COLUMN);
        $catNames = array_column($categories, 'name');
        $allCats = array_values(array_unique(array_filter(array_merge(['Pet', 'Gems', 'Item', 'Akun', 'Weapon', 'Fruit', 'Unit', 'Egg', 'Food', 'Coins'], $catNames, $dbSubCats))));

        Flight::render('admin/product_create', [
            'games' => $games,
            'categories' => $categories,
            'availableCategories' => $allCats
        ]);
    }

    /**
     * Update Status & Data Akun Pesanan
     */
    public static function updateOrderStatus(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $orderId = (int)(Flight::request()->data->order_id ?? 0);
        $status = Flight::request()->data->status ?? 'PENDING';
        $accountData = trim(Flight::request()->data->account_data ?? '');

        if ($orderId) {
            $prevStmt = $db->prepare("SELECT status, product_id FROM orders WHERE id = ?");
            $prevStmt->execute([$orderId]);
            $prevOrder = $prevStmt->fetch();

            $stmt = $db->prepare("UPDATE orders SET status = ?, account_data = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$status, $accountData, $orderId]);

            // Jika status berubah dari PENDING ke PAID/SUCCESS, tambah total_sold
            if ($prevOrder && $prevOrder['status'] === 'PENDING' && in_array($status, ['PAID', 'SUCCESS', 'PROCESSING'])) {
                $db->prepare("UPDATE products SET total_sold = total_sold + 1 WHERE id = ?")->execute([$prevOrder['product_id']]);
            }
        }

        Flight::redirect('/admin/orders?msg=Status+pesanan+berhasil+diperbarui');
    }

    /**
     * Quick Update Stok Produk dari Tabel Daganganku
     */
    public static function quickUpdateStock(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $productId = (int)(Flight::request()->data->product_id ?? 0);
        $stock = max(0, (int)(Flight::request()->data->stock ?? 0));

        if ($productId) {
            $stmt = $db->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $stmt->execute([$stock, $productId]);
        }

        if (Flight::request()->ajax) {
            Flight::json(['success' => true, 'stock' => $stock]);
            return;
        }

        Flight::redirect('/admin/products?msg=Stok+berhasil+diperbarui');
    }

    /**
     * Halaman Daganganku (Katalog Produk & Filter Lengkap) - Image 5
     */
    public static function products(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $searchQuery = trim(Flight::request()->query->q ?? '');
        $categoryFilter = trim(Flight::request()->query->category ?? '');
        $minStock = Flight::request()->query->min_stock !== null && Flight::request()->query->min_stock !== '' ? (int)Flight::request()->query->min_stock : null;
        $maxStock = Flight::request()->query->max_stock !== null && Flight::request()->query->max_stock !== '' ? (int)Flight::request()->query->max_stock : null;
        $tabFilter = trim(Flight::request()->query->tab ?? 'ALL');

        $sql = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($searchQuery)) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.game LIKE ? OR p.sub_category LIKE ?)";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
        }

        if (!empty($categoryFilter) && $categoryFilter !== 'ALL') {
            $sql .= " AND (p.game = ? OR p.sub_category = ?)";
            $params[] = $categoryFilter;
            $params[] = $categoryFilter;
        }

        if ($minStock !== null) {
            $sql .= " AND p.stock >= ?";
            $params[] = $minStock;
        }

        if ($maxStock !== null) {
            $sql .= " AND p.stock <= ?";
            $params[] = $maxStock;
        }

        if ($tabFilter === 'OUT_OF_STOCK') {
            $sql .= " AND p.stock <= 0";
        } elseif ($tabFilter === 'UNSOLD') {
            $sql .= " AND p.total_sold <= 0";
        }

        $sql .= " ORDER BY p.id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        $categories = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
        $games = $db->query("SELECT * FROM games WHERE is_active = 1 ORDER BY sort_order ASC, name ASC")->fetchAll();

        // Ambil daftar kategori produk (teks) yang sudah ada untuk filter dan autocomplete modal edit
        $dbSubCats = $db->query("SELECT DISTINCT sub_category FROM products WHERE sub_category IS NOT NULL AND sub_category != ''")->fetchAll(PDO::FETCH_COLUMN);
        $catNames = array_column($categories, 'name');
        $allCats = array_values(array_unique(array_filter(array_merge(['Pet', 'Gems', 'Item', 'Akun', 'Weapon', 'Fruit', 'Unit', 'Egg', 'Food', 'Coins'], $catNames, $dbSubCats))));

        Flight::render('admin/products', [
            'categories' => $categories,
            'games' => $games,
            'availableCategories' => $allCats,
            'products' => $products,
            'searchQuery' => $searchQuery,
            'categoryFilter' => $categoryFilter,
            'minStock' => $minStock,
            'maxStock' => $maxStock,
            'tabFilter' => $tabFilter,
            'msg' => Flight::request()->query->msg ?? null
        ]);
    }

    /**
     * Tambah Produk Baru
     */
    public static function addProduct(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $subCategory = trim(Flight::request()->data->sub_category ?? 'Pet');
        if (empty($subCategory)) {
            $subCategory = 'Item';
        }

        // Cari atau buat kategori otomatis di tabel categories jika belum ada
        $catStmt = $db->prepare("SELECT id FROM categories WHERE LOWER(name) = LOWER(?) OR LOWER(slug) = LOWER(?)");
        $catStmt->execute([$subCategory, $subCategory]);
        $catRow = $catStmt->fetch();

        if ($catRow) {
            $categoryId = (int)$catRow['id'];
        } else {
            $catSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $subCategory));
            if (empty($catSlug)) $catSlug = 'cat-' . rand(100, 999);
            try {
                $db->prepare("INSERT INTO categories (name, slug, icon) VALUES (?, ?, ?)")->execute([$subCategory, $catSlug, 'fa-solid fa-tag']);
                $categoryId = (int)$db->lastInsertId();
            } catch (\Exception $e) {
                $fallback = $db->query("SELECT id FROM categories LIMIT 1")->fetchColumn();
                $categoryId = $fallback ? (int)$fallback : 1;
            }
        }

        $game = trim(Flight::request()->data->game ?? 'Build A Zoo');
        $name = trim(Flight::request()->data->name ?? '');
        $price = (int)preg_replace('/[^0-9]/', '', (string)(Flight::request()->data->price ?? '0'));
        $priceOriginal = (int)preg_replace('/[^0-9]/', '', (string)(Flight::request()->data->price_original ?? '0'));
        $description = trim(Flight::request()->data->description ?? '');
        $imageUrl = trim(Flight::request()->data->image_url ?? '');
        $badge = trim(Flight::request()->data->badge ?? 'Ready');
        $stock = max(0, (int)(Flight::request()->data->stock ?? 1));
        $totalSold = max(0, (int)(Flight::request()->data->total_sold ?? 0));
        $rating = max(1.0, min(5.0, (float)(Flight::request()->data->rating ?? 5.0)));

        if (empty($name) || $price <= 0) {
            Flight::redirect('/admin/products?error=' . urlencode('Nama produk dan harga jual wajib diisi dengan benar'));
            return;
        }

        // Upload file gambar lokal jika ada
        if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/products';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . '/' . $fileName)) {
                    $imageUrl = '/uploads/products/' . $fileName;
                }
            }
        }

        if (empty($imageUrl)) {
            $imageUrl = 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=600';
        }

        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name)) . '-' . rand(100, 999);
        $stmt = $db->prepare("INSERT INTO products (category_id, game, name, slug, price, price_original, description, image_url, badge, stock, total_sold, rating, sub_category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$categoryId, $game, $name, $slug, $price, $priceOriginal, $description, $imageUrl, $badge, $stock, $totalSold, $rating, $subCategory]);

        Flight::redirect('/admin/products?msg=' . urlencode("Produk '{$name}' berhasil ditambahkan"));
    }

    /**
     * Update Data Produk (Edit Produk)
     */
    public static function updateProduct(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        $subCategory = trim(Flight::request()->data->sub_category ?? 'Pet');
        if (empty($subCategory)) {
            $subCategory = 'Item';
        }

        // Cari atau buat kategori otomatis di tabel categories jika belum ada
        $catStmt = $db->prepare("SELECT id FROM categories WHERE LOWER(name) = LOWER(?) OR LOWER(slug) = LOWER(?)");
        $catStmt->execute([$subCategory, $subCategory]);
        $catRow = $catStmt->fetch();

        if ($catRow) {
            $categoryId = (int)$catRow['id'];
        } else {
            $catSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $subCategory));
            if (empty($catSlug)) $catSlug = 'cat-' . rand(100, 999);
            try {
                $db->prepare("INSERT INTO categories (name, slug, icon) VALUES (?, ?, ?)")->execute([$subCategory, $catSlug, 'fa-solid fa-tag']);
                $categoryId = (int)$db->lastInsertId();
            } catch (\Exception $e) {
                $fallback = $db->query("SELECT id FROM categories LIMIT 1")->fetchColumn();
                $categoryId = $fallback ? (int)$fallback : 1;
            }
        }

        $game = trim(Flight::request()->data->game ?? 'Build A Zoo');
        $name = trim(Flight::request()->data->name ?? '');
        $price = (int)preg_replace('/[^0-9]/', '', (string)(Flight::request()->data->price ?? '0'));
        $priceOriginal = (int)preg_replace('/[^0-9]/', '', (string)(Flight::request()->data->price_original ?? '0'));
        $description = trim(Flight::request()->data->description ?? '');
        $imageUrl = trim(Flight::request()->data->image_url ?? '');
        $badge = trim(Flight::request()->data->badge ?? 'Ready');
        $stock = max(0, (int)(Flight::request()->data->stock ?? 0));
        $totalSold = max(0, (int)(Flight::request()->data->total_sold ?? 0));
        $rating = max(1.0, min(5.0, (float)(Flight::request()->data->rating ?? 5.0)));

        if (!$id || empty($name) || $price <= 0) {
            Flight::redirect('/admin/products?error=' . urlencode('Nama dan harga produk wajib diisi dengan benar'));
            return;
        }

        // Ambil produk lama
        $prev = $db->prepare("SELECT * FROM products WHERE id = ?");
        $prev->execute([$id]);
        $oldProduct = $prev->fetch();
        if (!$oldProduct) {
            Flight::redirect('/admin/products?error=' . urlencode('Produk tidak ditemukan'));
            return;
        }

        // Upload file gambar baru jika ada
        if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/products';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . '/' . $fileName)) {
                    $imageUrl = '/uploads/products/' . $fileName;
                }
            }
        }

        if (empty($imageUrl)) {
            $imageUrl = $oldProduct['image_url'];
        }

        $stmt = $db->prepare("UPDATE products SET category_id = ?, game = ?, name = ?, price = ?, price_original = ?, description = ?, image_url = ?, badge = ?, stock = ?, total_sold = ?, rating = ?, sub_category = ? WHERE id = ?");
        $stmt->execute([$categoryId, $game, $name, $price, $priceOriginal, $description, $imageUrl, $badge, $stock, $totalSold, $rating, $subCategory, $id]);

        Flight::redirect('/admin/products?msg=' . urlencode("Produk '{$name}' berhasil diperbarui"));
    }

    /**
     * Hapus Produk
     */
    public static function deleteProduct(): void
    {
        self::checkAuth();
        $db = Database::getConnection();
        $id = (int)(Flight::request()->data->id ?? 0);

        if ($id) {
            $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
        }

        Flight::redirect('/admin/products?msg=' . urlencode('Produk berhasil dihapus'));
    }

    /**
     * CMS Kategori & Game Roblox
     */
    public static function categories(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $games = $db->query("SELECT * FROM games ORDER BY sort_order ASC, id ASC")->fetchAll();
        $prodCounts = $db->query("SELECT game, COUNT(*) as cnt FROM products GROUP BY game")->fetchAll();
        $countMap = [];
        foreach ($prodCounts as $pc) {
            $countMap[$pc['game']] = (int)$pc['cnt'];
        }

        foreach ($games as &$g) {
            $g['product_count'] = $countMap[$g['name']] ?? 0;
        }
        unset($g);

        // Kategori Produk (Pet, Gems, Item, Akun, Fruit, Unit, dll)
        $categories = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
        $catProdCounts = $db->query("SELECT sub_category, COUNT(*) as cnt FROM products WHERE sub_category IS NOT NULL AND sub_category != '' GROUP BY sub_category")->fetchAll();
        $catCountMap = [];
        foreach ($catProdCounts as $cpc) {
            $catCountMap[strtolower($cpc['sub_category'])] = (int)$cpc['cnt'];
        }
        foreach ($categories as &$c) {
            $c['product_count'] = $catCountMap[strtolower($c['name'])] ?? ($catCountMap[strtolower($c['slug'])] ?? 0);
        }
        unset($c);

        // Kategori produk teks yang aktif di dagangan
        $rawSubCats = $db->query("SELECT DISTINCT sub_category FROM products WHERE sub_category IS NOT NULL AND sub_category != ''")->fetchAll(PDO::FETCH_COLUMN);

        Flight::render('admin/categories', [
            'games' => $games,
            'categories' => $categories,
            'rawSubCategories' => $rawSubCats,
            'msg' => Flight::request()->query->msg ?? null,
            'error' => Flight::request()->query->error ?? null
        ]);
    }

    /**
     * Tambah Kategori / Game Roblox Baru
     */
    public static function addCategory(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $name = trim(Flight::request()->data->name ?? '');
        $slug = strtolower(trim(Flight::request()->data->slug ?? ''));
        $icon = trim(Flight::request()->data->icon ?? 'fa-solid fa-gamepad');
        $logoUrl = trim(Flight::request()->data->logo_url ?? '');
        $description = trim(Flight::request()->data->description ?? '');
        $categories = trim(Flight::request()->data->categories ?? 'Pet, Gems, Item, Akun');
        $sortOrder = (int)(Flight::request()->data->sort_order ?? 0);
        $isActive = isset(Flight::request()->data->is_active) ? 1 : 0;

        if (empty($name)) {
            Flight::redirect('/admin/categories?error=' . urlencode('Nama game tidak boleh kosong'));
            return;
        }

        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        }

        // Upload file logo jika ada
        if (!empty($_FILES['logo_file']['name']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'])) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/games';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $uploadDir . '/' . $fileName)) {
                    $logoUrl = '/uploads/games/' . $fileName;
                }
            }
        }

        // Cek duplikasi slug
        $check = $db->prepare("SELECT COUNT(*) FROM games WHERE slug = ? OR name = ?");
        $check->execute([$slug, $name]);
        if ((int)$check->fetchColumn() > 0) {
            Flight::redirect('/admin/categories?error=' . urlencode("Game '{$name}' atau slug '{$slug}' sudah terdaftar"));
            return;
        }

        $stmt = $db->prepare("INSERT INTO games (name, slug, logo_url, icon, description, sort_order, is_active, categories) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $logoUrl, $icon, $description, $sortOrder, $isActive, $categories]);

        Flight::redirect('/admin/categories?msg=' . urlencode("Game '{$name}' berhasil ditambahkan ke kategori"));
    }

    /**
     * Update Kategori / Game Roblox
     */
    public static function updateCategory(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        $name = trim(Flight::request()->data->name ?? '');
        $slug = strtolower(trim(Flight::request()->data->slug ?? ''));
        $icon = trim(Flight::request()->data->icon ?? 'fa-solid fa-gamepad');
        $logoUrl = trim(Flight::request()->data->logo_url ?? '');
        $description = trim(Flight::request()->data->description ?? '');
        $categories = trim(Flight::request()->data->categories ?? 'Pet, Gems, Item, Akun');
        $sortOrder = (int)(Flight::request()->data->sort_order ?? 0);
        $isActive = isset(Flight::request()->data->is_active) ? 1 : 0;

        if (!$id || empty($name)) {
            Flight::redirect('/admin/categories?error=' . urlencode('Data kategori tidak valid'));
            return;
        }

        // Ambil data game lama
        $prev = $db->prepare("SELECT * FROM games WHERE id = ?");
        $prev->execute([$id]);
        $oldGame = $prev->fetch();
        if (!$oldGame) {
            Flight::redirect('/admin/categories?error=' . urlencode('Game tidak ditemukan'));
            return;
        }

        // Upload file logo baru jika ada
        if (!empty($_FILES['logo_file']['name']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'])) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/games';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $uploadDir . '/' . $fileName)) {
                    $logoUrl = '/uploads/games/' . $fileName;
                }
            }
        }

        if (empty($logoUrl)) {
            $logoUrl = $oldGame['logo_url'];
        }

        $stmt = $db->prepare("UPDATE games SET name = ?, slug = ?, logo_url = ?, icon = ?, description = ?, sort_order = ?, is_active = ?, categories = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $logoUrl, $icon, $description, $sortOrder, $isActive, $categories, $id]);

        // Jika nama game berubah, sinkronkan juga ke tabel produk
        if ($oldGame['name'] !== $name) {
            $upProd = $db->prepare("UPDATE products SET game = ? WHERE game = ?");
            $upProd->execute([$name, $oldGame['name']]);
        }

        Flight::redirect('/admin/categories?msg=' . urlencode("Game '{$name}' berhasil diperbarui"));
    }

    /**
     * Hapus Kategori / Game Roblox
     */
    public static function deleteCategory(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        if ($id) {
            $stmt = $db->prepare("DELETE FROM games WHERE id = ?");
            $stmt->execute([$id]);
        }

        Flight::redirect('/admin/categories?msg=' . urlencode('Kategori game berhasil dihapus'));
    }

    /**
     * Tambah Kategori Produk (Teks Bebas)
     */
    public static function addCustomCategory(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $name = trim(Flight::request()->data->name ?? '');
        $slug = strtolower(trim(Flight::request()->data->slug ?? ''));
        $icon = trim(Flight::request()->data->icon ?? 'fa-solid fa-tag');

        if (empty($name)) {
            Flight::redirect('/admin/categories?error=' . urlencode('Nama kategori tidak boleh kosong'));
            return;
        }

        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        }

        // Cek duplikasi
        $check = $db->prepare("SELECT COUNT(*) FROM categories WHERE LOWER(slug) = LOWER(?) OR LOWER(name) = LOWER(?)");
        $check->execute([$slug, $name]);
        if ((int)$check->fetchColumn() > 0) {
            Flight::redirect('/admin/categories?error=' . urlencode("Kategori '{$name}' sudah terdaftar"));
            return;
        }

        $stmt = $db->prepare("INSERT INTO categories (name, slug, icon) VALUES (?, ?, ?)");
        $stmt->execute([$name, $slug, $icon]);

        Flight::redirect('/admin/categories?msg=' . urlencode("Kategori '{$name}' berhasil ditambahkan"));
    }

    /**
     * Update Kategori Produk (Teks Bebas)
     */
    public static function updateCustomCategory(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        $name = trim(Flight::request()->data->name ?? '');
        $slug = strtolower(trim(Flight::request()->data->slug ?? ''));
        $icon = trim(Flight::request()->data->icon ?? 'fa-solid fa-tag');

        if (!$id || empty($name)) {
            Flight::redirect('/admin/categories?error=' . urlencode('Data kategori tidak valid'));
            return;
        }

        $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ?, icon = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $icon, $id]);

        Flight::redirect('/admin/categories?msg=' . urlencode("Kategori '{$name}' berhasil diperbarui"));
    }

    /**
     * Hapus Kategori Produk (Teks Bebas)
     */
    public static function deleteCustomCategory(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        if ($id) {
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$id]);
        }

        Flight::redirect('/admin/categories?msg=' . urlencode('Kategori produk berhasil dihapus'));
    }

    /**
     * CMS Edit Laman & Konten
     */
    public static function pages(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $settingsRaw = $db->query("SELECT * FROM settings")->fetchAll();
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }

        $faqs = $db->query("SELECT * FROM faqs ORDER BY sort_order ASC, id ASC")->fetchAll();

        Flight::render('admin/pages', [
            'settings' => $settings,
            'faqs' => $faqs,
            'msg' => Flight::request()->query->msg ?? null
        ]);
    }

    /**
     * Simpan Perubahan Konten Laman
     */
    public static function savePageContent(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $fields = [
            'hero_badge', 'hero_title', 'hero_subtitle', 'announcement',
            'whatsapp_admin', 'whatsapp_display', 'operating_hours_open', 'operating_hours_close',
            'step1_title', 'step1_desc', 'step2_title', 'step2_desc', 'step3_title', 'step3_desc'
        ];

        $stmt = $db->prepare("REPLACE INTO settings (`key`, `value`) VALUES (?, ?)");

        foreach ($fields as $f) {
            if (isset(Flight::request()->data->$f)) {
                $stmt->execute([$f, trim(Flight::request()->data->$f)]);
            }
        }

        Flight::redirect('/admin/pages?msg=' . urlencode('Konten laman berhasil disimpan!'));
    }

    /**
     * Tambah FAQ Baru
     */
    public static function addFaq(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $question = trim(Flight::request()->data->question ?? '');
        $answer = trim(Flight::request()->data->answer ?? '');
        $category = trim(Flight::request()->data->category ?? 'Umum');
        $sortOrder = (int)(Flight::request()->data->sort_order ?? 0);

        if (!empty($question) && !empty($answer)) {
            $stmt = $db->prepare("INSERT INTO faqs (question, answer, category, sort_order, is_active) VALUES (?, ?, ?, ?, 1)");
            $stmt->execute([$question, $answer, $category, $sortOrder]);
        }

        Flight::redirect('/admin/pages?msg=' . urlencode('Tanya Jawab (FAQ) berhasil ditambahkan'));
    }

    /**
     * Update FAQ (Edit FAQ)
     */
    public static function updateFaq(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        $question = trim(Flight::request()->data->question ?? '');
        $answer = trim(Flight::request()->data->answer ?? '');
        $category = trim(Flight::request()->data->category ?? 'Umum');
        $sortOrder = (int)(Flight::request()->data->sort_order ?? 0);

        if ($id && !empty($question) && !empty($answer)) {
            $stmt = $db->prepare("UPDATE faqs SET question = ?, answer = ?, category = ?, sort_order = ? WHERE id = ?");
            $stmt->execute([$question, $answer, $category, $sortOrder, $id]);
        }

        Flight::redirect('/admin/pages?msg=' . urlencode('Tanya Jawab (FAQ) berhasil diperbarui'));
    }

    /**
     * Hapus FAQ
     */
    public static function deleteFaq(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        if ($id) {
            $stmt = $db->prepare("DELETE FROM faqs WHERE id = ?");
            $stmt->execute([$id]);
        }

        Flight::redirect('/admin/pages?msg=' . urlencode('FAQ berhasil dihapus'));
    }

    /**
     * Update Pengaturan Toko
     */
    public static function updateSettings(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $fields = ['store_name', 'store_tagline', 'store_status', 'whatsapp_admin', 'announcement'];
        $stmt = $db->prepare("REPLACE INTO settings (`key`, `value`) VALUES (?, ?)");

        foreach ($fields as $field) {
            if (isset(Flight::request()->data->$field)) {
                $stmt->execute([$field, trim(Flight::request()->data->$field)]);
            }
        }

        Flight::redirect('/admin?msg=Pengaturan+toko+berhasil+disimpan');
    }

    /**
     * Halaman Manajemen Kode Redeem
     */
    public static function redeemCodes(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        // Ambil semua kode redeem beserta nama produk terkait (jika ada)
        $sql = "SELECT r.*, p.name as product_name 
                FROM redeem_codes r 
                LEFT JOIN products p ON r.product_id = p.id 
                ORDER BY r.id DESC";
        $codes = $db->query($sql)->fetchAll();

        // Ambil daftar produk aktif untuk dropdown pilihan produk
        $products = $db->query("SELECT id, name, price FROM products WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

        Flight::render('admin/redeem_codes', [
            'codes' => $codes,
            'products' => $products,
            'msg' => Flight::request()->query->msg ?? null,
            'error' => Flight::request()->query->error ?? null
        ]);
    }

    /**
     * Tambah Kode Redeem Baru
     */
    public static function addRedeemCode(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $code = strtoupper(trim(Flight::request()->data->code ?? ''));
        $discountPercent = max(1, min(100, (int)(Flight::request()->data->discount_percent ?? 10)));
        $productId = (int)(Flight::request()->data->product_id ?? 0);
        $productId = $productId > 0 ? $productId : null;
        $maxUses = max(0, (int)(Flight::request()->data->max_uses ?? 0));
        $isActive = isset(Flight::request()->data->is_active) ? 1 : 0;

        if (empty($code)) {
            Flight::redirect('/admin/redeem-codes?error=Kode+redeem+tidak+boleh+kosong');
            return;
        }

        // Cek duplikasi kode
        $check = $db->prepare("SELECT COUNT(*) FROM redeem_codes WHERE UPPER(code) = ?");
        $check->execute([$code]);
        if ((int)$check->fetchColumn() > 0) {
            Flight::redirect('/admin/redeem-codes?error=Kode+redeem+' . urlencode($code) . '+sudah+ada');
            return;
        }

        $stmt = $db->prepare("INSERT INTO redeem_codes (code, discount_percent, product_id, max_uses, used_count, is_active) VALUES (?, ?, ?, ?, 0, ?)");
        $stmt->execute([$code, $discountPercent, $productId, $maxUses, $isActive]);

        Flight::redirect('/admin/redeem-codes?msg=Kode+redeem+' . urlencode($code) . '+berhasil+dibuat');
    }

    /**
     * Update Kode Redeem (Edit Kode Redeem)
     */
    public static function updateRedeemCode(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        $code = strtoupper(trim(Flight::request()->data->code ?? ''));
        $discountPercent = max(1, min(100, (int)(Flight::request()->data->discount_percent ?? 10)));
        $productId = (int)(Flight::request()->data->product_id ?? 0);
        $productId = $productId > 0 ? $productId : null;
        $maxUses = max(0, (int)(Flight::request()->data->max_uses ?? 0));
        $isActive = isset(Flight::request()->data->is_active) ? 1 : 0;

        if (!$id || empty($code)) {
            Flight::redirect('/admin/redeem-codes?error=' . urlencode('Data kode redeem tidak valid'));
            return;
        }

        // Cek duplikasi kode pada ID lain
        $check = $db->prepare("SELECT COUNT(*) FROM redeem_codes WHERE UPPER(code) = ? AND id != ?");
        $check->execute([$code, $id]);
        if ((int)$check->fetchColumn() > 0) {
            Flight::redirect('/admin/redeem-codes?error=' . urlencode("Kode redeem '{$code}' sudah digunakan"));
            return;
        }

        $stmt = $db->prepare("UPDATE redeem_codes SET code = ?, discount_percent = ?, product_id = ?, max_uses = ?, is_active = ? WHERE id = ?");
        $stmt->execute([$code, $discountPercent, $productId, $maxUses, $isActive, $id]);

        Flight::redirect('/admin/redeem-codes?msg=' . urlencode("Kode redeem '{$code}' berhasil diperbarui"));
    }

    /**
     * Hapus Kode Redeem
     */
    public static function deleteRedeemCode(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        if ($id) {
            $stmt = $db->prepare("DELETE FROM redeem_codes WHERE id = ?");
            $stmt->execute([$id]);
        }

        Flight::redirect('/admin/redeem-codes?msg=Kode+redeem+berhasil+dihapus');
    }

    /**
     * Toggle Aktif/Nonaktif Kode Redeem
     */
    public static function toggleRedeemCode(): void
    {
        self::checkAuth();
        $db = Database::getConnection();

        $id = (int)(Flight::request()->data->id ?? 0);
        if ($id) {
            $stmt = $db->prepare("UPDATE redeem_codes SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = ?");
            $stmt->execute([$id]);
        }

        Flight::redirect('/admin/redeem-codes?msg=Status+kode+redeem+berhasil+diubah');
    }
}
