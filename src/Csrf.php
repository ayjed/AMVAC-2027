<?php
/**
 * AMVAC 2027 — CSRF Defense Manager
 */

declare(strict_types=1);

class Csrf {
    public static function getToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function getField(): string {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(?string $token): bool {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function validateOrFail(?string $token): void {
        if (!self::verify($token)) {
            http_response_code(403);
            throw new RuntimeException("Security token expired or invalid (CSRF failure). Please refresh and submit again.");
        }
    }
}
