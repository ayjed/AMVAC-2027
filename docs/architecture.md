# AMVAC 2027 — Technical Architecture & Directory Specification

**Document Reference:** Governed by `01_PROJECT_SOURCE_OF_TRUTH.md` Section 6  
**Stack Baseline:** PHP 8.3+ (Native Vanilla PHP, Lightweight MVC/Controller Pattern), MySQL 8+ / MariaDB 10.4+, Semantic HTML5, Vanilla Modern CSS, Lightweight Vanilla JavaScript.  
**Strict Prohibition:** No Laravel, No React, No WordPress, No heavy CMS, No external ticket marketplaces.

---

## 1. Directory Structure

```text
AMVAC 2027/
│
├── 01_PROJECT_SOURCE_OF_TRUTH.md       # Governing specification (LOCKED)
├── 02_PHASE_TRACKER.md                 # Single source of truth for phase progress
├── 03_AGENT_PROMPTS_ALL_PHASES.md      # Phase instructions and acceptance criteria
│
├── docs/                               # Engineering & architectural documentation
│   ├── content-matrix.md               # Detailed page-by-page content inventory
│   ├── decisions.md                    # D-01 through D-12 decision register
│   ├── registration-flow.md            # Lifecycle, FSM, and payment verification spec
│   └── architecture.md                 # Technical architecture and DB design (this file)
│
├── config/                             # Application configuration
│   ├── app.php                         # Core app settings, environment helpers
│   ├── database.php                    # PDO MySQL connection factory & credentials
│   └── constants.php                   # Theme, canonical objectives, fee matrix
│
├── database/                           # Schema and data migrations
│   ├── schema.sql                      # Complete DDL script for all tables
│   ├── seed.sql                        # Default admin user and initial settings
│   └── migrate.php                     # CLI migration runner
│
├── src/                                # Core Application Logic (OOP / Functional)
│   ├── Auth.php                        # Admin session auth, password hashing, lockout
│   ├── Database.php                    # Singleton PDO instance with secure defaults
│   ├── Csrf.php                        # Cryptographic token generator and validator
│   ├── Validator.php                   # Sanitization and validation rules
│   ├── RegistrationService.php         # Registration creation, fee calculation, state machine
│   ├── PaymentService.php              # Payment attempt generator, webhook verification
│   ├── AuditService.php                # Structured immutable audit logging
│   └── Mailer.php                      # Transactional mail sender / staging file logger
│
├── public/                             # Web root (accessible to web server)
│   ├── index.php                       # Single front controller / router
│   ├── .htaccess                       # Apache rewrite rules and security headers
│   │
│   ├── assets/                         # Static assets
│   │   ├── css/
│   │   │   ├── variables.css           # Design tokens: palette, typography, spacing
│   │   │   ├── base.css                # Reset, typography, accessibility, mobile layout
│   │   │   ├── components.css          # Navigation, buttons, cards, modals, tables, badges
│   │   │   ├── pages.css               # Page-specific editorial layouts
│   │   │   └── admin.css               # Private admin interface styles
│   │   ├── js/
│   │   │   ├── app.js                  # Lightweight UI enhancements: mobile menu, modal, mask
│   │   │   └── admin.js                # Admin table filtering and verification dialogs
│   │   └── images/                     # Optimized visual assets
│   │       ├── logo-crest.svg          # AMVAC 2027 gold seal vector emblem
│   │       ├── hero-conference.webp    # Conference hall visual backdrop
│   │       ├── banjul-waterfront.webp  # Coastal Banjul editorial image
│   │       ├── africa-map-accent.svg   # Subtle continent vector silhouette
│   │       ├── fidelis-nnadi.webp      # Amb. Prince Fidelis Nnadi portrait
│   │       ├── njie-baldeh.webp        # Njie Baldeh portrait
│   │       └── adama-barrow.webp       # President Adama Barrow portrait (staging only)
│
├── views/                              # Presentation templates & partials
│   ├── layouts/
│   │   ├── main.php                    # Public page shell (head, nav, content, footer)
│   │   └── admin.php                   # Admin portal shell (sidebar, topbar, content)
│   ├── partials/
│   │   ├── header.php                  # Public navigation header & mobile drawer
│   │   ├── footer.php                  # Public footer with objectives summary & credits
│   │   ├── alerts.php                  # Flash notifications (success/error messages)
│   │   └── objectives-grid.php         # Reusable 8-objective component
│   ├── pages/
│   │   ├── home.php                    # Route: /
│   │   ├── about.php                   # Route: /about
│   │   ├── programme.php               # Route: /programme
│   │   ├── register.php                # Route: /register
│   │   └── contact.php                 # Route: /contact
│   └── admin/
│       ├── login.php                   # Route: /admin/login
│       ├── dashboard.php               # Route: /admin/dashboard
│       ├── registrations.php           # Route: /admin/registrations
│       ├── payments.php                # Route: /admin/payments
│       ├── enquiries.php               # Route: /admin/enquiries
│       └── audit.php                   # Route: /admin/audit
│
├── tests/                              # Verification test scripts
│   ├── run-tests.php                   # CLI test runner for validation & FSM logic
│   ├── RegistrationTest.php            # Form validation, fee calculation, tamper protection
│   └── SecurityTest.php                # CSRF, XSS escaping, SQL injection checks
│
├── .env.example                        # Environment variables template
├── .gitignore                          # Ignore .env, vendor, uploads, local DB dumps
└── README.md                           # Local runbook and environment setup
```

