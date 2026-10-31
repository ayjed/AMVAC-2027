<?php
/**
 * AMVAC 2027 — Contact Conference Secretariat View
 * Governed strictly by 01_PROJECT_SOURCE_OF_TRUTH.md Section 2 & D-08
 */

$pageTitle = 'Contact Secretariat — AMVAC 2027';
$metaDescription = 'Contact the Africa Motor Vehicle Administration Conference 2027 secretariat desks in Banjul and Abuja.';
?>

<!-- Interior Page Header Banner -->
<section class="page-banner">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <span>&rsaquo;</span>
      <span>Contact Secretariat</span>
    </nav>
    <h1 class="page-banner-title">Contact Conference Secretariat</h1>
    <p class="page-banner-sub">
      For ministerial protocol, technical delegations, sponsorship, and delegate inquiries.
    </p>
  </div>
</section>

<section style="padding: var(--space-12) 0; background-color: var(--surface-light);">
  <div class="container">
    <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-8);" class="intro-grid">

      <!-- Secretariat Coordinates & Information -->
      <div>
        <div class="section-eyebrow">Official Desks</div>
        <h2 class="section-title">Secretariat Coordinates</h2>
        <p style="color: var(--slate-700); font-size: 1.05rem; line-height: 1.65; margin-bottom: var(--space-6);">
          The conference secretariat operates joint coordination desks in Banjul and Abuja to handle bilateral agency protocols, logistics accreditation, and travel facilitation.
        </p>

        <!-- Desk 1: Banjul Host Country Desk -->
        <div class="card" style="margin-bottom: var(--space-4);">
          <div class="card-body">
            <span class="badge badge-gold" style="margin-bottom: var(--space-2);">Banjul Host Desk</span>
            <h3 style="font-size: 1.15rem; color: var(--navy-900); margin-bottom: var(--space-2);">
              Road Safety and Driving Standard Association of The Gambia
            </h3>
            <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: var(--space-2);">
              Administrative Secretary: Njie Baldeh
            </p>
            <div style="font-size: 0.8125rem; color: var(--slate-500);">
              Location: Greater Banjul Area, The Gambia &bull; Host Nation Operations
            </div>
          </div>
        </div>

        <!-- Desk 2: Abuja Technical Secretariat Desk -->
        <div class="card" style="margin-bottom: var(--space-6);">
          <div class="card-body">
            <span class="badge badge-gold" style="margin-bottom: var(--space-2);">Abuja Secretariat Desk</span>
            <h3 style="font-size: 1.15rem; color: var(--navy-900); margin-bottom: var(--space-2);">
              Accident Prevention and Rescue Initiative (APRI)
            </h3>
            <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: var(--space-2);">
              Executive Director: Amb. Prince Fidelis Nnadi
            </p>
            <div style="font-size: 0.8125rem; color: var(--slate-500);">
              Location: Abuja, Federal Capital Territory, Nigeria &bull; Technical Secretariat
            </div>
          </div>
        </div>

        <!-- Official Channels Notice (D-08) -->
        <div class="alert alert-info" style="font-size: 0.8125rem; margin-bottom: 0;">
          <strong>Direct Inquiries:</strong> All inquiries submitted through the official portal form are logged into the central conference registry and routed directly to designated protocol officers.
        </div>
      </div>

      <!-- Inquiry Submission Form -->
      <div>
        <div class="card">
          <div class="card-body" style="padding: var(--space-8);">
            <div class="section-eyebrow">Direct Transmission</div>
            <h3 style="font-size: 1.35rem; color: var(--navy-900); margin-bottom: var(--space-4);">Send an Inquiry</h3>

            <form action="/contact" method="POST" novalidate>
              <?= Csrf::getField() ?>

              <div class="form-group">
                <label class="form-label" for="full_name">
                  Full Name <span class="required">*</span>
                </label>
                <input type="text" id="full_name" name="full_name" class="form-input" placeholder="Your Full Name" required>
              </div>

              <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-4);" class="form-grid-2">
                <div class="form-group">
                  <label class="form-label" for="email">
                    Official Email Address <span class="required">*</span>
                  </label>
                  <input type="email" id="email" name="email" class="form-input" placeholder="name@organization.com" required>
                </div>

                <div class="form-group">
                  <label class="form-label" for="phone">Telephone Number (Optional)</label>
                  <input type="tel" id="phone" name="phone" class="form-input" placeholder="+220 ... or +234 ...">
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="subject">
                  Inquiry Category <span class="required">*</span>
                </label>
                <select id="subject" name="subject" class="form-select" required>
                  <option value="General Delegate Inquiry">General Delegate Inquiry</option>
                  <option value="Ministerial & Diplomatic Protocol">Ministerial & Diplomatic Protocol</option>
                  <option value="Registration & Fee Verification">Registration & Fee Verification</option>
                  <option value="Exhibition & Technical Working Groups">Exhibition & Technical Working Groups</option>
                  <option value="Press & Media Accreditation">Press & Media Accreditation</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="message">
                  Message Details <span class="required">*</span>
                </label>
                <textarea id="message" name="message" rows="5" class="form-textarea" placeholder="Please provide specific details regarding your inquiry..." required></textarea>
              </div>

              <button type="submit" class="btn btn-gold btn-block btn-lg">
                Send Message to Secretariat
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
