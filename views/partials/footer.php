<?php
/**
 * AMVAC 2027 — Global Site Footer
 */
?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <!-- Col 1: Identity & Theme -->
      <div>
        <div class="footer-brand-title">AMVAC 2027</div>
        <p class="footer-theme"><?= e(AMVAC_THEME) ?></p>
        <div class="footer-slogan"><?= e(AMVAC_SLOGAN) ?></div>
        
        <div style="margin-top: var(--space-4); font-size: 0.8125rem; color: var(--slate-400);">
          <strong style="color: var(--white); display: block; margin-bottom: 4px;">Organized By:</strong>
          Accident Prevention and Rescue Initiative (APRI), Nigeria<br>
          Road Safety Driver Standard Association of The Gambia
        </div>
      </div>

      <!-- Col 2: Navigation Links -->
      <div>
        <div class="footer-heading">Quick Links</div>
        <ul class="footer-links">
          <li><a href="/" class="footer-link">Home</a></li>
          <li><a href="/about" class="footer-link">About & Leadership</a></li>
          <li><a href="/programme" class="footer-link">Programme & Venue</a></li>
          <li><a href="/register" class="footer-link">Delegate Registration</a></li>
          <li><a href="/contact" class="footer-link">Contact Secretariat</a></li>
        </ul>
      </div>

      <!-- Col 3: Key Details & Accreditation -->
      <div>
        <div class="footer-heading">Event Overview</div>
        <ul class="footer-links">
          <li><span style="color: var(--gold-400); font-weight: 600;">Dates:</span> <?= e(AMVAC_DATES) ?></li>
          <li><span style="color: var(--gold-400); font-weight: 600;">Location:</span> <?= e(AMVAC_LOCATION) ?></li>
          <li><span style="color: var(--gold-400); font-weight: 600;">Format:</span> <?= e(AMVAC_HYBRID_FORMAT) ?></li>
          <li style="margin-top: var(--space-3);">
            <a href="/register" class="btn btn-outline-white btn-sm">Accreditation Portal</a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <div>
        &copy; 2027 Africa Motor Vehicle Administration Conference. All rights reserved.
      </div>
      <div style="display: flex; gap: var(--space-4); align-items: center;">
        <span style="color: var(--slate-500); font-size: 0.75rem;">Diplomatic Policy Conference</span>
        <a href="/admin/login" style="font-size: 0.75rem; color: var(--slate-400);">Admin Portal</a>
      </div>
    </div>
  </div>
</footer>