---

## 2. Canonical Routing Matrix

All requests pass through `public/index.php`. The router maps URLs to controller actions:

| HTTP Method | Route | Controller / View | Description |
|---|---|---|---|
| `GET` | `/` | `views/pages/home.php` | Homepage: hero, intro, objectives preview, leadership preview, CTA. |
| `GET` | `/about` | `views/pages/about.php` | About: full background, all 8 canonical objectives verbatim, leadership profiles. |
| `GET` | `/programme` | `views/pages/programme.php` | Programme: provisional schedule notice, thematic tracks, venue, travel info. |
| `GET` | `/register` | `views/pages/register.php` | Registration: form view, status lookup by reference. |
| `POST` | `/register` | `RegistrationService::create()` | Process new delegate registration; returns redirect to reference page. |
| `POST` | `/register/submit-payment` | `PaymentService::submitManualProof()` | Handle bank transfer reference submission -> `pending_manual_review`. |
| `GET` | `/contact` | `views/pages/contact.php` | Contact: secretariat coordinates, inquiry form. |
| `POST` | `/contact` | `EnquiryService::submit()` | Process contact message, store in DB, queue email. |
| `POST` | `/webhook/payment` | `PaymentService::handleWebhook()` | Signature-verified webhook receiver for payment gateways. |
| `GET` / `POST` | `/admin/login` | `views/admin/login.php` | Admin authentication endpoint. |
| `GET` | `/admin/logout` | `Auth::logout()` | Invalidate admin session and redirect. |
| `GET` | `/admin` / `/admin/dashboard` | `views/admin/dashboard.php` | Admin overview KPI metrics and status breakdowns. |
| `GET` | `/admin/registrations` | `views/admin/registrations.php` | Search, filter, inspect, and export delegates. |
| `GET` / `POST` | `/admin/payments` | `views/admin/payments.php` | Review and verify manual bank transfer submissions. |
| `GET` | `/admin/enquiries` | `views/admin/enquiries.php` | Contact form inbox and response tracking. |
| `GET` | `/admin/audit` | `views/admin/audit.php` | Immutable system audit log viewer. |

---

## 3. Database Schema Plan (MySQL / MariaDB)

