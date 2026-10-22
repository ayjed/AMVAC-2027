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
    <a href="/admin" class="admin-brand">
      <img src="/assets/images/logo-crest.svg" alt="AMVAC Seal" width="32" height="32">
      <span>AMVAC 2027 Secretariat Admin</span>
    </a>

    <?php if (Auth::check()): ?>
      <nav class="admin-nav-cluster">
        <a href="/admin/dashboard" class="admin-nav-link <?= ($currentAdminNav === 'dashboard' ? 'active' : '') ?>">Overview</a>
        <a href="/admin/registrations" class="admin-nav-link <?= ($currentAdminNav === 'registrations' ? 'active' : '') ?>">Registrations</a>
        <a href="/admin/payments" class="admin-nav-link <?= ($currentAdminNav === 'payments' ? 'active' : '') ?>">Payment Verification</a>
        <a href="/admin/enquiries" class="admin-nav-link <?= ($currentAdminNav === 'enquiries' ? 'active' : '') ?>">Enquiries</a>
        <a href="/admin/audit" class="admin-nav-link <?= ($currentAdminNav === 'audit' ? 'active' : '') ?>">Audit Logs</a>
        
        <div class="admin-user-pill">
          <span><?= e($user['name'] ?? 'Admin') ?></span>
          <a href="/admin/logout" class="btn btn-outline-white btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Sign Out</a>
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
