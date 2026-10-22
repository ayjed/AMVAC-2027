<?php
/**
 * AMVAC 2027 — Database Connection Singleton
 */

declare(strict_types=1);

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            
            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                // Never leak database password or full DSN in public production
                error_log("AMVAC Database Connection Error: " . $e->getMessage());
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    throw $e;
                }
                throw new RuntimeException("Database service is currently unavailable. Please try again shortly.");
            }
        }
        return self::$instance;
    }
}
