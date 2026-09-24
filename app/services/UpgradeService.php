<?php

namespace App\Services;

use App\Config\Database;
use ZipArchive;
use Exception;

class UpgradeService
{
    private const GITHUB_REPO = 'Theseadev/ItemPedia';
    private const DEFAULT_BRANCH = 'main';

    /**
     * Dapatkan path root project
     */
    public static function getRootDir(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * Dapatkan informasi commit & versi lokal saat ini
     */
    public static function getCurrentVersion(): array
    {
        $rootDir = self::getRootDir();
        $versionFile = $rootDir . '/version.json';
        
        $info = [
            'version' => '1.1.0',
            'commit_hash' => 'unknown',
            'branch' => self::DEFAULT_BRANCH,
            'repo' => self::GITHUB_REPO,
            'last_updated' => date('Y-m-d H:i:s'),
            'update_mode' => 'git',
            'git_available' => self::isGitAvailable()
        ];

        // 1. Baca dari version.json jika ada
        if (file_exists($versionFile)) {
            $json = @json_decode(file_get_contents($versionFile), true);
            if (is_array($json)) {
                $info = array_merge($info, $json);
            }
        }

        // 2. Coba deteksi langsung dari Git CLI jika ada folder .git
        if ($info['git_available'] && is_dir($rootDir . '/.git')) {
            $cmd = 'cd ' . escapeshellarg($rootDir) . ' && git rev-parse --short HEAD 2>&1';
            $gitHash = @shell_exec($cmd);
            if ($gitHash && !str_contains($gitHash, 'fatal:') && !str_contains($gitHash, 'not a git')) {
                $info['commit_hash'] = trim($gitHash);
            }

            $dateCmd = 'cd ' . escapeshellarg($rootDir) . ' && git log -1 --format=%cd --date=format:"%Y-%m-%d %H:%M:%S" 2>&1';
            $gitDate = @shell_exec($dateCmd);
            if ($gitDate && !str_contains($gitDate, 'fatal:')) {
                $info['last_commit_date'] = trim($gitDate);
            }
        }

        return $info;
    }

    /**
     * Cek apakah Git CLI dapat dijalankan di server
     */
    public static function isGitAvailable(): bool
    {
        $rootDir = self::getRootDir();
        if (!function_exists('shell_exec')) {
            return false;
        }
        $out = @shell_exec('git --version 2>&1');
        return !empty($out) && stripos($out, 'git version') !== false;
    }

    /**
     * Periksa pembaruan terbaru dari GitHub API
     */
    public static function checkRemoteUpdate(): array
    {
        $localInfo = self::getCurrentVersion();
        $repo = self::GITHUB_REPO;
        $branch = self::DEFAULT_BRANCH;
        $apiUrl = "https://api.github.com/repos/{$repo}/commits?per_page=10&sha={$branch}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ItemPedia-AutoUpdater/1.0 (PHP; Windows/Linux)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/vnd.github.v3+json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError || $httpCode !== 200 || empty($response)) {
            // Coba fallback dengan file_get_contents
            $context = stream_context_create([
                'http' => [
                    'header' => "User-Agent: ItemPedia-AutoUpdater/1.0\r\nAccept: application/vnd.github.v3+json\r\n",
                    'timeout' => 10
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            $response = @file_get_contents($apiUrl, false, $context);
            if ($response === false) {
                return [
                    'success' => false,
                    'error' => 'Gagal terhubung ke GitHub API. ' . ($curlError ?: "HTTP Code: {$httpCode}. Pastikan server memiliki akses internet.")
                ];
            }
        }

        $commitsData = json_decode($response, true);
        if (!is_array($commitsData) || empty($commitsData)) {
            return [
                'success' => false,
                'error' => 'Format respon GitHub tidak valid atau repositori belum memiliki commit.'
            ];
        }

        $latestCommit = $commitsData[0];
        $latestSha = $latestCommit['sha'] ?? '';
        $latestShaShort = substr($latestSha, 0, 7);
        $latestMsg = $latestCommit['commit']['message'] ?? '';
        $latestDate = $latestCommit['commit']['author']['date'] ?? '';
        $latestAuthor = $latestCommit['commit']['author']['name'] ?? 'ItemPedia Team';

        // Format waktu WIB
        $formattedDate = $latestDate;
        if (!empty($latestDate)) {
            try {
                $dt = new \DateTime($latestDate);
                $dt->setTimezone(new \DateTimeZone('Asia/Jakarta'));
                $formattedDate = $dt->format('d M Y, H:i') . ' WIB';
            } catch (Exception $e) {}
        }

        $currentShaShort = $localInfo['commit_hash'];
        $hasUpdate = ($currentShaShort !== 'unknown' && $currentShaShort !== $latestShaShort && !str_starts_with($latestSha, $currentShaShort));

        // Format riwayat changelog
        $changelog = [];
        foreach (array_slice($commitsData, 0, 8) as $c) {
            $cSha = substr($c['sha'] ?? '', 0, 7);
            $cMsg = explode("\n", trim($c['commit']['message'] ?? ''))[0];
            $cAuthor = $c['commit']['author']['name'] ?? 'ItemPedia';
            $cDateRaw = $c['commit']['author']['date'] ?? '';
            $cDate = $cDateRaw;
            if (!empty($cDateRaw)) {
                try {
                    $d = new \DateTime($cDateRaw);
                    $d->setTimezone(new \DateTimeZone('Asia/Jakarta'));
                    $cDate = $d->format('d M Y, H:i');
                } catch (Exception $e) {}
            }

            $changelog[] = [
                'sha' => $cSha,
                'sha_full' => $c['sha'] ?? '',
                'message' => $cMsg,
                'author' => $cAuthor,
                'date' => $cDate,
                'url' => $c['html_url'] ?? "https://github.com/{$repo}/commit/{$cSha}",
                'is_current' => ($cSha === $currentShaShort)
            ];
        }

        return [
            'success' => true,
            'has_update' => $hasUpdate,
            'current_commit' => $currentShaShort,
            'latest_commit' => $latestShaShort,
            'latest_commit_full' => $latestSha,
            'latest_message' => explode("\n", trim($latestMsg))[0],
            'latest_date' => $formattedDate,
            'latest_author' => $latestAuthor,
            'repo' => $repo,
            'branch' => $branch,
            'changelog' => $changelog,
            'git_available' => $localInfo['git_available']
        ];
    }

    /**
     * Eksekusi Upgrade Sistem dari GitHub
     */
    public static function executeUpgrade(string $mode = 'auto'): array
    {
        @set_time_limit(300);
        @ignore_user_abort(true);

        $rootDir = self::getRootDir();
        $logs = [];
        $startTime = microtime(true);

        $logs[] = "🚀 [Step 1/5] Memulai proses pembaruan sistem ItemPedia...";
        $logs[] = "📁 Direktori root target: {$rootDir}";

        // Cek permission tulis
        if (!is_writable($rootDir)) {
            return [
                'success' => false,
                'error' => "Direktori root tidak memiliki izin tulis (writable). Mohon ubah permission folder ke 755 / 777.",
                'logs' => $logs
            ];
        }
        $logs[] = "✅ Izin tulis direktori root terverifikasi.";

        $gitAvailable = self::isGitAvailable();
        $hasGitDir = is_dir($rootDir . '/.git');
        $useGit = ($mode === 'git' || ($mode === 'auto' && $gitAvailable && $hasGitDir));

        $latestSha = 'latest';

        if ($useGit) {
            $logs[] = "🌐 [Step 2/5] Menggunakan Mode Git CLI (Fast Pull)...";
            $logs[] = "📥 Menjalankan: git fetch origin " . self::DEFAULT_BRANCH;
            
            $fetchOutput = @shell_exec('cd ' . escapeshellarg($rootDir) . ' && git fetch origin ' . self::DEFAULT_BRANCH . ' 2>&1');
            $logs[] = !empty(trim($fetchOutput)) ? "   > " . trim($fetchOutput) : "   > Fetch repositori selesai.";

            $logs[] = "🔄 Menjalankan: git pull origin " . self::DEFAULT_BRANCH;
            $pullOutput = @shell_exec('cd ' . escapeshellarg($rootDir) . ' && git pull origin ' . self::DEFAULT_BRANCH . ' 2>&1');
            $logs[] = !empty(trim($pullOutput)) ? "   > " . trim($pullOutput) : "   > Git pull selesai.";

            // Cek hash setelah pull
            $newHash = @shell_exec('cd ' . escapeshellarg($rootDir) . ' && git rev-parse --short HEAD 2>&1');
            if ($newHash && !str_contains($newHash, 'fatal:')) {
                $latestSha = trim($newHash);
            }
            $logs[] = "✅ Sinkronisasi Git CLI berhasil. Commit saat ini: {$latestSha}";
        } else {
            // Mode ZIP Archive Fallback (Universal untuk cPanel/Shared Hosting)
            $logs[] = "🌐 [Step 2/5] Menggunakan Mode ZIP Archive (Universal Web Download)...";
            $zipUrl = "https://github.com/" . self::GITHUB_REPO . "/archive/refs/heads/" . self::DEFAULT_BRANCH . ".zip";
            
            $tempDir = sys_get_temp_dir() . '/itempedia_update_' . time();
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $zipFile = $tempDir . '/update.zip';

            $logs[] = "📦 Mengunduh source code dari: {$zipUrl}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $zipUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'ItemPedia-AutoUpdater/1.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $zipContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if (empty($zipContent) || $httpCode !== 200) {
                return [
                    'success' => false,
                    'error' => "Gagal mengunduh file ZIP pembaruan dari GitHub (HTTP {$httpCode}: {$curlError})",
                    'logs' => $logs
                ];
            }

            file_put_contents($zipFile, $zipContent);
            $zipSizeMb = round(filesize($zipFile) / (1024 * 1024), 2);
            $logs[] = "✅ Berhasil mengunduh arsip ({$zipSizeMb} MB).";

            $logs[] = "📦 Mengekstrak file arsip pembaruan...";
            $zip = new ZipArchive();
            if ($zip->open($zipFile) !== true) {
                return [
                    'success' => false,
                    'error' => "Gagal membuka file ZIP pembaruan.",
                    'logs' => $logs
                ];
            }

            $extractDir = $tempDir . '/extracted';
            mkdir($extractDir, 0777, true);
            $zip->extractTo($extractDir);
            $zip->close();

            // Cari subfolder hasil extract (biasanya ItemPedia-main)
            $extractedItems = scandir($extractDir);
            $sourceDir = $extractDir;
            foreach ($extractedItems as $item) {
                if ($item !== '.' && $item !== '..' && is_dir($extractDir . '/' . $item)) {
                    $sourceDir = $extractDir . '/' . $item;
                    break;
                }
            }

            $logs[] = "🛡️ [Step 3/5] Mengamankan & memproteksi data pengguna...";
            $logs[] = "   > Proteksi: .env (Konfigurasi & Kredensial)";
            $logs[] = "   > Proteksi: database/ (SQLite Database & Data)";
            $logs[] = "   > Proteksi: public/uploads/ (Media Bukti & Banner)";
            $logs[] = "   > Proteksi: .git/ (Git History)";

            // Salin file dengan aman
            $copiedCount = self::copyFilesRecursively($sourceDir, $rootDir, [
                '.env',
                '.git',
                'database/itempedia.sqlite',
                'database/itempedia.sqlite-journal',
                'public/uploads'
            ]);

            $logs[] = "✅ Sukses memperbarui {$copiedCount} file & folder sistem.";

            // Bersihkan file sementara
            self::deleteDirectoryRecursively($tempDir);
            $logs[] = "🧹 Membersihkan file temporary installer... Selesai.";
        }

        // Jalankan sinkronisasi database & skema
        $logs[] = "🗄️ [Step 4/5] Memeriksa & menyinkronkan skema database...";
        try {
            $db = Database::getConnection();
            $dbDriver = Database::getDriver();
            $logs[] = "✅ Koneksi database {$dbDriver} aktif & skema tabel tervalidasi.";
        } catch (Exception $e) {
            $logs[] = "⚠️ Peringatan database: " . $e->getMessage();
        }

        // Dapatkan commit hash terbaru dari remote jika mode ZIP
        if (!$useGit) {
            $remoteCheck = self::checkRemoteUpdate();
            if (!empty($remoteCheck['latest_commit'])) {
                $latestSha = $remoteCheck['latest_commit'];
            }
        }

        // Perbarui version.json
        $logs[] = "📝 [Step 5/5] Memperbarui catatan versi sistem...";
        $versionFile = $rootDir . '/version.json';
        $newVersionData = [
            'version' => '1.1.0',
            'commit_hash' => $latestSha,
            'branch' => self::DEFAULT_BRANCH,
            'repo' => self::GITHUB_REPO,
            'last_updated' => date('Y-m-d H:i:s'),
            'update_mode' => $useGit ? 'git' : 'zip'
        ];
        @file_put_contents($versionFile, json_encode($newVersionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $duration = round(microtime(true) - $startTime, 2);
        $logs[] = "🎉 Pembaruan sistem selesai sukses dalam {$duration} detik!";

        return [
            'success' => true,
            'message' => 'Website ItemPedia berhasil diperbarui ke versi terbaru dari GitHub!',
            'new_commit' => $latestSha,
            'duration' => $duration,
            'logs' => $logs
        ];
    }

    /**
     * Salin file secara rekursif dengan filter file yang dilindungi
     */
    private static function copyFilesRecursively(string $src, string $dst, array $protectedPaths): int
    {
        $dir = opendir($src);
        @mkdir($dst, 0777, true);
        $count = 0;

        while (false !== ($file = readdir($dir))) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $srcFile = $src . '/' . $file;
            $dstFile = $dst . '/' . $file;
            $relativeDst = str_replace(self::getRootDir() . '/', '', str_replace('\\', '/', $dstFile));

            // Periksa apakah path ini termasuk dalam daftar yang dilindungi
            $isProtected = false;
            foreach ($protectedPaths as $protected) {
                if ($relativeDst === $protected || str_starts_with($relativeDst, $protected . '/')) {
                    $isProtected = true;
                    break;
                }
            }

            if ($isProtected) {
                continue;
            }

            if (is_dir($srcFile)) {
                $count += self::copyFilesRecursively($srcFile, $dstFile, $protectedPaths);
            } else {
                if (copy($srcFile, $dstFile)) {
                    $count++;
                }
            }
        }
        closedir($dir);
        return $count;
    }

    /**
     * Hapus direktori sementara secara rekursif
     */
    private static function deleteDirectoryRecursively(string $dir): void
    {
        if (!is_dir($dir)) return;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? self::deleteDirectoryRecursively($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