```sql
-- 1. Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'super_admin',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    failed_login_attempts INT UNSIGNED NOT NULL DEFAULT 0,
    locked_until DATETIME NULL,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Registrations Table
CREATE TABLE IF NOT EXISTS registrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    public_reference VARCHAR(32) NOT NULL UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(191) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    country VARCHAR(100) NOT NULL,
    organization VARCHAR(200) NOT NULL,
    job_title VARCHAR(150) NOT NULL,
    participation_format ENUM('in_person_banjul', 'virtual_hybrid') NOT NULL DEFAULT 'in_person_banjul',
    fee_category ENUM('nigerian_delegate', 'international_delegate') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) NOT NULL,
    status ENUM('pending_payment', 'pending_manual_review', 'paid', 'failed', 'cancelled') NOT NULL DEFAULT 'pending_payment',
    payment_method VARCHAR(50) NULL,
    consent TINYINT(1) NOT NULL DEFAULT 1,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    verified_at DATETIME NULL,
    verified_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_reg_email (email),
    INDEX idx_reg_status (status),
    INDEX idx_reg_reference (public_reference)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Payment Attempts Table
CREATE TABLE IF NOT EXISTS payment_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    registration_id BIGINT UNSIGNED NOT NULL,
    provider VARCHAR(50) NOT NULL, -- 'paystack', 'bank_transfer', 'sandbox'
    attempt_reference VARCHAR(100) NOT NULL UNIQUE,
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) NOT NULL,
    status ENUM('initiated', 'successful', 'failed', 'abandoned') NOT NULL DEFAULT 'initiated',
    provider_transaction_id VARCHAR(100) NULL,
    response_payload LONGTEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Payment Events Table (Webhook Idempotency)
CREATE TABLE IF NOT EXISTS payment_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(50) NOT NULL,
    event_id VARCHAR(100) NOT NULL UNIQUE,
    event_type VARCHAR(100) NOT NULL,
    payload LONGTEXT NOT NULL,
    processed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Manual Payment Reviews Table (Bank Transfers)
CREATE TABLE IF NOT EXISTS manual_payment_reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    registration_id BIGINT UNSIGNED NOT NULL,
    bank_reference VARCHAR(100) NOT NULL,
    deposit_date DATE NULL,
    notes TEXT NULL,
    receipt_filename VARCHAR(255) NULL,
    review_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    reviewed_by INT UNSIGNED NULL,
    reviewed_at DATETIME NULL,
    admin_notes TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Contact Enquiries Table
CREATE TABLE IF NOT EXISTS contact_enquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(191) NOT NULL,
    phone VARCHAR(30) NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    replied_at DATETIME NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Audit Logs Table (Immutable)
CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    actor_type ENUM('admin', 'system', 'delegate') NOT NULL,
    actor_id VARCHAR(50) NULL,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(50) NOT NULL,
    target_id VARCHAR(50) NOT NULL,
    details JSON NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_action (action),
    INDEX idx_audit_target (target_type, target_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Content Entries Table (Controlled Editables)
CREATE TABLE IF NOT EXISTS content_entries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_key VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(200) NOT NULL,
    content_value LONGTEXT NOT NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    updated_by INT UNSIGNED NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Settings Table
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT NOT NULL,
    description VARCHAR(255) NULL,
    is_sensitive TINYINT(1) NOT NULL DEFAULT 0,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 4. Security Boundaries & Enforcement

1. **PDO Prepared Statements:** Every database query must use parametrized prepared statements with typed parameters (`$stmt->execute([':ref' => $ref])`). String concatenation into SQL queries is strictly prohibited.
2. **Output Escaping:** All user-supplied data rendered into HTML templates must pass through `htmlspecialchars($var, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.
3. **CSRF Tokens:** All state-changing POST requests (`/register`, `/contact`, `/admin/*`) require a cryptographically random session CSRF token (`Csrf::verify()`).
4. **Session Hardening:**
   - `cookie_httponly = true`
   - `cookie_secure = true` (in HTTPS / production)
   - `cookie_samesite = 'Lax'`
   - `session.use_strict_mode = 1`
   - Session ID regeneration on login (`session_regenerate_id(true)`).
5. **Rate Limiting:** IP-based request throttling on form endpoints (max 5 registration submissions per 10 minutes per IP; max 5 failed login attempts before 15-minute lockout).
6. **Zero Secrets in Repository:** Database credentials, encryption keys, and mail credentials reside only in `.env`.
