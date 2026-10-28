<?php
/**
 * AMVAC 2027 — Database Migration and Seeder Runner
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
$dbConfig = require __DIR__ . '/../config/database.php';

echo "=== AMVAC 2027 Database Migration ===\n";

try {
    // Connect without selecting DB first to ensure DB exists
    $dsnRoot = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};charset={$dbConfig['charset']}";
    $pdoRoot = new PDO($dsnRoot, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
    
    try {
        echo "[1/4] Checking/ensuring database '{$dbConfig['database']}' exists...\n";
        $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    } catch (PDOException $e) {
        // In shared hosting (cPanel), user may not have CREATE DATABASE privileges if DB already created
        echo "      Notice: {$e->getMessage()} (proceeding with existing database)\n";
    }

    // Connect to target DB
    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);

    echo "[2/4] Executing production_install.sql...\n";
    $sqlFile = file_exists(__DIR__ . '/production_install.sql') ? __DIR__ . '/production_install.sql' : __DIR__ . '/schema.sql';
    $installSql = file_get_contents($sqlFile);
    $pdo->exec($installSql);
    echo "      Tables created/verified successfully.\n";

    echo "[3/4] Executing seed.sql...\n";
    $seedSql = file_get_contents(__DIR__ . '/seed.sql');
    $pdo->exec($seedSql);
    echo "      Seed data populated successfully.\n";

    echo "[4/4] Verifying tables...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "      Tables present: " . implode(', ', $tables) . "\n";

    echo "=== Migration Complete! Database is ready. ===\n";
} catch (PDOException $e) {
    echo "ERROR during migration: " . $e->getMessage() . "\n";
    exit(1);
}
