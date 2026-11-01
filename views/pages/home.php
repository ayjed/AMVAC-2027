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
      <h1 class="hero-title">
        Africa Motor Vehicle<br>
        Administration<br>
        <span class="hero-title-accent">Conference 2027</span>
      </h1>
      <p class="hero-desc">Advancing road safety, security, and economic integration across Africa.</p>

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
    </div>
  </div>
</section>

<!-- 2. Three Pillar Highlights Strip -->
<div class="container highlights-strip">
  <div class="highlights-grid">
    <div class="highlight-card">
      <div class="highlight-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          <path d="M12 8v8"></path>
        </svg>
      </div>
      <div>
        <div class="highlight-title">Safer African Roads</div>
        <div class="highlight-desc">Reducing road crashes and saving lives through harmonized vehicle inspection standards across Africa.</div>
      </div>
    </div>

    <div class="highlight-card">
      <div class="highlight-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
      </div>
      <div>
        <div class="highlight-title">Stronger Security</div>
        <div class="highlight-desc">Integrated vehicle data across AU states to combat cross-border theft, smuggling and crime.</div>
      </div>
    </div>

    <div class="highlight-card">
      <div class="highlight-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="20" x2="18" y2="10"></line>
          <line x1="12" y1="20" x2="12" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="14"></line>
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
        <p style="color: var(--slate-600); line-height: 1.6;">
          By establishing uniform guidelines for vehicle registration, digital verification, and inter-agency collaboration, AMVAC 2027 directly addresses the dual imperatives of continental security and economic mobility.
        </p>
        <div style="margin-top: var(--space-6);">
          <a href="/about" class="btn btn-navy">Learn More About the Conference &rarr;</a>
        </div>
      </div>

      <div class="intro-media">
        <img src="/assets/images/banjul-waterfront.jpg" alt="Banjul, The Gambia — Host City for AMVAC 2027" width="600" height="360" loading="lazy">
        <div class="media-location-badge">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span>Banjul, The Gambia</span>
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

<!-- 5. Continental Leadership Carousel -->
<section class="leadership-section" aria-label="Continental Leadership Carousel">
  <div class="container">
    <div class="leadership-header-row">
      <div class="leadership-header-content">
        <div class="section-eyebrow">Conveners &amp; Leadership</div>
        <h2 class="section-title">Continental Leadership</h2>
        <p class="section-subtitle">
          Distinguished Leaders &amp; Invited Dignitaries driving road safety administration and continental transport policy.
        </p>
      </div>

      <div class="leadership-header-actions">
        <div class="carousel-nav-buttons" role="group" aria-label="Carousel Navigation">
          <button type="button" class="carousel-btn carousel-prev-btn" id="leaderPrevBtn" aria-label="Previous leadership profile" disabled>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
          <button type="button" class="carousel-btn carousel-next-btn" id="leaderNextBtn" aria-label="Next leadership profile">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Carousel Viewport & Horizontal Scroll Track -->
    <div class="leadership-carousel-viewport">
      <div class="leadership-carousel-track" id="leadershipTrack" tabindex="0" role="region" aria-label="Leadership profiles scroll track">

        <!-- Leader 1: Chairperson of the African Union -->
        <article class="leader-carousel-card" role="group" aria-roledescription="slide" aria-label="1 of 5: His Excellency Évariste Ndayishimiye">
          <div class="leader-carousel-photo">
            <img src="/assets/images/evariste-ndayishimiye.jpg" alt="His Excellency Évariste Ndayishimiye" width="480" height="480" loading="lazy">
          </div>
          <div class="leader-carousel-body">
            <h3 class="leader-carousel-name">His Excellency &Eacute;variste Ndayishimiye</h3>
            <div class="leader-carousel-title">President of Burundi &amp; Chairperson of the African Union</div>
            <p class="leader-carousel-role">African Union Continental Leadership &amp; Patronage.</p>
          </div>
        </article>

        <!-- Leader 2: President Adama Barrow -->
        <article class="leader-carousel-card" role="group" aria-roledescription="slide" aria-label="2 of 5: His Excellency Adama Barrow">
          <div class="leader-carousel-photo">
            <img src="/assets/images/adama-barrow.jpg" alt="His Excellency Adama Barrow" width="480" height="480" loading="lazy">
          </div>
          <div class="leader-carousel-body">
            <h3 class="leader-carousel-name">His Excellency Adama Barrow</h3>
            <div class="leader-carousel-title">President of The Republic of The Gambia</div>
            <p class="leader-carousel-role">Invited Host Head of State. High-level protocol confirmation undergoing official verification.</p>
          </div>
        </article>

        <!-- Leader 3: Nigeria FRSC Corps Marshal -->
        <article class="leader-carousel-card" role="group" aria-roledescription="slide" aria-label="3 of 5: Corps Marshal Shehu Mohammed">
          <div class="leader-carousel-photo">
            <img src="/assets/images/shehu-mohammed.jpg" alt="Corps Marshal Shehu Mohammed" width="480" height="480" loading="lazy">
          </div>
          <div class="leader-carousel-body">
            <h3 class="leader-carousel-name">Shehu Mohammed, <span style="font-size: 0.9em; font-weight: 500;">FCA, CPFA, mni</span></h3>
            <div class="leader-carousel-title">Corps Marshal, Federal Road Safety Corps (FRSC)</div>
            <p class="leader-carousel-role">Lead Statutory Road Safety &amp; Motor Vehicle Administration Agency, Nigeria.</p>
          </div>
        </article>

        <!-- Leader 4: Amb. Prince Fidelis Nnadi -->
        <article class="leader-carousel-card" role="group" aria-roledescription="slide" aria-label="4 of 5: Amb. Prince Fidelis Nnadi">
          <div class="leader-carousel-photo">
            <img src="/assets/images/fidelis-nnadi.jpg" alt="Amb. Prince Fidelis Nnadi" width="480" height="480" loading="lazy">
          </div>
          <div class="leader-carousel-body">
            <h3 class="leader-carousel-name">Amb. Prince Fidelis Nnadi</h3>
            <div class="leader-carousel-title">Executive Director, APRI - Nigeria</div>
            <p class="leader-carousel-role">Conference Host &amp; Lead Consultant on Motor Vehicle Administration.</p>
          </div>
        </article>

        <!-- Leader 5: Njie Baldeh -->
        <article class="leader-carousel-card" role="group" aria-roledescription="slide" aria-label="5 of 5: Njie Baldeh">
          <div class="leader-carousel-photo">
            <img src="/assets/images/njie-baldeh.jpg" alt="Njie Baldeh" width="480" height="480" loading="lazy">
          </div>
          <div class="leader-carousel-body">
            <h3 class="leader-carousel-name">Njie Baldeh</h3>
            <div class="leader-carousel-title">Administrative Secretary</div>
            <p class="leader-carousel-role">Road Safety and Driving Standard Association of The Gambia (Host Association Co-Organizer).</p>
          </div>
        </article>

      </div>
    </div>

    <!-- Minimal Progress Indicator & Dots -->
    <div class="carousel-footer-bar">
      <div class="carousel-progress-track" aria-hidden="true">
        <div class="carousel-progress-fill" id="leaderProgressFill"></div>
      </div>
      <div class="carousel-dots" id="leaderCarouselDots" role="tablist" aria-label="Leadership slide dots">
        <!-- Injected via JavaScript -->
      </div>
    </div>
  </div>
