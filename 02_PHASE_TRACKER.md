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
| 1. Discovery & Content | In progress | Senior Architect / Engineer | `docs/content-matrix.md`, `docs/decisions.md`, `docs/registration-flow.md`, `docs/architecture.md` | Baseline drafted 22 Oct 2026 |
| 2. UI/UX Design | Not started | TBD | — | — |
| 3. Public Frontend | Not started | TBD | — | — |
| 4. Backend & Integrations | Not started | TBD | — | — |
| 5. QA & UAT | Not started | TBD | — | — |
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
- [ ] Set up clean PHP project structure and frontend tooling (only necessary dependencies).
- [ ] Implement shared header, mobile menu, footer and design-token stylesheet.
- [ ] Build Home `/`.
- [ ] Build About & Leadership `/about`.
- [ ] Build Programme & Venue `/programme`.
- [ ] Build Registration `/register` form and interface states.
- [ ] Build Contact `/contact`.
- [ ] Wire approved editable content and safe fallbacks for missing schedule.
- [ ] Optimize supplied photos, alt text, lazy loading and image ratio consistency.
- [ ] Verify no horizontal overflow at 360/390/768/1024/1440 px.
- [ ] Complete keyboard, semantic markup, contrast and reduced-motion pass.
**Deliverable:** testable responsive five-page website with realistic allowed content.  
**Gate:** five routes render and mobile/device review passes.

## Phase 4 — PHP backend, admin, registration & payments
- [ ] Set up DB schema, migrations, config/env example and backups.
- [ ] Build secure admin authentication, session policy, CSRF and rate limits.
- [ ] Implement validated registration workflow and duplicate handling.
- [ ] Implement approved server-side fee logic (no client-controlled amounts).
- [ ] Integrate authorized NGN gateway sandbox, if approved.
- [ ] Integrate authorized USD gateway sandbox, if approved.
- [ ] Implement signature-verified webhook, status reconciliation and idempotency.
- [ ] Implement bank transfer pending-review workflow and audit trail.
- [ ] Implement receipt/confirmation and submission emails with delivery handling.
- [ ] Implement admin registration search/filter/detail/export.
- [ ] Implement admin payment verification and overview.
- [ ] Implement limited content editing and secured contact form delivery.
- [ ] Test authorization, injection, cross-site scripting, forgery and file upload policies.
**Deliverable:** working protected backend and payment test evidence.  
**Gate:** no unverified payment is marked paid; security and workflow tests pass.

## Phase 5 — QA, UAT and launch-readiness
- [ ] Compare every page with source-of-truth content and eight objectives.
- [ ] Validate all titles, people, logos, dates, venue, fee rules and bank data with client.
- [ ] Test 360/390/768/1024/1440 px on current browsers.
- [ ] Test keyboard navigation, screen readers, focus states, errors and color contrast.
- [ ] Test registration happy path, failed form, duplicate submission, expiry and retries.
- [ ] Test online payment success, failure, fake callback, repeated webhook and refunds/edge cases.
- [ ] Test manual bank-transfer review, unauthorized review attempt and audit logs.
- [ ] Verify mail sender identity, acknowledgements and receipts.
- [ ] Review security headers, dependencies, input/output safety, backup restore and logs.
- [ ] Fix all critical/high defects; document remaining accepted minor issues.
- [ ] Obtain written staging UAT acceptance.
**Deliverable:** QA report, bug register, evidence and signed UAT.  
**Gate:** client approves production launch; payment merchant/account verified.

## Phase 6 — Deployment & handover
- [ ] Back up staging and production configurations; set secrets securely.
- [ ] Provision HTTPS, DNS, DB, mail and production payment webhook endpoints.
- [ ] Apply migrations and deploy optimized code/assets.
- [ ] Smoke test each public route and mobile navigation on production.
- [ ] Complete one controlled payment validation using permitted safe provider flow.
- [ ] Confirm email receipt and manual-review controls on production.
- [ ] Set up uptime/error monitoring and backups with restore instructions.
- [ ] Hand over admin credentials using a secure channel (never paste into docs).
- [ ] Deliver deployment runbook, configuration guide, editor instructions and asset inventory.
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
| 2026-10-22 | Setup | Initial project documents created | Client approvals pending | Start phase 1 | TBD |

## Scope / change requests
| CR ID | Change | Reason | Impact (time/cost/risk) | Decision | Source-of-truth updated? |
|---|---|---|---|---|---|
| — | — | — | — | — | — |
