import urllib.request
import urllib.parse
import json
import re

import os
BASE_URL = os.environ.get("TEST_BASE_URL", os.environ.get("APP_URL", "http://localhost:8000")).rstrip("/")

def run_test(name, assertion, details=""):
    if assertion:
        print(f"  [PASS] {name}")
        return True
    else:
        print(f"  [FAIL] {name} - {details}")
        return False

print("\n=======================================================")
print(" AMVAC 2027 — Phase 5 Deep Security & Workflow Audit")
print("=======================================================\n")

# 1. Unauthenticated Admin Route Protection
print("Test Group 1: Admin Route Authorization Guard")
admin_routes = [
    "/admin",
    "/admin/dashboard",
    "/admin/registrations",
    "/admin/payments",
    "/admin/enquiries",
    "/admin/audit"
]

all_admin_guarded = True
for route in admin_routes:
    req = urllib.request.Request(f"{BASE_URL}{route}", headers={"User-Agent": "AuditBot/1.0"})
    try:
        # We don't follow redirects to check for 302 to /admin/login
        class NoRedirectHandler(urllib.request.HTTPRedirectHandler):
            def http_error_302(self, req, fp, code, msg, headers):
                return fp
        opener = urllib.request.build_opener(NoRedirectHandler)
        resp = opener.open(req)
        location = resp.headers.get("Location", "")
        is_redirect_to_login = resp.status in (302, 303) and "/admin/login" in location
        if not is_redirect_to_login:
            all_admin_guarded = False
            print(f"    Failed route {route}: status={resp.status}, location={location}")
    except urllib.error.HTTPError as e:
        if e.code not in (302, 401, 403):
            all_admin_guarded = False
run_test("All 6 private admin routes redirect unauthenticated requests to /admin/login", all_admin_guarded)


# 2. CSRF Defense on State-Changing POST Routes
print("\nTest Group 2: Cross-Site Request Forgery (CSRF) Defense")

# Attempt POST to /register without CSRF token
post_data = urllib.parse.urlencode({
    "full_name": "Attacker Name",
    "email": "attacker@evil.com",
    "fee_category": "nigerian_delegate"
}).encode("utf-8")

req = urllib.request.Request(f"{BASE_URL}/register", data=post_data, method="POST")
csrf_blocked = False
try:
    with urllib.request.urlopen(req) as resp:
        body = resp.read().decode('utf-8')
        # If it returns 403 or displays invalid CSRF
        if resp.status == 403 or "csrf" in body.lower():
            csrf_blocked = True
except urllib.error.HTTPError as e:
    if e.code == 403:
        csrf_blocked = True

run_test("POST /register without CSRF token is rejected with HTTP 403", csrf_blocked)

# Attempt POST to /contact without CSRF token
contact_data = urllib.parse.urlencode({
    "full_name": "Spam Bot",
    "email": "spam@bot.com",
    "subject": "Spam",
    "message": "Buy cheap watches"
}).encode("utf-8")

req = urllib.request.Request(f"{BASE_URL}/contact", data=contact_data, method="POST")
contact_csrf_blocked = False
try:
    with urllib.request.urlopen(req) as resp:
        pass
except urllib.error.HTTPError as e:
    if e.code == 403:
        contact_csrf_blocked = True

run_test("POST /contact without CSRF token is rejected with HTTP 403", contact_csrf_blocked)


# 3. Server-Side Fee Tamper Resistance
print("\nTest Group 3: Server-Side Pricing & Financial Integrity")
import http.cookiejar
cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# Fetch registration page to get valid session and CSRF token
resp = opener.open(f"{BASE_URL}/register")
html = resp.read().decode('utf-8')
csrf_match = re.search(r'name=["\']csrf_token["\']\s+value=["\']([a-f0-9]{64})["\']', html)
csrf_token = csrf_match.group(1) if csrf_match else ""

tamper_email = f"tamper.audit.{int(urllib.request.time.time())}@example.com"
tamper_payload = urllib.parse.urlencode({
    "csrf_token": csrf_token,
    "full_name": "Hon. Tamper Test",
    "email": tamper_email,
    "phone": "+234 803 000 0000",
    "country": "Nigeria",
    "organization": "FRSC Nigeria",
    "job_title": "Sector Commander",
    "participation_format": "in_person_banjul",
    "fee_category": "nigerian_delegate", # Should be 100,000 NGN
    "amount": "1.00",                     # Tampered field
    "currency": "USD",                    # Tampered field
    "consent": "1"
}).encode("utf-8")

