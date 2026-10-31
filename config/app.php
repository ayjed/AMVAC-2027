<?php
/**
 * AMVAC 2027 — Application Configuration & Core Helpers
 */

declare(strict_types=1);

require_once __DIR__ . '/constants.php';

// Load .env with multi-path fallback and BOM resilience
if (!function_exists('amvac_load_env')) {
    function amvac_load_env(): array {
        if (!isset($GLOBALS['AMVAC_ENV'])) {
            $GLOBALS['AMVAC_ENV'] = [];
        }

        $candidates = [
            dirname(__DIR__) . '/.env',
            dirname(__DIR__) . '/.env.production',
            dirname(__DIR__, 2) . '/.env',
            dirname(__DIR__) . '/public/.env',
            __DIR__ . '/.env',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate) && is_readable($candidate)) {
                $content = file_get_contents($candidate);
                // Strip UTF-8 BOM if present
                $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
                $lines = preg_split('/\r\n|\r|\n/', $content);

                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#')) {
                        continue;
                    }
                    if (str_contains($line, '=')) {
                        [$key, $value] = explode('=', $line, 2);
                        $key = trim($key);
                        $value = trim($value, " \t\n\r\0\x0B\"'");
                        $GLOBALS['AMVAC_ENV'][$key] = $value;
                        $_ENV[$key] = $value;
                        $_SERVER[$key] = $value;
                        @putenv("{$key}={$value}");
                    }
                }
                break;
            }
        }

        return $GLOBALS['AMVAC_ENV'];
    }
}

if (!function_exists('amvac_env')) {
    function amvac_env(string $key, mixed $default = null): mixed {
        if (!isset($GLOBALS['AMVAC_ENV'])) {
            amvac_load_env();
        }
        if (isset($GLOBALS['AMVAC_ENV'][$key]) && $GLOBALS['AMVAC_ENV'][$key] !== '') {
            return $GLOBALS['AMVAC_ENV'][$key];
        }
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return $_SERVER[$key];
        }
        $val = getenv($key);
        if ($val !== false && $val !== '') {
            return $val;
        }
        return $default;
    }
}

amvac_load_env();

// Environment Detection
define('APP_ENV', (string)amvac_env('APP_ENV', 'development'));
define('APP_DEBUG', filter_var(amvac_env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN));
define('APP_URL', (string)amvac_env('APP_URL', ''));

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
