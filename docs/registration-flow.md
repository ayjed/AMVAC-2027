# AMVAC 2027 — Registration & Payment Workflow Specification

**Document Reference:** Governed by `01_PROJECT_SOURCE_OF_TRUTH.md` Section 5  
**Engine:** PHP 8.3+ / PDO MySQL Architecture  
**Security Standard:** Strict Server-Enforced Fees, CSRF Protection, Anti-Replay Idempotency, Audited Manual Reconciliation.

---

## 1. Registration Data Schema & Field Requirements

### Required Input Fields
| Field Name | Type | Validation Rules | Purpose |
|---|---|---|---|
| `full_name` | string (max 150) | Required, trimmed, UTF-8, sanitize HTML, regex `^[a-zA-Z\s\.\-']+$` | Delegate name on conference badge and certificate. |
| `email` | string (max 255) | Required, `FILTER_VALIDATE_EMAIL`, lowercase normalized | Primary key for transactional communications. |
| `phone` | string (max 30) | Required, E.164 international format or numeric + symbols | Urgent protocol and security logistics. |
| `country` | string (max 100) | Required, whitelist against standard ISO 3166-1 country list | Regional grouping and fee category validation. |
| `organization` | string (max 200) | Required, trimmed, sanitize HTML | Ministry, Police, FRSC, VIO, NGO, or corporate affiliation. |
| `job_title` | string (max 150) | Required, trimmed, sanitize HTML | Delegate designation / protocol ranking. |
| `participation_format` | enum | `in_person_banjul` \| `virtual_hybrid` | Logistics capacity planning. |
| `fee_category` | enum | `nigerian_delegate` \| `international_delegate` | Triggers immutable server-side fee rule. |
| `consent` | boolean | Must be `1` (checked) | Explicit consent for conference data processing. |
| `csrf_token` | string (64) | Must match `$_SESSION['csrf_token']` | Protection against Cross-Site Request Forgery. |

### System-Generated Immutable Attributes
- `id`: Bigint primary key (internal use only; never exposed in public URLs).
- `public_reference`: Cryptographically secure unique string (e.g., `AMVAC-2027-` + 8 uppercase alphanumeric chars, e.g. `AMVAC-2027-7K9W4D2E`).
- `amount`: Fixed server-side according to `fee_category`:
  - `nigerian_delegate` -> `100000.00`
  - `international_delegate` -> `50.00`
- `currency`: Fixed server-side:
  - `nigerian_delegate` -> `NGN`
  - `international_delegate` -> `USD`
- `status`: Enum (`pending_payment`, `pending_manual_review`, `paid`, `failed`, `cancelled`).
- `created_at`, `updated_at`: Timestamps.

---

## 2. Finite State Machine (Registration Lifecycle)

```mermaid
stateDiagram-v2
    [*] --> FormSubmitted: POST /register
    FormSubmitted --> ValidationFailed: Invalid Data / Rate Limit Exceeded
    ValidationFailed --> FormSubmitted: Display Field Errors
    FormSubmitted --> PendingPayment: Valid -> Insert DB -> Generate AMVAC-2027-XXXX
    
    state PendingPayment {
        [*] --> SelectPaymentMethod
        SelectPaymentMethod --> OnlineCheckout: Online Gateway Chosen
        SelectPaymentMethod --> BankTransfer: Direct Bank Transfer Chosen
    }
    
    state OnlineCheckout {
        OnlineGatewayRedirect --> WebhookReceived: Signature Validated
        WebhookReceived --> IdempotencyCheck: Verify Event Not Processed
        IdempotencyCheck --> ProviderQuery: Query Provider API to Verify Status & Amount
        ProviderQuery --> Paid: Status Confirmed + Amount Matched
        ProviderQuery --> Failed: Payment Expired or Cancelled
    }
    
    state BankTransfer {
        SubmitTransferReference --> PendingManualReview: Delegate Enters Teller / Trans Ref
        PendingManualReview --> AdminBankAudit: Admin Logs into Private Admin Portal
        AdminBankAudit --> Paid: Bank Credit Found in Bloom Bank Ledger
        AdminBankAudit --> Rejected: Bank Credit Not Found / Invalid Slip
    }
    
    state Paid {
        SendConfirmationReceipt --> BadgeAccreditationReady
    }
    
    state Rejected {
        SendDeficiencyNotice --> PendingPayment: Delegate May Re-attempt
    }
    
    Failed --> PendingPayment: Delegate Retry
    Paid --> [*]
```

### Detailed State Transitions & Rules

1. **`pending_payment` (Initial State):**
   - Triggered upon valid form submission.
   - Record created in database with server-calculated `amount` and `currency`.
   - Client is redirected to `/register?ref=AMVAC-2027-XXXX`.
   - Initial acknowledgement email dispatched: *"Registration Received — Awaiting Payment"*.

