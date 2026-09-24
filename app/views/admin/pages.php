<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laman & Konten (CMS) - Seller Center ItemPedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="icon" type="image/svg+xml" href="/images/logo-icon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f0f2f5] text-slate-800 min-h-screen flex flex-col antialiased selection:bg-blue-100 selection:text-blue-700">

    <?php 
    $activeMenu = 'pages';
    include __DIR__ . '/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main id="adminMainArea" class="md:pl-72 flex-grow transition-all duration-300 flex flex-col">
        <?php include __DIR__ . '/navbar.php'; ?>
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 flex-grow">

            <!-- Top Header & Breadcrumb -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                        <a href="/admin" class="hover:text-blue-600">Tokoku</a>
                        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                        <span class="text-slate-600 font-bold">Laman & Konten Toko</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Edit Laman & Konten Toko (CMS)</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ubah teks banner hero, jam buka, kontak WhatsApp, panduan langkah, dan tanya jawab (FAQ)</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="/" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white font-bold text-xs sm:text-sm transition flex items-center gap-2 shadow-xs active:scale-95">
                        <i class="fa-solid fa-store text-xs"></i>
                        <span>Lihat Hasil di Toko</span>
                    </a>
                </div>
            </div>

            <!-- Pesan Notifikasi Sukses -->
            <?php if (!empty($msg)): ?>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 font-bold">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span><?= htmlspecialchars($msg) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Kolom Kiri: Pengaturan Konten Utama Laman -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <form action="/admin/pages/update" method="POST" class="space-y-6">
                        
                        <!-- 1. Hero Banner -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-sm">
                                    <i class="fa-solid fa-heading"></i>
                                </div>
                                <div>
                                    <h2 class="font-extrabold text-slate-900 text-base">Hero Banner Beranda</h2>
                                    <p class="text-[11px] text-slate-400">Teks utama paling atas yang pertama kali dilihat pembeli</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Judul Besar Hero Banner
                                    </label>
                                    <input type="text" 
                                           name="hero_title" 
                                           value="<?= htmlspecialchars($settings['hero_title'] ?? 'Item & Akun Roblox Impianmu, Dikirim Hitungan Menit.') ?>" 
                                           placeholder="Item & Akun Roblox Impianmu, Dikirim Hitungan Menit."
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Sub-Judul / Deskripsi Hero
                                    </label>
                                    <textarea name="hero_subtitle" 
                                              rows="2" 
                                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-blue-600 focus:bg-white resize-none"><?= htmlspecialchars($settings['hero_subtitle'] ?? 'Pusat marketplace item game Build A Zoo, Chop Your Tree, Catch and Tame, Pet Sim 99, & Blox Fruits terlengkap dengan sistem otomatis dan garansi 100% aman anti-banned.') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Kontak WhatsApp & Jam Operasional Toko -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-sm">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <div>
                                    <h2 class="font-extrabold text-slate-900 text-base">Kontak WhatsApp & Jam Buka Operasional</h2>
                                    <p class="text-[11px] text-slate-400">Atur nomor WhatsApp admin dan jam operasional layanan toko</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Nomor WhatsApp Admin (Format 62...)
                                    </label>
                                    <input type="text" 
                                           name="whatsapp_admin" 
                                           value="<?= htmlspecialchars($settings['whatsapp_admin'] ?? '6281234567890') ?>" 
                                           placeholder="6281234567890"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-emerald-600 focus:outline-none focus:border-emerald-600 focus:bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Teks Tampilan WhatsApp
                                    </label>
                                    <input type="text" 
                                           name="whatsapp_display" 
                                           value="<?= htmlspecialchars($settings['whatsapp_display'] ?? '+62 812-3456-7890') ?>" 
                                           placeholder="+62 812-3456-7890"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-600 focus:bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Jam Buka (Open)
                                    </label>
                                    <input type="text" 
                                           name="operating_hours_open" 
                                           value="<?= htmlspecialchars($settings['operating_hours_open'] ?? '07:00') ?>" 
                                           placeholder="07:00"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Jam Tutup (Close)
                                    </label>
                                    <input type="text" 
                                           name="operating_hours_close" 
                                           value="<?= htmlspecialchars($settings['operating_hours_close'] ?? '21:00') ?>" 
                                           placeholder="21:00"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Panduan 3 Langkah Pembeli -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-black text-sm">
                                    <i class="fa-solid fa-list-check"></i>
                                </div>
                                <div>
                                    <h2 class="font-extrabold text-slate-900 text-base">Panduan 3-Langkah Pembeli</h2>
                                    <p class="text-[11px] text-slate-400">Langkah mudah bertransaksi yang tampil di beranda</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Step 1 -->
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600 block">Langkah 1</span>
                                    <input type="text" name="step1_title" value="<?= htmlspecialchars($settings['step1_title'] ?? 'Pilih Item / Akun Game') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600">
                                    <textarea name="step1_desc" rows="2" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-600 focus:outline-none focus:border-blue-600 resize-none"><?= htmlspecialchars($settings['step1_desc'] ?? 'Cari hewan Prismatic, akun Sultan, atau item game favoritmu sesuai kebutuhan.') ?></textarea>
                                </div>

                                <!-- Step 2 -->
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-600 block">Langkah 2</span>
                                    <input type="text" name="step2_title" value="<?= htmlspecialchars($settings['step2_title'] ?? 'Isi Data & Bayar QRIS') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600">
                                    <textarea name="step2_desc" rows="2" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-600 focus:outline-none focus:border-blue-600 resize-none"><?= htmlspecialchars($settings['step2_desc'] ?? 'Masukkan username Roblox & nomor WhatsApp. Scan QRIS instan dari semua e-wallet & m-banking.') ?></textarea>
                                </div>

                                <!-- Step 3 -->
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-600 block">Langkah 3</span>
                                    <input type="text" name="step3_title" value="<?= htmlspecialchars($settings['step3_title'] ?? 'Trade Langsung di Server') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600">
                                    <textarea name="step3_desc" rows="2" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-600 focus:outline-none focus:border-blue-600 resize-none"><?= htmlspecialchars($settings['step3_desc'] ?? 'Admin join server privatmu atau kirim via trade instan dalam 3-5 menit beres!') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Simpan Seluruh Konten Laman -->
                        <div class="sticky bottom-4 z-20">
                            <button type="submit" class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-blue-500/25 transition flex items-center justify-center gap-2 active:scale-98">
                                <i class="fa-solid fa-floppy-disk text-base"></i>
                                <span>Simpan Semua Perubahan Konten Laman</span>
                            </button>
                        </div>

                    </form>

                </div>

                <!-- Kolom Kanan: FAQ (Tanya Jawab) Manager -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Form Tambah FAQ Baru -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-circle-question"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">Tambah FAQ Baru</h3>
                                <p class="text-[10px] text-slate-400">Pertanyaan umum pelanggan</p>
                            </div>
                        </div>

                        <form action="/admin/faqs/add" method="POST" class="space-y-3.5">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pertanyaan (Question)</label>
                                <input type="text" name="question" required placeholder="Contoh: Apakah item ini permanen?" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500 focus:bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Jawaban (Answer)</label>
                                <textarea name="answer" required rows="3" placeholder="Jelaskan jawaban secara jelas dan ramah..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500 focus:bg-white resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                                    <input type="text" name="category" value="Umum" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                                    <input type="number" name="sort_order" value="<?= count($faqs ?? []) + 1 ?>" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500 focus:bg-white">
                                </div>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-xs">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Tambah Tanya Jawab</span>
                            </button>
                        </form>
                    </div>

                    <!-- Daftar FAQ yang Aktif -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-extrabold text-slate-900 text-base">Daftar FAQ (<?= count($faqs ?? []) ?>)</h3>
                            <span class="text-[10px] text-slate-400 font-bold">Tampil di Beranda</span>
                        </div>

                        <div class="space-y-3">
                            <?php if (empty($faqs)): ?>
                                <div class="text-center py-6 text-slate-400 text-xs">
                                    Belum ada FAQ. Tambahkan melalui form di atas.
                                </div>
                            <?php else: ?>
                                <?php foreach ($faqs as $faq): ?>
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="font-extrabold text-xs text-slate-900 flex items-start gap-1.5">
                                            <i class="fa-solid fa-circle-question text-blue-600 text-[10px] mt-0.5 flex-shrink-0"></i>
                                            <span><?= htmlspecialchars($faq['question']) ?></span>
                                        </div>
                                        <div class="flex items-center gap-1 flex-shrink-0">
                                            <button type="button" 
                                                    onclick='openEditFaqModal(<?= json_encode($faq, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' 
                                                    class="text-slate-400 hover:text-blue-600 p-1 transition" 
                                                    title="Edit FAQ">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>
                                            <form action="/admin/faqs/delete" method="POST" onsubmit="return confirmFormSubmit(event, { title: 'Hapus Tanya Jawab (FAQ)?', itemName: '<?= addslashes(mb_substr($faq['question'], 0, 45)) ?>...', itemIcon: 'fa-solid fa-circle-question text-rose-500', message: 'Pertanyaan FAQ ini akan dihapus dari daftar pusat bantuan website.' });" class="inline">
                                                <input type="hidden" name="id" value="<?= $faq['id'] ?>">
                                                <button type="submit" class="text-slate-400 hover:text-rose-500 p-1 transition" title="Hapus FAQ">
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-slate-600 leading-relaxed pl-4 border-l-2 border-slate-200">
                                        <?= htmlspecialchars($faq['answer']) ?>
                                    </p>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- Modal Edit FAQ (Light Theme) -->
    <div id="editFaqModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 animate-in fade-in duration-150">
        <div class="bg-white rounded-3xl border border-slate-200 w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/80">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Edit Tanya Jawab (FAQ)</h3>
                        <p class="text-[11px] text-slate-400">Perbarui pertanyaan atau isi jawaban</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditFaqModal()" class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="/admin/faqs/update" method="POST" class="p-6 space-y-4 text-xs">
                <input type="hidden" name="id" id="edit_faq_id">

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pertanyaan (Question) <span class="text-rose-500">*</span></label>
                    <input type="text" name="question" id="edit_faq_question" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jawaban (Answer) <span class="text-rose-500">*</span></label>
                    <textarea name="answer" id="edit_faq_answer" required rows="4" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                        <input type="text" name="category" id="edit_faq_category" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampil</label>
                        <input type="number" name="sort_order" id="edit_faq_sort_order" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                    <button type="button" onclick="closeEditFaqModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan FAQ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditFaqModal(faq) {
        document.getElementById('edit_faq_id').value = faq.id || '';
        document.getElementById('edit_faq_question').value = faq.question || '';
        document.getElementById('edit_faq_answer').value = faq.answer || '';
        document.getElementById('edit_faq_category').value = faq.category || 'Umum';
        document.getElementById('edit_faq_sort_order').value = faq.sort_order || 1;

        document.getElementById('editFaqModal').classList.remove('hidden');
    }

    function closeEditFaqModal() {
        document.getElementById('editFaqModal').classList.add('hidden');
    }
    </script>

</body>
</html>
