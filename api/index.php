<?php
// Vercel Serverless Function Bridge for ItemPedia

// Normalize server environment variables so Flight PHP routes correctly without prefixing /api
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__ . '/../public/index.php';
