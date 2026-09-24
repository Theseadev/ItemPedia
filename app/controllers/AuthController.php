<?php

namespace App\Controllers;

use Flight;
use App\Config\Database;
use PDO;

class AuthController
{
    /**
     * Endpoint API: Login / Autentikasi Menggunakan Akun Google
     * Pembeli cukup klik akun Google (tanpa memasukkan password secara manual)
     */
    public static function loginGoogle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = Database::getConnection();

        $input = Flight::request()->data;
        $email = trim($input->email ?? '');
        $name = trim($input->name ?? '');
        $avatar = trim($input->avatar ?? '');
        $googleId = trim($input->google_id ?? '');

        // Jika email tidak berformat @gmail.com atau @..., otomatis lengkapi jika cuma username
        if (!empty($email) && !str_contains($email, '@')) {
            $email .= '@gmail.com';
        }

        if (empty($email)) {
            Flight::json(['success' => false, 'message' => 'Email Google tidak boleh kosong'], 400);
            return;
        }

        // Jika nama kosong, ambil dari bagian depan email
        if (empty($name)) {
            $parts = explode('@', $email);
            $name = ucwords(str_replace(['.', '_', '-'], ' ', $parts[0]));
        }

        // Jika avatar kosong, buatkan avatar Google Material Design cantik
        if (empty($avatar)) {
            $avatar = "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background=4285F4&color=fff&bold=true&size=150";
        }

        if (empty($googleId)) {
            $googleId = 'goog_' . substr(md5($email), 0, 16);
        }

        try {
            // Cek apakah pembeli sudah terdaftar
            $stmt = $db->prepare("SELECT * FROM buyers WHERE email = ?");
            $stmt->execute([$email]);
            $existingBuyer = $stmt->fetch();

            if ($existingBuyer) {
                // Update login terakhir & avatar
                $upd = $db->prepare("UPDATE buyers SET name = ?, avatar_url = ?, last_login = CURRENT_TIMESTAMP WHERE id = ?");
                $upd->execute([$name, $avatar, $existingBuyer['id']]);
                $buyerId = $existingBuyer['id'];
            } else {
                // Daftarkan pembeli baru
                $ins = $db->prepare("INSERT INTO buyers (google_id, email, name, avatar_url, created_at, last_login) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
                $ins->execute([$googleId, $email, $name, $avatar]);
                $buyerId = $db->lastInsertId();
            }

            // Simpan ke sesi pembeli
            $_SESSION['buyer_user'] = [
                'id' => $buyerId,
                'google_id' => $googleId,
                'email' => $email,
                'name' => $name,
                'avatar_url' => $avatar
            ];

            Flight::json([
                'success' => true,
                'message' => 'Login Google berhasil!',
                'user' => $_SESSION['buyer_user']
            ]);
        } catch (\Exception $e) {
            error_log("Google Auth Error: " . $e->getMessage());
            Flight::json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat autentikasi'], 500);
        }
    }

    /**
     * Logout Sesi Akun Google Pembeli
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['buyer_user']);

        // Redirect ke halaman utama
        Flight::redirect('/?logout=success');
    }

    /**
     * Halaman Pesanan Saya Khusus Pembeli yang Login Google
     */
    public static function myOrders(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $buyer = $_SESSION['buyer_user'] ?? null;
        if (!$buyer) {
            Flight::redirect('/?error=Silakan+masuk+dengan+Google+terlebih+dahulu#login-google');
            return;
        }

        $db = Database::getConnection();

        // Ambil semua pesanan milik pembeli berdasarkan email atau username
        $stmt = $db->prepare("SELECT * FROM orders WHERE buyer_email = ? ORDER BY id DESC");
        $stmt->execute([$buyer['email']]);
        $orders = $stmt->fetchAll();

        // Jika tidak ada pesanan dengan email tsb, cek pesanan dengan nama roblox jika cocok
        if (empty($orders)) {
            $stmt2 = $db->prepare("SELECT * FROM orders WHERE roblox_username = ? ORDER BY id DESC LIMIT 20");
            $stmt2->execute([$buyer['name']]);
            $orders = $stmt2->fetchAll();
        }

        $settingsRaw = $db->query("SELECT * FROM settings")->fetchAll();
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }

        Flight::render('my_orders', [
            'buyer' => $buyer,
            'orders' => $orders,
            'settings' => $settings
        ]);
    }
}
