<?php
/**
 * AMVAC 2027 — Delegate Registration & Payment Portal View
 * Governed strictly by 01_PROJECT_SOURCE_OF_TRUTH.md Section 5
 */

$pageTitle = 'Delegate Registration & Status — AMVAC 2027';
$metaDescription = 'Register for the Africa Motor Vehicle Administration Conference 2027 in Banjul, The Gambia, or track your accreditation pass.';

$ref = trim($_GET['ref'] ?? '');
$registration = null;
if (!empty($ref)) {
    $registration = RegistrationService::findByReference($ref);
}

$feeMatrix = AMVAC_FEE_MATRIX;
$bankDetails = AMVAC_BANK_DETAILS;
?>

<!-- Interior Page Header Banner -->
<section class="page-banner">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <span>&rsaquo;</span>
      <span>Delegate Registration</span>
    </nav>
    <h1 class="page-banner-title">Delegate Registration & Accreditation</h1>
    <p class="page-banner-sub">
      Secure your delegate credential for the 1st Continental Policy Conference in Banjul.
    </p>
  </div>
</section>

<section style="padding: var(--space-12) 0; background-color: var(--surface-light);">
  <div class="container reg-container">

    <?php if ($registration): ?>
      <!-- ====================================================================
           STATE VIEW: Existing Registration Record Found
           ==================================================================== -->
      <div class="reg-state-box">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: var(--space-3); margin-bottom: var(--space-4);">
          <div>
            <span class="section-eyebrow">Delegate Dossier</span>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem; color: var(--navy-900);">
              <?= e($registration['full_name']) ?>
            </h2>
            <div style="color: var(--slate-600); font-size: 0.9375rem;">
              <?= e($registration['job_title']) ?> &bull; <?= e($registration['organization']) ?> (<?= e($registration['country']) ?>)
            </div>
          </div>

          <div>
            <?php if ($registration['status'] === 'paid'): ?>
              <span class="badge badge-success" style="font-size: 0.875rem; padding: 0.35rem 0.85rem;">Accreditation Confirmed (Paid)</span>
            <?php elseif ($registration['status'] === 'pending_manual_review'): ?>
              <span class="badge badge-warning" style="font-size: 0.875rem; padding: 0.35rem 0.85rem;">Bank Proof Under Audit</span>
            <?php elseif ($registration['status'] === 'pending_payment'): ?>
              <span class="badge badge-blue" style="font-size: 0.875rem; padding: 0.35rem 0.85rem;">Awaiting Payment</span>
            <?php else: ?>
              <span class="badge badge-danger" style="font-size: 0.875rem; padding: 0.35rem 0.85rem;"><?= e(ucfirst($registration['status'])) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div style="background-color: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: var(--space-4); margin-bottom: var(--space-6);">
          <div style="font-size: 0.8125rem; color: var(--slate-500); text-transform: uppercase; font-weight: 700;">
            Official Public Reference Code:
          </div>
          <div class="reference-display-badge"><?= e($registration['public_reference']) ?></div>
          <button type="button" class="btn btn-outline-white btn-sm btn-copy-ref" data-ref="<?= e($registration['public_reference']) ?>" style="color: var(--navy-900); border-color: var(--slate-300);">
            Copy Reference
          </button>
          <div style="font-size: 0.8125rem; color: var(--slate-500); margin-top: var(--space-2);">
            Quote this reference code in all bank transfers, email correspondence, and at the venue accreditation desk.
          </div>
        </div>

        <!-- STATE 1: PAID / VERIFIED -->
        <?php if ($registration['status'] === 'paid'): ?>
          <div class="alert alert-success">
            <h3 style="font-size: 1.15rem; margin-bottom: var(--space-2); color: #166534;">Accreditation Verified & Confirmed</h3>
            <p style="margin-bottom: var(--space-2);">
              Your delegate registration has been verified by the conference secretariat. Your seat for AMVAC 2027 in Banjul, The Gambia (15–19 March 2027) is officially reserved.
            </p>
            <div style="font-size: 0.875rem; margin-top: var(--space-3);">
              <strong>Amount Verified:</strong> <?= e($registration['currency']) ?> <?= number_format((float)$registration['amount'], 2) ?><br>
              <strong>Payment Method:</strong> <?= e(ucwords(str_replace('_', ' ', $registration['payment_method'] ?? 'Verified'))) ?><br>
              <strong>Verification Date:</strong> <?= e($registration['verified_at'] ?? 'Confirmed') ?>
            </div>
          </div>

          <div style="text-align: center; margin-top: var(--space-6);">
            <button onclick="window.print()" class="btn btn-navy">
              Print Official Confirmation Slip
            </button>
          </div>

        <!-- STATE 2: PENDING MANUAL REVIEW -->
        <?php elseif ($registration['status'] === 'pending_manual_review'): ?>
          <div class="alert alert-warning">
            <h3 style="font-size: 1.15rem; margin-bottom: var(--space-2); color: #92400E;">Bank Transfer Proof Queued for Audit</h3>
            <p>
              Thank you. Your submitted bank transfer reference is currently undergoing administrative reconciliation against Bloom Bank Africa account records.
            </p>
            <p style="font-size: 0.875rem; margin-top: var(--space-2);">
              In strict adherence to conference financial governance, accreditation credentials are issued only after verified bank receipt. You will receive an official confirmation email as soon as the finance officer clears this transaction.
            </p>
          </div>

        <!-- STATE 3: PENDING PAYMENT -->
        <?php else: ?>
          <div style="margin-bottom: var(--space-6);">
            <h3 style="font-size: 1.25rem; color: var(--navy-900); margin-bottom: var(--space-2);">Complete Delegate Fee Payment</h3>
            <p style="color: var(--slate-600);">
              <strong>Fixed Server Amount:</strong> <?= e($registration['currency']) ?> <?= number_format((float)$registration['amount'], 2) ?> 
              (<?= $registration['fee_category'] === 'nigerian_delegate' ? 'Nigerian Resident Category' : 'International / Regional Category' ?>)
            </p>
          </div>

          <!-- Option 1: Direct Bank Transfer (Bloom Bank Africa) -->
          <div class="card" style="margin-bottom: var(--space-6);">
            <div class="card-body">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-3);">
                <h4 style="color: var(--navy-900); font-size: 1.1rem;">Option 1: Direct Bank Wire / Transfer</h4>
                <span class="badge badge-gold">Recommended</span>
              </div>

              <div style="background-color: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-sm); padding: var(--space-4); margin-bottom: var(--space-4); font-size: 0.875rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-3);">
                  <div><strong>Bank Name:</strong> <?= e($bankDetails['bank_name']) ?></div>
                  <div><strong>Country:</strong> <?= e($bankDetails['bank_country']) ?></div>
                  <div><strong>Account Number:</strong> <?= e($bankDetails['account_number']) ?></div>
                  <div><strong>BBAN:</strong> <?= e($bankDetails['bban']) ?></div>
                </div>
                <div style="margin-top: var(--space-3); padding-top: var(--space-2); border-top: 1px dashed var(--slate-300); color: var(--state-warning); font-size: 0.8125rem;">
                  <strong>Required Transfer Memo / Remark:</strong> Quote Reference <code><?= e($registration['public_reference']) ?></code>
                </div>
              </div>

              <!-- Form to submit Bank Transfer Reference -->
              <form action="/register/submit-payment" method="POST">
                <?= Csrf::getField() ?>
                <input type="hidden" name="public_reference" value="<?= e($registration['public_reference']) ?>">

                <div class="form-group">
                  <label class="form-label" for="bank_reference">
                    Bank Deposit / Transaction Reference Number <span class="required">*</span>
                  </label>
                  <input type="text" id="bank_reference" name="bank_reference" class="form-input" placeholder="e.g. BLMB-TRF-982341 or Teller No." required>
                  <span class="form-helper">Enter the transaction reference or teller code from your bank slip/app.</span>
                </div>

                <div class="form-group">
                  <label class="form-label" for="deposit_date">Date of Transfer</label>
                  <input type="date" id="deposit_date" name="deposit_date" class="form-input" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                  <label class="form-label" for="notes">Additional Transfer Details (Optional)</label>
                  <input type="text" id="notes" name="notes" class="form-input" placeholder="Depositor name or issuing bank if different">
                </div>

                <button type="submit" class="btn btn-gold btn-block">
                  Submit Transfer Reference for Audit Review
                </button>
              </form>
            </div>
          </div>

          <!-- Option 2: Online Payment Sandbox Simulator (Clearly marked per D-07) -->
          <div class="card">
            <div class="card-body">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-2);">
                <h4 style="color: var(--navy-900); font-size: 1.1rem;">Option 2: Online Card / Gateway Payment</h4>
                <span class="badge badge-warning">Staging Sandbox Adapter (D-07)</span>
              </div>
              <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: var(--space-4);">
                In accordance with project governance, live merchant processing remains in sandbox testing until authorized client credentials and currency rules are finalized. You may simulate payment verification below.
              </p>

              <form action="/register/sandbox-pay" method="POST">
                <?= Csrf::getField() ?>
                <input type="hidden" name="public_reference" value="<?= e($registration['public_reference']) ?>">
                <button type="submit" class="btn btn-navy btn-block">
                  Simulate Verified Sandbox Payment (<?= e($registration['currency']) ?> <?= number_format((float)$registration['amount'], 2) ?>)
                </button>
              </form>
            </div>
          </div>

        <?php endif; ?>

        <div style="margin-top: var(--space-8); padding-top: var(--space-4); border-top: 1px solid var(--slate-200); text-align: center;">
          <a href="/register" class="btn btn-outline-white btn-sm" style="color: var(--slate-600); border-color: var(--slate-300);">
            &larr; Submit Another Registration
          </a>
        </div>
      </div>

    <?php else: ?>
      <!-- ====================================================================
           DEFAULT VIEW: New Delegate Registration Form
           ==================================================================== -->
      <div class="reg-state-box">
        <div class="section-header-block" style="margin-bottom: var(--space-6);">
          <div class="section-eyebrow">Accreditation Desk</div>
          <h2 class="section-title">AMVAC 2027 Registration Form</h2>
          <p style="color: var(--slate-600);">
            Please fill out all required details accurately for security protocol and official certificate issuance.
          </p>
        </div>

        <!-- Pricing Disparity Transparency Notice (D-05) -->
        <div class="alert alert-info" style="font-size: 0.875rem; margin-bottom: var(--space-6);">
          <strong>Pricing & Currency Rule (D-05):</strong>
          In accordance with conference promotional guidelines, registration fees are structured by regional delegation:
          <strong>₦100,000</strong> for Nigerian domestic delegates, and <strong>US$50</strong> for International and ECOWAS delegates. Fee amounts are fixed server-side.
        </div>

        <form action="/register" method="POST" novalidate>
          <?= Csrf::getField() ?>

          <!-- Full Name -->
          <div class="form-group">
            <label class="form-label" for="full_name">
              Full Legal Name <span class="required">*</span>
            </label>
            <input type="text" id="full_name" name="full_name" class="form-input" placeholder="e.g. Dr. Aminu O. Toure" required>
            <span class="form-helper">As it should appear on your official conference badge and certificate.</span>
          </div>

          <!-- Email & Phone Grid -->
          <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-4);" class="form-grid-2">
            <div class="form-group">
              <label class="form-label" for="email">
                Official / Work Email <span class="required">*</span>
              </label>
              <input type="email" id="email" name="email" class="form-input" placeholder="name@agency.gov or name@domain.com" required>
              <span class="form-helper">Registration reference and receipts will be dispatched here.</span>
            </div>

            <div class="form-group">
              <label class="form-label" for="phone">
                Telephone Number (with Country Code) <span class="required">*</span>
              </label>
              <input type="tel" id="phone" name="phone" class="form-input" placeholder="e.g. +220 700 0000 or +234 803 000 0000" required>
            </div>
          </div>

          <!-- Country Selection -->
          <div class="form-group">
            <label class="form-label" for="country">
              Country of Residence / Delegation <span class="required">*</span>
            </label>
            <select id="country" name="country" class="form-select" required>
              <option value="">-- Select Country --</option>
              <option value="The Gambia">The Gambia (Host Nation)</option>
              <option value="Nigeria">Nigeria</option>
              <option value="Ghana">Ghana</option>
              <option value="Senegal">Senegal</option>
              <option value="Sierra Leone">Sierra Leone</option>
              <option value="Liberia">Liberia</option>
              <option value="Cote d'Ivoire">Côte d'Ivoire</option>
              <option value="Kenya">Kenya</option>
              <option value="South Africa">South Africa</option>
              <option value="Rwanda">Rwanda</option>
              <option value="Ethiopia">Ethiopia</option>
              <option value="Egypt">Egypt</option>
              <option value="Other African Nation">Other African Nation</option>
              <option value="International / Non-AU">International / Non-AU</option>
            </select>
          </div>

          <!-- Organization & Job Title -->
          <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-4);" class="form-grid-2">
            <div class="form-group">
              <label class="form-label" for="organization">
                Organization / Ministry / Agency <span class="required">*</span>
              </label>
              <input type="text" id="organization" name="organization" class="form-input" placeholder="e.g. Ministry of Transport / FRSC / VIO" required>
            </div>

            <div class="form-group">
              <label class="form-label" for="job_title">
                Official Designation / Job Title <span class="required">*</span>
              </label>
              <input type="text" id="job_title" name="job_title" class="form-input" placeholder="e.g. Director of Vehicle Inspection" required>
            </div>
          </div>

          <!-- Participation Format -->
          <div class="form-group">
            <label class="form-label">
              Participation Modality <span class="required">*</span>
            </label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
              <label style="border: 1px solid var(--slate-300); padding: var(--space-3); border-radius: var(--radius-sm); display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                <input type="radio" name="participation_format" value="in_person_banjul" checked>
                <span><strong>In-Person</strong> (Banjul, The Gambia)</span>
              </label>
              <label style="border: 1px solid var(--slate-300); padding: var(--space-3); border-radius: var(--radius-sm); display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                <input type="radio" name="participation_format" value="virtual_hybrid">
                <span><strong>Virtual Hybrid</strong> (Abuja / Online Relay)</span>
              </label>
            </div>
          </div>

          <!-- Fee Category Selection -->
          <div class="form-group">
            <label class="form-label">
              Registration Fee Category <span class="required">*</span>
            </label>
            
            <div class="fee-selector-card selected">
              <label style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; width: 100%;">
                <div style="display: flex; align-items: center; gap: var(--space-3);">
                  <input type="radio" name="fee_category" value="nigerian_delegate" checked>
                  <div>
                    <strong style="color: var(--navy-900);">Nigerian Resident Delegate</strong>
                    <div style="font-size: 0.8125rem; color: var(--slate-600);">For delegates resident in Nigeria or affiliated with Nigerian institutions.</div>
                  </div>
                </div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--gold-600);">₦100,000</div>
              </label>
            </div>

            <div class="fee-selector-card">
              <label style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; width: 100%;">
                <div style="display: flex; align-items: center; gap: var(--space-3);">
                  <input type="radio" name="fee_category" value="international_delegate">
                  <div>
                    <strong style="color: var(--navy-900);">International & ECOWAS Delegate</strong>
                    <div style="font-size: 0.8125rem; color: var(--slate-600);">For delegates from The Gambia, ECOWAS member states, and international institutions.</div>
                  </div>
                </div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--gold-600);">US$50</div>
              </label>
            </div>
          </div>

          <!-- Consent Checkbox -->
          <div class="form-group" style="margin-top: var(--space-6);">
            <label class="form-checkbox-label">
              <input type="checkbox" name="consent" value="1" class="form-checkbox" required checked>
              <span>
                I agree to the processing of my registration details for AMVAC 2027 conference security accreditation, badge printing, and official protocol communications.
              </span>
            </label>
          </div>

          <!-- Submit Button -->
          <div style="margin-top: var(--space-8);">
            <button type="submit" class="btn btn-gold btn-lg btn-block">
              Submit Delegate Registration & Continue to Payment
            </button>
          </div>
        </form>
      </div>

      <!-- Quick Reference Lookup Box -->
      <div class="card" style="max-width: 540px; margin: 0 auto; text-align: center;">
        <div class="card-body">
          <h4 style="font-size: 1rem; color: var(--navy-900); margin-bottom: var(--space-2);">Already Registered?</h4>
          <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: var(--space-3);">
            Enter your registration reference to verify status or upload bank payment proof.
          </p>
          <form action="/register" method="GET" style="display: flex; gap: var(--space-2);">
            <input type="text" name="ref" class="form-input" placeholder="e.g. AMVAC-2027-XXXXXXXX" required>
            <button type="submit" class="btn btn-navy btn-sm">Lookup</button>
          </form>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
