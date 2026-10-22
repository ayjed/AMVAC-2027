<?php
/**
 * AMVAC 2027 — Main Public Layout Shell
 */

$pageTitle = $pageTitle ?? 'AMVAC 2027 — Africa Motor Vehicle Administration Conference';
$metaDescription = $metaDescription ?? 'Official website for the Africa Motor Vehicle Administration Conference 2027 (AMVAC 2027). Enhancing Continental Security, Road Safety and Economic Integration.';
$currentRoute = $currentRoute ?? '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($metaDescription) ?>">

  <!-- Open Graph Meta -->
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($metaDescription) ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?= asset('/assets/images/logo-crest.svg') ?>">

  <!-- Google Fonts: Playfair Display & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Core Design Tokens & Scoped Stylesheets -->
  <link rel="stylesheet" href="<?= asset('/assets/css/variables.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/base.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/components.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/pages.css') ?>">
</head>
<body>
  <!-- Accessible Bypass Navigation (WCAG 2.2 AA) -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Navigation Header -->
  <?php require __DIR__ . '/../partials/header.php'; ?>

  <!-- Main View Area -->
  <main class="main-content" id="main-content">
    <div class="container" style="padding-top: var(--space-4);">
      <?php require __DIR__ . '/../partials/alerts.php'; ?>
    </div>
    <?= $content ?>
  </main>

  <!-- Global Footer -->
  <?php require __DIR__ . '/../partials/footer.php'; ?>

  <!-- Client-side Lightweight Scripts -->
  <script src="<?= asset('/assets/js/app.js') ?>" defer></script>
</body>
</html>