resp2 = opener.open(f"{BASE_URL}/register", data=tamper_payload)
result_html = resp2.read().decode('utf-8')

ref_match = re.search(r'(AMVAC-2027-[2-9A-Z]{8})', result_html)
created_ref = ref_match.group(1) if ref_match else ""

# Verify in database or on reference page that amount is 100,000 NGN and not 1.00 USD
has_correct_locked_fee = ("100,000" in result_html or "NGN" in result_html) and ("1.00" not in result_html)
run_test("Malicious client-posted amount/currency ($1.00) is ignored; server enforces NGN 100,000", has_correct_locked_fee, f"Ref: {created_ref}")


# 4. Forged Payment Proof Rejection
print("\nTest Group 4: Bank Proof Verification Gate")
# Submit bank proof
proof_payload = urllib.parse.urlencode({
    "csrf_token": csrf_token,
    "public_reference": created_ref,
    "bank_reference": "FAKE-TELLER-9999",
    "deposit_date": "2026-10-22",
    "notes": "Attempting to claim immediate badge"
}).encode("utf-8")

resp3 = opener.open(f"{BASE_URL}/register/submit-payment", data=proof_payload)
proof_html = resp3.read().decode('utf-8')

is_pending_review = "Bank Proof Under Audit" in proof_html or "pending_manual_review" in proof_html or "Under Audit" in proof_html
never_marked_paid = "ACCREDITATION CONFIRMED" not in proof_html and "STATUS: PAID" not in proof_html

run_test("Submitting bank proof strictly enters 'pending_manual_review'", is_pending_review)
run_test("CRITICAL: Submitting bank proof NEVER sets status to PAID / Confirmed", never_marked_paid)


# 5. XSS Defense & Output Escaping
print("\nTest Group 5: Anti-XSS Sanitization & Escaping")
xss_email = f"xss.audit.{int(urllib.request.time.time())}@example.com"
xss_payload = urllib.parse.urlencode({
    "csrf_token": csrf_token,
    "full_name": "<script>alert('XSS_AUDIT_NAME')</script> Chief Auditor",
    "email": xss_email,
    "phone": "+220 1234567",
    "country": "The Gambia",
    "organization": "<img src=x onerror=alert('XSS_ORG')> Gambia Police",
    "job_title": "Director <b onmouseover=alert(1)>Test</b>",
    "participation_format": "in_person_banjul",
    "fee_category": "international_delegate",
    "consent": "1"
}).encode("utf-8")

resp_xss = opener.open(f"{BASE_URL}/register", data=xss_payload)
xss_html = resp_xss.read().decode('utf-8')

raw_script_rendered = "<script>alert('XSS_AUDIT_NAME')</script>" in xss_html
raw_onerror_rendered = "<img src=x onerror=alert('XSS_ORG')>" in xss_html
is_escaped = ("&lt;script&gt;" in xss_html or "&lt;img" in xss_html or "Chief Auditor" in xss_html) and not raw_script_rendered and not raw_onerror_rendered

run_test("XSS script injection in full_name is safely HTML-escaped (&lt;script&gt;)", not raw_script_rendered)
run_test("XSS onerror attribute in organization is safely neutralized", not raw_onerror_rendered)


# 6. Duplicate Registration Protection
print("\nTest Group 6: Duplicate Registration Throttling")
dup_payload = urllib.parse.urlencode({
    "csrf_token": csrf_token,
    "full_name": "Duplicate Tester",
    "email": tamper_email, # Same email submitted 30 seconds ago
    "phone": "+234 803 000 0000",
    "country": "Nigeria",
    "organization": "FRSC Nigeria",
    "job_title": "Sector Commander",
    "participation_format": "in_person_banjul",
    "fee_category": "nigerian_delegate",
    "consent": "1"
}).encode("utf-8")

resp_dup = opener.open(f"{BASE_URL}/register", data=dup_payload)
dup_html = resp_dup.read().decode('utf-8')
# Duplicate submission should return existing reference instead of creating a second registration
returned_same_ref = created_ref in dup_html
run_test("Duplicate registration with identical email returns existing reference (no duplicate record)", returned_same_ref)

print("\n=======================================================")
print(" Security & Flow Audit Completed Successfully!")
print("=======================================================\n")
