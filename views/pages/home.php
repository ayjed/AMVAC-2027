<?php
/**
 * AMVAC 2027 — Homepage View
 * Governed strictly by 01_PROJECT_SOURCE_OF_TRUTH.md
 */

$pageTitle = 'AMVAC 2027 — Africa Motor Vehicle Administration Conference';
?>

<!-- 1. Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="hero-content">
      <div class="hero-eyebrow">1st Continental Policy Conference</div>
      <h1 class="hero-title">AFRICA MOTOR VEHICLE ADMINISTRATION CONFERENCE 2027</h1>
      <p class="hero-theme"><?= e(AMVAC_THEME) ?></p>

      <div class="hero-meta-grid">
        <div class="hero-meta-item">
          <!-- Calendar Icon -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          <span><?= e(AMVAC_DATES) ?></span>
        </div>
        <div class="hero-meta-item">
          <!-- Map Pin Icon -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span><?= e(AMVAC_LOCATION) ?></span>
        </div>
        <div class="hero-meta-item">
          <!-- Globe / Hybrid Icon -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
          </svg>
          <span><?= e(AMVAC_HYBRID_FORMAT) ?></span>
        </div>
      </div>

      <div class="hero-cta-group">
        <a href="/register" class="btn btn-gold btn-lg">Register Now</a>
        <a href="/programme" class="btn btn-outline-white btn-lg">View Programme</a>
      </div>
    </div>
  </div>
</section>

<!-- 2. Three Pillar Highlights Strip -->
<div class="container highlights-strip">
  <div class="highlights-grid">
    <div class="highlight-card">
      <div class="highlight-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline>
        </svg>
      </div>
      <div>
        <div class="highlight-title">Safer African Roads</div>
        <div class="highlight-desc">Reducing road crashes and saving lives through harmonized vehicle inspection standards.</div>
      </div>
    </div>

    <div class="highlight-card">
      <div class="highlight-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
      </div>
      <div>
        <div class="highlight-title">Stronger Security</div>
        <div class="highlight-desc">Integrated vehicle data across AU states to combat cross-border theft, smuggling and crime.</div>
      </div>
    </div>

    <div class="highlight-card">
      <div class="highlight-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
        </svg>
      </div>
      <div>
        <div class="highlight-title">Greater Economic Integration</div>
        <div class="highlight-desc">Harmonised licensing and assembly systems to unlock revenue and accelerate African growth.</div>
      </div>
    </div>
  </div>
</div>

<!-- 3. Editorial Introduction Section -->
<section class="intro-section">
  <div class="container">
    <div class="intro-grid">
      <div>
        <div class="section-eyebrow">Continental Convergence</div>
        <h2 class="section-title">A Continental Platform for Safer Roads and Stronger Economies</h2>
        <p style="font-size: 1.05rem; color: var(--slate-700); line-height: 1.65;">
          The Africa Motor Vehicle Administration Conference 2027 brings together government officials, road safety experts, transport regulators and key stakeholders to advance effective motor vehicle administration across Africa.
        </p>
        <p style="color: var(--slate-600);">
          By establishing uniform guidelines for vehicle registration, digital verification, and inter-agency collaboration, AMVAC 2027 directly addresses the dual imperatives of continental security and economic mobility.
        </p>
        <div style="margin-top: var(--space-6);">
          <a href="/about" class="btn btn-navy">Learn More About the Conference</a>
        </div>
      </div>

      <div class="intro-media">
        <img src="/assets/images/banjul-waterfront.jpg" alt="Banjul, The Gambia — Host City for AMVAC 2027" width="600" height="360" loading="lazy">
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(0deg, rgba(6,19,37,0.85) 0%, transparent 100%); padding: var(--space-4) var(--space-5); color: var(--white); font-size: 0.8125rem;">
          <strong>Banjul, The Gambia</strong> — Host City for AMVAC 2027 (15–19 March 2027)
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 4. Key Objectives Preview (All 8 Objectives) -->
<section class="objectives-section">
  <div class="container">
    <div class="section-header-block">
      <div class="section-eyebrow">Strategic Mandate</div>
      <h2 class="section-title">Key Objectives</h2>
      <p style="color: var(--slate-600);">
        The eight foundational pillars guiding pan-African policy dialogue, regulatory standardization, and enforcement synergy.
      </p>
    </div>

    <!-- Reusable Objectives Grid -->
    <?php require __DIR__ . '/../partials/objectives-grid.php'; ?>

    <div style="text-align: center; margin-top: var(--space-8);">
      <a href="/about#objectives" class="btn btn-navy">Read Complete Objective Definitions</a>
    </div>
  </div>
