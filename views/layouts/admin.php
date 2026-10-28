<?php
/**
 * AMVAC 2027 — Private Administrator Layout Shell
 */

$adminTitle = $adminTitle ?? 'AMVAC 2027 Admin Portal';
$currentAdminNav = $currentAdminNav ?? 'dashboard';
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($adminTitle) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('/assets/css/variables.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/base.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/components.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/admin.css') ?>">
</head>
<body class="admin-body">

  <header class="admin-header">
    <div class="admin-brand-group">
      <a href="/admin/dashboard" class="admin-brand" aria-label="AMVAC 2027 Admin Home">
        <img src="/assets/images/logo-official-white.png" alt="AMVAC 2027 Logo" class="admin-brand-logo" height="38">
      </a>
      <span class="admin-portal-badge">Secretariat Portal</span>
    </div>

    <?php if (Auth::check()): ?>
      <nav class="admin-nav-cluster">
        <a href="/admin/dashboard" class="admin-nav-link <?= ($currentAdminNav === 'dashboard' ? 'active' : '') ?>">Overview</a>
        <a href="/admin/registrations" class="admin-nav-link <?= ($currentAdminNav === 'registrations' ? 'active' : '') ?>">Registrations</a>
        <a href="/admin/payments" class="admin-nav-link <?= ($currentAdminNav === 'payments' ? 'active' : '') ?>">Payment Verification</a>
        <a href="/admin/enquiries" class="admin-nav-link <?= ($currentAdminNav === 'enquiries' ? 'active' : '') ?>">Enquiries</a>
        <a href="/admin/audit" class="admin-nav-link <?= ($currentAdminNav === 'audit' ? 'active' : '') ?>">Audit Logs</a>
        <a href="/" target="_blank" rel="noopener" class="admin-nav-link admin-nav-public">Public Site &nearr;</a>
        
        <div class="admin-user-pill">
          <span class="admin-user-name"><?= e($user['name'] ?? 'Admin') ?></span>
          <a href="/admin/logout" class="btn btn-outline-white btn-sm btn-admin-logout">Sign Out</a>
        </div>
      </nav>
    <?php endif; ?>
  </header>

  <main class="admin-main">
    <?php require __DIR__ . '/../partials/alerts.php'; ?>
    <?= $content ?>
  </main>

  <footer style="background: var(--navy-950); color: var(--slate-400); text-align: center; padding: var(--space-4); font-size: 0.75rem;">
    AMVAC 2027 Internal Administration & Audit System · Confidential & Restricted Access
  </footer>

  <script src="<?= asset('/assets/js/admin.js') ?>" defer></script>
</body>
</html>
