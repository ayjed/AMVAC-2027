# AMVAC 2027 — Project Source of Truth

**Version:** 1.0  
**Status:** Planning / awaiting client verification  
**Last updated:** 22 October 2026  
**Project:** Africa Motor Vehicle Administration Conference (working name) website  
**Document authority:** This document governs scope, content, design, implementation, testing, and launch. If a coding-agent output conflicts with it, this document wins. Changes require an entry in the Change Log.

## 1. Project purpose
Build a premium, mobile-responsive international conference website that communicates the event's purpose, introduces approved leadership, publishes the programme and venue details, accepts delegate registrations, supports payments, and lets a small administrator team handle registrations and approved content. Do not create an oversized conference portal.

### Success criteria
- Five polished public pages, complete and functional on desktop, tablet, and mobile.
- Clear official conference theme and purpose, with all **eight original objectives** preserved.
- Reliable delegate registration, payment-status tracking, and confirmations.
- Secure, minimal administrator area with auditable payment verification.
- Fast, accessible pages with real/approved imagery and accurate text.
- No invented dates, guests, endorsements, statistics, agendas, testimonials, or institutional partnerships.

## 2. Canonical scope and navigation (LOCKED)

| Route | Page | Required content |
|---|---|---|
| `/` | Home | Hero with approved title/theme/date/location; event introduction; four highlight themes; approved leadership preview; concise presentation of all eight objectives; schedule/venue preview; registration CTA; organizers and footer. |
| `/about` | About & Leadership | Conference background, full eight objectives, organizers and collaborator, approved leader profiles and **confirmed** partners/speakers only. |
| `/programme` | Programme & Venue | Approved agenda; daily events when supplied; official venue/address when supplied; hybrid details if confirmed; travel guidance if supplied; brochure download if approved. |
| `/register` | Registration | Registration form, choice of fee/currency as approved, supported payment options, payment status, legal consent, success/failure/pending states and confirmation instructions. |
| `/contact` | Contact | Official email/phone/address when supplied, organizer identities, inquiry form and approved social links. |

**Not standalone pages:** Speakers, Sponsors, Partners, News, Media, Travel, Gallery, Resources, or FAQs. Incorporate only approved content into the five pages, or omit empty sections. Legal privacy/terms can be lightweight supplementary routes or dialogs if necessary for compliance; they are not promotional navigation pages.

### Admin (one private area, not a client/staff dashboard)
- Secure login and logout.
- Overview: registrations counts and payment-status breakdown.
- Registrations: list, search, filter, view, CSV export; limited corrections with audit logging.
- Payments: online transaction verification and admin-review workflow for bank transfers; never mark paid solely because the visitor clicks a button or uploads a slip.
- Content: small, controlled editables (approved programme entries, venue, contact info, profiles, downloadable brochure). Avoid general-purpose CMS complexity.
- Minimal roles: one super-admin initially; expand only on written approval.

## 3. Event content — client-supplied, pending confirmation

**Working public name:** Africa Motor Vehicle Administration Conference.  
**Alternate flyer name:** 1st International Conference on Motor Vehicle Administration.  
**Theme (retain exact wording):** Enhancing Continental Security, Road Safety and Economic Integration Through Effective Motor Vehicle Administration in Africa.

**Event dates in supplied flyer:** 15–19 March 2027.  
**Geography in supplied material:** Banjul, The Gambia; separate reference also says “Hybrid – Abuja & Banjul.”  
**Venue in original promotional flyer:** BICC Conference Centre, Banjul (verify exact official name and address).  
**Registration fees supplied:** ₦100,000 and US$50; clarify eligibility/pricing rule, because these are not equivalent fees.  
**Bank supplied:** Bloom Bank Africa, The Gambia.  
**Account number supplied:** 1031770538978.  
**BBAN supplied:** 013202177053897837.  
**Payment details MUST stay out of live public pages until beneficiary name, bank details and beneficiary authorization have been verified in writing.** Do not store account numbers in client-side code beyond intentionally published approved display content.

**Organizing entities mentioned:**
- Accident Prevention and Rescue Initiative (APRI), Nigeria.
- Road Safety Driver Standard Association of The Gambia.
- Network of Africa Road Safety NGOs appears on the reference screenshot. Its event role needs verification before publication.

**People / identities:**
- Amb. Prince Fidelis Nnadi — client identifies as Executive Director, APRI, and conference host / consultant on motor vehicle administration.
- Njie Baldeh — client identifies as Executive Secretary, Road Safety Driver Standard Association of The Gambia; photo provided (blue shirt).
- President Adama Barrow — photo provided; **do not imply endorsement, hosting, attendance or confirmed involvement** without written authorization and verification.
- Other figures shown on promotional flyer are **not automatically confirmed speakers or participants**.

