# AMVAC 2027 — Decision Register & Outstanding Approvals

**Document Reference:** Mirrored from `01_PROJECT_SOURCE_OF_TRUTH.md` Section 10  
**Last Updated:** 22 October 2026  
**Governance Rule:** Default behavior must be strictly maintained in code and content until formal written client approval is received. No assumptions may be published as verified facts.

---

## Decision Register (D-01 through D-12)

| ID | Decision Item | Client Supplied Reference | Open Question / Risk | Default Operational Behavior Until Decided | Current Status |
|---|---|---|---|---|---|
| **D-01** | Exact public event name and brand acronym | "Africa Motor Vehicle Administration Conference" (working title); "1st International Conference on Motor Vehicle Administration" (flyer title). | Which title is canonical for public branding, domain names, and press releases? | Use working name: **Africa Motor Vehicle Administration Conference 2027 (AMVAC 2027)** with subtitle referencing the 1st International Conference. Do not lock final vector logo artwork. | `PENDING CLIENT APPROVAL` |
| **D-02** | Exact dates, venue/address, and hybrid/Abuja format | 15–19 March 2027; Banjul, The Gambia; "Hybrid – Abuja & Banjul"; "BICC Conference Centre, Banjul". | What is the exact official facility name (e.g., Sir Dawda Kairaba Jawara International Conference Centre / BICC)? Is Abuja an active physical plenary hub or virtual broadcast relay? | Display dates and venue clearly marked as **provisional / conference baseline** in development; do not publish unverified physical addresses. | `PENDING CLIENT APPROVAL` |
| **D-03** | Legal organizing entities and institutional partnerships | Accident Prevention and Rescue Initiative (APRI), Nigeria; Road Safety and Driving Standard Association of The Gambia; Network of Africa Road Safety NGOs on flyer. | Is the Network of Africa Road Safety NGOs an official co-convener, endorsing partner, or observer? | Attribute APRI and Road Safety and Driving Standard Association of The Gambia as co-conveners. Hold Network of Africa Road Safety NGOs until role is formally approved. Do not imply official AU or ECOWAS endorsement without authorization. | `PENDING CLIENT APPROVAL` |
| **D-04** | Confirmed speakers, VIPs, and official portrait authorization | Amb. Prince Fidelis Nnadi; Njie Baldeh; President Adama Barrow (photo provided). Other dignitaries on promo flyer. | Has His Excellency President Adama Barrow agreed to attend as Special Guest of Honour or Patron? Are other flyer figures confirmed? | Feature Amb. Prince Fidelis Nnadi (Conference Host/Lead) and Njie Baldeh (Host Country Lead). **Do not claim President Adama Barrow is confirmed or endorsed** without written State House protocol approval. Hide all unconfirmed speakers/dignitaries. | `PENDING CLIENT APPROVAL` |
| **D-05** | Registration fees by resident / country / currency | ₦100,000 and US$50 supplied in promotional materials. | ₦100,000 does not equal US$50 (currency disparity). What is the exact pricing logic? (e.g., ₦100,000 for Nigerian domestic delegates, US$50 for African regional / international delegates, or virtual vs physical)? | Enforce fee rules strictly on the server side: Nigerian delegates = ₦100,000, International delegates = US$50. Display currency eligibility notice. **Hold live online checkout until client verifies pricing rule.** | `PENDING CLIENT APPROVAL` |
| **D-06** | Bank account beneficiary and authorized collection entity | Bloom Bank Africa, The Gambia; Account: 1031770538978; BBAN: 013202177053897837. | What is the exact registered legal beneficiary name on the account? Has the beneficiary entity authorized AMVAC 2027 fee collection? | **Payment details MUST stay off live public production pages** until beneficiary name and written authorization are verified. In staging/development, display only under controlled test notices with manual admin verification. | `PENDING CLIENT APPROVAL` |
| **D-07** | Online payment provider and merchant credentials | Paystack suggested for NGN; international provider for USD. | Is Paystack authorized for the Nigerian entity? What merchant account collects USD in The Gambia / internationally? | Build clean gateway adapter architecture with a safe disabled/sandbox stub. Never set payment state to 'paid' via URL redirects. Keep sandbox mode active until merchant accounts are authorized. | `PENDING CLIENT APPROVAL` |
| **D-08** | Official secretariat contact channels | Working contacts needed for Banjul and Abuja secretariat desks. | What are the official monitored emails, telephone numbers, and social media handles? | Provide a secure PHP inquiry form on `/contact` that logs directly into the database. Display provisional secretariat notices; do not invent personal phone numbers. | `PENDING CLIENT APPROVAL` |
| **D-09** | Detailed agenda, day-by-day timetable, and brochure PDF | General 5-day duration (15–19 March 2027) with high-level themes. | When will the committee release the approved day-by-day timetable and official conference prospectus PDF? | Publish high-level thematic focus tracks mapped to the 8 canonical objectives. Show a polished **"Programme details to be announced upon committee ratification"** block. Never fabricate sessions, times, or panel topics. | `PENDING CLIENT APPROVAL` |
| **D-10** | Citation and factual wording for road mortality statistic | Objective 5 mentions "reduce the over 300,000 annual road deaths in Africa." | Which authority (e.g., WHO Global Status Report on Road Safety / AU Transport Policy) is the official citation? | Retain the verbatim text of Objective 5 as approved client policy text. Flag the statistic as requiring citation review before external press citations. Do not fabricate an arbitrary footnote. | `PENDING CLIENT APPROVAL` |
| **D-11** | Approved photos and asset licensing | Original portraits of Adama Barrow, Prince Fidelis Nnadi, Njie Baldeh; promo flyer screenshot. | Are high-resolution official portraits with copyright clearance available for all featured leaders? | Use supplied original portraits with respectful, non-distorted aspect-ratio cropping. Do not apply synthetic face alterations. Mark portraits with clear role metadata. | `PENDING CLIENT APPROVAL` |
| **D-12** | Transactional email sender and confirmation wording | Acknowledgement email and payment receipt templates. | What sender domain (e.g., `@amvac2027.org` or host organization) and SMTP provider will dispatch transactional emails? | Use safe PHP mailer interface with a local file logger/staging SMTP stub. Provide structured draft templates for "Registration Received" and "Payment Verified". | `PENDING CLIENT APPROVAL` |

