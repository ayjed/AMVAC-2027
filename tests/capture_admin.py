import os
import re
import urllib.request
import urllib.parse
import http.cookiejar

cookie_jar = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cookie_jar))

# 1. Fetch login page to extract CSRF token
resp = opener.open('http://127.0.0.1:8000/admin/login')
html = resp.read().decode('utf-8')
match = re.search(r'name="csrf_token"\s+value="([^"]+)"', html)
if not match:
    raise Exception("CSRF token not found on admin login page")
csrf_token = match.group(1)

# 2. Post login credentials
login_data = urllib.parse.urlencode({
    'csrf_token': csrf_token,
    'email': 'admin@amvac2027.org',
    'password': 'ChangeMe2027!Secure'
}).encode('utf-8')

req = urllib.request.Request('http://127.0.0.1:8000/admin/login', data=login_data)
resp = opener.open(req)
print("Login completed. Final URL:", resp.geturl())

# 3. Check dashboard
dash_resp = opener.open('http://127.0.0.1:8000/admin/dashboard')
dash_html = dash_resp.read().decode('utf-8')
print("Dashboard HTTP status:", dash_resp.status)
print("Has 'Secretariat Overview':", "Secretariat Overview" in dash_html)
print("Has 'logo-official-white.png':", "logo-official-white.png" in dash_html)
print("Has 'Public Site':", "Public Site" in dash_html)

# 4. Check registrations
reg_resp = opener.open('http://127.0.0.1:8000/admin/registrations')
print("Registrations HTTP status:", reg_resp.status)

# 5. Check payments
pay_resp = opener.open('http://127.0.0.1:8000/admin/payments')
print("Payments HTTP status:", pay_resp.status)
