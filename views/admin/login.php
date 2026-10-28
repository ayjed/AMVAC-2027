<?php
/**
 * AMVAC 2027 — Administrator Login View
 */

$adminTitle = 'Admin Login — AMVAC 2027';
?>
<div style="max-width: 460px; margin: var(--space-12) auto; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-lg); overflow: hidden;">
  <div style="background-color: var(--navy-950); padding: var(--space-6) var(--space-6) var(--space-5); text-align: center; border-bottom: 2px solid var(--gold-500);">
    <a href="/" title="Return to Public Website" style="display: inline-block;">
      <img src="/assets/images/logo-official-white.png" alt="AMVAC 2027" height="54" style="margin: 0 auto var(--space-3); height: 54px; width: auto; display: block;">
    </a>
    <h1 style="color: var(--gold-400); font-size: 1.15rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: var(--space-1);">
      Secretariat Portal
    </h1>
    <div style="color: var(--slate-300); font-size: 0.8125rem;">
      Authorized Administrative Personnel Only
    </div>
  </div>

  <div style="padding: var(--space-8);">
    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <form action="/admin/login" method="POST">
      <?= Csrf::getField() ?>

      <div class="form-group">
        <label class="form-label" for="email">Admin Email Address</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="admin@amvac2027.org" required autofocus>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••••••" required>
      </div>

      <div style="margin-top: var(--space-6);">
        <button type="submit" class="btn btn-navy btn-block btn-lg" style="box-shadow: var(--shadow-sm);">
          Sign In to Portal &rarr;
        </button>
      </div>
    </form>

    <div style="margin-top: var(--space-6); text-align: center; font-size: 0.775rem; color: var(--slate-500); line-height: 1.5;">
      Default setup credentials: <code>admin@amvac2027.org</code><br>
      Strict session auditing and failed attempt lockout active.
    </div>

    <div style="margin-top: var(--space-5); padding-top: var(--space-4); border-top: 1px solid var(--slate-100); text-align: center;">
      <a href="/" style="color: var(--gold-600); font-size: 0.8125rem; font-weight: 600; text-decoration: none;">&larr; Return to Public Website</a>
    </div>
  </div>
</div>
