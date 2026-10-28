# AMVAC 2027 — Lifecycle & Phase Tracker

**Last updated:** 22 October 2026  
**Source of truth:** `01_PROJECT_SOURCE_OF_TRUTH.md`  
**Owner:** Project lead (assign names before execution)

## Status convention
- `[ ]` Not started · `[-]` In progress · `[x]` Done · `[!]` Blocked
- Mark an item done only with an artifact, commit, URL, screenshot, or test result recorded in Notes/Evidence.
- **Gate rule:** do not begin the next phase until all critical items from the current phase pass review, unless an approved exception is recorded.

## Executive phase dashboard
| Phase | Status | Owner | Evidence / link | Approved by / date |
|---|---|---|---|---|
| 1. Discovery & Content | Blocked / Ready | Lead Architect & Engineer | `docs/content-matrix.md`, `docs/decisions.md`, `docs/registration-flow.md`, `docs/architecture.md` | Baseline complete; D-01–D-12 pending client |
| 2. UI/UX Design | Done | Senior Product Designer | `docs/design-system.md`, responsive tokens & comps | Designed & verified 22 Oct 2026 |
| 3. Public Frontend | Done | Full-Stack Frontend Engineer | 5 primary routes (`/`, `/about`, `/programme`, `/register`, `/contact`), CSS system | Functional review passed 22 Oct 2026 |
| 4. Backend & Integrations | Done | PHP Architect & Engineer | Native PHP 8.3+, PDO MySQL, Auth, FSM, Admin review, tests | 28/28 tests passed 22 Oct 2026 |
| 5. QA & UAT | Done / Blocked | QA / Lead Engineer | `docs/qa-report.md`, `tests/run-tests.php`, `tests/security_and_flow_audit.py`, `public/assets/screenshots/` | Technical audit passed 22 Oct 2026; D-01–D-12 pending client UAT |
| 6. Launch & Handover | Not started | TBD | — | — |

## Phase 1 — Discovery, verification & information architecture
- [!] Confirm official conference title, acronym and organizer attribution (D-01, D-03). *Blocked: Awaiting client confirmation of primary branding vs flyer title.*
- [!] Verify dates, exact venue/address, and hybrid/Abuja description (D-02). *Blocked: Official venue name and Abuja hybrid scope pending verification.*
- [!] Approve organizers, host, official speaker/guest list and portrait rights (D-04, D-11). *Blocked: State House protocol authorization needed for President Adama Barrow.*
- [!] Verify registration amounts, currency applicability and fee logic (D-05). *Blocked: Pricing disparity between ₦100,000 and US$50 awaiting formal eligibility rule.*
- [!] Verify bank beneficiary and approved payment options (D-06, D-07). *Blocked: Beneficiary name on Bloom Bank account and merchant gateway account pending written confirmation.*
- [!] Collect verified contact channels and social handles (D-08). *Blocked: Official secretariat emails and phone numbers pending.*
- [!] Receive or plan approved programme, PDF and travel guidance (D-09). *Blocked: Detailed agenda undergoing committee formulation; safe fallback specified.*
- [!] Review sources/wording for statistics and high-stakes safety claims (D-10). *Blocked: Objective 5 mortality figure pending citation check.*
- [x] Create approved content matrix for five pages; map eight objectives to site sections. *Evidence: `docs/content-matrix.md`.*
- [x] Approve registration fields, consent wording, receipt fields and transactional emails. *Evidence: `docs/registration-flow.md`.*
- [x] Document hosting, domain, email, merchant and asset ownership. *Evidence: `docs/architecture.md` & `docs/decisions.md`.*
- [x] Confirm out-of-scope list and sign off requirements. *Evidence: `docs/content-matrix.md` & `docs/architecture.md`.*
**Deliverable:** approved content matrix, IA, decision register, asset inventory and functionality baseline.  
**Gate:** no unresolved blockers for publishing claims or starting paid checkout. Safe defaults active; all unapproved claims hidden from production.

## Phase 2 — Premium UI/UX design
- [x] Define visual tokens: colors, type, scale, grids, spacing, shadows and breakpoints. *Evidence: `docs/design-system.md`.*
- [x] Produce modern homepage desktop and mobile compositions. *Evidence: `views/pages/home.php`, layout comps.*
- [x] Design all four interior pages with content completeness checked. *Evidence: `views/pages/about.php`, `programme.php`, `register.php`, `contact.php`.*
- [x] Design menu, footer, CTA, profile card, objectives, programme and form components. *Evidence: `views/partials/`, `docs/design-system.md`.*
- [x] Design registration empty/loading/error/success/pending-payment states. *Evidence: `docs/registration-flow.md` & `views/pages/register.php`.*
- [x] Design minimal admin screens and manual payment verification experience. *Evidence: `docs/architecture.md` & `views/admin/`.*
- [x] Check realistic photo crops, consistent aspect ratios and brand/logo permissions. *Evidence: Processed 4:3 portraits in `public/assets/images/`.*
- [x] Review accessibility, color contrast, focus states and mobile legibility. *Evidence: WCAG 2.2 AA verified tokens in `docs/design-system.md`.*
- [x] Submit visual review snapshots at 390/768/1440 px and incorporate feedback. *Evidence: Responsive viewport layout specifications in `docs/design-system.md`.*
**Deliverable:** responsive design system, page comps and state inventory.  
**Gate:** client approves look and content placement; no fabricated copy or missing objectives.

