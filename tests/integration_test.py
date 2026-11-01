import os
import urllib.request
import urllib.parse
import http.cookiejar
import re
import time

BASE_URL = os.environ.get("TEST_BASE_URL", os.environ.get("APP_URL", "http://localhost:8000")).rstrip("/")
ADMIN_EMAIL = os.environ.get("ADMIN_DEFAULT_EMAIL", "admin@amvac2027.org")
ADMIN_PASSWORD = os.environ.get("ADMIN_DEFAULT_PASSWORD", "ChangeMe2027!Secure")

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# 1. Fetch /register to get CSRF token
resp = opener.open(f"{BASE_URL}/register")
html = resp.read().decode('utf-8')
csrf_match = re.search(r'name="csrf_token" value="([a-f0-9]+)"', html)
csrf_token = csrf_match.group(1) if csrf_match else ''
print('1. Register page loaded, CSRF token:', csrf_token[:8] + '...')

# 2. Submit new delegate registration
data = urllib.parse.urlencode({
    'csrf_token': csrf_token,
    'full_name': 'Director Ibrahim Sowe',
    'email': f'ibrahim.sowe.{int(time.time())}@transport.gm',
    'phone': '+220 788 1234',
    'country': 'The Gambia',
    'organization': 'Ministry of Transport, Works and Infrastructure',
    'job_title': 'Director of Road Transport Services',
    'participation_format': 'in_person_banjul',
    'fee_category': 'international_delegate',
    'notes': 'High-level protocol delegate accreditation',
    'consent': '1'
}).encode('utf-8')

req = urllib.request.Request(f"{BASE_URL}/register", data=data)
resp = opener.open(req)
final_url = resp.geturl()
print('2. Registration submitted, redirected to:', final_url)
ref_match = re.search(r'ref=([A-Z0-9\-]+)', final_url)
ref = ref_match.group(1) if ref_match else ''
print('   Created Reference:', ref)

# 3. Submit bank transfer proof
status_html = resp.read().decode('utf-8')
csrf_match2 = re.search(r'name="csrf_token" value="([a-f0-9]+)"', status_html)
csrf_token2 = csrf_match2.group(1) if csrf_match2 else csrf_token

proof_data = urllib.parse.urlencode({
    'csrf_token': csrf_token2,
    'public_reference': ref,
    'bank_reference': 'BLMB-BANJUL-2027-0091',
    'deposit_date': '2026-10-22',
    'notes': 'Deposited at Bloom Bank Banjul Main Branch'
}).encode('utf-8')

req_proof = urllib.request.Request(f"{BASE_URL}/register/submit-payment", data=proof_data)
resp_proof = opener.open(req_proof)
proof_html = resp_proof.read().decode('utf-8')
assert 'Bank Proof Under Audit' in proof_html or 'pending_manual_review' in proof_html
print('3. Bank proof submitted, status updated to: Bank Proof Under Audit')

# 4. Admin Login
admin_cj = http.cookiejar.CookieJar()
admin_opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(admin_cj))

admin_login_page = admin_opener.open(f"{BASE_URL}/admin/login").read().decode('utf-8')
admin_csrf = re.search(r'name="csrf_token" value="([a-f0-9]+)"', admin_login_page).group(1)

login_data = urllib.parse.urlencode({
    'csrf_token': admin_csrf,
    'email': ADMIN_EMAIL,
    'password': ADMIN_PASSWORD
}).encode('utf-8')

req_login = urllib.request.Request(f"{BASE_URL}/admin/login", data=login_data)
resp_login = admin_opener.open(req_login)
print('4. Admin logged in, redirected to:', resp_login.geturl())

# 5. Admin Payment Verification
payments_page = admin_opener.open(f"{BASE_URL}/admin/payments").read().decode('utf-8')
review_id_match = re.search(r'openReviewModal\((\d+),\s*[\'"]' + re.escape(ref) + r'[\'"]', payments_page)
if not review_id_match:
    # fallback to first review modal if table rows sorted differently
    review_id_match = re.search(r'openReviewModal\((\d+),\s*\'[^\']+\',\s*\'approve\'\)', payments_page)
review_id = review_id_match.group(1) if review_id_match else '1'
admin_csrf2 = re.search(r'name="csrf_token" value="([a-f0-9]+)"', payments_page).group(1)

approve_data = urllib.parse.urlencode({
    'csrf_token': admin_csrf2,
    'review_id': review_id,
    'decision': 'approve',
    'admin_notes': 'Verified on Bloom Bank Africa Gambia online statement. Full $50 received.'
}).encode('utf-8')

req_approve = urllib.request.Request(f"{BASE_URL}/admin/payments/review", data=approve_data)
resp_approve = admin_opener.open(req_approve)
print('5. Admin verified payment receipt and approved registration in review queue.')

# 6. Verify Delegate Status is now PAID on public portal
final_check = opener.open(f"{BASE_URL}/register?ref=" + ref).read().decode('utf-8')
assert 'Accreditation Confirmed' in final_check
print('6. Public portal verified: Delegate', ref, 'status is ACCREDITATION CONFIRMED (PAID)!')
print('\n*** ALL END-TO-END HTTP WORKFLOWS VERIFIED 100% ***')
