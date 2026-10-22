<?php
/**
 * AMVAC 2027 — About & Leadership View
 * Governed strictly by 01_PROJECT_SOURCE_OF_TRUTH.md Section 3 & 4
 */

$pageTitle = 'About the Conference & Leadership — AMVAC 2027';
$metaDescription = 'Learn about the mission, organizers, confirmed leadership, and canonical objectives of the Africa Motor Vehicle Administration Conference 2027.';
?>

<!-- Interior Page Header Banner -->
<section class="page-banner">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <span>&rsaquo;</span>
      <span>About & Leadership</span>
    </nav>
    <h1 class="page-banner-title">About the Conference & Leadership</h1>
    <p class="page-banner-sub">
      A continental platform for dialogue, harmonization, and decisive action on motor vehicle administration across Africa.
    </p>
  </div>
</section>

<!-- About Section with Africa Accent Card -->
<section style="padding: var(--space-12) 0; background-color: var(--white); border-bottom: 1px solid var(--slate-200);">
  <div class="container">
    <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-8); align-items: center;" class="about-grid">
      <div>
        <div class="section-eyebrow">Institutional Background</div>
        <h2 class="section-title">About the Conference</h2>
        <p style="font-size: 1.05rem; color: var(--slate-800); line-height: 1.7; margin-bottom: var(--space-4);">
          The Africa Motor Vehicle Administration Conference 2027 is a continental platform for dialogue, collaboration and action on effective motor vehicle administration. The conference brings together policymakers, regulators, road safety experts and stakeholders to promote harmonized systems, stronger security, safer roads and economic integration across Africa.
        </p>
        <p style="color: var(--slate-600); line-height: 1.65; margin-bottom: var(--space-4);">
          Effective motor vehicle administration forms the backbone of civil registration, national internal security, and trans-continental commerce under the African Continental Free Trade Area (AfCFTA). Without integrated registries, vehicle-enabled crimes such as cross-border vehicle theft, smuggling, and terrorism flourish unchecked across porous national borders.
        </p>
        <div style="background-color: var(--surface-light); padding: var(--space-4); border-left: 4px solid var(--gold-500); border-radius: var(--radius-sm); font-size: 0.9375rem; color: var(--navy-900); font-weight: 600;">
          Theme: &ldquo;<?= e(AMVAC_THEME) ?>&rdquo;
        </div>
      </div>

      <!-- Right Accent Motif Card -->
      <div style="background: linear-gradient(135deg, #F8FAFC 0%, #EDF2F7 100%); border: 1px solid var(--slate-200); border-radius: var(--radius-lg); padding: var(--space-6); text-align: center; box-shadow: var(--shadow-sm);">
        <div style="width: 100%; height: 220px; margin-bottom: var(--space-4);">
          <img src="/assets/images/africa-map-accent.svg" alt="Pan-African Vehicle Administration Integration" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <div style="color: var(--navy-900); font-weight: 800; font-size: 0.875rem; letter-spacing: 0.12em; text-transform: uppercase;">
          COLLABORATION &bull; POLICY &bull; SAFETY &bull; INTEGRATION
        </div>
        <div style="width: 48px; height: 3px; background-color: var(--gold-500); margin: var(--space-2) auto 0;"></div>
      </div>
    </div>
  </div>
</section>

<!-- The Eight Canonical Objectives (Full Verbatim Text - 2 Columns) -->
<section id="objectives" style="padding: var(--space-12) 0; background-color: var(--surface-light); border-bottom: 1px solid var(--slate-200);">
  <div class="container">
    <div class="section-header-block">
      <div class="section-eyebrow">Canonical Mandate</div>
      <h2 class="section-title">Our Objectives</h2>
      <p style="color: var(--slate-600);">
        The eight strategic policy objectives adopted for AMVAC 2027. Full text presented without omission.
      </p>
    </div>

    <div class="about-full-objectives">
      <?php foreach (AMVAC_OBJECTIVES as $num => $obj): ?>
        <div class="objective-item-row">
          <div class="objective-row-number"><?= $num ?></div>
          <div class="objective-row-content">
            <h3><?= e($obj['title']) ?></h3>
            <p><?= e($obj['full_text']) ?></p>
            <?php if (!empty($obj['citation_note'])): ?>
              <small style="display: block; margin-top: var(--space-2); color: var(--slate-500); font-size: 0.775rem; font-style: italic;">
                * Citation Note: <?= e($obj['citation_note']) ?>
              </small>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Organizing Institutions -->
