<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Services\UpgradeService;
use App\Controllers\AdminController;

echo "=== TESTING ITEMPEDIA SYSTEM UPGRADE MODULE ===\n\n";

$passed = 0;
$total = 0;

function assertTest(string $name, bool $condition, string $details = '') {
    global $passed, $total;
    $total++;
    if ($condition) {
        $passed++;
        echo "✅ [PASS] {$name}: {$details}\n";
    } else {
        echo "❌ [FAIL] {$name}: {$details}\n";
    }
}

// 1. Test getCurrentVersion
$version = UpgradeService::getCurrentVersion();
assertTest(
    "1. UpgradeService::getCurrentVersion()",
    !empty($version['commit_hash']) && !empty($version['branch']) && isset($version['git_available']),
    "Commit: {$version['commit_hash']}, Branch: {$version['branch']}, Repo: {$version['repo']}, Git Available: " . ($version['git_available'] ? 'Yes' : 'No')
);

// 2. Test checkRemoteUpdate from GitHub API
$check = UpgradeService::checkRemoteUpdate();
assertTest(
    "2. UpgradeService::checkRemoteUpdate() from GitHub",
    $check['success'] === true && !empty($check['latest_commit']),
    "Success: " . ($check['success'] ? 'true' : 'false') . ", Latest Remote Commit: " . ($check['latest_commit'] ?? 'none') . ", Total Commits in Log: " . count($check['changelog'] ?? [])
);

if (!empty($check['changelog'])) {
    $firstCommit = $check['changelog'][0];
    assertTest(
        "3. GitHub Changelog Parsing",
        !empty($firstCommit['sha']) && !empty($firstCommit['message']),
        "First Commit: [{$firstCommit['sha']}] {$firstCommit['message']} by {$firstCommit['author']}"
    );
}

// 4. Test Route & HTTP Status via Localhost
$ch = curl_init('http://127.0.0.1:8000/admin/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['username' => 'admin', 'password' => 'admin123']));
curl_setopt($ch, CURLOPT_COOKIEJAR, sys_get_temp_dir() . '/cookie_admin_upgrade_test.txt');
curl_exec($ch);
curl_close($ch);

$ch = curl_init('http://127.0.0.1:8000/admin/upgrade');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, sys_get_temp_dir() . '/cookie_admin_upgrade_test.txt');
$upgradeHtml = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest(
    "4. GET /admin/upgrade page rendered",
    $httpCode === 200 && str_contains($upgradeHtml, 'Pembaruan Sistem') && str_contains($upgradeHtml, 'Auto Upgrade'),
    "HTTP Status: {$httpCode}, contains 'Pembaruan Sistem' and 'Auto Upgrade'"
);

// 5. Test API Check Endpoint
$ch = curl_init('http://127.0.0.1:8000/api/admin/upgrade/check');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, sys_get_temp_dir() . '/cookie_admin_upgrade_test.txt');
$apiRes = curl_exec($ch);
$apiCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$apiJson = json_decode($apiRes, true);
curl_close($ch);

assertTest(
    "5. POST /api/admin/upgrade/check endpoint",
    $apiCode === 200 && ($apiJson['success'] ?? false) === true,
    "HTTP {$apiCode}, API Response success: true, latest_commit: " . ($apiJson['latest_commit'] ?? 'none')
);

echo "\n======================================================\n";
echo "SUMMARY: {$passed}/{$total} TESTS PASSED\n";
echo "======================================================\n";
