import os
import subprocess
import shutil

CHROME_PATH = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
if not os.path.exists(CHROME_PATH):
    CHROME_PATH = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

OUTPUT_DIR = os.path.abspath(r"public\assets\screenshots")
ARTIFACT_DIR = r"C:\Users\DELL\.gemini\antigravity-ide\brain\5206aaa3-4591-4f3e-b046-b84d21e33ea4"
os.makedirs(OUTPUT_DIR, exist_ok=True)
os.makedirs(ARTIFACT_DIR, exist_ok=True)

pages = [
    ("home_preview.png", "http://127.0.0.1:8000/", 1440, 3900),
    ("about_preview.png", "http://127.0.0.1:8000/about", 1440, 2600),
    ("programme_preview.png", "http://127.0.0.1:8000/programme", 1440, 2400),
    ("register_preview.png", "http://127.0.0.1:8000/register", 1440, 2200),
    ("contact_preview.png", "http://127.0.0.1:8000/contact", 1440, 1600),
    ("admin_login_preview.png", "http://127.0.0.1:8000/admin/login", 1200, 900),
    ("home_mobile_preview.png", "http://127.0.0.1:8000/", 390, 7200)
]

for filename, url, w, h in pages:
    out_path = os.path.join(OUTPUT_DIR, filename)
    artifact_path = os.path.join(ARTIFACT_DIR, filename)
    cmd = [
        CHROME_PATH,
        "--headless=new",
        "--disable-gpu",
        "--hide-scrollbars",
        f"--window-size={w},{h}",
        f"--screenshot={out_path}",
        url
    ]
    try:
        subprocess.run(cmd, check=True, timeout=25)
        if os.path.exists(out_path):
            shutil.copy2(out_path, artifact_path)
            print(f"Captured {filename}")
    except Exception as e:
        print(f"Failed {filename}: {e}")

print("Batch capture completed.")
