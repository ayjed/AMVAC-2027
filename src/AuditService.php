<?php
/**
 * AMVAC 2027 — Structured Audit Logging Service
 */

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class AuditService {
    public static function log(
        string $actorType,
        ?string $actorId,
        string $action,
        string $targetType,
        string $targetId,
        ?array $details = null
    ): void {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO audit_logs 
                    (actor_type, actor_id, action, target_type, target_id, details, ip_address, created_at)
                VALUES 
                    (:actor_type, :actor_id, :action, :target_type, :target_id, :details, :ip, NOW())
            ");

            $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
            $detailsJson = $details !== null ? json_encode($details, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

            $stmt->execute([
                ':actor_type'  => $actorType,
                ':actor_id'    => $actorId,
                ':action'      => $action,
                ':target_type' => $targetType,
                ':target_id'   => $targetId,
                ':details'     => $detailsJson,
                ':ip'          => $ip
            ]);
        } catch (Throwable $e) {
            // Never let audit log failure abort normal execution, but log to error log
            error_log("AMVAC AuditService failure: " . $e->getMessage());
        }
    }
}
