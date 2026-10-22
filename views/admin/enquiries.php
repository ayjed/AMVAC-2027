<?php
/**
 * AMVAC 2027 — Admin Contact Enquiries Inbox
 */

$adminTitle = 'Contact Inquiries — AMVAC 2027 Admin';
$currentAdminNav = 'enquiries';

$pdo = Database::getConnection();
$enquiries = $pdo->query("
    SELECT * FROM contact_enquiries ORDER BY created_at DESC LIMIT 50
")->fetchAll();
?>

<div style="margin-bottom: var(--space-6);">
  <h1 style="font-size: 1.65rem; color: var(--navy-900);">Secretariat Inquiries Inbox</h1>
  <div style="color: var(--slate-600); font-size: 0.875rem;">Messages and protocol requests submitted via the public contact form.</div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Sender</th>
            <th>Contact</th>
            <th>Category / Subject</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($enquiries)): ?>
            <tr>
              <td colspan="5" style="text-align: center; color: var(--slate-500); padding: var(--space-6);">
                No contact inquiries received yet.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($enquiries as $item): ?>
              <tr>
                <td><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></td>
                <td><strong><?= e($item['full_name']) ?></strong></td>
                <td>
                  <a href="mailto:<?= e($item['email']) ?>"><?= e($item['email']) ?></a><br>
                  <small style="color: var(--slate-500);"><?= e($item['phone'] ?? '—') ?></small>
                </td>
                <td><span class="badge badge-blue"><?= e($item['subject']) ?></span></td>
                <td style="max-width: 400px; font-size: 0.875rem; color: var(--slate-700);">
                  <?= nl2br(e($item['message'])) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
