<?php
/**
 * AMVAC 2027 — Administrator Authentication & Session Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/AuditService.php';

class Auth {
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 15;

    public static function check(): bool {
        return !empty($_SESSION['admin_id']);
    }

    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }
        return $_SESSION['admin_user'] ?? null;
    }

    public static function id(): ?int {
        return $_SESSION['admin_id'] ?? null;
    }

    public static function requireAuth(): void {
        if (!self::check()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/admin';
            header('Location: /admin/login');
            exit;
        }
    }

    public static function attempt(string $email, string $password): bool {
        $pdo = Database::getConnection();
        $email = strtolower(trim($email));

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch();

        if (!$admin || !$admin['is_active']) {
            AuditService::log('admin', null, 'LOGIN_FAILED_UNKNOWN_USER', 'auth', $email);
            return false;
        }

        // Check if locked out
        if ($admin['locked_until'] !== null) {
            $lockedUntil = strtotime($admin['locked_until']);
            if ($lockedUntil > time()) {
                AuditService::log('admin', (string)$admin['id'], 'LOGIN_ATTEMPT_WHILE_LOCKED', 'auth', $email);
                throw new RuntimeException("Account is temporarily locked due to multiple failed login attempts. Please try again later.");
            }
        }

        // Verify password
        if (!password_verify($password, $admin['password_hash'])) {
            $attempts = (int)$admin['failed_login_attempts'] + 1;
            $lockedUntil = null;

            if ($attempts >= self::MAX_ATTEMPTS) {
                $lockedUntil = date('Y-m-d H:i:s', time() + (self::LOCKOUT_MINUTES * 60));
            }

            $updateStmt = $pdo->prepare("
                UPDATE admins 
                SET failed_login_attempts = :attempts, locked_until = :locked_until 
                WHERE id = :id
            ");
            $updateStmt->execute([
                ':attempts'     => $attempts,
                ':locked_until' => $lockedUntil,
                ':id'           => $admin['id']
            ]);

            AuditService::log('admin', (string)$admin['id'], 'LOGIN_FAILED_BAD_PASSWORD', 'auth', $email, ['attempts' => $attempts]);
            return false;
        }

        // Login successful: reset failed attempts, update last login, regenerate session
        $updateStmt = $pdo->prepare("
            UPDATE admins 
            SET failed_login_attempts = 0, locked_until = NULL, last_login_at = NOW() 
            WHERE id = :id
        ");
        $updateStmt->execute([':id' => $admin['id']]);

        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_user'] = [
            'id'    => (int)$admin['id'],
            'name'  => $admin['name'],
            'email' => $admin['email'],
            'role'  => $admin['role']
        ];

        AuditService::log('admin', (string)$admin['id'], 'LOGIN_SUCCESSFUL', 'auth', $email);
        return true;
    }

    public static function logout(): void {
        if (self::check()) {
            AuditService::log('admin', (string)self::id(), 'LOGOUT', 'auth', (string)self::id());
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}