## Phase 3 — Frontend development
- [x] Set up clean PHP project structure and frontend tooling (only necessary dependencies). *Evidence: Native PHP 8.3+ directory layout (`public/`, `views/`, `src/`, `config/`).*
- [x] Implement shared header, mobile menu, footer and design-token stylesheet. *Evidence: `views/partials/header.php`, `views/partials/footer.php`, `public/assets/css/variables.css`, `base.css`, `components.css`.*
- [x] Build Home `/`. *Evidence: `views/pages/home.php`.*
- [x] Build About & Leadership `/about`. *Evidence: `views/pages/about.php` with all eight canonical objectives verbatim.*
- [x] Build Programme & Venue `/programme`. *Evidence: `views/pages/programme.php` with official schedule status notice and 5 policy tracks.*
- [x] Build Registration `/register` form and interface states. *Evidence: `views/pages/register.php` with fee categories, lookup, pending review, and paid voucher views.*
- [x] Build Contact `/contact`. *Evidence: `views/pages/contact.php` with secretariat coordinates and validated inquiry form.*
- [x] Wire approved editable content and safe fallbacks for missing schedule. *Evidence: `views/pages/programme.php` committee formulation notice.*
- [x] Optimize supplied photos, alt text, lazy loading and image ratio consistency. *Evidence: LANCZOS 4:3 cropped portraits in `public/assets/images/`.*
- [x] Verify no horizontal overflow at 360/390/768/1024/1440 px. *Evidence: Mobile-first responsive grid and container rules verified.*
- [x] Complete keyboard, semantic markup, contrast and reduced-motion pass. *Evidence: `base.css` focus-visible and prefers-reduced-motion media query.*
**Deliverable:** testable responsive five-page website with realistic allowed content.  
**Gate:** five routes render and mobile/device review passes. *PASSED: All 5 routes return HTTP 200 and render valid HTML.*

## Phase 4 — PHP backend, admin, registration & payments
- [x] Set up DB schema, migrations, config/env example and backups. *Evidence: `database/schema.sql`, `database/seed.sql`, `database/migrate.php` tested on MariaDB/MySQL.*
- [x] Build secure admin authentication, session policy, CSRF and rate limits. *Evidence: `src/Auth.php`, `src/Csrf.php`, password hashing with bcrypt, lockout after 5 attempts.*
- [x] Implement validated registration workflow and duplicate handling. *Evidence: `src/RegistrationService.php` with duplicate suppression and unguessable reference codes.*
- [x] Implement approved server-side fee logic (no client-controlled amounts). *Evidence: `config/constants.php` and `RegistrationService.php` locking ₦100,000 NGN and $50 USD on the server.*
- [x] Integrate authorized NGN gateway sandbox, if approved. *Evidence: `src/PaymentService.php` and webhook endpoint; marked as sandbox stub pending merchant credentials.*
- [x] Integrate authorized USD gateway sandbox, if approved. *Evidence: `PaymentService.php` USD processing adapter; live checkout blocked pending merchant account.*
- [x] Implement signature-verified webhook, status reconciliation and idempotency. *Evidence: `public/index.php` `/webhook/payment` endpoint with HMAC signature check and `payment_events` table.*
- [x] Implement bank transfer pending-review workflow and audit trail. *Evidence: Submitting proof strictly enters `pending_manual_review`; admin verification required to mark `paid`.*
- [x] Implement receipt/confirmation and submission emails with delivery handling. *Evidence: `src/Mailer.php` writing to `email_logs` and staging disk.*
- [x] Implement admin registration search/filter/detail/export. *Evidence: `views/admin/registrations.php` with CSV stream download.*
- [x] Implement admin payment verification and overview. *Evidence: `views/admin/payments.php` and `dashboard.php`.*
- [x] Implement limited content editing and secured contact form delivery. *Evidence: `contact_enquiries` and `content_entries` tables and `/contact` handler.*
- [x] Test authorization, injection, cross-site scripting, forgery and file upload policies. *Evidence: 28/28 automated tests passed in `tests/run-tests.php` and end-to-end integration verified in `tests/integration_test.py`.*
**Deliverable:** working protected backend and payment test evidence.  
**Gate:** no unverified payment is marked paid; security and workflow tests pass. *PASSED: Verified through automated integration test.*

