<?php
/**
 * AMVAC 2027 — Administrator Login View
 */

$adminTitle = 'Admin Login — AMVAC 2027';
?>
<div style="max-width: 440px; margin: var(--space-12) auto; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--slate-200); box-shadow: var(--shadow-lg); overflow: hidden;">
  <div style="background-color: var(--navy-950); padding: var(--space-6); text-align: center; border-bottom: 2px solid var(--gold-500);">
    <img src="/assets/images/logo-crest.svg" alt="AMVAC Seal" width="64" height="64" style="margin: 0 auto var(--space-2);">
    <h1 style="color: var(--gold-400); font-size: 1.25rem; font-weight: 800; text-transform: uppercase;">
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
        <button type="submit" class="btn btn-navy btn-block btn-lg">
          Sign In to Portal
        </button>
      </div>
    </form>

    <div style="margin-top: var(--space-6); text-align: center; font-size: 0.775rem; color: var(--slate-500);">
      Default setup credentials: <code>admin@amvac2027.org</code><br>
      Strict session auditing and failed attempt lockout active.
    </div>
  </div>
</div>
