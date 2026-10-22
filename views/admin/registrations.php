<?php
/**
 * AMVAC 2027 — Admin Registrations Management
 */

$adminTitle = 'Delegates List — AMVAC 2027 Admin';
$currentAdminNav = 'registrations';

$statusFilter = $_GET['status'] ?? '';
$searchFilter = $_GET['search'] ?? '';

$filters = [];
if (!empty($statusFilter)) {
    $filters['status'] = $statusFilter;
}
if (!empty($searchFilter)) {
    $filters['search'] = $searchFilter;
}

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $all = RegistrationService::getAll($filters, 10000, 0);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="AMVAC2027_Delegates_' . date('Ymd_His') . '.csv"');
    
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Reference', 'Full Name', 'Email', 'Phone', 'Country', 'Organization', 'Job Title', 'Format', 'Fee Category', 'Amount', 'Currency', 'Status', 'Payment Method', 'Verified At', 'Created At']);
    
    foreach ($all as $row) {
        fputcsv($out, [
            $row['public_reference'],
            $row['full_name'],
            $row['email'],
            $row['phone'],
            $row['country'],
            $row['organization'],
            $row['job_title'],
            $row['participation_format'],
            $row['fee_category'],
            $row['amount'],
            $row['currency'],
            $row['status'],
            $row['payment_method'],
            $row['verified_at'],
            $row['created_at']
        ]);
    }
    fclose($out);
    exit;
}

$totalCount = RegistrationService::countAll($filters);
$registrations = RegistrationService::getAll($filters, 100, 0);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6);">
  <div>
    <h1 style="font-size: 1.65rem; color: var(--navy-900);">Delegate Registrations</h1>
    <div style="color: var(--slate-600); font-size: 0.875rem;">Total matching delegates: <?= $totalCount ?></div>
  </div>
  <a href="/admin/registrations?status=<?= urlencode($statusFilter) ?>&search=<?= urlencode($searchFilter) ?>&export=csv" class="btn btn-navy btn-sm">
    Download CSV Export
  </a>
</div>

<!-- Filter Bar -->
<form method="GET" action="/admin/registrations" class="admin-filter-bar">
  <input type="text" name="search" class="form-input" style="max-width: 320px;" placeholder="Search name, ref, email, org..." value="<?= e($searchFilter) ?>">
  
  <select name="status" class="form-select" style="max-width: 220px;">
    <option value="">All Statuses</option>
    <option value="pending_payment" <?= ($statusFilter === 'pending_payment' ? 'selected' : '') ?>>Pending Payment</option>
    <option value="pending_manual_review" <?= ($statusFilter === 'pending_manual_review' ? 'selected' : '') ?>>Pending Bank Audit</option>
    <option value="paid" <?= ($statusFilter === 'paid' ? 'selected' : '') ?>>Verified Paid</option>
    <option value="failed" <?= ($statusFilter === 'failed' ? 'selected' : '') ?>>Failed / Rejected</option>
  </select>

  <button type="submit" class="btn btn-navy btn-sm">Filter</button>
  <?php if (!empty($statusFilter) || !empty($searchFilter)): ?>
    <a href="/admin/registrations" class="btn btn-outline-white btn-sm" style="color: var(--slate-600); border-color: var(--slate-300);">Reset</a>
  <?php endif; ?>
</form>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Public Ref</th>
            <th>Delegate Name</th>
            <th>Contact Details</th>
            <th>Country</th>
            <th>Organization & Role</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($registrations)): ?>
            <tr>
              <td colspan="8" style="text-align: center; color: var(--slate-500); padding: var(--space-6);">
                No delegates matching the specified filter criteria.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($registrations as $reg): ?>
              <tr>
                <td><code><?= e($reg['public_reference']) ?></code></td>
                <td><strong><?= e($reg['full_name']) ?></strong></td>
                <td>
                  <?= e($reg['email']) ?><br>
                  <small style="color: var(--slate-500);"><?= e($reg['phone']) ?></small>
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
