<?php
/**
 * AMVAC 2027 — Database Configuration
 * Resilient multi-tier environment loader with cPanel/Linux PDO compatibility.
 */

declare(strict_types=1);

if (!function_exists('amvac_env')) {
    require_once __DIR__ . '/app.php';
}

$dbConfig = [
    'host'     => (string)amvac_env('DB_HOST', 'localhost'),
    'port'     => (int)amvac_env('DB_PORT', 3306),
    'database' => (string)amvac_env('DB_NAME', 'amvac2027'),
    'username' => (string)amvac_env('DB_USER', 'root'),
    'password' => (string)amvac_env('DB_PASS', ''),
    'charset'  => 'utf8mb4',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
];

// Optional local override if developer creates database.local.php
if (is_file(__DIR__ . '/database.local.php')) {
    $localConfig = require __DIR__ . '/database.local.php';
    if (is_array($localConfig)) {
        $dbConfig = array_merge($dbConfig, $localConfig);
    }
}

return $dbConfig;