### Eight objectives — canonical copy
1. **Continental Harmonization:** To establish a unified legal and regulatory framework for vehicle inspection, registration, licensing and number plate administration across Africa.
2. **Security Integration:** To integrate motor vehicle data into Africa's security system to combat cross-border vehicle theft, smuggling, kidnapping and terrorism.
3. **Digital Transformation:** To promote automation, centralized digital databases and biometric systems for transparency and traceability continent-wide.
4. **Capacity Building:** To train and retrain Motor Vehicle Administrators, VIOs and Road Safety Officers in modern techniques and global best practices.
5. **Safer African Roads:** To rid African roads of unroadworthy vehicles and reduce the over 300,000 annual road deaths in Africa.
6. **Public Enlightenment:** To reorient public perception of Motor Vehicle Administration as a life-saving service, not punishment.
7. **Inter-Agency & Cross-Border Collaboration:** To foster partnership among transport agencies, Police, Customs, FRSC, Immigration and Interpol across AU states.
8. **Economic Development:** To unlock revenue, create jobs, and promote establishment of number plate, driver license and vehicle assembly plants across Africa.

**Slogan:** Please be road safety conscious to save lives.

**Editorial rule:** Preserve these eight client-supplied objectives faithfully. The “over 300,000 annual road deaths” figure requires a reputable source and time period before publishing as a verified factual claim; if not substantiated, request client-approved revision rather than fabricating a citation. Other policy or security claims are event goals, not assertions that systems already exist.

## 4. Visual design direction
- Brand mood: authoritative, editorial, diplomatic, credible, contemporary African policy conference; not generic event/NGO template.
- Palette: deep navy `#091D38`, restrained gold `#C9A24A`, off-white `#F7F8FA`, royal blue `#2866A0`; accessible body text.
- Typography: pair one elegant, legible headline family with a neutral, readable UI/body family; use only licensed/web-safe fonts and verify mobile rendering.
- Layout: purposeful asymmetry, strong typographic hierarchy, generous whitespace, consistent spacing, real-event photography/approved portraits, subtle continent/map texture where appropriate.
- UI: clean SVG or icon-font icons; no tacky 3D icons, artificial glows, excessive gradients, stock-looking people, fake endorsements, or AI-looking hero compositions.
- Images: use supplied originals as starting assets; gently enhance exposure, crop and sharpness without altering identities or misrepresenting events. Never use screenshot composites as final web assets. If the portrait is too low-resolution, request a higher-quality approved photo.
- Mobile first, with consistent image ratios and predictable header/navigation, forms and content stacking.
- Design elements must serve meaning, not decoration.

## 5. Functional specification

### Registration fields (minimum)
`full_name`, `email`, `phone`, `country`, `organization`, `job_title`, `participation_format` (only if confirmed), `fee_option` (only approved pricing structure), `consent`, timestamps. Optional fields require written justification.

### Registration lifecycle
1. Validate/sanitize server-side; apply spam and rate limits.
2. Create registration in `pending_payment` state with non-guessable public reference.
3. Generate a payment attempt with currency/amount fixed **server-side** from approved fee rules.
4. For online payment: redirect to provider; use authenticated webhook and independent provider verification with idempotency. Never trust the success-page query alone.
5. For bank transfer: show approved details and collection of a reference; keep `pending_manual_review` until an authorized admin confirms actual receipt in the bank.
6. Only verified payment sets `paid`, with amount, currency, method, provider reference, verified_at, and verifying actor recorded.
7. Send transactional acknowledgement at submission and confirmed-registration email/receipt only after verified payment. Support safe retry on email failure.
8. Prevent duplicate fee confirmations and double-processing.

### Payment considerations
- Nigeria: Paystack may be a candidate for NGN; determine whether it meets exact merchant/currency requirements before choosing.
- International: select a provider only after confirming the receiving entity, supported settlement currencies and merchant eligibility. Do **not** assume Paystack supports the US$50 flow in the client configuration.
- No card details stored on site; use provider-hosted checkout.
- Configure webhook signature validation, replay/idempotency protection and fraud-aware verification.
- Keep credentials and email secrets only in server environment variables; never commit `.env`.

### Contact
Validated inquiry form with CSRF/rate limiting, transactional delivery to approved organizer email; no public email or phone until confirmed.

## 6. Technical architecture
- **Backend:** PHP 8.3+ (plain PHP, simple MVC-style structure; no framework unless approved).
- **Database:** MySQL 8+ / compatible MariaDB.
- **Frontend:** semantic HTML5, modern CSS, lightweight vanilla JavaScript; no mandatory SPA framework.
- **CSS:** custom design tokens and components; no reliance on a heavy page builder.
- **Deployment:** secure HTTPS on cPanel or VPS; environment-dependent credentials, migrations, backups and restricted admin routes.
- **Mail:** authenticated SMTP/transactional provider, queued/retry strategy when practical.
- **Assets:** approved photos in optimized AVIF/WebP with source backups; SVG only for clean logos/icons.

