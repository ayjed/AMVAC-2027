<?php
/**
 * AMVAC 2027 — Application Configuration & Core Helpers
 */

declare(strict_types=1);

require_once __DIR__ . '/constants.php';

// Load .env if present
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Environment Detection
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_DEBUG', (getenv('APP_DEBUG') ?: 'true') === 'true');
define('APP_URL', getenv('APP_URL') ?: '');

// Start Hardened Session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

/**
 * Escapes output safely for HTML context (Anti-XSS).
 */
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Generate application URL.
 */
function url(string $path = '/'): string {
    $path = '/' . ltrim($path, '/');
    return $path;
}

/**
 * Generate asset URL.
 */
function asset(string $path): string {
    return url($path);
}

/**
 * Flash message helpers.
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
