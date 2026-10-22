# AMVAC 2027 — Design System & UI/UX Specifications

**Document Authority:** Governed by `01_PROJECT_SOURCE_OF_TRUTH.md` Section 4  
**Lifecycle Stage:** Phase 2 Deliverable (UI/UX Design Baseline)  
**Target Identity:** Authoritative, Diplomatic, Editorial, Contemporary African Policy Summit.

---

## 1. Design Rationale & Brand Mood

### Core Aesthetic Principles
1. **Diplomatic Gravitas over Generic NGO / Corporate SaaS:**
   AMVAC 2027 brings together sovereign transport ministries, law enforcement chiefs, vehicle inspection directors, and multilateral delegates. The aesthetic deliberately rejects generic startup templates, trendy drop-shadow glows, oversized gradients, floating 3D icons, and artificial AI-rendered faces. Instead, it embodies the restrained prestige of an international diplomatic summit: crisp editorial typography, formal spatial harmony, structured grid discipline, and real photojournalism.
2. **Color Palette as Identity & Function:**
   - **Deep Navy (`#091D38` / `#061325`):** Serves as the anchor for sovereign authority, institutional stability, and continental security.
   - **Refined Gold (`#C9A24A` / `#A68233`):** Symbolizes pan-African excellence and prestige, applied with deliberate restraint on emblems, active indicators, and primary CTAs.
   - **Royal Blue (`#2866A0`):** Acts as the secondary connective accent, guiding hyperlink interactions and digital infrastructure motifs.
   - **Off-White Surface (`#F7F8FA`):** Provides a calm, low-glare editorial background that optimizes readability across diverse mobile and desktop displays.
3. **Typography Pairing:**
   - **Headings & Display:** `Playfair Display` (Serif). Imparts historical weight, diplomatic dignity, and high-level editorial clarity.
   - **Body & UI Controls:** `Plus Jakarta Sans` / `Inter` (Sans-Serif). Delivers ultra-clean geometry, exceptional legibility at small mobile sizes (12–15px), and neutral modern ergonomics.

---

## 2. Design Tokens & Palette Specifications

### Color Tokens
| Token Name | Hex Code | RGB | Purpose / Context | WCAG AA Contrast Ratio |
|---|---|---|---|---|
| `--color-navy-950` | `#061325` | `6, 19, 37` | Top header, darkest surface, dark mode accents | 17.2:1 (on White) |
| `--color-navy-900` | `#091D38` | `9, 29, 56` | Primary brand navy, hero backdrop, card borders | 15.1:1 (on White) |
| `--color-navy-800` | `#122B4D` | `18, 43, 77` | Elevated navy containers, table subheaders | 12.3:1 (on White) |
| `--color-gold-500` | `#C9A24A` | `201, 162, 74` | Primary brand gold, primary CTAs, active lines | 4.8:1 (on Navy-900) |
| `--color-gold-600` | `#A68233` | `166, 130, 51` | Gold hover state, high-contrast borders | 6.5:1 (on Navy-900) |
| `--color-gold-100` | `#FBF6E9` | `251, 246, 233`| Light gold card fill, numbered badge surface | 1.1:1 (fill surface) |
| `--color-blue-600` | `#2866A0` | `40, 102, 160` | Secondary royal blue, links, focus rings | 5.2:1 (on White) |
| `--color-surface-light`| `#F7F8FA` | `247, 248, 250`| Page body canvas, alternating rows | Base canvas |
| `--color-white` | `#FFFFFF` | `255, 255, 255`| Panel surfaces, card containers, inputs | Base canvas |

### Text & Semantic State Tokens
- `--color-text-main`: `#1A202C` (Deep charcoal; 13.8:1 contrast on surface-light; optimal for prolonged reading).
- `--color-text-muted`: `#4A5568` (Slate gray; 7.1:1 contrast on surface-light; for secondary labels).
- `--color-text-inverse`: `#FFFFFF` (Pure white on navy backgrounds; 15.1:1 contrast).
- `--color-state-success`: `#15803D` (Dark green; for confirmed payment and verified credentials).
- `--color-state-warning`: `#B45309` (Amber; for bank proof queued for manual audit).
- `--color-state-danger`: `#B91C1C` (Crimson; for validation errors and rejected references).
- `--color-state-info`: `#1D4ED8` (Cobalt blue; for provisional schedule notices).