### Suggested tables
`admins`, `registrations`, `payment_attempts`, `payment_events`, `manual_payment_reviews`, `content_entries`, `contact_enquiries`, `audit_logs`, `settings`.

### Engineering constraints
Use prepared statements (PDO), output escaping, HTML allowlists for approved rich text, password hashing, server-side sessions and cookie hardening, CSRF defense, rate limiting, authorization for admin actions, upload restrictions, secure file storage, sensible HTTP headers and detailed server logging without secret leakage. Back up database before deployment. Provide an install/rollback runbook and test migrations.

## 7. Quality and acceptance gates
- Every public route passes desktop/tablet/mobile review (minimum widths: 360, 390, 768, 1024, 1440 px).
- No horizontal overflow, broken navigation, overlapping CTAs, distorted faces, mismatched image proportions, or clipped text.
- Keyboard accessible navigation/forms, visible focus, form error messages, meaningful alt text, reasonable WCAG 2.2 AA practices.
- Optimize images; defer offscreen media; functional site without hero video.
- Test correct currency and fee selection, safe server-side amount enforcement, form validation, webhook verification, duplicate events, manual-review states and confirmation emails.
- All contact, venue, date, speaker, logo, account and organizer copy approved before live launch.
- Staging demo approved before launch; backups, DNS, HTTPS, privacy notice and basic analytics decisions documented.
- Target good Core Web Vitals and Lighthouse scores, but no invented guaranteed performance score.

## 8. Phases and deliverables
| Phase | Purpose | Exit condition |
|---|---|---|
| 1 — Discovery & content | Approve naming, ownership, source text, scope, payment flow, and content gaps | Requirements and content inventory signed off |
| 2 — Design | Design system, homepage and four interior responsive page designs, admin patterns | Responsive design approved; no missing approved copy |
| 3 — Public frontend | Build five semantic responsive pages, reusable UI, content wiring | Visual and functional review passed |
| 4 — Backend & integrations | DB, admin, registration, payment workflows, email, contact | Integration and security tests passed |
| 5 — QA & UAT | Cross-browser/device QA, accessibility, payment sandbox and content check | Defects fixed, client accepts staging |
| 6 — Launch & handover | Production setup, migration, monitoring, training and documentation | Live smoke test, backups and handover complete |

## 9. Out of scope by default
No hotel/flight booking engine, visa processing, ticket marketplace, delegate login portal, staff accounts, mobile application, full CRM, AI chatbot, livestream platform, full CMS/page-builder, social feed integration, automatic speaker invitations, or unapproved sponsor/partner pages.

## 10. Decision register / outstanding approvals
| ID | Decision needed | Default until decided |
|---|---|---|
| D-01 | Exact public event name and short brand/initialism | Working name; do not lock final artwork |
| D-02 | Exact dates, venue/address and Abuja/hybrid format | Display only clearly identified provisional copy in development |
| D-03 | Legal organizing entity and logo usage | Do not imply unapproved institutional affiliation |
| D-04 | Confirmed speakers, VIPs and authorization for official portraits | Hide unapproved individuals |
| D-05 | Registration fees by resident/country/currency | Do not activate checkout |
| D-06 | Bank account beneficiary, authorized collection entity and details | Hide live payment instructions |
| D-07 | Online payment provider and merchant credentials | Implement provider adapter/sandbox only |
| D-08 | Official emails, phone, socials and venue contact | Keep placeholders on staging only |
| D-09 | Full programme PDF and day-by-day agenda | Show “Programme details to be announced” without invented events |
| D-10 | Citation/approved wording for annual road deaths statistic | Hold that numerical assertion pending review |
| D-11 | Approved photos and people represented in promo flyer | Use approved photos only |
| D-12 | Registration confirmation email sender and content | Use draft until approved |

## 11. Change control
- Changes to routes/pages, pricing, payment providers, scope or public claims require client approval and an entry below.
- Update this document before updating prompts, designs, schemas or code.

| Date | Version | Change | Approved by |
|---|---|---|---|
| 2026-10-22 | 1.0 | Initial 5-page project baseline and six-phase plan | Pending |

## 12. Asset inventory (provided references)
- Portrait of President Adama Barrow (supplied; authorization/role pending).
- Portrait of Njie Baldeh in blue shirt (client-supplied identification).
- Screenshot of conference promotional flyer (reference only; obtain original design assets).
- Screenshot of existing/reference conference homepage (visual/content reference only; no copying pixel-for-pixel).

**Important:** Only use content and rights the client is entitled to publish. Never invent official seals, flags, organizational endorsements or biographies.
