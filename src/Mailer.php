<?php
/**
 * AMVAC 2027 — Transactional Mailer & Staging Mail Logger
 */

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class Mailer {
    public static function send(string $recipientEmail, string $recipientName, string $subject, string $template, string $htmlBody): bool {
        $pdo = Database::getConnection();
        $status = 'sent';
        $errorMessage = null;

        // In staging/local development, record email safely to disk and database log
        $storageDir = dirname(__DIR__) . '/storage/mail';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $mailRecord = [
            'to'        => "{$recipientName} <{$recipientEmail}>",
            'subject'   => $subject,
            'template'  => $template,
            'date'      => date('Y-m-d H:i:s'),
            'body'      => $htmlBody
        ];

        $safeFile = $storageDir . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $recipientEmail) . '.json';
        file_put_contents($safeFile, json_encode($mailRecord, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Insert into email_logs
        try {
            $stmt = $pdo->prepare("
                INSERT INTO email_logs 
                    (recipient_email, recipient_name, subject, template, status, error_message, sent_at)
                VALUES 
                    (:email, :name, :subject, :template, :status, :error, NOW())
            ");
            $stmt->execute([
                ':email'    => $recipientEmail,
                ':name'     => $recipientName,
                ':subject'  => $subject,
                ':template' => $template,
                ':status'   => $status,
                ':error'    => $errorMessage
            ]);
        } catch (Throwable $e) {
            error_log("Failed to insert email_logs: " . $e->getMessage());
        }

        return true;
    }

    public static function sendRegistrationReceived(array $registration): bool {
        $subject = "AMVAC 2027 Registration Received — Ref: {$registration['public_reference']}";
        $body = "
            <h2>Africa Motor Vehicle Administration Conference 2027</h2>
            <p>Dear {$registration['full_name']},</p>
            <p>Your registration for AMVAC 2027 has been received in <strong>pending payment</strong> status.</p>
            <p><strong>Registration Reference:</strong> {$registration['public_reference']}<br>
            <strong>Category:</strong> " . ($registration['fee_category'] === 'nigerian_delegate' ? 'Nigerian Resident Delegate' : 'International Delegate') . "<br>
            <strong>Fee:</strong> {$registration['currency']} " . number_format((float)$registration['amount'], 2) . "</p>
            <p>To finalize your accreditation, please visit your reference portal to complete payment or submit transfer reference:<br>
            <a href='" . APP_URL . "/register?ref={$registration['public_reference']}'>" . APP_URL . "/register?ref={$registration['public_reference']}</a></p>
            <p>Kind regards,<br>AMVAC 2027 Conference Secretariat</p>
        ";

        return self::send($registration['email'], $registration['full_name'], $subject, 'registration_received', $body);
    }

    public static function sendBankProofQueued(array $registration, string $bankRef): bool {
        $subject = "AMVAC 2027 — Bank Transfer Proof Received ({$registration['public_reference']})";
        $body = "
            <h2>Africa Motor Vehicle Administration Conference 2027</h2>
            <p>Dear {$registration['full_name']},</p>
            <p>We have received your bank transfer reference: <strong>{$bankRef}</strong> for registration <strong>{$registration['public_reference']}</strong>.</p>
            <p>Your status is currently <strong>Under Administrative Review</strong>. Our finance team is reconciling this reference against Bloom Bank Africa account records. Once verified, your official accreditation pass and confirmation voucher will be issued.</p>
            <p>You may check your status anytime at:<br>
            <a href='" . APP_URL . "/register?ref={$registration['public_reference']}'>" . APP_URL . "/register?ref={$registration['public_reference']}</a></p>
            <p>Secretariat Desk, AMVAC 2027</p>
        ";

        return self::send($registration['email'], $registration['full_name'], $subject, 'bank_proof_queued', $body);
    }

    public static function sendPaymentConfirmed(array $registration): bool {
        $subject = "OFFICIAL CONFIRMATION: AMVAC 2027 Accreditation Pass ({$registration['public_reference']})";
        $body = "
            <h2>Africa Motor Vehicle Administration Conference 2027</h2>
            <p>Dear {$registration['full_name']},</p>
            <p>We are pleased to confirm that your delegate payment of <strong>{$registration['currency']} " . number_format((float)$registration['amount'], 2) . "</strong> has been <strong>VERIFIED AND APPROVED</strong>.</p>
            <p><strong>Official Accreditation Pass Reference:</strong> {$registration['public_reference']}<br>
            <strong>Organization:</strong> {$registration['organization']}<br>
            <strong>Designation:</strong> {$registration['job_title']}<br>
            <strong>Event Dates:</strong> 15–19 March 2027<br>
            <strong>Location:</strong> Banjul, The Gambia</p>
            <p>Please present this confirmation reference at the conference accreditation secretariat upon arrival.</p>
            <p>Warm regards,<br>Conference Convener & Secretariat</p>
        ";

        return self::send($registration['email'], $registration['full_name'], $subject, 'payment_confirmed', $body);
    }

    public static function sendPaymentRejected(array $registration, string $reason): bool {
        $subject = "AMVAC 2027 — Payment Reference Discrepancy Notice ({$registration['public_reference']})";
        $body = "
            <h2>Africa Motor Vehicle Administration Conference 2027</h2>
            <p>Dear {$registration['full_name']},</p>
            <p>We are contacting you regarding registration <strong>{$registration['public_reference']}</strong>.</p>
            <p>Our finance desk was unable to reconcile your submitted payment reference against bank records. Reason noted: <em>{$reason}</em>.</p>
            <p>Please check your reference portal to resubmit valid transfer details or contact the secretariat:<br>
            <a href='" . APP_URL . "/register?ref={$registration['public_reference']}'>" . APP_URL . "/register?ref={$registration['public_reference']}</a></p>
            <p>AMVAC 2027 Finance Secretariat</p>
        ";

        return self::send($registration['email'], $registration['full_name'], $subject, 'payment_rejected', $body);
    }
}
