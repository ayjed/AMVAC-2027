import os
import subprocess

CHROME_PATH = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
if not os.path.exists(CHROME_PATH):
    CHROME_PATH = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

OUTPUT_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", "public", "assets", "screenshots"))
ARTIFACT_DIR = r"C:\Users\DELL\.gemini\antigravity-ide\brain\5206aaa3-4591-4f3e-b046-b84d21e33ea4"

os.makedirs(OUTPUT_DIR, exist_ok=True)
os.makedirs(ARTIFACT_DIR, exist_ok=True)

configs = [
    ("home_full_1440px.png", 1440, 3900),
    ("home_full_390px.png", 390, 7200),
]

for filename, w, h in configs:
    out_path = os.path.join(OUTPUT_DIR, filename)
    artifact_path = os.path.join(ARTIFACT_DIR, filename)
    cmd = [
        CHROME_PATH,
        "--headless=new",
        "--disable-gpu",
        "--hide-scrollbars",
        f"--window-size={w},{h}",
        f"--screenshot={out_path}",
        "http://127.0.0.1:8000/"
    ]
    subprocess.run(cmd, check=True, timeout=20)
    if os.path.exists(out_path):
        import shutil
        shutil.copy2(out_path, artifact_path)
        print(f"Captured {filename}")
