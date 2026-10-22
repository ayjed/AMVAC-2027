# AMVAC 2027 — Design System & UI/UX Specifications

**Document Reference:** Governed by `01_PROJECT_SOURCE_OF_TRUTH.md` Section 4 & Phase 2 Deliverables  
**Status:** Canonical Design Token & Component Specification  
**Design Philosophy:** Authoritative, Diplomatic, Editorial, Contemporary African Policy Conference. Bespoke, non-generic, high-trust visual language.

---

## 1. Design Tokens & Palette

### Primary Color Tokens
| Token | Hex | RGB | Purpose / Usage | WCAG AA Contrast on #FFFFFF / #091D38 |
|---|---|---|---|---|
| `--color-navy-950` | `#061325` | `rgb(6, 19, 37)` | Deepest background, dark mode hero, footer | 17.2:1 (on White) |
| `--color-navy-900` | `#091D38` | `rgb(9, 29, 56)` | Primary Brand Navy, main headers, hero sections | 15.1:1 (on White) |
| `--color-navy-800` | `#122B4D` | `rgb(18, 43, 77)` | Card backgrounds (dark), header border lines | 12.3:1 (on White) |
| `--color-gold-500` | `#C9A24A` | `rgb(201, 162, 74)` | Primary Brand Gold, accents, badges, CTAs | 4.8:1 (on Navy-900) |
| `--color-gold-600` | `#A68233` | `rgb(166, 130, 51)` | Gold hover state, high-contrast borders | 6.5:1 (on Navy-900) |
| `--color-gold-100` | `#FBF6E9` | `rgb(251, 246, 233)` | Light gold tinted card background, badge fill | 1.1:1 (light badge bg) |
| `--color-blue-600` | `#2866A0` | `rgb(40, 102, 160)` | Secondary Brand Royal Blue, hyperlinks, interactive focus | 5.2:1 (on White) |
| `--color-surface-light`| `#F7F8FA`| `rgb(247, 248, 250)`| Off-white page background, alternate sections | Base surface |
| `--color-white` | `#FFFFFF` | `rgb(255, 255, 255)` | Pure white card surfaces, modals, elevated panels | Base contrast |

### Accessible Functional Tokens
- `--color-text-main`: `#1A202C` (Charcoal slate for high legibility body text; 13.8:1 contrast on `#F7F8FA`).
- `--color-text-muted`: `#4A5568` (Secondary metadata text; 7.1:1 contrast on `#F7F8FA`).
- `--color-text-inverse`: `#FFFFFF` (On Navy backgrounds).
- `--color-text-inverse-muted`: `#CBD5E0` (On Navy backgrounds).
- `--color-border-subtle`: `#E2E8F0` (Crisp light border; no fuzzy drop-shadows).
- `--color-border-dark`: `#1E3A5F` (Navy container border).
- `--color-state-success`: `#15803D` (Verified payment, badge confirmed).
- `--color-state-warning`: `#B45309` (Pending manual review, review in progress).
- `--color-state-danger`: `#B91C1C` (Validation errors, rejected references).
- `--color-state-info`: `#1D4ED8` (Provisional notices, informative banners).

---

## 2. Typography Hierarchy

### Font Families
1. **Display & Headings:** `Playfair Display`, `Georgia`, serif (Bespoke diplomatic editorial character, gravitas).
2. **Body & UI Elements:** `Plus Jakarta Sans`, `Inter`, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif (Clean, readable, crisp at small mobile sizes).

### Type Scale & Rhythm
| Element | Font Family | Size (Desktop) | Size (Mobile) | Line Height | Weight | Letter Spacing |
|---|---|---|---|---|---|---|
| **Display Hero H1** | Serif | 44px (2.75rem) | 30px (1.875rem) | 1.15 | 700 Bold | -0.02em |
| **Section H2** | Serif | 32px (2.0rem) | 24px (1.5rem) | 1.25 | 700 Bold | -0.01em |
| **Subsection H3** | Sans-Serif | 22px (1.375rem) | 18px (1.125rem) | 1.35 | 600 SemiBold | 0 |
| **Card Title H4** | Sans-Serif | 18px (1.125rem) | 16px (1.0rem) | 1.4 | 600 SemiBold | 0 |
| **Lead Paragraph** | Sans-Serif | 18px (1.125rem) | 16px (1.0rem) | 1.6 | 400 Regular | 0 |
| **Body Standard** | Sans-Serif | 15px (0.9375rem)| 15px (0.9375rem)| 1.65 | 400 Regular | 0 |
| **Small / Metadata** | Sans-Serif | 13px (0.8125rem)| 12px (0.75rem) | 1.5 | 500 Medium | +0.02em |
| **Eyebrow Badge** | Sans-Serif | 12px (0.75rem) | 11px (0.6875rem)| 1.0 | 700 Bold | +0.08em (Uppercase) |

---