</section>

<!-- 5. Conference Leadership Preview -->
<section class="leadership-section">
  <div class="container">
    <div class="section-header-block">
      <div class="section-eyebrow">Conveners & Leadership</div>
      <h2 class="section-title">Conference Leadership</h2>
      <p style="color: var(--slate-600);">
        Distinguished leaders driving road safety administration and continental transport policy.
      </p>
    </div>

    <div class="leadership-grid">
      <!-- Leader 1: President Adama Barrow (With careful verification flag per D-04) -->
      <div class="leader-card">
        <div class="leader-photo-wrap">
          <img src="/assets/images/adama-barrow.jpg" alt="His Excellency Adama Barrow" width="480" height="360" loading="lazy">
        </div>
        <div class="leader-card-body">
          <div class="leader-name">His Excellency Adama Barrow</div>
          <div class="leader-title">President of The Republic of The Gambia</div>
          <div class="leader-role">Invited Host Head of State. High-level protocol confirmation undergoing official verification.</div>
          <span class="leader-status-tag badge-warning">Protocol Pending</span>
        </div>
      </div>

      <!-- Leader 2: Amb. Prince Fidelis Nnadi -->
      <div class="leader-card">
        <div class="leader-photo-wrap">
          <img src="/assets/images/fidelis-nnadi.jpg" alt="Amb. Prince Fidelis Nnadi" width="480" height="360" loading="lazy">
        </div>
        <div class="leader-card-body">
          <div class="leader-name">Amb. Prince Fidelis Nnadi</div>
          <div class="leader-title">Executive Director, APRI - Nigeria</div>
          <div class="leader-role">Conference Host & Lead Consultant on Motor Vehicle Administration.</div>
          <span class="leader-status-tag badge-success">Conference Convener</span>
        </div>
      </div>

      <!-- Leader 3: Njie Baldeh -->
      <div class="leader-card">
        <div class="leader-photo-wrap">
          <img src="/assets/images/njie-baldeh.jpg" alt="Njie Baldeh" width="480" height="360" loading="lazy">
        </div>
        <div class="leader-card-body">
          <div class="leader-name">Njie Baldeh</div>
          <div class="leader-title">Executive Secretary</div>
          <div class="leader-role">Road Safety Driver Standard Association of The Gambia (Host Association Co-Organizer).</div>
          <span class="leader-status-tag badge-success">Host Country Co-Organizer</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 6. Bottom Registration CTA Strip -->
<section style="background-color: var(--navy-900); color: var(--white); padding: var(--space-12) 0; border-top: 1px solid var(--navy-700);">
  <div class="container text-center" style="text-align: center; max-width: 760px; margin: 0 auto;">
    <h2 style="color: var(--gold-400); font-family: var(--font-serif); font-size: 2rem; margin-bottom: var(--space-3);">
      Participate in AMVAC 2027
    </h2>
    <p style="color: var(--slate-300); font-size: 1.05rem; margin-bottom: var(--space-6);">
      Join transport ministers, VIO chiefs, road safety administrators, law enforcement leaders, and technology partners from across Africa.
    </p>
    <div style="display: flex; justify-content: center; gap: var(--space-4); flex-wrap: wrap;">
      <a href="/register" class="btn btn-gold btn-lg">Register as Delegate</a>
      <a href="/contact" class="btn btn-outline-white btn-lg">Contact Secretariat</a>
    </div>
  </div>
</section>