---

## Classification of Current Knowledge Base

### 1. Provided Facts (Canonical Baseline)
- Conference Theme: *"Enhancing Continental Security, Road Safety and Economic Integration Through Effective Motor Vehicle Administration in Africa."*
- Exactly Eight Canonical Objectives (Items 1 to 8 in `01_PROJECT_SOURCE_OF_TRUTH.md`).
- Slogan: *"Please be road safety conscious to save lives."*
- Host Organization 1: Accident Prevention and Rescue Initiative (APRI), Nigeria; Executive Director: Amb. Prince Fidelis Nnadi.
- Host Organization 2: Road Safety and Driving Standard Association of The Gambia; Administrative Secretary: Njie Baldeh.
- Five required public pages: `/`, `/about`, `/programme`, `/register`, `/contact`.
- Single private admin area for registration and payment audits.

### 2. Working Assumptions (Safe for Development)
- Event schedule span: 15–19 March 2027.
- Primary location: Banjul, The Gambia; secondary hybrid virtual hub: Abuja, Nigeria.
- Venue: BICC Conference Centre, Banjul.
- Tiered fee structure: Nigerian delegates ₦100,000; International delegates US$50.
- Manual bank transfer review workflow requiring administrator sign-off against bank records.

### 3. Facts Requiring Verification (Blocked from Live Public Production)
- President Adama Barrow's confirmed protocol status (Guest of Honour / Patron / Keynote).
- Bank account beneficiary name on Bloom Bank Africa account.
- Live merchant gateway API keys and payout authorization.
- Official secretariat phone numbers and public email addresses.
- Day-by-day speaker timetable.