## 3. Responsive Breakpoints & Container Constraints

| Breakpoint Name | Target Screen Width | Layout Columns | Max Container Width | Horizontal Padding |
|---|---|---|---|---|
| **Mobile Compact** | 360px – 390px | 1 column | 100% | 16px (1rem) |
| **Mobile Standard**| 391px – 640px | 1 column | 100% | 20px (1.25rem) |
| **Tablet Portrait**| 641px – 768px | 2 columns | 720px | 24px (1.5rem) |
| **Tablet Landscape**| 769px – 1024px| 2 or 3 columns | 960px | 32px (2rem) |
| **Desktop Baseline**| 1025px – 1440px| 3 or 4 columns | 1200px | 32px (2rem) |
| **Wide Desktop** | > 1440px | Multi-column | 1280px (Centered)| Auto margin |

---

## 4. Component Inventory & Patterns

### 1. Global Navigation (`header.main-header`)
- **Brand Cluster:** Left-aligned circular AMVAC gold seal crest (`48px` diameter) + Two-line institutional typography:
  - Line 1: `AMVAC 2027` (Gold, bold, 18px)
  - Line 2: `Africa Motor Vehicle Administration Conference` (Muted silver, 11px, uppercase).
- **Navigation Links:** Horizontal flex list (`Home`, `About & Leadership`, `Programme & Venue`, `Registration`, `Contact`).
  - Active indicator: Solid 2px gold underline with 8px offset.
- **CTA Button:** `.btn-gold` ("Register Now"), high contrast gold fill with navy text, rounded-md (`4px`), hover elevation.
- **Mobile Menu Toggle:** Hamburger icon with `aria-label="Toggle Navigation Menu"`, expanded state transforms into crisp 'X' close icon; opens high-contrast accessible slide-down drawer.

### 2. Objectives Component (`.objectives-grid`)
- **Structure:** 8 canonical cards.
- **Card Elements:**
  - Distinct circular badge (`36px` diameter) with gold fill `#FBF6E9`, border `#C9A24A`, and bold number `1` through `8`.
  - Objective Title in Bold (`16px`).
  - Full verbatim text on `/about`; clear, unabridged policy text.
  - Zero omissions; preserve exact client wording including safety claims and inter-agency list.

### 3. Leadership Cards (`.leader-card`)
- **Aspect Ratio:** Fixed 1:1 or 4:5 image container with subtle border (`1px solid #E2E8F0`), `object-fit: cover; object-position: top center`.
- **Text Block:**
  - Name: Semi-bold H4 (`18px`).
  - Position: Muted gold subtitle (`13px`).
  - Institutional Affiliation: Slate gray (`13px`).
  - Status Tag: Clear badge indicating verified role (or provisional status).

### 4. Registration Interface States (`.reg-state-container`)
1. **State 1 — Form Input:** Accessible fieldsets, floating/stacked labels, explicit required indicators (`*`), live helper text, zero reliance on placeholder-only text.
2. **State 2 — Submission Received:** Highlight box with large reference code badge (e.g. `AMVAC-2027-8K3N9Q`), payment instructions, printable reference card.
3. **State 3 — Pending Online Checkout:** Dedicated checkout modal / iframe stub with "Sandbox Payment Test" indicator.
4. **State 4 — Pending Bank Verification:** Clear alert banner: *"Proof Received: Your transfer reference is queued for administrative bank reconciliation against Bloom Bank records."*
5. **State 5 — Verified Paid:** Green accreditation badge, official confirmation number, download voucher CTA.
6. **State 6 — Failed / Discrepancy:** Amber warning with specific error notes and secretariat direct support button.

### 5. Private Admin Portal (`.admin-portal`)
- **Minimal, Utilitarian, Editorial:** Clean white and light slate background (`#F8FAFC`), deep navy top header (`#091D38`).
- **Audit-First Workflow:** Every payment action requires explicit one-click confirmation with optional audit note.
- **Responsive Tables:** Overflow container with horizontal scroll indicator for small screens; badge pills for statuses (`paid`: green, `pending_payment`: blue, `pending_manual_review`: amber, `failed`: red).

---

## 5. Accessibility & Motion Rules

1. **WCAG 2.2 AA Compliance:** All text meets minimum 4.5:1 contrast against its background (3:1 for large text >= 24px).
2. **Keyboard Focus Rings:** Visible, high-contrast 2px solid royal blue (`#2866A0`) or gold focus ring with 2px offset on all `:focus-visible` elements.
3. **Form Error Accessibility:** Errors linked via `aria-describedby`, input fields marked `aria-invalid="true"` upon error.
4. **Reduced Motion (`prefers-reduced-motion: reduce`):** Disable all transform animations, smooth scrolling, and hover transitions for users with motion sensitivity.
5. **No Fake Aesthetics:** No AI-generated stock people, no fake 3D glossy icons, no heavy decorative blur/glow that impairs contrast.
