<?php
/**
 * AMVAC 2027 — Global Site Header & Navigation
 */

$currentRoute = $currentRoute ?? '/';
?>
<header class="site-header">
  <div class="container">
    <div class="header-inner">
      <!-- Brand Cluster -->
      <a href="/" class="brand-link" aria-label="AMVAC 2027 Home">
        <img src="/assets/images/logo-crest.svg" alt="AMVAC 2027 Crest" class="brand-crest" width="48" height="48">
        <div class="brand-text">
          <span class="brand-title">AMVAC 2027</span>
          <span class="brand-subtitle">Africa Motor Vehicle Administration Conference</span>
        </div>
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
            <a href="/register" class="nav-link <?= ($currentRoute === '/register' ? 'active' : '') ?>">Registration</a>
          </li>
          <li>
            <a href="/contact" class="nav-link <?= ($currentRoute === '/contact' ? 'active' : '') ?>">Contact</a>
          </li>
        </ul>
        <a href="/register" class="btn btn-gold btn-sm">Register Now</a>
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
      <li><a href="/register" class="mobile-nav-link <?= ($currentRoute === '/register' ? 'active' : '') ?>">Registration</a></li>
      <li><a href="/contact" class="mobile-nav-link <?= ($currentRoute === '/contact' ? 'active' : '') ?>">Contact</a></li>
    </ul>
    <a href="/register" class="btn btn-gold btn-block">Register Now</a>
  </div>
</header>