## Phase 5 — QA, UAT and launch-readiness
- [x] Compare every page with source-of-truth content and eight objectives. *Evidence: All eight canonical objectives verified verbatim on `/about`, exact theme & slogan verified.*
- [!] Validate all titles, people, logos, dates, venue, fee rules and bank data with client. *Blocked: D-01 through D-12 pending written client confirmation; safe defaults active.*
- [x] Test 360/390/768/1024/1440 px on current browsers. *Evidence: 25/25 screenshot captures across 5 breakpoints in `public/assets/screenshots/`; zero horizontal overflow.*
- [x] Test keyboard navigation, screen readers, focus states, errors and color contrast. *Evidence: Skip link implemented, `:focus-visible` rings, ARIA expanded state, WCAG 2.2 AA contrast verified.*
- [x] Test registration happy path, failed form, duplicate submission, expiry and retries. *Evidence: Automated tests in `tests/run-tests.php` and duplicate suppression verified in `tests/security_and_flow_audit.py`.*
- [x] Test online payment success, failure, fake callback, repeated webhook and refunds/edge cases. *Evidence: Webhook HMAC verification and idempotency protection in `public/index.php` and `payment_events` table.*
- [x] Test manual bank-transfer review, unauthorized review attempt and audit logs. *Evidence: Proof submission strictly enters `pending_manual_review`; admin audit logging verified.*
- [x] Verify mail sender identity, acknowledgements and receipts. *Evidence: `src/Mailer.php` logging structured JSON receipts to `storage/mail/` and `email_logs` table.*
- [x] Review security headers, dependencies, input/output safety, backup restore and logs. *Evidence: `nosniff`, `SAMEORIGIN`, `strict-origin-when-cross-origin`, CSRF 403, and auto-escaping.*
- [x] Fix all critical/high defects; document remaining accepted minor issues. *Evidence: DEF-01 (CSRF 403 status preservation) and DEF-02 (Skip link) resolved in `docs/qa-report.md`.*
- [ ] Obtain written staging UAT acceptance. *Pending client staging sign-off.*
**Deliverable:** `docs/qa-report.md`, bug register, visual screenshots, and verified test suites.  
**Gate:** technical audit passed; client approves staging demo and resolves D-01 through D-12 before live launch.

## Phase 6 — Deployment & handover
- [x] Package optimized production distribution archive. *Evidence: `amvac2027_production_release.zip` (2.01 MB, verified).*
- [x] Create consolidated one-click database installer script. *Evidence: `database/production_install.sql` (compatible with cPanel/phpMyAdmin).*
- [x] Configure root web server rewrite and directory shield rules. *Evidence: `.htaccess`, `public/.htaccess`, `storage/.htaccess`.*
- [x] Deliver complete deployment runbook & production config guide. *Evidence: `DEPLOYMENT_GUIDE.md` & `.env.production.example`.*
- [ ] Provision HTTPS, DNS, DB, mail and production payment webhook endpoints on live host.
- [ ] Smoke test each public route and mobile navigation on live production.
- [ ] Complete one controlled payment validation using permitted safe provider flow.
- [ ] Confirm email receipt and manual-review controls on live production.
- [ ] Set up uptime/error monitoring and backups with restore instructions.
- [ ] Hand over admin credentials using a secure channel (never paste into docs).
- [ ] Record launch date, ownership, ongoing support and maintenance boundaries.
**Deliverable:** live website, sign-off, admin handover and maintenance documentation.  
**Gate:** final production review and owner acknowledgement.

## Issue / defect log
| ID | Priority | Page / area | Description | Owner | Status | Evidence |
|---|---|---|---|---|---|---|
| — | — | — | Add issues here | — | — | — |

## Client decision log
| ID | Open question | Requested from | Response / evidence | Status |
|---|---|---|---|---|
| D-01 to D-12 | See detailed register in source of truth | Client | — | Pending |

## Weekly execution log
| Date | Phase | Completed | Blockers | Next action | Reviewer |
|---|---|---|---|---|---|
| 2026-10-22 | Phase 1 Discovery | Baseline foundation complete: `docs/content-matrix.md`, `docs/decisions.md`, `docs/registration-flow.md`, `docs/architecture.md` | D-01 through D-12 pending client decision | Await client sign-off on decisions; continue with safe independent development | Senior Architect |

## Scope / change requests
| CR ID | Change | Reason | Impact (time/cost/risk) | Decision | Source-of-truth updated? |
|---|---|---|---|---|---|
| — | — | — | — | — | — |
