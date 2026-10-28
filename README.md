# Africa Motor Vehicle Administration Conference 2027 (AMVAC 2027)

Official web portal and registration platform for the **Africa Motor Vehicle Administration Conference 2027 (AMVAC 2027)**.

## Core Features
- **5 Canonical Public Routes:**
  - `/` (Home)
  - `/about` (About & Canonical Objectives)
  - `/programme` (Programme & Venue)
  - `/register` (Delegate Registration & Payment Verification)
  - `/contact` (Secretariat Contact & Inquiries)
- **Administrative Portal (`/admin`):**
  - Delegate registration tracking & status management
  - Offline bank transfer review & audit workflow
  - System audit logs and CSV export
- **Security & Integrity:**
  - Native PHP 8.2+ with PDO MySQL prepared statements
  - Strict CSRF token protection & Anti-XSS escaping
  - Tamper-proof server-side fee matrix locking (₦100,000 NGN / $50 USD)
  - Secure session policy and brute-force lockout defense

## Local Development
```bash
# 1. Copy environment template
cp .env.example .env

# 2. Run database migration & seed
php database/migrate.php

# 3. Start local development server
php -S localhost:8000 -t public
```

## Production Deployment
Please see [`DEPLOYMENT_GUIDE.md`](./DEPLOYMENT_GUIDE.md) for full deployment instructions for cPanel, Shared Hosting, Apache, and Nginx.
