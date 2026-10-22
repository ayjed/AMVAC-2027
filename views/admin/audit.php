<?php
/**
 * AMVAC 2027 — Admin Immutable Audit Trail
 */

$adminTitle = 'System Audit Logs — AMVAC 2027 Admin';
$currentAdminNav = 'audit';

$pdo = Database::getConnection();
$logs = $pdo->query("
    SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 100
")->fetchAll();
?>

<div style="margin-bottom: var(--space-6);">
  <h1 style="font-size: 1.65rem; color: var(--navy-900);">System Audit Trail</h1>
  <div style="color: var(--slate-600); font-size: 0.875rem;">Immutable activity log for security compliance, financial verifications, and administrative actions.</div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Timestamp</th>
            <th>Actor</th>
            <th>Action</th>
            <th>Target Type</th>
            <th>Target ID</th>
            <th>IP Address</th>
            <th>Event Details</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($logs)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--slate-500); padding: var(--space-6);">
                No audit logs recorded yet.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($logs as $log): ?>
              <tr>
                <td style="white-space: nowrap; font-size: 0.8125rem;">
                  <?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?>
                </td>
                <td>
                  <span class="badge <?= ($log['actor_type'] === 'admin' ? 'badge-gold' : 'badge-blue') ?>">
                    <?= e($log['actor_type']) ?> <?= $log['actor_id'] ? '#' . e($log['actor_id']) : '' ?>
                  </span>
                </td>
                <td><code><?= e($log['action']) ?></code></td>
                <td><?= e($log['target_type']) ?></td>
                <td><code><?= e($log['target_id']) ?></code></td>
                <td><small><?= e($log['ip_address']) ?></small></td>
                <td style="max-width: 320px; font-size: 0.775rem; font-family: monospace; color: var(--slate-700);">
                  <?= e($log['details'] ?? '—') ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