---

## 3. Typography Scale & Spatial Hierarchy

| Semantic Level | Font Family | Size (Desktop >=1024px) | Size (Tablet 768px) | Size (Mobile 390px) | Weight | Line Height |
|---|---|---|---|---|---|---|
| **Display H1** | Playfair Display | 44px (2.75rem) | 36px (2.25rem) | 28px (1.75rem) | 700 Bold | 1.15 |
| **Section H2** | Playfair Display | 32px (2.00rem) | 28px (1.75rem) | 22px (1.375rem)| 700 Bold | 1.25 |
| **Subsection H3**| Plus Jakarta Sans| 20px (1.25rem) | 18px (1.125rem)| 17px (1.0625rem)| 600 SemiBold | 1.35 |
| **Card Title H4**| Plus Jakarta Sans| 17px (1.0625rem)| 16px (1.00rem) | 16px (1.00rem) | 600 SemiBold | 1.40 |
| **Lead Paragraph**| Plus Jakarta Sans| 18px (1.125rem)| 17px (1.0625rem)| 16px (1.00rem) | 400 Regular | 1.65 |
| **Body Standard**| Plus Jakarta Sans| 15px (0.9375rem)| 15px (0.9375rem)| 15px (0.9375rem)| 400 Regular | 1.65 |
| **Small / Helper**| Plus Jakarta Sans| 13px (0.8125rem)| 12px (0.75rem) | 12px (0.75rem) | 500 Medium | 1.50 |
| **Eyebrow Tag** | Plus Jakarta Sans| 12px (0.75rem) | 11px (0.6875rem)| 11px (0.6875rem)| 700 Bold | 1.00 (Uppercase)|

---

## 4. Page Layout Compositions (The Five Public Pages)

### Page 1: Homepage (`/`)
1. **Masthead / Header:**
   - Gold vector seal emblem (`logo-crest.svg`) with 2-line institutional lockup: "AMVAC 2027 / Africa Motor Vehicle Administration Conference".
   - Primary 5-page horizontal navigation with solid gold underline active state.
   - Dedicated "Register Now" button with immediate contrast.
   - Mobile: High-contrast compact hamburger transforming into 'X' close icon with full ARIA support.
2. **Hero Section:**
   - Editorial dark atmosphere blending deep navy gradient into a genuine assembly photograph (`hero-conference.jpg`).
   - Eyebrow badge: "1ST CONTINENTAL POLICY CONFERENCE".
   - Display title in gold serif typography: "AFRICA MOTOR VEHICLE ADMINISTRATION CONFERENCE 2027".
   - Verbatim canonical theme in crisp off-white text.
   - Meta strip: Dates (15–19 March 2027), Location (Banjul, The Gambia), Modality (Hybrid – Abuja & Banjul).
   - Dual CTAs: Primary Gold "Register Now" and Secondary Outline "View Programme".
3. **Three Pillars Highlight Strip (Negative Margin Elevation):**
   - Three cards floating over the hero boundary: "Safer African Roads", "Stronger Security", "Greater Economic Integration".
4. **Editorial Intro (Banjul Waterfront):**
   - Asymmetric 2-column layout: Text explanation on left, genuine high-res coastal photograph of Banjul on right with credit badge.
5. **Key Objectives Preview:**
   - 8 responsive cards with circular gold numbered badges (1 to 8) and clear summaries of all 8 objectives.
6. **Conference Leadership Preview:**
   - 3 cards with uniform 4:3 cropped portraits: Amb. Prince Fidelis Nnadi, Njie Baldeh, and His Excellency Adama Barrow (clearly marked with "Protocol Pending" tag per D-04/D-11).
7. **Accreditation CTA Strip:**
   - Full-width dark navy banner with dual action links.

### Page 2: About & Leadership (`/about`)
1. **Institutional Banner:**
   - Title and breadcrumb navigation (`Home > About & Leadership`).
2. **Background & Pan-African Integration Motif:**
   - Narrative block detailing the strategic necessity of vehicle administration harmonization under AfCFTA.
   - Accent card featuring the stylized Africa continent vector (`africa-map-accent.svg`) highlighting Banjul and Abuja nodes.