<section style="padding: var(--space-12) 0; background-color: var(--white); border-bottom: 1px solid var(--slate-200);">
  <div class="container">
    <div class="section-header-block">
      <div class="section-eyebrow">Institutional Framework</div>
      <h2 class="section-title">Organizing Institutions</h2>
      <p style="color: var(--slate-600);">
        Convened through bilateral non-governmental and regulatory partnership between Nigeria and The Gambia.
      </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-6);">
      <!-- Entity 1: APRI Nigeria -->
      <div class="card">
        <div class="card-body">
          <span class="badge badge-gold" style="margin-bottom: var(--space-2);">Technical Secretariat</span>
          <h3 style="font-size: 1.2rem; color: var(--navy-900); margin-bottom: var(--space-2);">Accident Prevention and Rescue Initiative (APRI)</h3>
          <p style="color: var(--slate-600); font-size: 0.875rem;">
            A premier non-governmental organisation in Nigeria dedicated to motor vehicle safety advocacy, regulatory reform, emergency rescue coordination, and continental motor vehicle administration consulting.
          </p>
          <div style="font-size: 0.8125rem; color: var(--navy-900); font-weight: 600;">
            Location: Abuja, Federal Capital Territory, Nigeria
          </div>
        </div>
      </div>

      <!-- Entity 2: Road Safety Driver Standard Association of The Gambia -->
      <div class="card">
        <div class="card-body">
          <span class="badge badge-gold" style="margin-bottom: var(--space-2);">Host Country Co-Organizer</span>
          <h3 style="font-size: 1.2rem; color: var(--navy-900); margin-bottom: var(--space-2);">Road Safety Driver Standard Association of The Gambia</h3>
          <p style="color: var(--slate-600); font-size: 0.875rem;">
            The registered national professional association spearheading driver standards, commercial vehicle inspection enlightenment, and transport sector compliance in The Gambia.
          </p>
          <div style="font-size: 0.8125rem; color: var(--navy-900); font-weight: 600;">
            Location: Banjul, The Gambia
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Conference Leadership -->
<section style="padding: var(--space-12) 0; background-color: var(--surface-light);">
  <div class="container">
    <div class="section-header-block">
      <div class="section-eyebrow">Leadership & Conveners</div>
      <h2 class="section-title">Conference Leadership</h2>
      <p style="color: var(--slate-600);">
        Approved conveners and host delegates spearheading the 2027 continental summit.
      </p>
    </div>

    <div class="leadership-grid">
      <!-- 1. President Adama Barrow -->
      <div class="leader-card">
        <div class="leader-photo-wrap">
          <img src="/assets/images/adama-barrow.jpg" alt="His Excellency Adama Barrow" width="480" height="360">
        </div>
        <div class="leader-card-body">
          <div class="leader-name">His Excellency Adama Barrow</div>
          <div class="leader-title">President of The Republic of The Gambia</div>
          <div class="leader-role">Invited Host Head of State. High-level protocol confirmation undergoing official verification.</div>
          <span class="leader-status-tag badge-warning">Protocol Pending</span>
        </div>
      </div>

      <!-- 2. Amb. Prince Fidelis Nnadi -->
      <div class="leader-card">
        <div class="leader-photo-wrap">
          <img src="/assets/images/fidelis-nnadi.jpg" alt="Amb. Prince Fidelis Nnadi" width="480" height="360">
        </div>
        <div class="leader-card-body">
          <div class="leader-name">Amb. Prince Fidelis Nnadi</div>
          <div class="leader-title">Executive Director, APRI - Nigeria</div>
          <div class="leader-role">Conference Host & Lead Consultant on Motor Vehicle Administration.</div>
          <span class="leader-status-tag badge-success">Conference Convener</span>
        </div>
      </div>

      <!-- 3. Njie Baldeh -->
      <div class="leader-card">
        <div class="leader-photo-wrap">
          <img src="/assets/images/njie-baldeh.jpg" alt="Njie Baldeh" width="480" height="360">
        </div>
        <div class="leader-card-body">
          <div class="leader-name">Njie Baldeh</div>
          <div class="leader-title">Executive Secretary</div>
          <div class="leader-role">Road Safety Driver Standard Association of The Gambia.</div>
          <span class="leader-status-tag badge-success">Host Country Co-Organizer</span>
        </div>
      </div>
    </div>
  </div>
</section>
