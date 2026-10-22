<?php
/**
 * AMVAC 2027 — Admin Overview Dashboard
 */

$adminTitle = 'Dashboard Overview — AMVAC 2027 Admin';
$currentAdminNav = 'dashboard';

$pdo = Database::getConnection();

// Fetch summary metrics
$counts = [
    'total'                  => (int)$pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn(),
    'pending_payment'        => (int)$pdo->query("SELECT COUNT(*) FROM registrations WHERE status = 'pending_payment'")->fetchColumn(),
    'pending_manual_review'  => (int)$pdo->query("SELECT COUNT(*) FROM registrations WHERE status = 'pending_manual_review'")->fetchColumn(),
    'paid'                   => (int)$pdo->query("SELECT COUNT(*) FROM registrations WHERE status = 'paid'")->fetchColumn(),
    'enquiries'              => (int)$pdo->query("SELECT COUNT(*) FROM contact_enquiries")->fetchColumn()
];

// Recent registrations
$recentRegistrations = $pdo->query("
    SELECT * FROM registrations ORDER BY created_at DESC LIMIT 8
")->fetchAll();

// Pending manual reviews
$pendingReviews = $pdo->query("
    SELECT m.id AS review_id, m.bank_reference, m.created_at AS proof_date, r.public_reference, r.full_name, r.organization, r.amount, r.currency
    FROM manual_payment_reviews m
    JOIN registrations r ON m.registration_id = r.id
    WHERE m.review_status = 'pending'
    ORDER BY m.created_at ASC LIMIT 5
")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6);">
  <div>
    <h1 style="font-size: 1.65rem; color: var(--navy-900);">Secretariat Overview</h1>
    <div style="color: var(--slate-600); font-size: 0.875rem;">Summary of registrations, pending bank verifications, and conference metrics.</div>
  </div>
  <a href="/admin/registrations?export=csv" class="btn btn-navy btn-sm">Export Registrations (CSV)</a>
</div>

<!-- KPI Cards Grid -->
<div class="admin-stats-grid">
  <div class="stat-card">
    <div class="stat-label">Total Delegates</div>
    <div class="stat-value"><?= number_format($counts['total']) ?></div>
  </div>

  <div class="stat-card" style="border-left: 4px solid var(--state-success);">
    <div class="stat-label" style="color: var(--state-success);">Verified & Paid</div>
    <div class="stat-value" style="color: var(--state-success);"><?= number_format($counts['paid']) ?></div>
  </div>

  <div class="stat-card" style="border-left: 4px solid var(--state-warning);">
    <div class="stat-label" style="color: var(--state-warning);">Bank Proofs in Review</div>
    <div class="stat-value" style="color: var(--state-warning);"><?= number_format($counts['pending_manual_review']) ?></div>
  </div>

  <div class="stat-card" style="border-left: 4px solid var(--state-info);">
    <div class="stat-label" style="color: var(--state-info);">Awaiting Payment</div>
    <div class="stat-value" style="color: var(--state-info);"><?= number_format($counts['pending_payment']) ?></div>
  </div>

  <div class="stat-card">
    <div class="stat-label">Contact Inquiries</div>
    <div class="stat-value"><?= number_format($counts['enquiries']) ?></div>
  </div>
</div>

<!-- Pending Bank Transfer Reviews Callout -->
<?php if (!empty($pendingReviews)): ?>
  <div class="card" style="border: 2px solid var(--gold-500); margin-bottom: var(--space-8);">
    <div class="card-body" style="padding: var(--space-5);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-3);">
        <h3 style="color: var(--navy-900); font-size: 1.15rem; display: flex; align-items: center; gap: var(--space-2);">
          <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: var(--state-warning);"></span>
          Action Required: <?= count($pendingReviews) ?> Bank Transfer Proof(s) Awaiting Audit
        </h3>
        <a href="/admin/payments" class="btn btn-gold btn-sm">Open Verification Queue</a>
      </div>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Reference</th>
              <th>Delegate Name</th>
              <th>Organization</th>
              <th>Bank Ref / Teller</th>
              <th>Amount</th>
              <th>Submitted</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pendingReviews as $rev): ?>
              <tr>
                <td><code><?= e($rev['public_reference']) ?></code></td>
                <td><strong><?= e($rev['full_name']) ?></strong></td>
                <td><?= e($rev['organization']) ?></td>
                <td><span class="badge badge-gold"><?= e($rev['bank_reference']) ?></span></td>
                <td><?= e($rev['currency']) ?> <?= number_format((float)$rev['amount'], 2) ?></td>
                <td><?= date('d M Y, H:i', strtotime($rev['proof_date'])) ?></td>
                <td>
                  <a href="/admin/payments" class="btn btn-navy btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Audit</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Recent Registrations Table -->
<div class="card">
  <div class="card-body">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4);">
      <h3 style="color: var(--navy-900); font-size: 1.15rem;">Recent Delegate Registrations</h3>
      <a href="/admin/registrations" style="font-size: 0.875rem; font-weight: 600;">View All Registrations &rarr;</a>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Public Ref</th>
            <th>Delegate</th>
            <th>Country</th>
            <th>Organization & Designation</th>
            <th>Fee Option</th>
            <th>Status</th>
            <th>Registered</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentRegistrations)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--slate-500); padding: var(--space-6);">
                No delegate registrations recorded yet.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentRegistrations as $reg): ?>
              <tr>
                <td><code><?= e($reg['public_reference']) ?></code></td>
                <td>
                  <strong><?= e($reg['full_name']) ?></strong><br>
                  <small style="color: var(--slate-500);"><?= e($reg['email']) ?></small>
                </td>
                <td><?= e($reg['country']) ?></td>
                <td>
                  <?= e($reg['job_title']) ?><br>
                  <small style="color: var(--slate-500);"><?= e($reg['organization']) ?></small>
                </td>
                <td><?= e($reg['currency']) ?> <?= number_format((float)$reg['amount'], 2) ?></td>
                <td>
                  <?php if ($reg['status'] === 'paid'): ?>
                    <span class="badge badge-success">Paid</span>
                  <?php elseif ($reg['status'] === 'pending_manual_review'): ?>
                    <span class="badge badge-warning">Proof Under Audit</span>
                  <?php elseif ($reg['status'] === 'pending_payment'): ?>
                    <span class="badge badge-blue">Pending Payment</span>
                  <?php else: ?>
                    <span class="badge badge-danger"><?= e($reg['status']) ?></span>
                  <?php endif; ?>
                </td>
                <td><?= date('d M Y', strtotime($reg['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
