import os
import subprocess
import time

CHROME_PATH = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
if not os.path.exists(CHROME_PATH):
    CHROME_PATH = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

BASE_URL = "http://127.0.0.1:8000"
OUTPUT_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", "public", "assets", "screenshots"))
ARTIFACT_DIR = r"C:\Users\DELL\.gemini\antigravity-ide\brain\5206aaa3-4591-4f3e-b046-b84d21e33ea4"

os.makedirs(OUTPUT_DIR, exist_ok=True)
os.makedirs(ARTIFACT_DIR, exist_ok=True)

routes = [
    ("/", "home"),
    ("/about", "about"),
    ("/programme", "programme"),
    ("/register", "register"),
    ("/contact", "contact")
]

breakpoints = [
    (360, 740, "360px"),
    (390, 844, "390px"),
    (768, 1024, "768px"),
    (1024, 768, "1024px"),
    (1440, 900, "1440px")
]

print(f"Using browser: {CHROME_PATH}")
print(f"Output directory: {OUTPUT_DIR}")

results = []

for route, name in routes:
    for width, height, bp_label in breakpoints:
        filename = f"{name}_{bp_label}.png"
        out_path = os.path.join(OUTPUT_DIR, filename)
        artifact_path = os.path.join(ARTIFACT_DIR, filename)
        url = f"{BASE_URL}{route}"

        cmd = [
            CHROME_PATH,
            "--headless=new",
            "--disable-gpu",
            "--hide-scrollbars",
            f"--window-size={width},{height}",
            f"--screenshot={out_path}",
            url
        ]

        try:
            subprocess.run(cmd, check=True, timeout=15)
            # copy to artifact dir as well
            if os.path.exists(out_path):
                import shutil
                shutil.copy2(out_path, artifact_path)
                size_kb = os.path.getsize(out_path) / 1024
                results.append((name, bp_label, width, height, filename, True, size_kb))
                print(f"[OK] {name} @ {bp_label} ({width}x{height}) -> {filename} ({size_kb:.1f} KB)")
            else:
                results.append((name, bp_label, width, height, filename, False, 0))
                print(f"[FAIL] Output missing for {name} @ {bp_label}")
        except Exception as e:
            results.append((name, bp_label, width, height, filename, False, str(e)))
            print(f"[ERROR] {name} @ {bp_label}: {e}")

print("\nScreenshot capture completed!")
print(f"Total captures: {len([r for r in results if r[5] is True])} / {len(results)}")