2. **`pending_manual_review` (Bank Transfer Route):**
   - Delegate selects Bank Transfer and submits bank transaction reference / proof details.
   - **CRITICAL SECURITY RULE:** The registration status transitions strictly to `pending_manual_review`.
   - **NEVER** marks registration as `paid` upon user slip submission.
   - Admin receives notification in private review queue.

3. **`paid` (Verified Success State):**
   - Can ONLY be transitioned via:
     a) Verified Server-to-Server Webhook with valid cryptographic HMAC signature, checked against provider REST API, and confirmed amount matches expected server price.
     b) Authorized Administrator action in `/admin/payments/verify` with logged verification notes and admin user identity.
   - Once set to `paid`, the record is locked against duplicate payment attempts.
   - System sends official *"AMVAC 2027 Confirmed Accreditation Pass & Receipt"* email with QR/reference.

4. **`failed` / `cancelled`:**
   - Online checkout timeout, bank decline, or admin rejection of fraudulent transfer reference.
   - Audit trail updated with rejection reason.
   - User notified with opportunity to retry.

---

## 3. Server-Side Fee Enforcement (No Client-Controlled Amounts)

```php
// Strict server-side fee matrix
$approved_fee_matrix = [
    'nigerian_delegate' => [
        'amount'   => 100000.00,
        'currency' => 'NGN',
        'label'    => 'Nigerian Resident Delegate (₦100,000)'
    ],
    'international_delegate' => [
        'amount'   => 50.00,
        'currency' => 'USD',
        'label'    => 'International / Regional Delegate (US$50)'
    ]
];

// Calculation is locked:
$selected_category = $_POST['fee_category'] ?? '';
if (!array_key_exists($selected_category, $approved_fee_matrix)) {
    throw new InvalidArgumentException("Invalid fee category selected.");
}

$fixed_amount   = $approved_fee_matrix[$selected_category]['amount'];
$fixed_currency = $approved_fee_matrix[$selected_category]['currency'];
```

**Client Tamper Resistance:** The frontend form only supplies the category string (`nigerian_delegate` or `international_delegate`). Even if a malicious visitor alters the DOM, the PHP backend discards any posted numeric amount and enforces the matrix values.

---

## 4. Manual Bank Transfer Verification Protocol

Because Bloom Bank Africa (The Gambia) is the nominated institution, online automated clearing is not immediately assumed. The manual verification pipeline follows strict financial auditing:

1. **Information Display (Staging Only until D-06 is approved):**
   - Bank: Bloom Bank Africa, The Gambia
   - Account: 1031770538978 | BBAN: 013202177053897837
   - Mandatory Transfer Memo: Must quote public reference `AMVAC-2027-XXXX`.
2. **Delegate Action:**
   - Delegate submits bank reference number and date of deposit.
3. **Database Logging:**
   - Insert row in `manual_payment_reviews` table (`registration_id`, `bank_reference`, `notes`, `submitted_at`).
4. **Admin Verification (In `/admin/payments`):**
   - Admin must review Bloom Bank account statement or bank portal.
   - Admin clicks **"Confirm Payment Receipt"** or **"Reject Invalid Proof"**.
   - If confirmed:
     - Updates `registrations.status = 'paid'`.
     - Inserts record into `audit_logs` (`actor_id`, `action='PAYMENT_MANUAL_VERIFIED'`, `target_id`, `ip_address`, `timestamp`).
     - Triggers transactional confirmation email.
   - If rejected:
     - Updates `registrations.status = 'failed'` or leaves in `pending_payment`.
     - Logs admin justification in `audit_logs`.

---

## 5. Transactional Email Pipeline & Failure Retries

| Email Event | Trigger | Recipient | Key Content |
|---|---|---|---|
| **Registration Acknowledged** | State -> `pending_payment` | Delegate | Registration Reference, Selected Category, Server Fee, Payment Instructions link. |
| **Bank Reference Received** | State -> `pending_manual_review` | Delegate | Notice that payment proof is undergoing administrative bank reconciliation (1–2 business days). |
| **Payment Confirmed & Receipt** | State -> `paid` | Delegate | Official Accreditation Confirmation, Verification Code, Delegate Name, Organization, Payment Method, Amount, Date. |
| **Payment Rejected / Query** | State -> `rejected` | Delegate | Notification of discrepancy in bank payment reference with support contact link. |

**Retry & Reliability Rules:**
- Transactional messages are logged in `email_logs` table (`recipient`, `template`, `status`, `retry_count`, `last_attempt_at`, `error_message`).
- Failures do not crash the user request; failures are flagged for admin retry in `/admin`.

---

## 6. Duplicate Submission & Replay Attack Defense

1. **Unique Email per Active Cycle:** Prevent duplicate registrations with the exact same email while in `pending_payment` or `paid` state within 30 minutes.
2. **Webhook Idempotency:** Every webhook payload stores `provider_event_id` in `payment_events`. If a webhook arrives with an existing event ID, respond immediately with HTTP 200 without executing state mutations.
3. **Session Replay Prevention:** One-time CSRF tokens generated per session, validated on every POST request.