3. **The Eight Objectives (Full Canonical Text):**
   - Two-column numbered layout with distinct circular gold badges (1 through 8).
   - **Zero omissions or shortcuts**: All eight objectives rendered in full verbatim client text.
   - Objective 5 includes explicit editorial citation review note for the 300,000 road deaths figure.
4. **Organizing Institutions:**
   - Dedicated cards for APRI (Nigeria) and Road Safety Driver Standard Association of The Gambia.
5. **Conference Leadership & Conveners:**
   - Full cards with leadership biographies, verified roles, and status tags.

### Page 3: Programme & Venue (`/programme`)
1. **Programme Status Alert:**
   - Prominent ministerial review alert banner explaining that the day-by-day timetable is finalizing (no fake times or fabricated speakers).
2. **Five Policy Focus Tracks (Derived from Objectives):**
   - Track 1: Continental Vehicle Database & Biometric Integration
   - Track 2: Harmonized Vehicle Inspection & Roadworthiness
   - Track 3: Cross-Border Anti-Theft & Security Collaboration
   - Track 4: Driver Licensing Standards & Public Enlightenment
   - Track 5: Industrial Vehicle Assembly & Domestic Number Plate Plants
3. **Venue & City Profile:**
   - BICC Conference Centre (Sir Dawda Kairaba Jawara International Conference Centre / BICC), Banjul, with technical plenary specifications.
4. **Delegate Travel Advisory:**
   - Port of entry (Banjul International Airport), visa waiver rules for ECOWAS delegates, yellow fever health requirements.

### Page 4: Delegate Registration (`/register`)
- **Dual-State Architecture:** Form input mode vs Reference lookup/status mode.
- Detailed state designs below in Section 5.

### Page 5: Contact (`/contact`)
1. **Secretariat Coordinates:**
   - Details for Banjul Host Nation Desk and Abuja Technical Secretariat Desk.
2. **Inquiry Form:**
   - Validated fields: Full Name, Official Email, Phone (optional), Inquiry Category, Detailed Message.
   - Anti-CSRF protection, rate limiting, and database-backed message storage.

---

## 5. Registration Interface State Inventory

```text
[State 1: Form Input]
      ↓
[State 2: Submission Received (Ref Generated: AMVAC-2027-XXXXXXXX)]
      ↓
┌─────────────────────────────────────────────────────────────┐
│ Choice of Payment Route                                     │
├───────────────────────────────┬─────────────────────────────┤
│ Route A: Direct Bank Wire     │ Route B: Online Gateway     │
│ [State 3: Proof Queued for    │ [State 4: Sandbox Adapter   │
│  Administrative Bank Audit]   │  Simulator (D-07)]          │
└───────────────┬───────────────┴──────────────┬──────────────┘
                ↓                              ↓
      [State 5: Verified Paid & Confirmed Accreditation Pass]
                OR
      [State 6: Discrepancy / Rejected Reference Retry]
```

1. **State 1 — Form Input / Validation Error:**
   - Clean fieldsets, explicit `*` required indicators, live helper text.
   - Invalidation states: red input border, `aria-invalid="true"`, and specific error message banner.
   - Pricing selector displaying ₦100,000 NGN (Nigerian delegates) and US$50 (International/ECOWAS delegates) with server-side enforcement.
2. **State 2 — Submission Received (Reference Card):**
   - Generates non-guessable, unguessable public reference (e.g. `AMVAC-2027-KS2C434J`).
   - High-contrast monospace display box with one-click "Copy Reference" helper.
   - Clear breakdown of selected category, fixed server fee, and currency.
3. **State 3 — Pending Online Payment (Sandbox Stub):**
   - Clear badge indicating staging sandbox adapter.
   - One-click test simulation button that triggers full state verification.
4. **State 4 — Pending Bank Verification (`pending_manual_review`):**
   - High-visibility amber alert banner.
   - Copy: *"Proof Received: Your transfer reference is queued for administrative bank reconciliation against Bloom Bank records."*
   - Explains that accreditation passes are issued only upon confirmed bank credit.
5. **State 5 — Verified Paid (`paid`):**
   - High-contrast green verified accreditation badge: *"ACCREDITATION CONFIRMED"*.
   - Displays verified amount, currency, verification date, and payment method.
   - One-click "Print Official Confirmation Slip" action.
