<?php
/**
 * AMVAC 2027 — Admin Manual Payment Verification Queue
 * Governed strictly by 01_PROJECT_SOURCE_OF_TRUTH.md Section 5
 */

$adminTitle = 'Bank Payment Verification — AMVAC 2027 Admin';
$currentAdminNav = 'payments';

$pdo = Database::getConnection();

// Fetch pending reviews
$pendingReviews = $pdo->query("
    SELECT m.id AS review_id, m.bank_reference, m.deposit_date, m.notes, m.created_at AS submitted_at,
           r.id AS reg_id, r.public_reference, r.full_name, r.email, r.organization, r.amount, r.currency, r.fee_category
    FROM manual_payment_reviews m
    JOIN registrations r ON m.registration_id = r.id
    WHERE m.review_status = 'pending'
    ORDER BY m.created_at ASC
")->fetchAll();

// Fetch recent verification history
$recentHistory = $pdo->query("
    SELECT m.id AS review_id, m.bank_reference, m.review_status, m.admin_notes, m.reviewed_at,
           r.public_reference, r.full_name, r.amount, r.currency, a.name AS reviewer_name
    FROM manual_payment_reviews m
    JOIN registrations r ON m.registration_id = r.id
    LEFT JOIN admins a ON m.reviewed_by = a.id
    WHERE m.review_status IN ('approved', 'rejected')
    ORDER BY m.reviewed_at DESC LIMIT 15
")->fetchAll();
?>

<div style="margin-bottom: var(--space-6);">
  <h1 style="font-size: 1.65rem; color: var(--navy-900);">Bank Transfer Payment Verification</h1>
  <div style="color: var(--slate-600); font-size: 0.875rem;">
    Strict Financial Audit Queue: Verify that funds have cleared in the Bloom Bank Africa account before confirming delegate accreditation.
  </div>
</div>

<!-- Financial Audit Warning Notice -->
<div class="alert alert-warning" style="font-size: 0.875rem; margin-bottom: var(--space-6);">
  <strong>Mandatory Verification Rule:</strong>
  Under no circumstances should an administrator mark a registration as paid solely because a bank teller number or slip was provided. Always verify the transaction reference against the Bloom Bank Africa online statement. Every verification decision is logged in the immutable system audit trail with your administrator identity.
</div>

<!-- Active Review Queue -->
<div class="card" style="margin-bottom: var(--space-8);">
  <div class="card-body">
    <h3 style="color: var(--navy-900); font-size: 1.2rem; margin-bottom: var(--space-4);">
      Pending Verification Queue (<?= count($pendingReviews) ?>)
    </h3>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Public Ref</th>
            <th>Delegate</th>
            <th>Expected Fee</th>
            <th>Submitted Bank Ref / Memo</th>
            <th>Transfer Date</th>
            <th>Submitted At</th>
            <th style="text-align: right;">Audit Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($pendingReviews)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--slate-500); padding: var(--space-6);">
                No pending bank transfers currently require review. All submissions are cleared.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($pendingReviews as $rev): ?>
              <tr>
                <td><code><?= e($rev['public_reference']) ?></code></td>
                <td>
                  <strong><?= e($rev['full_name']) ?></strong><br>
                  <small style="color: var(--slate-500);"><?= e($rev['organization']) ?></small>
                </td>
                <td>
                  <strong><?= e($rev['currency']) ?> <?= number_format((float)$rev['amount'], 2) ?></strong>
                </td>
                <td>
                  <span class="badge badge-gold" style="font-size: 0.8125rem; font-family: monospace;">
                    <?= e($rev['bank_reference']) ?>
                  </span>
                  <?php if (!empty($rev['notes'])): ?>
                    <div style="font-size: 0.75rem; color: var(--slate-600); margin-top: 2px;">
                      <em><?= e($rev['notes']) ?></em>
                    </div>
                  <?php endif; ?>
                </td>
                <td><?= !empty($rev['deposit_date']) ? date('d M Y', strtotime($rev['deposit_date'])) : '—' ?></td>
                <td><?= date('d M Y, H:i', strtotime($rev['submitted_at'])) ?></td>
                <td style="text-align: right; white-space: nowrap;">
                  <button type="button" class="btn btn-gold btn-sm" onclick="openReviewModal(<?= (int)$rev['review_id'] ?>, '<?= e($rev['public_reference']) ?>', 'approve')">
                    Verify & Mark Paid
                  </button>
                  <button type="button" class="btn btn-navy btn-sm" style="background-color: var(--state-danger); border-color: var(--state-danger);" onclick="openReviewModal(<?= (int)$rev['review_id'] ?>, '<?= e($rev['public_reference']) ?>', 'reject')">
                    Reject
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Past Verification History -->
<div class="card">
  <div class="card-body">
    <h3 style="color: var(--navy-900); font-size: 1.15rem; margin-bottom: var(--space-4);">
      Recent Verification History
    </h3>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Public Ref</th>
            <th>Delegate</th>
            <th>Bank Ref</th>
            <th>Decision</th>
            <th>Admin Notes</th>
            <th>Reviewed By</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentHistory)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--slate-500); padding: var(--space-4);">
                No recent review history.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentHistory as $hist): ?>
              <tr>
                <td><code><?= e($hist['public_reference']) ?></code></td>
                <td><?= e($hist['full_name']) ?></td>
                <td><code><?= e($hist['bank_reference']) ?></code></td>
                <td>
                  <?php if ($hist['review_status'] === 'approved'): ?>
                    <span class="badge badge-success">Approved / Paid</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Rejected</span>
                  <?php endif; ?>
                </td>
                <td><small style="color: var(--slate-600);"><?= e($hist['admin_notes'] ?? '—') ?></small></td>
                <td><?= e($hist['reviewer_name'] ?? 'Admin') ?></td>
                <td><?= date('d M Y, H:i', strtotime($hist['reviewed_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Review Action Modal Dialog -->
<div id="reviewModal" class="modal-backdrop">
  <div class="modal-content">
    <div class="modal-header">
      <h4 style="color: var(--white); font-size: 1.1rem;" id="modalDecisionLabel">Verify Payment Reference</h4>
      <button type="button" onclick="closeReviewModal()" style="background: none; border: none; color: var(--white); font-size: 1.25rem; cursor: pointer;">&times;</button>
    </div>

    <form id="reviewForm" action="/admin/payments/review" method="POST">
      <?= Csrf::getField() ?>
      <input type="hidden" name="review_id" id="modalReviewId">
      <input type="hidden" name="decision" id="modalDecision">

      <div class="modal-body">
        <p style="font-size: 0.9375rem; margin-bottom: var(--space-3);">
          You are reviewing registration: <strong id="modalRefDisplay" style="color: var(--navy-900);"></strong>
        </p>

        <div class="form-group">
          <label class="form-label" for="admin_notes">
            Audit Justification / Verification Notes <span class="required">*</span>
          </label>
          <textarea id="admin_notes" name="admin_notes" rows="3" class="form-textarea" placeholder="e.g. Confirmed on Bloom Bank statement dated 22 Oct 2026, Txn ID #98234" required></textarea>
          <span class="form-helper">This note is recorded in the permanent audit trail and included in delegate correspondence if rejected.</span>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="closeReviewModal()" class="btn btn-outline-white" style="color: var(--slate-600); border-color: var(--slate-300);">Cancel</button>
        <button type="submit" id="modalSubmitBtn" class="btn btn-gold">Confirm Action</button>
      </div>
    </form>
  </div>
</div>
