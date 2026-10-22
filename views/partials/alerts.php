<?php
/**
 * AMVAC 2027 — Flash Alerts Partial
 */

$flash = get_flash();
if ($flash):
    $alertClass = match($flash['type']) {
        'success' => 'alert-success',
        'error', 'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        default => 'alert-info'
    };
?>
<div class="alert <?= $alertClass ?>" role="alert">
  <?= e($flash['message']) ?>
</div>
<?php endif; ?>
