# ItemPedia - Toko Item & Akun Roblox (Flight PHP)

Website e-commerce mandiri (*single-seller*) khusus menjual produk virtual Roblox:
1. **Item Game Roblox** (Blox Fruits, Murder Mystery 2, Pet Simulator, dll.)
2. **Akun Game Roblox** (Akun Sultan Max Level, Race V4, Polosan Old-Gen, dll.)

Dibangun menggunakan micro-framework **Flight PHP**, database SQLite PDO yang sangat ringan, dan tampilan antarmuka modern **Tailwind CSS** (Dark Mode Gaming).

---

## 🚀 Cara Menjalankan Server Lokal

Buka terminal PowerShell di folder proyek ini (`C:\Users\fahru\.gemini\antigravity\scratch\itempedia`), lalu jalankan:

```powershell
& "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" -S localhost:8000 -t public
```

Buka browser dan akses:
- **Toko Pembeli:** [http://localhost:8000](http://localhost:8000)
- **Pelacakan Pesanan:** [http://localhost:8000/lacak](http://localhost:8000/lacak)
- **Panel Admin:** [http://localhost:8000/admin](http://localhost:8000/admin)

---

## 🔐 Kredensial Login Admin

- **URL:** `http://localhost:8000/admin/login`
- **Username:** `admin`
- **Password:** `admin123`

---

## ✨ Fitur-Fitur Utama

1. **Katalog & Filter Kategori:**
   - Filter instan: "Semua Produk", "Item Game", dan "Akun Game".
   - Pencarian produk real-time.
   - Badge produk (Mythical, Sultan Ready, Old Gen, Diskon).
2. **Checkout Cerdas Khusus Roblox:**
   - Input Username Roblox dilengkapi fitur **Cek Avatar Otomatis** langsung terhubung ke API Roblox resmi untuk menampilkan foto avatar karakter pembeli (mencegah salah kirim).
   - Input kontak WhatsApp untuk koordinasi trade item atau serah terima data akun.
3. **Invoice & Pembayaran QRIS Dinamis:**
   - Kode transaksi unik (contoh: `ITP-20260921-XXXXX`).
   - Kode QRIS otomatis.
   - **Tombol Simulasi Pembayaran Sukses (Sandbox):** Kamu bisa mengetes transaksi dari sisi pembeli hingga status Lunas tanpa uang sungguhan.
4. **Sistem Khusus Akun Game:**
   - Ketika pembeli membeli produk kategori **Akun Game**, setelah kamu mengubah status menjadi **Selesai** di admin panel, data akun (Username & Password) akan langsung ditampilkan secara rahasia dan aman di halaman invoice pembeli dengan tombol 1-klik salin!
5. **Panel Admin Lengkap:**
   - Ringkasan Omset, Pesanan Menunggu Diproses, dan Produk Aktif.
   - Tabel pesanan masuk dengan tombol 1-klik salin username Roblox pembeli.
   - Tambah produk baru (Item / Akun), edit harga coret, deskripsi, gambar, dan stok.

---

## 📁 Struktur Direktori

```
itempedia/
├── app/
│   ├── config/
│   │   └── database.php       # Inisialisasi SQLite & auto-seed
│   ├── controllers/
│   │   ├── HomeController.php # Etalase, filter & search
│   │   ├── OrderController.php# Checkout, proxy Roblox API & invoice
│   │   └── AdminController.php# Dashboard admin & kelola produk
│   └── views/
│       ├── layout.php         # Master layout Tailwind dark mode
│       ├── home.php           # Etalase produk & modal checkout
│       ├── order_detail.php   # Halaman invoice QRIS & serah terima akun
│       ├── lacak.php          # Halaman lacak pesanan
│       └── admin/
│           ├── login.php      # Login admin
│           ├── dashboard.php  # Dashboard pesanan & statistik omset
│           └── products.php   # Form tambah & kelola produk
├── database/
│   └── itempedia.sqlite       # Database SQLite
├── public/
│   ├── .htaccess              # Apache rewrite rule
│   └── index.php              # Routing Flight PHP
├── composer.json
└── README.md
```
