<?php

namespace App\Controllers;

use Flight;
use App\Config\Database;
use PDO;

class OrderController
{
    /**
     * Endpoint API Proxy untuk mencari Roblox Avatar berdasarkan username
     */
    public static function checkRoblox(): void
    {
        $username = trim(Flight::request()->query->username ?? '');

        if (empty($username)) {
            Flight::json(['success' => false, 'message' => 'Username tidak boleh kosong'], 400);
            return;
        }

        // 1. Dapatkan User ID dari Roblox Username
        $userSearchUrl = "https://users.roblox.com/v1/usernames/users";
        $payload = json_encode([
            "usernames" => [$username],
            "excludeBannedUsers" => false
        ]);

        $ch = curl_init($userSearchUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Jika cURL error atau timeout, buatkan avatar UI cantik berinisial
        if ($httpCode !== 200 || empty($response)) {
            $fallbackAvatar = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=06b6d4&color=fff&bold=true&size=150";
            Flight::json([
                'success' => true,
                'isFallback' => true,
                'userId' => 0,
                'username' => $username,
                'displayName' => $username,
                'avatarUrl' => $fallbackAvatar
            ]);
            return;
        }

        $userData = json_decode($response, true);
        if (empty($userData['data'])) {
            Flight::json([
                'success' => false, 
                'message' => "Username Roblox '{$username}' tidak ditemukan. Pastikan ejaan username benar!"
            ], 404);
            return;
        }

        $user = $userData['data'][0];
        $userId = $user['id'];
        $exactName = $user['name'] ?? $username;
        $displayName = $user['displayName'] ?? $exactName;

        // 2. Dapatkan Foto Avatar Headshot dari User ID
        $thumbUrl = "https://thumbnails.roblox.com/v1/users/avatar-headshot?userIds={$userId}&size=150x150&format=Png&isCircular=false";
        $chThumb = curl_init($thumbUrl);
        curl_setopt_array($chThumb, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $thumbResp = curl_exec($chThumb);
        curl_close($chThumb);

        $thumbData = json_decode($thumbResp, true);
        $avatarUrl = $thumbData['data'][0]['imageUrl'] ?? null;

        // Jika thumbnail gagal di-generate oleh Roblox, pakai fallback avatar yang valid
        if (empty($avatarUrl)) {
            $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($exactName) . "&background=06b6d4&color=fff&bold=true&size=150";
        }

        Flight::json([
            'success' => true,
            'userId' => $userId,
            'username' => $exactName,
            'displayName' => $displayName,
            'avatarUrl' => $avatarUrl
        ]);
    }

    /**
     * Endpoint API untuk mengecek dan menghitung diskon kode redeem (khusus 1 produk)
     */
    public static function checkRedeemCode(): void
    {
        $code = strtoupper(trim(Flight::request()->data->code ?? Flight::request()->query->code ?? ''));
        $productId = (int)(Flight::request()->data->product_id ?? Flight::request()->query->product_id ?? 0);
        $quantity = max(1, (int)(Flight::request()->data->quantity ?? Flight::request()->query->quantity ?? 1));

        if (empty($code)) {
            Flight::json(['success' => false, 'message' => 'Silakan masukkan kode redeem'], 400);
            return;
        }

        $db = Database::getConnection();

        // Ambil produk
        $prodStmt = $db->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
        $prodStmt->execute([$productId]);
        $product = $prodStmt->fetch();

        if (!$product) {
            Flight::json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
            return;
        }

        // Ambil data kode redeem
        $codeStmt = $db->prepare("SELECT * FROM redeem_codes WHERE UPPER(code) = ?");
        $codeStmt->execute([$code]);
        $redeem = $codeStmt->fetch();

        if (!$redeem) {
            Flight::json(['success' => false, 'message' => "Kode redeem '{$code}' tidak ditemukan atau salah ketik"], 404);
            return;
        }

        if (empty($redeem['is_active'])) {
            Flight::json(['success' => false, 'message' => "Kode redeem '{$code}' saat ini sedang tidak aktif"], 400);
            return;
        }

        if ((int)$redeem['max_uses'] > 0 && (int)$redeem['used_count'] >= (int)$redeem['max_uses']) {
            Flight::json(['success' => false, 'message' => "Kuota penggunaan kode redeem '{$code}' sudah habis"], 400);
            return;
        }

        // Cek apakah kode redeem ini dibatasi untuk 1 produk tertentu
        if (!empty($redeem['product_id']) && (int)$redeem['product_id'] !== $productId) {
            $allowedProdStmt = $db->prepare("SELECT name FROM products WHERE id = ?");
            $allowedProdStmt->execute([$redeem['product_id']]);
            $allowedProdName = $allowedProdStmt->fetchColumn() ?: 'produk tertentu';
            Flight::json(['success' => false, 'message' => "Kode redeem ini khusus hanya berlaku untuk produk '{$allowedProdName}'"], 400);
            return;
        }

        // Hitung diskon: "cuman berlaku 1 produk aja"
        // Diskon persen dihitung dari harga 1 satuan produk (unit price)
        $unitPrice = (int)$product['price'];
        $discountPercent = (int)$redeem['discount_percent'];
        $discountAmount = (int)round($unitPrice * ($discountPercent / 100));
        
        $subtotal = $unitPrice * $quantity;
        $finalPrice = max(0, $subtotal - $discountAmount);

        Flight::json([
            'success' => true,
            'code' => $redeem['code'],
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'final_price' => $finalPrice,
            'formatted_discount' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
            'formatted_subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            'formatted_final' => 'Rp ' . number_format($finalPrice, 0, ',', '.'),
            'message' => "Diskon {$discountPercent}% berhasil diterapkan untuk 1 produk (-Rp " . number_format($discountAmount, 0, ',', '.') . ")"
        ]);
    }

    /**
     * Endpoint API untuk mengecek ketersediaan & subtotal produk di keranjang
     */
    public static function checkCart(): void
    {
        $items = Flight::request()->data->items ?? Flight::request()->query->items ?? [];
        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }
        if (empty($items)) {
            Flight::json(['success' => true, 'items' => [], 'subtotal' => 0, 'formatted_subtotal' => 'Rp 0']);
            return;
        }

        $db = Database::getConnection();
        $validated = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $productId = (int)($item['id'] ?? 0);
            $qty = max(1, (int)($item['qty'] ?? 1));
            if ($productId <= 0) continue;

            $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.is_active = 1");
            $stmt->execute([$productId]);
            $prod = $stmt->fetch();

            if ($prod) {
                $effectiveQty = min($qty, max(0, (int)$prod['stock']));
                $itemTotal = (int)$prod['price'] * $effectiveQty;
                $subtotal += $itemTotal;
                $validated[] = [
                    'id' => (int)$prod['id'],
                    'name' => $prod['name'],
                    'game' => $prod['game'] ?? 'Blox Fruits',
                    'price' => (int)$prod['price'],
                    'original_price' => (int)$prod['price_original'],
                    'image_url' => $prod['image_url'],
                    'stock' => (int)$prod['stock'],
                    'sub_category' => $prod['sub_category'] ?? 'Pet',
                    'category_name' => $prod['category_name'],
                    'qty' => $effectiveQty,
                    'item_total' => $itemTotal,
                    'formatted_price' => 'Rp ' . number_format($prod['price'], 0, ',', '.'),
                    'formatted_total' => 'Rp ' . number_format($itemTotal, 0, ',', '.'),
                    'is_out_of_stock' => ((int)$prod['stock'] <= 0)
                ];
            }
        }

        Flight::json([
            'success' => true,
            'items' => $validated,
            'subtotal' => $subtotal,
            'formatted_subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.')
        ]);
    }

    /**
     * Membuat Pesanan Baru (Mendukung Single Product & Multi-Item Keranjang)
     */
    public static function createOrder(): void
    {
        $db = Database::getConnection();

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || 
                  (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
                  (!empty(Flight::request()->data->is_ajax));

        $productId = (int)(Flight::request()->data->product_id ?? 0);
        $robloxUsername = trim(Flight::request()->data->roblox_username ?? '');
        $robloxAvatar = trim(Flight::request()->data->roblox_avatar ?? '');
        $whatsapp = trim(Flight::request()->data->whatsapp ?? '-');
        if (empty($whatsapp)) {
            $whatsapp = '-';
        }
        $quantity = max(1, (int)(Flight::request()->data->quantity ?? 1));
        $note = trim(Flight::request()->data->note ?? '');
        $redeemCodeInput = strtoupper(trim(Flight::request()->data->redeem_code ?? ''));

        if (empty($robloxUsername)) {
            if ($isAjax) {
                Flight::json(['success' => false, 'message' => 'Mohon masukkan username Roblox kamu'], 400);
                return;
            }
            Flight::redirect('/?error=Mohon+masukkan+username+Roblox+kamu');
            return;
        }

        // Cek apakah Checkout dari Keranjang (Multi-Item)
        $cartItemsRaw = Flight::request()->data->cart_items ?? null;
        $cartItems = [];
        if (!empty($cartItemsRaw)) {
            if (is_string($cartItemsRaw)) {
                $cartItems = json_decode($cartItemsRaw, true) ?: [];
            } elseif (is_array($cartItemsRaw)) {
                $cartItems = $cartItemsRaw;
            }
        }

        if (!empty($cartItems) && is_array($cartItems)) {
            $validatedItems = [];
            $subtotalPrice = 0;
            $productNames = [];
            $firstCategory = 'Marketplace';
            $firstProductId = 0;

            foreach ($cartItems as $cItem) {
                $pId = (int)($cItem['id'] ?? 0);
                $qty = max(1, (int)($cItem['qty'] ?? 1));
                if ($pId <= 0) continue;

                $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.is_active = 1");
                $stmt->execute([$pId]);
                $p = $stmt->fetch();

                if (!$p || $p['stock'] <= 0) {
                    $errName = $p['name'] ?? 'Item';
                    if ($isAjax) {
                        Flight::json(['success' => false, 'message' => "Stok produk '{$errName}' sedang habis."], 400);
                        return;
                    }
                    Flight::redirect('/?error=' . urlencode("Stok produk '{$errName}' sedang habis."));
                    return;
                }

                if ($qty > $p['stock']) {
                    $qty = (int)$p['stock'];
                }

                if ($firstProductId === 0) {
                    $firstProductId = $p['id'];
                    $firstCategory = $p['category_name'];
                }

                $itemSubtotal = (int)$p['price'] * $qty;
                $subtotalPrice += $itemSubtotal;
                $productNames[] = $p['name'] . ($qty > 1 ? " ({$qty}x)" : "");

                $validatedItems[] = [
                    'id' => (int)$p['id'],
                    'name' => $p['name'],
                    'game' => $p['game'] ?? 'Blox Fruits',
                    'price' => (int)$p['price'],
                    'image_url' => $p['image_url'],
                    'qty' => $qty,
                    'subtotal' => $itemSubtotal,
                    'category_name' => $p['category_name'],
                    'sub_category' => $p['sub_category'] ?? 'Item'
                ];
            }

            if (empty($validatedItems)) {
                if ($isAjax) {
                    Flight::json(['success' => false, 'message' => 'Keranjang belanjamu masih kosong.'], 400);
                    return;
                }
                Flight::redirect('/?error=Keranjang+kosong');
                return;
            }

            // Validasi & Hitung Diskon Kode Redeem (Berlaku untuk 1 Item Pertama)
            $discountAmount = 0;
            $appliedCode = null;
            if (!empty($redeemCodeInput)) {
                $codeStmt = $db->prepare("SELECT * FROM redeem_codes WHERE UPPER(code) = ? AND is_active = 1");
                $codeStmt->execute([$redeemCodeInput]);
                $redeem = $codeStmt->fetch();

                if ($redeem) {
                    $canUse = true;
                    if ((int)$redeem['max_uses'] > 0 && (int)$redeem['used_count'] >= (int)$redeem['max_uses']) {
                        $canUse = false;
                    }
                    if ($canUse) {
                        $appliedCode = $redeem['code'];
                        $discountPercent = (int)$redeem['discount_percent'];
                        $discountAmount = (int)round((int)$validatedItems[0]['price'] * ($discountPercent / 100));
                        $db->prepare("UPDATE redeem_codes SET used_count = used_count + 1 WHERE id = ?")->execute([$redeem['id']]);
                    }
                }
            }

            $totalPrice = max(0, $subtotalPrice - $discountAmount);
            $productDisplayName = implode(', ', $productNames);
            if (mb_strlen($productDisplayName) > 250) {
                $productDisplayName = count($validatedItems) . ' Item (' . $validatedItems[0]['name'] . ' + ' . (count($validatedItems) - 1) . ' lainnya)';
            }

            $invoiceNumber = 'ITP-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));

            if (empty($robloxAvatar)) {
                $robloxAvatar = "https://ui-avatars.com/api/?name=" . urlencode($robloxUsername) . "&background=06b6d4&color=fff&bold=true&size=150";
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $buyerEmail = $_SESSION['buyer_user']['email'] ?? null;
            $itemsJson = json_encode($validatedItems);

            $insertStmt = $db->prepare("INSERT INTO orders 
                (invoice_number, product_id, product_name, category_name, price, roblox_username, roblox_avatar_url, whatsapp, note, status, payment_method, buyer_email, redeem_code, discount_amount, items_json) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', 'QRIS', ?, ?, ?, ?)");

            $insertStmt->execute([
                $invoiceNumber,
                $firstProductId,
                $productDisplayName,
                count($validatedItems) > 1 ? 'Keranjang (' . count($validatedItems) . ' Item)' : $firstCategory,
                $totalPrice,
                $robloxUsername,
                $robloxAvatar,
                $whatsapp,
                $note,
                $buyerEmail,
                $appliedCode,
                $discountAmount,
                $itemsJson
            ]);

            // Kurangi stok masing-masing produk
            foreach ($validatedItems as $vItem) {
                $db->prepare("UPDATE products SET stock = CASE WHEN stock >= ? THEN stock - ? ELSE 0 END WHERE id = ?")->execute([$vItem['qty'], $vItem['qty'], $vItem['id']]);
            }

            // Pesan chat awal
            try {
                if (!empty($note)) {
                    $msgStmt = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, 'buyer', ?, ?, 0, CURRENT_TIMESTAMP)");
                    $msgStmt->execute([$invoiceNumber, $robloxUsername, $note]);
                }

                $itemsLines = [];
                foreach ($validatedItems as $vItem) {
                    $itemsLines[] = "• " . $vItem['name'] . " (" . $vItem['qty'] . "x)";
                }
                $welcomeText = "Halo kak " . $robloxUsername . "! 👋 Pesanan Keranjangmu (" . count($validatedItems) . " item) telah diterima di sistem ItemPedia:\n" . implode("\n", $itemsLines) . "\n\nSilakan selesaikan pembayaran QRIS dan kirimkan link server Roblox kamu di room chat ini ya!";
                
                $botStmt = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, 'seller', 'Seller ItemPedia', ?, 0, CURRENT_TIMESTAMP)");
                $botStmt->execute([$invoiceNumber, $welcomeText]);
            } catch (\Exception $e) {
                error_log("Failed to insert initial chat messages: " . $e->getMessage());
            }

            if ($isAjax) {
                Flight::json([
                    'success' => true,
                    'invoice' => $invoiceNumber,
                    'redirect_url' => '/order/' . $invoiceNumber
                ]);
                return;
            }

            Flight::redirect('/order/' . $invoiceNumber);
            return;
        }

        // Single Product Direct Checkout
        if (!$productId) {
            if ($isAjax) {
                Flight::json(['success' => false, 'message' => 'Produk tidak valid'], 400);
                return;
            }
            Flight::redirect('/?error=Produk+tidak+valid');
            return;
        }

        // Ambil produk
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.is_active = 1");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if (!$product) {
            if ($isAjax) {
                Flight::json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
                return;
            }
            Flight::redirect('/?error=Produk+tidak+ditemukan');
            return;
        }

        if ($product['stock'] <= 0) {
            if ($isAjax) {
                Flight::json(['success' => false, 'message' => 'Stok produk ini sedang habis'], 400);
                return;
            }
            Flight::redirect('/?error=Stok+produk+ini+sedang+habis');
            return;
        }

        if ($quantity > $product['stock']) {
            if ($isAjax) {
                Flight::json(['success' => false, 'message' => 'Jumlah melebihi stok tersedia (Maks: ' . $product['stock'] . ')'], 400);
                return;
            }
            Flight::redirect('/?error=Jumlah+melebihi+stok+tersedia+(Maks:+' . $product['stock'] . ')');
            return;
        }

        // Validasi & Hitung Diskon Kode Redeem (Khusus 1 Produk Saja)
        $discountAmount = 0;
        $appliedCode = null;
        if (!empty($redeemCodeInput)) {
            $codeStmt = $db->prepare("SELECT * FROM redeem_codes WHERE UPPER(code) = ? AND is_active = 1");
            $codeStmt->execute([$redeemCodeInput]);
            $redeem = $codeStmt->fetch();

            if ($redeem) {
                $canUse = true;
                if ((int)$redeem['max_uses'] > 0 && (int)$redeem['used_count'] >= (int)$redeem['max_uses']) {
                    $canUse = false;
                }
                if (!empty($redeem['product_id']) && (int)$redeem['product_id'] !== $productId) {
                    $canUse = false;
                }

                if ($canUse) {
                    $appliedCode = $redeem['code'];
                    $discountPercent = (int)$redeem['discount_percent'];
                    // Diskon berlaku untuk 1 unit produk
                    $discountAmount = (int)round((int)$product['price'] * ($discountPercent / 100));

                    // Update jumlah penggunaan
                    $db->prepare("UPDATE redeem_codes SET used_count = used_count + 1 WHERE id = ?")->execute([$redeem['id']]);
                }
            }
        }

        $subtotalPrice = (int)$product['price'] * $quantity;
        $totalPrice = max(0, $subtotalPrice - $discountAmount);
        $productDisplayName = $product['name'] . ($quantity > 1 ? " ({$quantity}x)" : "");

        $singleItemJson = json_encode([[
            'id' => (int)$product['id'],
            'name' => $product['name'],
            'game' => $product['game'] ?? 'Blox Fruits',
            'price' => (int)$product['price'],
            'image_url' => $product['image_url'],
            'qty' => $quantity,
            'subtotal' => $subtotalPrice,
            'category_name' => $product['category_name'],
            'sub_category' => $product['sub_category'] ?? 'Item'
        ]]);

        // Generate Invoice Unik
        $invoiceNumber = 'ITP-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));

        if (empty($robloxAvatar)) {
            $robloxAvatar = "https://ui-avatars.com/api/?name=" . urlencode($robloxUsername) . "&background=06b6d4&color=fff&bold=true&size=150";
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $buyerEmail = $_SESSION['buyer_user']['email'] ?? null;

        // Simpan pesanan
        $insertStmt = $db->prepare("INSERT INTO orders 
            (invoice_number, product_id, product_name, category_name, price, roblox_username, roblox_avatar_url, whatsapp, note, status, payment_method, buyer_email, redeem_code, discount_amount, items_json) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', 'QRIS', ?, ?, ?, ?)");

        $insertStmt->execute([
            $invoiceNumber,
            $product['id'],
            $productDisplayName,
            $product['category_name'],
            $totalPrice,
            $robloxUsername,
            $robloxAvatar,
            $whatsapp,
            $note,
            $buyerEmail,
            $appliedCode,
            $discountAmount,
            $singleItemJson
        ]);

        // Kurangi stok
        $db->prepare("UPDATE products SET stock = CASE WHEN stock >= ? THEN stock - ? ELSE 0 END WHERE id = ?")->execute([$quantity, $quantity, $productId]);

        // Inisialisasi Pesan Chat Awal
        try {
            if (!empty($note)) {
                $msgStmt = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, 'buyer', ?, ?, 0, CURRENT_TIMESTAMP)");
                $msgStmt->execute([$invoiceNumber, $robloxUsername, $note]);
            }

            $welcomeText = "Halo kak " . $robloxUsername . "! 👋 Pesananmu (" . $productDisplayName . ") telah diterima di sistem ItemPedia. ";
            if ($product['category_name'] === 'Akun Game') {
                $welcomeText .= "Setelah pembayaran terkonfirmasi, data akun akan langsung otomatis kami kirimkan di halaman invoice ini.";
            } else {
                $welcomeText .= "Silakan standby di dalam game atau kirimkan link private server kamu di room chat ini ya!";
            }
            $botStmt = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, 'seller', 'Seller ItemPedia', ?, 0, CURRENT_TIMESTAMP)");
            $botStmt->execute([$invoiceNumber, $welcomeText]);
        } catch (\Exception $e) {
            error_log("Failed to insert initial chat messages: " . $e->getMessage());
        }

        if ($isAjax) {
            Flight::json([
                'success' => true,
                'invoice' => $invoiceNumber,
                'redirect_url' => '/order/' . $invoiceNumber
            ]);
            return;
        }

        Flight::redirect('/order/' . $invoiceNumber);
        return;
    }

    /**
     * Halaman Detail Invoice & Pembayaran QRIS
     */
    public static function showOrder(string $invoice): void
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT o.*, p.description as product_description, p.image_url as product_image 
                              FROM orders o 
                              LEFT JOIN products p ON o.product_id = p.id 
                              WHERE o.invoice_number = ?");
        $stmt->execute([$invoice]);
        $order = $stmt->fetch();

        if (!$order) {
            Flight::redirect('/lacak?error=Invoice+tidak+ditemukan');
            return;
        }

        // Ambil ulasan jika pesanan ini sudah pernah direview
        $reviewStmt = $db->prepare("SELECT * FROM reviews WHERE order_id = ?");
        $reviewStmt->execute([$order['id']]);
        $review = $reviewStmt->fetch();

        // Pembeli membuka halaman invoice: tandai pesan seller sebagai dibaca
        $db->prepare("UPDATE order_messages SET is_read = 1 WHERE invoice_number = ? AND sender = 'seller' AND is_read = 0")->execute([$invoice]);

        // Ambil riwayat chat pesan
        $chatStmt = $db->prepare("SELECT * FROM order_messages WHERE invoice_number = ? ORDER BY id ASC");
        $chatStmt->execute([$invoice]);
        $chatMessages = $chatStmt->fetchAll();
        foreach ($chatMessages as &$cm) {
            $cm['time_formatted'] = date('H:i', strtotime($cm['created_at']));
        }

        $settingsRaw = $db->query("SELECT * FROM settings")->fetchAll();
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }

        Flight::render('order_detail', [
            'order' => $order,
            'review' => $review,
            'chatMessages' => $chatMessages,
            'settings' => $settings
        ]);
    }

    /**
     * Endpoint API: Ambil Daftar Percakapan Inbox Chat (Untuk Floating Chat Widget & Seller Dock)
     */
    public static function getChatInbox(): void
    {
        $db = Database::getConnection();

        // Cek apakah tabel orders memiliki data atau buatkan sampel realistis
        $countOrders = (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        if ($countOrders === 0) {
            // Seed sample orders & chat messages matching screenshot
            $sampleData = [
                [
                    'invoice' => 'ITP-20260923-JJI01',
                    'user' => 'Jajie',
                    'country' => 'MY',
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
                    'country' => 'ID',
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
                    'country' => 'SG',
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
                    'country' => 'ID',
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
                    'country' => 'ID',
                    'product' => 'Crystalline ($525.277/s) (Divine) (Jurassic)',
                    'price' => 35000,
                    'status' => 'SUCCESS',
                    'avatar' => 'https://ui-avatars.com/api/?name=Made+Wistara&background=06b6d4&color=fff&bold=true',
                    'messages' => [
                        ['buyer', 'Made Wistara', 'https://www.roblox.com/share?code=abc', '22 Sep 08:30', 1]
                    ]
                ]
            ];

            foreach ($sampleData as $sd) {
                $insOrder = $db->prepare("INSERT INTO orders (invoice_number, product_id, product_name, category_name, price, roblox_username, roblox_avatar_url, whatsapp, status, payment_method, created_at) VALUES (?, 1, ?, 'Build A Zoo', ?, ?, ?, '-', ?, 'QRIS', CURRENT_TIMESTAMP)");
                $insOrder->execute([$sd['invoice'], $sd['product'], $sd['price'], $sd['user'], $sd['avatar'], $sd['status']]);

                $insMsg = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
                foreach ($sd['messages'] as $m) {
                    $insMsg->execute([$sd['invoice'], $m[0], $m[1], $m[2], $m[4]]);
                }
            }
        }

        // Ambil list percakapan terbaru
        $sql = "SELECT o.id, o.invoice_number, o.roblox_username, o.roblox_avatar_url, o.product_name, o.price, o.status, o.created_at,
                (SELECT message FROM order_messages WHERE invoice_number = o.invoice_number ORDER BY id DESC LIMIT 1) as last_message,
                (SELECT created_at FROM order_messages WHERE invoice_number = o.invoice_number ORDER BY id DESC LIMIT 1) as last_message_time,
                (SELECT COUNT(*) FROM order_messages WHERE invoice_number = o.invoice_number AND sender = 'buyer' AND is_read = 0) as unread_count
                FROM orders o 
                ORDER BY CASE WHEN unread_count > 0 THEN 0 ELSE 1 END, o.id DESC LIMIT 30";
        $inbox = $db->query($sql)->fetchAll();

        foreach ($inbox as &$item) {
            $timeSource = $item['last_message_time'] ?: $item['created_at'];
            $timestamp = strtotime($timeSource);
            if (date('Y-m-d', $timestamp) === date('Y-m-d')) {
                $item['time_formatted'] = date('H.i', $timestamp);
            } else {
                $item['time_formatted'] = date('d M', $timestamp);
            }
            $item['unread_count'] = (int)$item['unread_count'];
            $item['last_message'] = $item['last_message'] ?: 'Pesanan baru dibuat';
        }

        Flight::json([
            'success' => true,
            'conversations' => $inbox
        ]);
    }

    /**
     * Endpoint API: Ambil Pesan Chat Realtime (Polling)
     */
    public static function getChatMessages(string $invoice): void
    {
        $db = Database::getConnection();
        $role = Flight::request()->query->role ?? 'buyer';
        $afterId = (int)(Flight::request()->query->after_id ?? 0);

        // Validasi invoice
        $orderStmt = $db->prepare("SELECT o.*, p.image_url as product_image_url FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.invoice_number = ?");
        $orderStmt->execute([$invoice]);
        $order = $orderStmt->fetch();

        if (!$order) {
            Flight::json(['success' => false, 'message' => 'Invoice tidak ditemukan'], 404);
            return;
        }

        // Update status baca
        if ($role === 'buyer') {
            // Pembeli membaca pesan dari penjual
            $db->prepare("UPDATE order_messages SET is_read = 1 WHERE invoice_number = ? AND sender = 'seller' AND is_read = 0")->execute([$invoice]);
        } else if ($role === 'seller') {
            // Penjual membaca pesan dari pembeli
            $db->prepare("UPDATE order_messages SET is_read = 1 WHERE invoice_number = ? AND sender = 'buyer' AND is_read = 0")->execute([$invoice]);
        }

        // Query pesan
        if ($afterId > 0) {
            $stmt = $db->prepare("SELECT id, invoice_number, sender, sender_name, message, is_read, created_at FROM order_messages WHERE invoice_number = ? AND id > ? ORDER BY id ASC");
            $stmt->execute([$invoice, $afterId]);
        } else {
            $stmt = $db->prepare("SELECT id, invoice_number, sender, sender_name, message, is_read, created_at FROM order_messages WHERE invoice_number = ? ORDER BY id ASC");
            $stmt->execute([$invoice]);
        }
        $messages = $stmt->fetchAll();

        foreach ($messages as &$m) {
            $m['time_formatted'] = date('H.i', strtotime($m['created_at']));
        }

        // Cek ID pesan terakhir yang sudah dibaca lawan bicara
        $lastReadBuyerStmt = $db->prepare("SELECT COALESCE(MAX(id), 0) FROM order_messages WHERE invoice_number = ? AND sender = 'buyer' AND is_read = 1");
        $lastReadBuyerStmt->execute([$invoice]);
        $lastReadBuyerMsgId = (int)$lastReadBuyerStmt->fetchColumn();

        $lastReadSellerStmt = $db->prepare("SELECT COALESCE(MAX(id), 0) FROM order_messages WHERE invoice_number = ? AND sender = 'seller' AND is_read = 1");
        $lastReadSellerStmt->execute([$invoice]);
        $lastReadSellerMsgId = (int)$lastReadSellerStmt->fetchColumn();

        Flight::json([
            'success' => true,
            'order' => [
                'invoice_number' => $order['invoice_number'],
                'roblox_username' => $order['roblox_username'],
                'roblox_avatar_url' => $order['roblox_avatar_url'],
                'product_name' => $order['product_name'],
                'price' => (int)$order['price'],
                'status' => $order['status'],
                'image_url' => $order['product_image_url'] ?: 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=200',
                'created_at' => $order['created_at']
            ],
            'order_status' => $order['status'],
            'messages' => $messages,
            'last_read_buyer_msg_id' => $lastReadBuyerMsgId,
            'last_read_seller_msg_id' => $lastReadSellerMsgId
        ]);
    }

    /**
     * Endpoint API: Kirim Pesan Chat Baru
     */
    public static function sendChatMessage(string $invoice): void
    {
        $db = Database::getConnection();

        $orderStmt = $db->prepare("SELECT id, roblox_username FROM orders WHERE invoice_number = ?");
        $orderStmt->execute([$invoice]);
        $order = $orderStmt->fetch();

        if (!$order) {
            Flight::json(['success' => false, 'message' => 'Invoice tidak ditemukan'], 404);
            return;
        }

        $input = Flight::request()->data;
        $message = trim($input->message ?? '');
        $sender = trim($input->sender ?? 'buyer');
        if (!in_array($sender, ['buyer', 'seller'])) {
            $sender = 'buyer';
        }

        if (empty($message)) {
            Flight::json(['success' => false, 'message' => 'Pesan tidak boleh kosong'], 400);
            return;
        }

        $senderName = trim($input->sender_name ?? '');
        if (empty($senderName)) {
            $senderName = ($sender === 'buyer') ? $order['roblox_username'] : 'Seller ItemPedia';
        }

        $stmt = $db->prepare("INSERT INTO order_messages (invoice_number, sender, sender_name, message, is_read, created_at) VALUES (?, ?, ?, ?, 0, CURRENT_TIMESTAMP)");
        $stmt->execute([$invoice, $sender, $senderName, $message]);

        $newId = $db->lastInsertId();

        $fetchStmt = $db->prepare("SELECT id, invoice_number, sender, sender_name, message, is_read, created_at FROM order_messages WHERE id = ?");
        $fetchStmt->execute([$newId]);
        $newMessage = $fetchStmt->fetch();
        if ($newMessage) {
            $newMessage['time_formatted'] = date('H.i', strtotime($newMessage['created_at']));
        }

        Flight::json([
            'success' => true,
            'message' => $newMessage
        ]);
    }

    /**
     * Simulasi Pembayaran Berhasil (Dev / Sandbox)
     */
    public static function simulatePayment(string $invoice): void
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM orders WHERE invoice_number = ?");
        $stmt->execute([$invoice]);
        $order = $stmt->fetch();

        if ($order && $order['status'] === 'PENDING') {
            $updateStmt = $db->prepare("UPDATE orders SET status = 'PAID', updated_at = CURRENT_TIMESTAMP WHERE invoice_number = ?");
            $updateStmt->execute([$invoice]);

            // Increment total_sold di database
            $db->prepare("UPDATE products SET total_sold = total_sold + 1 WHERE id = ?")->execute([$order['product_id']]);
        }

        Flight::redirect('/order/' . $invoice . '?paid=success');
    }

    /**
     * Kirim Ulasan Pembeli untuk Pesanan Terverifikasi
     */
    public static function submitReview(string $invoice): void
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM orders WHERE invoice_number = ?");
        $stmt->execute([$invoice]);
        $order = $stmt->fetch();

        if (!$order) {
            Flight::redirect('/?error=Pesanan+tidak+ditemukan');
            return;
        }

        // Hanya pesanan yang lunas/diproses/selesai yang dapat memberi ulasan
        if (!in_array($order['status'], ['PAID', 'PROCESSING', 'SUCCESS'])) {
            Flight::redirect('/order/' . $invoice . '?error=Pesanan+harus+lunas+terlebih+dahulu+untuk+mengirim+ulasan');
            return;
        }

        // Cek jika sudah pernah memberikan ulasan
        $checkStmt = $db->prepare("SELECT id FROM reviews WHERE order_id = ?");
        $checkStmt->execute([$order['id']]);
        if ($checkStmt->fetch()) {
            Flight::redirect('/order/' . $invoice . '?error=Anda+sudah+mengirimkan+ulasan+untuk+pesanan+ini');
            return;
        }

        $rating = (int)(Flight::request()->data->rating ?? 5);
        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }
        $comment = trim(Flight::request()->data->comment ?? '');
        if (empty($comment)) {
            $comment = 'Proses cepat dan aman, penjual sangat recommended!';
        }

        // Simpan ulasan ke database
        $insStmt = $db->prepare("INSERT INTO reviews (product_id, order_id, roblox_username, roblox_avatar_url, rating, comment, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $insStmt->execute([
            $order['product_id'],
            $order['id'],
            $order['roblox_username'],
            $order['roblox_avatar_url'],
            $rating,
            $comment
        ]);

        // Hitung rata-rata rating baru dari tabel reviews
        $avgStmt = $db->prepare("SELECT AVG(rating) FROM reviews WHERE product_id = ?");
        $avgStmt->execute([$order['product_id']]);
        $avgVal = (float)($avgStmt->fetchColumn() ?: 5.0);
        $avgRating = round($avgVal, 1);

        // Update rating produk di database
        $db->prepare("UPDATE products SET rating = ? WHERE id = ?")->execute([$avgRating, $order['product_id']]);

        Flight::redirect('/order/' . $invoice . '?review=success');
    }

    /**
     * Halaman Lacak Pesanan
     */
    public static function lacak(): void
    {
        $db = Database::getConnection();
        $query = trim(Flight::request()->query->q ?? '');
        $orders = [];

        if (!empty($query)) {
            $stmt = $db->prepare("SELECT * FROM orders WHERE invoice_number = ? OR whatsapp = ? OR roblox_username = ? ORDER BY id DESC LIMIT 10");
            $stmt->execute([$query, $query, $query]);
            $orders = $stmt->fetchAll();
        }

        $settingsRaw = $db->query("SELECT * FROM settings")->fetchAll();
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }

        Flight::render('lacak', [
            'query' => $query,
            'orders' => $orders,
            'settings' => $settings
        ]);
    }
}
