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
        <div class="objective-icon-box" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <?php if ($num === 1): // Harmonization - Document / Balance / Unified ?>
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
            <?php elseif ($num === 2): // Security - Shield ?>
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            <?php elseif ($num === 3): // Digital - Screen / Tech ?>
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
            <?php elseif ($num === 4): // Capacity - Users / Training ?>
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            <?php elseif ($num === 5): // Safer Roads - Road / Shield / Triangle ?>
              <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
              <polyline points="2 17 12 22 22 17"></polyline>
              <polyline points="2 12 12 17 22 12"></polyline>
            <?php elseif ($num === 6): // Public Enlightenment - Lightbulb ?>
              <path d="M9 18h6"></path>
              <path d="M10 22h4"></path>
              <path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5.76.76 1.23 1.52 1.41 2.5"></path>
            <?php elseif ($num === 7): // Inter-Agency - Collaboration / Nodes ?>
              <circle cx="18" cy="5" r="3"></circle>
              <circle cx="6" cy="12" r="3"></circle>
              <circle cx="18" cy="19" r="3"></circle>
              <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
              <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
            <?php else: // Economy - Chart / Growth ?>
              <line x1="18" y1="20" x2="18" y2="10"></line>
              <line x1="12" y1="20" x2="12" y2="4"></line>
              <line x1="6" y1="20" x2="6" y2="14"></line>
            <?php endif; ?>
          </svg>
        </div>
        <div class="objective-header-meta">
          <span class="objective-number-badge" aria-label="Objective <?= sprintf('%02d', $num) ?>">
            <?= sprintf('%02d', $num) ?>
          </span>
          <h3 class="objective-card-title"><?= e($obj['title']) ?></h3>
        </div>
      </div>
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
