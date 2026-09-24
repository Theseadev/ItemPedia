<?php

$url = 'http://127.0.0.1:8000/';
$opts = [
    'http' => [
        'method' => 'GET',
        'timeout' => 5
    ]
];
$ctx = stream_context_create($opts);
$html = @file_get_contents($url, false, $ctx);

if ($html === false) {
    echo "ERROR: Failed to connect to server at {$url}\n";
    exit(1);
}

echo "HTTP Request to {$url} SUCCESS!\n";
echo "Response size: " . strlen($html) . " bytes\n";

$checks = [
    'ItemPedia Brand' => 'ItemPedia',
    'Catalog Section' => 'Katalog',
    'FAQ Section' => 'PERTANYAAN UMUM',
    'Hero Title' => 'Roblox',
];

foreach ($checks as $label => $needle) {
    if (strpos($html, $needle) !== false) {
        echo "[PASS] {$label}\n";
    } else {
        echo "[FAIL] {$label} not found in HTML\n";
    }
}

// Verify that removed items are really gone
if (strpos($html, 'storeOperatingHoursText') === false) {
    echo "[PASS] Operating Hours Badge Successfully Removed\n";
} else {
    echo "[FAIL] Operating Hours Badge still present\n";
}

if (strpos($html, 'FLASH SALE TERBATAS') === false) {
    echo "[PASS] Flash Sale Badge Successfully Removed\n";
} else {
    echo "[FAIL] Flash Sale Badge still present\n";
}
