<?php
/**
 * AMVAC 2027 — Global Site Header & Navigation
 */

$currentRoute = $currentRoute ?? '/';
?>
<header class="site-header">
  <div class="container">
    <div class="header-inner">
      <!-- Brand Cluster with Official AMVAC 2027 Logo -->
      <a href="/" class="brand-link" aria-label="AMVAC 2027 — Africa Motor Vehicle Administration Conference">
        <img src="/assets/images/logo-official-white.png" alt="AMVAC 2027 — Africa Motor Vehicle Administration Conference" class="brand-logo-img" height="56">
      </a>

      <!-- Desktop Navigation (Locked 5 Public Pages) -->
      <nav class="main-nav" aria-label="Main Navigation">
        <ul class="nav-list">
          <li>
            <a href="/" class="nav-link <?= ($currentRoute === '/' ? 'active' : '') ?>">Home</a>
          </li>
          <li>
            <a href="/about" class="nav-link <?= ($currentRoute === '/about' ? 'active' : '') ?>">About & Leadership</a>
          </li>
          <li>
            <a href="/programme" class="nav-link <?= ($currentRoute === '/programme' ? 'active' : '') ?>">Programme & Venue</a>
          </li>
          <li>
            <a href="/contact" class="nav-link <?= ($currentRoute === '/contact' ? 'active' : '') ?>">Contact</a>
          </li>
        </ul>
        <a href="/register" class="btn btn-gold btn-sm btn-header-cta<?= ($currentRoute === '/register' ? ' active' : '') ?>">Register Now &rarr;</a>
      </nav>

      <!-- Mobile Navigation Toggle -->
      <button class="mobile-nav-toggle" aria-expanded="false" aria-controls="mobileDrawer" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>

  <!-- Mobile Drawer Menu -->
  <div id="mobileDrawer" class="mobile-drawer">
    <ul class="mobile-nav-list">
      <li><a href="/" class="mobile-nav-link <?= ($currentRoute === '/' ? 'active' : '') ?>">Home</a></li>
      <li><a href="/about" class="mobile-nav-link <?= ($currentRoute === '/about' ? 'active' : '') ?>">About & Leadership</a></li>
      <li><a href="/programme" class="mobile-nav-link <?= ($currentRoute === '/programme' ? 'active' : '') ?>">Programme & Venue</a></li>
      <li><a href="/contact" class="mobile-nav-link <?= ($currentRoute === '/contact' ? 'active' : '') ?>">Contact</a></li>
    </ul>
    <a href="/register" class="btn btn-gold btn-block">Register Now &rarr;</a>
  </div>
</header>
