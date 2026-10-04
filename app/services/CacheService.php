<?php

namespace App\Services;

use App\Config\Database;
use PDO;

class CacheService
{
    /**
     * In-memory cache for ultra-fast same-request lookups
     * @var array
     */
    private static array $memoryCache = [];

    /**
     * Get base directory for file-based cache
     */
    public static function getCacheDir(): string
    {
        $dir = dirname(__DIR__, 2) . '/storage/cache';
        
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        // Fallback to system temp directory if storage/cache is not writable
        if (!is_writable($dir)) {
            $dir = sys_get_temp_dir() . '/itempedia_cache';
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
        }

        return rtrim($dir, '/\\');
    }

    /**
     * Get path for a specific cache key
     */
    private static function getFilePath(string $key): string
    {
        $safeKey = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $key);
        $hash = md5($key);
        return self::getCacheDir() . '/' . $safeKey . '_' . substr($hash, 0, 8) . '.cache.json';
    }

    /**
     * Retrieve an item from the cache
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // 1. Check in-memory first
        if (array_key_exists($key, self::$memoryCache)) {
            return self::$memoryCache[$key];
        }

        // 2. Check file cache
        $path = self::getFilePath($key);
        if (!file_exists($path)) {
            return $default;
        }

        $content = @file_get_contents($path);
        if ($content === false) {
            return $default;
        }

        $data = @json_decode($content, true);
        if (!is_array($data) || !isset($data['expires_at'])) {
            @unlink($path);
            return $default;
        }

        // Check expiration (0 means forever)
        if ($data['expires_at'] > 0 && $data['expires_at'] < time()) {
            @unlink($path);
            return $default;
        }

        $value = $data['payload'] ?? $default;
        self::$memoryCache[$key] = $value;
        return $value;
    }

    /**
     * Store an item in the cache
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl Seconds until expiration (default: 3600 = 1 hour, 0 = forever)
     * @return bool
     */
    public static function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        self::$memoryCache[$key] = $value;

        $path = self::getFilePath($key);
        $data = [
            'key' => $key,
            'created_at' => time(),
            'expires_at' => $ttl > 0 ? time() + $ttl : 0,
            'ttl' => $ttl,
            'payload' => $value
        ];

        $json = @json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        return @file_put_contents($path, $json, LOCK_EX) !== false;
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param string $key
     * @param int $ttl
     * @param callable $callback
     * @return mixed
     */
    public static function remember(string $key, int $ttl, callable $callback): mixed
    {
        $val = self::get($key);
        if ($val !== null) {
            return $val;
        }

        $val = $callback();
        self::set($key, $val, $ttl);
        return $val;
    }

    /**
     * Check if an item exists in the cache
     */
    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    /**
     * Remove an item from the cache
     */
    public static function forget(string $key): bool
    {
        unset(self::$memoryCache[$key]);
        $path = self::getFilePath($key);
        if (file_exists($path)) {
            return @unlink($path);
        }
        return true;
    }

    /**
     * Remove all items from the cache
     */
    public static function clear(): bool
    {
        self::$memoryCache = [];
        $dir = self::getCacheDir();
        $files = @glob($dir . '/*.cache.json');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
        return true;
    }

    /**
     * Get statistics of the cache system
     */
    public static function getStats(): array
    {
        $dir = self::getCacheDir();
        $files = @glob($dir . '/*.cache.json') ?: [];
        $totalBytes = 0;
        $activeKeys = [];
        $latestTime = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                $size = filesize($file) ?: 0;
                $totalBytes += $size;
                $mtime = filemtime($file) ?: 0;
                if ($mtime > $latestTime) {
                    $latestTime = $mtime;
                }

                $content = @file_get_contents($file);
                if ($content) {
                    $json = @json_decode($content, true);
                    if (!empty($json['key'])) {
                        $activeKeys[] = [
                            'key' => $json['key'],
                            'size' => self::formatBytes($size),
                            'created_at' => date('d M Y H:i:s', $json['created_at'] ?? $mtime),
                            'expires_at' => !empty($json['expires_at']) ? date('d M Y H:i:s', $json['expires_at']) : 'Permanen'
                        ];
                    }
                }
            }
        }

        return [
            'total_files' => count($files),
            'total_size_bytes' => $totalBytes,
            'total_size_formatted' => self::formatBytes($totalBytes),
            'last_saved_time' => $latestTime > 0 ? date('d M Y H:i:s', $latestTime) : 'Belum ada cache',
            'storage_path' => $dir,
            'is_writable' => is_writable($dir),
            'keys' => $activeKeys
        ];
    }

    /**
     * Pre-warm and rebuild all key application caches from database
     *
     * @return array
     */
    public static function rebuildAll(): array
    {
        $startTime = microtime(true);
        $db = Database::getConnection();

        // 1. Cache Categories
        $categories = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        self::set('catalog_categories', $categories, 86400);

        // 2. Cache Games
        $dbGames = $db->query("SELECT * FROM games WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
        self::set('catalog_games', $dbGames, 86400);

        // 3. Cache Active Products
        $products = $db->query("SELECT p.*, c.name as category_name, c.slug as category_slug 
                                FROM products p 
                                JOIN categories c ON p.category_id = c.id 
                                WHERE p.is_active = 1 
                                ORDER BY p.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        self::set('catalog_products_all', $products, 86400);

        // 4. Cache Reviews
        $reviews = $db->query("SELECT r.*, p.name as product_name, p.game 
                               FROM reviews r 
                               JOIN products p ON r.product_id = p.id 
                               ORDER BY r.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        self::set('catalog_reviews_all', $reviews, 86400);

        // 5. Cache Settings
        $settingsRaw = $db->query("SELECT * FROM settings")->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }
        self::set('site_settings', $settings, 86400);

        // 6. Cache FAQs
        $faqs = $db->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
        self::set('site_faqs', $faqs, 86400);

        // 7. Cache Build A Zoo Subcategory Counts
        $allBazProds = $db->query("SELECT sub_category, category_id FROM products WHERE game = 'Build A Zoo' AND is_active = 1")->fetchAll(PDO::FETCH_ASSOC);
        $bazCounts = [
            'semua' => count($allBazProds),
            'pet' => 0,
            'egg' => 0,
            'food' => 0,
            'item' => 0,
            'akun' => 0,
        ];
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
        self::set('baz_subcategory_counts', $bazCounts, 86400);

        // 8. Cache Live Purchases Feed
        $rawOrders = $db->query("SELECT o.id, o.invoice_number, o.product_name, o.price, o.roblox_username, o.roblox_avatar_url, o.status, o.created_at, p.image_url as product_image, p.game 
                                FROM orders o 
                                LEFT JOIN products p ON o.product_id = p.id 
                                ORDER BY o.id DESC 
                                LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);
        self::set('live_purchases_feed', $rawOrders, 3600);

        $elapsedMs = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'success' => true,
            'time_ms' => $elapsedMs,
            'stats' => self::getStats()
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2, ',', '.') . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2, ',', '.') . ' KB';
        }
        return $bytes . ' B';
    }
}