6. **State 6 — Payment Failed / Discrepancy:**
   - Crimson alert banner detailing administrative notes (e.g., *"Transfer reference not found on bank statement"*).
   - Allows delegate to re-enter valid bank reference or contact secretariat.

---

## 6. Minimal Private Admin Portal Compositions

1. **Admin Login (`/admin/login`):**
   - Centered diplomatic login card with gold seal emblem.
   - Email and password inputs, CSRF token, failed attempt counter, and 15-minute brute-force lockout banner.
2. **Overview Dashboard (`/admin/dashboard`):**
   - 5 KPI stat cards: Total Delegates, Verified & Paid (green), Bank Proofs in Review (amber), Awaiting Payment (blue), Contact Inquiries.
   - Action Required Callout Banner listing pending bank transfer proofs.
   - Recent delegate registrations table with direct audit links.
3. **Registration Review & Export (`/admin/registrations`):**
   - Real-time search by delegate name, reference, email, and organization.
   - Status dropdown filter (`pending_payment`, `pending_manual_review`, `paid`, `failed`).
   - One-click live streaming CSV export (`AMVAC2027_Delegates_YYYYMMDD.csv`).
4. **Bank Payment Verification Queue (`/admin/payments`):**
   - Dedicated table listing every submitted bank transfer reference, expected fee, depositor memo, and submission timestamp.
   - Action buttons: "Verify & Mark Paid" and "Reject".
   - Accessible modal dialog requiring the administrator to enter an **audit justification note** (e.g., *"Confirmed on Bloom Bank Gambia statement dated 22 Oct 2026, Txn ID #98234"*).
   - Records admin ID, timestamp, and action in the immutable audit log table.
5. **Controlled Content Edit Screen (`/admin/content`):**
   - Restrained key-value editor for specific editable text entries (hero introduction, venue update, provisional schedule notice). Avoids general-purpose CMS complexity.

---

## 7. Responsive Breakpoint Matrix & Device Behavior

### Mobile Compact (360px – 390px)
- **Navigation:** Main navigation collapses into accessible hamburger button; opens high-contrast slide-down drawer with full tap targets (min 44px height).
- **Hero:** Typography scales to 28px/1.75rem; meta items stack vertically; CTA buttons span full width.
- **Grids:** Highlights strip, 8 objectives, and leadership profiles collapse into a clean single-column vertical stack with 16px lateral padding and zero horizontal overflow.
- **Tables:** Wrapped in `.table-responsive` container with smooth horizontal touch-scrolling.

### Tablet Portrait (641px – 768px)
- **Layout:** 2-column symmetric grids for objectives and leadership cards.
- **Hero:** Typography scales to 36px/2.25rem; meta badges display in a 2x2 grid.
- **Forms:** 2-column inline layout for Email/Phone and Organization/Job Title fields.

### Desktop Wide (1024px – 1440px+)
- **Navigation:** Full horizontal menu with solid gold underline active indicators and right-aligned "Register Now" action button.
- **Objectives Grid:** 4-column layout on Homepage (2x4 matrix) and 2-column numbered rows on About page.
- **Container:** Maximum content width constrained to `1200px` (or `1280px` centered on wide monitors) preventing unreadable line lengths.

---

## 8. Accessibility & Compliance Verification (WCAG 2.2 AA)

1. **Color Contrast:**
   - Deep Navy text on White/Surface: `15.1:1` (Exceeds AA standard of 4.5:1).
   - Gold `#C9A24A` on Deep Navy `#091D38`: `4.8:1` (Exceeds AA standard).
   - Charcoal `#1A202C` on White: `13.8:1`.
2. **Keyboard Focus Rings:**
   - Visible, high-contrast 2px solid royal blue (`#2866A0`) or gold focus ring with 2px offset on all interactive elements via `:focus-visible`.
3. **Semantic HTML & Screen Readers:**
   - Single `<h1>` per page, logical `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>` landmarks.
   - Screen-reader helper class `.sr-only` used for icon-only controls.
4. **Reduced Motion (`prefers-reduced-motion: reduce`):**
   - Automatically disables all transform transitions, hover translations, and smooth scrolling for users with motion sensitivity.
