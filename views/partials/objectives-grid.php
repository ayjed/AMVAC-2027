<?php
/**
 * AMVAC 2027 — Canonical 8 Objectives Component
 * Strictly preserves all 8 objectives without omission
 */

$isFull = $isFull ?? false;
$objectives = AMVAC_OBJECTIVES;
?>
<div class="objectives-grid">
  <?php foreach ($objectives as $num => $obj): ?>
    <div class="objective-card">
      <div class="objective-card-top">
        <div class="objective-number-badge" aria-label="Objective <?= $num ?>">
          <?= $num ?>
        </div>
        <div class="objective-icon-subtle">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <?php if ($num === 1): // Harmonization ?>
              <circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
            <?php elseif ($num === 2): // Security ?>
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            <?php elseif ($num === 3): // Digital ?>
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line>
            <?php elseif ($num === 4): // Capacity ?>
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            <?php elseif ($num === 5): // Safer Roads ?>
              <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline>
            <?php elseif ($num === 6): // Public Enlightenment ?>
              <line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><circle cx="12" cy="12" r="5"></circle>
            <?php elseif ($num === 7): // Inter-Agency ?>
              <circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
            <?php else: // Economy ?>
              <line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            <?php endif; ?>
          </svg>
        </div>
      </div>
      <h3 class="objective-card-title"><?= e($obj['title']) ?></h3>
      <p class="objective-card-text">
        <?= e($isFull ? $obj['full_text'] : $obj['summary']) ?>
      </p>
      <?php if (!empty($obj['citation_note'])): ?>
        <small style="display: block; margin-top: var(--space-2); color: var(--slate-500); font-size: 0.75rem; font-style: italic;">
          * Note: <?= e($obj['citation_note']) ?>
        </small>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
