# AMVAC 2027 — Production Deployment & Upload Guide

This document provides clear, step-by-step instructions for deploying the **Africa Motor Vehicle Administration Conference 2027 (AMVAC 2027)** website to your live web hosting server.

---

## 📦 What is in the Deployment Package?

The production package (`amvac2027_production_release.zip`) is fully self-contained:

```text
AMVAC 2027 Production Release
├── config/                  # App, database & constant configuration
├── database/                # Schema, seeds, migrations & production_install.sql
├── public/                  # Web root (index.php, .htaccess, css, js, images)
├── src/                     # Core backend services, auth, validation & security
├── storage/                 # Writable storage directory with access protection
├── views/                   # HTML/PHP page templates & admin views
├── .env.production.example  # Production environment template
├── .htaccess                # Root web server rewrite & security rule set
├── index.php                # Root fallback forwarder for shared hosting
└── DEPLOYMENT_GUIDE.md      # This guide
```

*Note: Development files (`.git`, test suites, raw development screenshots, and local `.env`) have been excluded to keep the package lean, fast, and secure.*

---

## 🚀 Option 1: Deploying to Shared Hosting (cPanel / Namecheap / Hostinger / Bluehost)

### Step 1: Upload and Extract Files
1. Log in to your **cPanel** dashboard.
2. Open **File Manager** and navigate to your web root (`public_html` or your domain's document root folder).
3. Click **Upload** and upload `amvac2027_production_release.zip`.
4. Right-click the uploaded zip file and select **Extract**.
5. Ensure the extracted files and folders (`public/`, `config/`, `src/`, `views/`, `.htaccess`, etc.) reside directly in your target folder.

### Step 2: Create MySQL Database & User
1. In cPanel, navigate to **MySQL Databases** (or **MySQL Database Wizard**).
2. Create a new database (e.g. `cpaneluser_amvac2027`).
3. Create a new database user with a strong password.
4. Add the user to the database and grant **ALL PRIVILEGES**. Note down:
   - Database Name
   - Database User
   - Database Password
   - Database Host (usually `localhost` or `127.0.0.1`)

### Step 3: Import Database Schema & Seed Data
1. In cPanel, open **phpMyAdmin**.
2. Select your newly created database in the left sidebar.
3. Click the **Import** tab at the top.
4. Click **Choose File** and select `database/production_install.sql` (found inside the extracted package).
5. Click **Import** (or **Go**) at the bottom.
   > All 10 tables and initial settings will be created immediately.

### Step 4: Configure Environment (`.env`)
1. In cPanel File Manager, find `.env.production.example`.
2. Rename or copy it to `.env` (ensure "Show Hidden Files" is enabled in File Manager Settings).
3. Open `.env` in the cPanel Code Editor and update:
   ```ini
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourconferencedomain.org

   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=cpaneluser_amvac2027
   DB_USER=cpaneluser_dbuser
   DB_PASS=YourStrongPasswordHere
   ```
4. Save the file.

### Step 5: Verify File Permissions
1. Ensure `storage/` and `storage/mail/` have write permissions (chmod `755` or `775`).
2. Verify that the `.htaccess` files exist in both the root folder and `public/`.

---

## 🖥️ Option 2: Deploying to a VPS / Cloud Server (Ubuntu / Debian, Nginx / Apache)

### Requirements
- PHP 8.2 or 8.3 with extensions: `pdo_mysql`, `mbstring`, `openssl`, `curl`
- MySQL 8.0+ or MariaDB 10.4+
- Web server (Nginx or Apache 2.4+)
- SSL Certificate (Let's Encrypt / Certbot)

### Step 1: Upload Files
```bash
# Upload to /var/www/amvac2027
scp amvac2027_production_release.zip user@your-server-ip:/var/www/
cd /var/www/
unzip amvac2027_production_release.zip -d amvac2027
cd /var/www/amvac2027
```

### Step 2: Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/amvac2027
sudo find /var/www/amvac2027 -type d -exec chmod 755 {} \;
sudo find /var/www/amvac2027 -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/amvac2027/storage
```

### Step 3: Database Import
```bash
mysql -u root -p -e "CREATE DATABASE amvac2027 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p amvac2027 < database/production_install.sql
```

### Step 4: Configure `.env`
```bash
cp .env.production.example .env
nano .env
# Set APP_ENV=production, APP_DEBUG=false, APP_URL, and DB_* credentials
```

### Step 5: Nginx Configuration (Recommended for VPS)
If using Nginx, configure your virtual host to serve from the `/public` subdirectory:

```nginx
server {
    listen 80;
    server_name amvac2027.org www.amvac2027.org;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name amvac2027.org www.amvac2027.org;

    root /var/www/amvac2027/public;
    index index.php index.html;

    # SSL configuration
    ssl_certificate /etc/letsencrypt/live/amvac2027.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/amvac2027.org/privkey.pem;

    # Security Headers
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Prevent direct access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Static assets cache
    location ~* \.(jpg|jpeg|png|gif|svg|webp|css|js|ico)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```

---

## 🔒 Post-Deployment Verification Checklist

1. **Verify Public Routes (HTTP 200)**:
   - `https://yourdomain.org/` (Homepage)
   - `https://yourdomain.org/about` (About & Objectives)
   - `https://yourdomain.org/programme` (Programme & Venue)
   - `https://yourdomain.org/register` (Delegate Registration)
   - `https://yourdomain.org/contact` (Contact & Inquiry Form)
2. **Test Admin Portal**:
   - URL: `https://yourdomain.org/admin/login`
   - Initial Email: `admin@amvac2027.org`
   - Initial Password: `ChangeMe2027!Secure`
   - **Action Required:** Change this password immediately upon first login!
3. **Test Security Headers**:
   - Check headers with browser DevTools or `curl -I`:
     - `X-Content-Type-Options: nosniff`
     - `X-Frame-Options: SAMEORIGIN`
     - `X-XSS-Protection: 1; mode=block`
     - `Referrer-Policy: strict-origin-when-cross-origin`
4. **Test Sensitive Directory Protection**:
   - Attempt to access `https://yourdomain.org/.env` in your browser.
   - It should return **403 Forbidden** or **404 Not Found**, never the raw credentials.
5. **Test Registration Form**:
   - Submit a test registration and confirm reference code generation (format: `AMVAC-2027-XXXXXXXX`).
