-- ==============================================================================
-- AMVAC 2027 — Consolidated Production Database Schema & Seed Data
-- ==============================================================================
-- Stack: MySQL 8.0+ / MariaDB 10.4+
-- Charset: utf8mb4 (Unicode 5.2.0 / full emoji & accent support)
--
-- USAGE INSTRUCTIONS:
-- 1. Create a database on your host (e.g. via cPanel MySQL Databases or phpMyAdmin)
-- 2. Select that database
-- 3. Import this single SQL file
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Administrators Table
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'super_admin',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `failed_login_attempts` INT UNSIGNED NOT NULL DEFAULT 0,
    `locked_until` DATETIME NULL,
    `last_login_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 2. Registrations Table
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `registrations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `public_reference` VARCHAR(32) NOT NULL UNIQUE,
    `full_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `country` VARCHAR(100) NOT NULL,
    `organization` VARCHAR(200) NOT NULL,
    `job_title` VARCHAR(150) NOT NULL,
    `participation_format` ENUM('in_person_banjul', 'virtual_hybrid') NOT NULL DEFAULT 'in_person_banjul',
    `fee_category` ENUM('nigerian_delegate', 'international_delegate') NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `currency` VARCHAR(3) NOT NULL,
    `status` ENUM('pending_payment', 'pending_manual_review', 'paid', 'failed', 'cancelled') NOT NULL DEFAULT 'pending_payment',
    `payment_method` VARCHAR(50) NULL,
    `consent` TINYINT(1) NOT NULL DEFAULT 1,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `verified_at` DATETIME NULL,
    `verified_by` INT UNSIGNED NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_reg_email` (`email`),
    INDEX `idx_reg_status` (`status`),
    INDEX `idx_reg_reference` (`public_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. Payment Attempts (Online & Offline Initiations)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payment_attempts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `registration_id` BIGINT UNSIGNED NOT NULL,
    `provider` VARCHAR(50) NOT NULL, -- 'paystack', 'bank_transfer', 'sandbox'
    `attempt_reference` VARCHAR(100) NOT NULL UNIQUE,
    `amount` DECIMAL(10, 2) NOT NULL,
    `currency` VARCHAR(3) NOT NULL,
    `status` ENUM('initiated', 'successful', 'failed', 'abandoned') NOT NULL DEFAULT 'initiated',
    `provider_transaction_id` VARCHAR(100) NULL,
    `response_payload` LONGTEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payment_registration` FOREIGN KEY (`registration_id`) REFERENCES `registrations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. Payment Events (Webhook Idempotency & Replay Defense)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payment_events` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `provider` VARCHAR(50) NOT NULL,
    `event_id` VARCHAR(100) NOT NULL UNIQUE,
    `event_type` VARCHAR(100) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `processed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. Manual Payment Reviews (Bank Transfers Queue)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `manual_payment_reviews` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `registration_id` BIGINT UNSIGNED NOT NULL,
    `bank_reference` VARCHAR(100) NOT NULL,
    `deposit_date` DATE NULL,
    `notes` TEXT NULL,
    `receipt_filename` VARCHAR(255) NULL,
    `review_status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `reviewed_by` INT UNSIGNED NULL,
    `reviewed_at` DATETIME NULL,
    `admin_notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_manual_registration` FOREIGN KEY (`registration_id`) REFERENCES `registrations`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_manual_admin` FOREIGN KEY (`reviewed_by`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. Contact Inquiries Table
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_enquiries` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `replied_at` DATETIME NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 7. Audit Logs Table (Strict Immutability)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `actor_type` ENUM('admin', 'system', 'delegate') NOT NULL,
    `actor_id` VARCHAR(50) NULL,
    `action` VARCHAR(100) NOT NULL,
    `target_type` VARCHAR(50) NOT NULL,
    `target_id` VARCHAR(50) NOT NULL,
    `details` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_target` (`target_type`, `target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 8. Content Entries Table (Controlled Content Editing)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `content_entries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `content_key` VARCHAR(100) NOT NULL UNIQUE,
    `title` VARCHAR(200) NOT NULL,
    `content_value` LONGTEXT NOT NULL,
    `is_published` TINYINT(1) NOT NULL DEFAULT 1,
    `updated_by` INT UNSIGNED NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 9. System Settings Table
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
    `setting_key` VARCHAR(100) PRIMARY KEY,
    `setting_value` TEXT NOT NULL,
    `description` VARCHAR(255) NULL,
    `is_sensitive` TINYINT(1) NOT NULL DEFAULT 0,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 10. Transactional Email Logs Table
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `email_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `recipient_email` VARCHAR(191) NOT NULL,
    `recipient_name` VARCHAR(150) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `template` VARCHAR(100) NOT NULL,
    `status` ENUM('queued', 'sent', 'failed') NOT NULL DEFAULT 'sent',
    `retry_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `error_message` TEXT NULL,
    `sent_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_email_recipient` (`recipient_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- INITIAL SEED DATA
-- ==============================================================================

-- Default Super Administrator
-- Default credentials: admin@amvac2027.org / ChangeMe2027!Secure
-- MUST be updated on first login via the Admin Portal
INSERT INTO `admins` (`name`, `email`, `password_hash`, `role`, `is_active`)
VALUES (
    'AMVAC Lead Administrator',
    'admin@amvac2027.org',
    '$2y$10$pxiIvScNJY6igvWkIugnNuBPucxrkL00XlEQCMRS96yb3gEjvwUZq',
    'super_admin',
    1
) ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Default System Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`, `is_sensitive`) VALUES
('site_title', 'Africa Motor Vehicle Administration Conference 2027', 'Official Public Conference Name', 0),
('event_dates', '15–19 March 2027', 'Provisional Event Dates (D-02)', 0),
('event_venue', 'BICC Conference Centre, Banjul, The Gambia', 'Provisional Conference Venue (D-02)', 0),
('event_format', 'Hybrid – Abuja & Banjul', 'Participation Modality (D-02)', 0),
('payment_gateway_status', 'sandbox_stub', 'Payment Gateway State (D-07: live disabled until authorized)', 0),
('bank_display_public', '0', 'Flag to reveal bank account details on public pages (0=hidden, 1=visible)', 1),
('bank_account_beneficiary', 'PENDING CLIENT APPROVAL', 'Beneficiary name on Bloom Bank Africa account (D-06)', 1)
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- Initial Controlled Content Entries
INSERT INTO `content_entries` (`content_key`, `title`, `content_value`, `is_published`) VALUES
('hero_intro', 'Hero Introduction Banner', 'Enhancing Continental Security, Road Safety and Economic Integration Through Effective Motor Vehicle Administration in Africa.', 1),
('about_summary', 'Conference Purpose Statement', 'The Africa Motor Vehicle Administration Conference 2027 is a continental platform for dialogue, collaboration and action on effective motor vehicle administration. The conference brings together policymakers, regulators, road safety experts and stakeholders to promote harmonized systems, stronger security, safer roads and economic integration across Africa.', 1),
('programme_status_note', 'Official Schedule Notice', 'The detailed day-by-day conference timetable, plenary speaker roster and technical working sessions are currently undergoing final committee sign-off and will be published upon ratification.', 1)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

SET FOREIGN_KEY_CHECKS = 1;