</section>

<!-- 6. Impact Metrics Strip (4 Columns) -->
<section class="metrics-strip">
  <div class="container">
    <div class="metrics-grid">
      <!-- Metric 1: Expected Delegates -->
      <div class="metric-item">
        <div class="metric-icon-wrap" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
        </div>
        <div class="metric-content">
          <div class="metric-number">250+</div>
          <div class="metric-title">Expected Delegates</div>
          <p class="metric-desc">Ministers, regulators, industry leaders and development partners</p>
        </div>
      </div>

      <!-- Metric 2: African Countries -->
      <div class="metric-item">
        <div class="metric-icon-wrap" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="2" y1="12" x2="22" y2="12"></line>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
          </svg>
        </div>
        <div class="metric-content">
          <div class="metric-number">15+</div>
          <div class="metric-title">African Countries</div>
          <p class="metric-desc">Represented across government and private sector</p>
        </div>
      </div>

      <!-- Metric 3: Strategic Partnerships -->
      <div class="metric-item">
        <div class="metric-icon-wrap" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <polyline points="16 11 18 13 22 9"></polyline>
          </svg>
        </div>
        <div class="metric-content">
          <div class="metric-number" style="font-size: 1.35rem; line-height: 1.25;">Strategic</div>
          <div class="metric-title">Partnerships</div>
          <p class="metric-desc">With regional and international organizations</p>
        </div>
      </div>

      <!-- Metric 4: Policy-Focused Outcomes -->
      <div class="metric-item">
        <div class="metric-icon-wrap" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
          </svg>
        </div>
        <div class="metric-content">
          <div class="metric-number" style="font-size: 1.35rem; line-height: 1.25;">Policy-Focused</div>
          <div class="metric-title">Outcomes</div>
          <p class="metric-desc">Actionable recommendations for safer roads and stronger economies</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 7. Split Registration CTA Section -->
<section class="cta-split-section">
  <div class="container">
    <div class="cta-split-grid">
      <div>
        <h2 class="cta-heading">Participate in AMVAC 2027</h2>
        <p class="cta-desc">
          Join transport ministers, VIO chiefs, road safety administrators, law enforcement leaders, and technology partners from across Africa.
        </p>
        <div class="cta-actions">
          <a href="/register" class="btn btn-gold btn-lg">Register as Delegate &rarr;</a>
          <a href="/contact" class="btn btn-outline-white btn-lg">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>Contact Secretariat</span>
          </a>
        </div>
      </div>

      <div class="slogan-editorial-lockup">
        <div class="slogan-line">Safer Roads</div>
        <div class="slogan-line">Stronger People</div>
        <div class="slogan-line">Stronger Africa</div>
        <div class="slogan-underline"></div>
      </div>
    </div>
  </div>
</section>

