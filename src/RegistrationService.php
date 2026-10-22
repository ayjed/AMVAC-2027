<?php
/**
 * AMVAC 2027 — Registration Service
 */

declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/AuditService.php';
require_once __DIR__ . '/Mailer.php';
require_once __DIR__ . '/../config/constants.php';

class RegistrationService {
    /**
     * Generate non-sequential, unguessable public reference
     * Format: AMVAC-2027-XXXXXXXX (8 uppercase alphanumeric characters)
     */
    public static function generateReference(): string {
        $alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // Exclude ambiguous chars 0, 1, I, O
        $code = '';
        $max = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $code .= $alphabet[random_int(0, $max)];
        }
        return 'AMVAC-2027-' . $code;
    }

    /**
     * Create new delegate registration with strictly enforced server-side fees
     */
    public static function create(array $data, ?string $ip = null, ?string $userAgent = null): array {
        $categoryKey = $data['fee_category'] ?? '';
        if (!isset(AMVAC_FEE_MATRIX[$categoryKey])) {
            throw new InvalidArgumentException("Invalid fee category selected.");
        }

        $feeRule = AMVAC_FEE_MATRIX[$categoryKey];
        $fixedAmount = $feeRule['amount'];
        $fixedCurrency = $feeRule['currency'];

        $pdo = Database::getConnection();

        // Check for duplicate pending/paid registration with same email in past 10 minutes
        $dupStmt = $pdo->prepare("
            SELECT id, public_reference, status 
            FROM registrations 
            WHERE email = :email AND status IN ('pending_payment', 'pending_manual_review', 'paid') 
              AND created_at > (NOW() - INTERVAL 10 MINUTE)
            LIMIT 1
        ");
        $dupStmt->execute([':email' => $data['email']]);
        $existing = $dupStmt->fetch();
        if ($existing) {
            return [
                'success' => true,
                'is_existing' => true,
                'public_reference' => $existing['public_reference'],
                'status' => $existing['status']
            ];
        }

        $reference = self::generateReference();

        $stmt = $pdo->prepare("
            INSERT INTO registrations (
                public_reference, full_name, email, phone, country,
                organization, job_title, participation_format, fee_category,
                amount, currency, status, consent, ip_address, user_agent, created_at
            ) VALUES (
                :reference, :full_name, :email, :phone, :country,
                :organization, :job_title, :format, :category,
                :amount, :currency, 'pending_payment', 1, :ip, :ua, NOW()
            )
        ");

        $stmt->execute([
            ':reference'    => $reference,
            ':full_name'    => $data['full_name'],
            ':email'        => $data['email'],
            ':phone'        => $data['phone'],
            ':country'      => $data['country'],
            ':organization' => $data['organization'],
            ':job_title'    => $data['job_title'],
            ':format'       => $data['participation_format'] ?? 'in_person_banjul',
            ':category'     => $categoryKey,
            ':amount'       => $fixedAmount,
            ':currency'     => $fixedCurrency,
            ':ip'           => $ip,
            ':ua'           => mb_substr((string)$userAgent, 0, 255)
        ]);

        $regId = (int)$pdo->lastInsertId();

        AuditService::log('delegate', null, 'REGISTRATION_CREATED', 'registration', (string)$regId, [
            'reference' => $reference,
            'fee_category' => $categoryKey,
            'amount' => $fixedAmount,
            'currency' => $fixedCurrency
        ]);

        // Fetch saved registration
        $saved = self::findByReference($reference);
        if ($saved) {
            Mailer::sendRegistrationReceived($saved);
        }

        return [
            'success' => true,
            'is_existing' => false,
            'registration_id' => $regId,
            'public_reference' => $reference
        ];
    }

    public static function findByReference(string $reference): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM registrations WHERE public_reference = :ref LIMIT 1");
        $stmt->execute([':ref' => trim($reference)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getAll(array $filters = [], int $limit = 50, int $offset = 0): array {
        $pdo = Database::getConnection();
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(full_name LIKE :search OR email LIKE :search OR public_reference LIKE :search OR organization LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql = "SELECT * FROM registrations";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function countAll(array $filters = []): int {
        $pdo = Database::getConnection();
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(full_name LIKE :search OR email LIKE :search OR public_reference LIKE :search OR organization LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql = "SELECT COUNT(*) FROM registrations";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
