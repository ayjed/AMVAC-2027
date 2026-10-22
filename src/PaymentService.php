<?php
/**
 * AMVAC 2027 — Payment Service & Gateway Adapter
 */

declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/AuditService.php';
require_once __DIR__ . '/Mailer.php';
require_once __DIR__ . '/RegistrationService.php';

class PaymentService {
    /**
     * Submit Bank Transfer Reference for manual administrative reconciliation
     * CRITICAL RULE: NEVER marks paid automatically!
     */
    public static function submitManualProof(string $reference, string $bankReference, ?string $depositDate, ?string $notes): array {
        $registration = RegistrationService::findByReference($reference);
        if (!$registration) {
            throw new InvalidArgumentException("Registration record not found.");
        }

        if ($registration['status'] === 'paid') {
            return [
                'success' => true,
                'status' => 'paid',
                'message' => 'Registration is already verified and paid.'
            ];
        }

        $pdo = Database::getConnection();

        // Insert into manual_payment_reviews
        $stmt = $pdo->prepare("
            INSERT INTO manual_payment_reviews 
                (registration_id, bank_reference, deposit_date, notes, review_status, created_at)
            VALUES 
                (:reg_id, :bank_ref, :deposit_date, :notes, 'pending', NOW())
        ");

        $stmt->execute([
            ':reg_id'       => $registration['id'],
            ':bank_ref'     => trim($bankReference),
            ':deposit_date' => !empty($depositDate) ? $depositDate : null,
            ':notes'        => $notes
        ]);

        $reviewId = (int)$pdo->lastInsertId();

        // Update registration status to pending_manual_review
        $updateStmt = $pdo->prepare("
            UPDATE registrations 
            SET status = 'pending_manual_review', payment_method = 'bank_transfer', updated_at = NOW() 
            WHERE id = :id
        ");
        $updateStmt->execute([':id' => $registration['id']]);

        AuditService::log('delegate', null, 'PAYMENT_PROOF_SUBMITTED', 'registration', (string)$registration['id'], [
            'bank_reference' => $bankReference,
            'review_id' => $reviewId
        ]);

        // Send acknowledgement email that proof is queued for audit
        Mailer::sendBankProofQueued($registration, $bankReference);

        return [
            'success' => true,
            'status' => 'pending_manual_review',
            'message' => 'Your bank transfer reference has been received and queued for administrative reconciliation against Bloom Bank Africa records.'
        ];
    }

    /**
     * Admin Review & Verification of Bank Transfer
     * Requires authorized administrator session
     */
    public static function reviewManualPayment(int $reviewId, string $decision, int $adminId, ?string $adminNotes = null): array {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            SELECT r.*, m.id AS review_id, m.bank_reference, m.review_status
            FROM manual_payment_reviews m
            JOIN registrations r ON m.registration_id = r.id
            WHERE m.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $reviewId]);
        $record = $stmt->fetch();

        if (!$record) {
            throw new InvalidArgumentException("Review record not found.");
        }

        if ($decision === 'approve') {
            // Update review record
            $reviewStmt = $pdo->prepare("
                UPDATE manual_payment_reviews 
                SET review_status = 'approved', reviewed_by = :admin_id, reviewed_at = NOW(), admin_notes = :notes 
                WHERE id = :id
            ");
            $reviewStmt->execute([
                ':admin_id' => $adminId,
                ':notes'    => $adminNotes,
                ':id'       => $reviewId
            ]);

            // Update registration status to paid
            $regStmt = $pdo->prepare("
                UPDATE registrations 
                SET status = 'paid', payment_method = 'bank_transfer', verified_at = NOW(), verified_by = :admin_id, updated_at = NOW() 
                WHERE id = :id
            ");
            $regStmt->execute([
                ':admin_id' => $adminId,
                ':id'       => $record['id']
            ]);

            AuditService::log('admin', (string)$adminId, 'PAYMENT_MANUAL_VERIFIED', 'registration', (string)$record['id'], [
                'review_id'      => $reviewId,
                'bank_reference' => $record['bank_reference'],
                'amount'         => $record['amount'],
                'currency'       => $record['currency'],
                'admin_notes'    => $adminNotes
            ]);

            // Send Official Confirmation
            $updatedReg = RegistrationService::findById((int)$record['id']);
            if ($updatedReg) {
                Mailer::sendPaymentConfirmed($updatedReg);
            }

            return ['success' => true, 'status' => 'paid', 'message' => 'Payment verified and confirmed successfully. Confirmation receipt dispatched to delegate.'];
        } elseif ($decision === 'reject') {
            $reviewStmt = $pdo->prepare("
                UPDATE manual_payment_reviews 
                SET review_status = 'rejected', reviewed_by = :admin_id, reviewed_at = NOW(), admin_notes = :notes 
                WHERE id = :id
            ");
            $reviewStmt->execute([
                ':admin_id' => $adminId,
                ':notes'    => $adminNotes,
                ':id'       => $reviewId
            ]);

            $regStmt = $pdo->prepare("
                UPDATE registrations 
                SET status = 'pending_payment', updated_at = NOW() 
                WHERE id = :id
            ");
            $regStmt->execute([':id' => $record['id']]);

            AuditService::log('admin', (string)$adminId, 'PAYMENT_MANUAL_REJECTED', 'registration', (string)$record['id'], [
                'review_id'   => $reviewId,
                'admin_notes' => $adminNotes
            ]);

            $reason = $adminNotes ?: "The submitted transfer reference could not be matched with bank ledger records.";
            Mailer::sendPaymentRejected($record, $reason);

            return ['success' => true, 'status' => 'rejected', 'message' => 'Payment reference was rejected and delegate has been notified.'];
        }

        throw new InvalidArgumentException("Invalid decision action.");
    }

    /**
     * Staging Sandbox Payment Simulator
     * Explicitly for local/staging testing; blocked in live production
     */
    public static function processSandboxPayment(string $reference): array {
        if (defined('APP_ENV') && APP_ENV === 'production') {
            throw new RuntimeException("Sandbox payments are disabled in production.");
        }

        $registration = RegistrationService::findByReference($reference);
        if (!$registration) {
            throw new InvalidArgumentException("Registration not found.");
        }

        $pdo = Database::getConnection();

        // Record payment attempt
        $attemptRef = 'SBX-' . strtoupper(bin2hex(random_bytes(6)));
        $stmt = $pdo->prepare("
            INSERT INTO payment_attempts 
                (registration_id, provider, attempt_reference, amount, currency, status, created_at)
            VALUES 
                (:reg_id, 'sandbox', :ref, :amount, :currency, 'successful', NOW())
        ");
        $stmt->execute([
            ':reg_id'   => $registration['id'],
            ':ref'      => $attemptRef,
            ':amount'   => $registration['amount'],
            ':currency' => $registration['currency']
        ]);

        // Transition registration to paid
        $updateStmt = $pdo->prepare("
            UPDATE registrations 
            SET status = 'paid', payment_method = 'sandbox_test', verified_at = NOW(), updated_at = NOW() 
            WHERE id = :id
        ");
        $updateStmt->execute([':id' => $registration['id']]);

        AuditService::log('system', null, 'PAYMENT_SANDBOX_SIMULATED', 'registration', (string)$registration['id'], [
            'attempt_reference' => $attemptRef,
            'amount'            => $registration['amount'],
            'currency'          => $registration['currency']
        ]);

        $updatedReg = RegistrationService::findById((int)$registration['id']);
        if ($updatedReg) {
            Mailer::sendPaymentConfirmed($updatedReg);
        }

        return [
            'success' => true,
            'status' => 'paid',
            'message' => 'Sandbox payment completed successfully. Delegate accreditation confirmed.'
        ];
    }
}
