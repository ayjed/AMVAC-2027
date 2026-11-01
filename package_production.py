#!/usr/bin/env python3
"""
AMVAC 2027 — Linux/cPanel Compatible Production Packaging Script
Creates a zip with strict Unix permissions (0755 for dirs, 0644 for files)
and standard Unix file attributes to prevent 'checkdir error: Permission denied' in cPanel unzip.
"""

import os
import zipfile
import stat
import time

WORKSPACE_ROOT = os.path.dirname(os.path.abspath(__file__))
ZIP_OUTPUT = os.path.join(WORKSPACE_ROOT, "amvac2027_production_release.zip")

# Explicit file list / patterns to package
INCLUDE_DIRS = [
    "config",
    "database",
    "src",
    "views",
]

PUBLIC_INCLUDE_SUBDIRS = [
    ("assets/css", "public/assets/css"),
    ("assets/js", "public/assets/js"),
    ("assets/images", "public/assets/images"),
]

ROOT_FILES = [
    (".env.production.example", ".env.production.example"),
    (".htaccess", ".htaccess"),
    ("index.php", "index.php"),
    ("DEPLOYMENT_GUIDE.md", "DEPLOYMENT_GUIDE.md"),
    ("README.md", "README.md"),
    ("public/.htaccess", "public/.htaccess"),
    ("public/index.php", "public/index.php"),
    ("storage/.htaccess", "storage/.htaccess"),
]

def create_unix_zip():
    print(f"Creating Linux/cPanel-compatible zip: {ZIP_OUTPUT}...")
    
    # We will gather entries: (archive_path, disk_path, is_dir)
    entries = []
    
    # Add root files
    for src_rel, arc_path in ROOT_FILES:
        full_src = os.path.join(WORKSPACE_ROOT, src_rel.replace("/", os.sep))
        if os.path.isfile(full_src):
            entries.append((arc_path.replace("\\", "/"), full_src, False))
        else:
            print(f"Warning: Root file missing: {full_src}")

    # Add core directories
    for d in INCLUDE_DIRS:
        full_dir = os.path.join(WORKSPACE_ROOT, d)
        for root, dirs, files in os.walk(full_dir):
            rel_root = os.path.relpath(root, WORKSPACE_ROOT).replace("\\", "/")
            entries.append((rel_root + "/", root, True))
            for f in files:
                full_f = os.path.join(root, f)
                rel_f = os.path.relpath(full_f, WORKSPACE_ROOT).replace("\\", "/")
                entries.append((rel_f, full_f, False))

    # Add public assets (css, js, images) - excluding screenshots
    for sub, arc_prefix in PUBLIC_INCLUDE_SUBDIRS:
        full_sub = os.path.join(WORKSPACE_ROOT, "public", sub.replace("/", os.sep))
        if os.path.isdir(full_sub):
            entries.append((arc_prefix + "/", full_sub, True))
            for root, dirs, files in os.walk(full_sub):
                for f in files:
                    if 'backup_portraits' in root or 'backup_portraits' in f:
                        continue
                    full_f = os.path.join(root, f)
                    rel_to_sub = os.path.relpath(full_f, full_sub).replace("\\", "/")
                    arc_f = f"{arc_prefix}/{rel_to_sub}".replace("//", "/")
                    entries.append((arc_f, full_f, False))

    # Add storage directory and clean mail/.gitkeep
    entries.append(("storage/", os.path.join(WORKSPACE_ROOT, "storage"), True))
    entries.append(("storage/mail/", os.path.join(WORKSPACE_ROOT, "storage", "mail"), True))
    
    gitkeep_path = os.path.join(WORKSPACE_ROOT, "storage", "mail", ".gitkeep")
    if not os.path.exists(gitkeep_path):
        with open(gitkeep_path, "w") as gk:
            pass
    entries.append(("storage/mail/.gitkeep", gitkeep_path, False))

    # Pre-configured production .env for seamless cPanel deployment
    cpanel_env_content = (
        'APP_NAME="AMVAC 2027"\n'
        'APP_ENV=production\n'
        'APP_DEBUG=false\n'
        'APP_URL=http://amvac.deckify.space\n\n'
        'DB_HOST=localhost\n'
        'DB_PORT=3306\n'
        'DB_NAME=deckify_amvacdb\n'
        'DB_USER=deckify_amvacuser\n'
        'DB_PASS=\n\n'
        '# Transactional Email\n'
        'MAIL_DRIVER=log\n'
        'MAIL_FROM_ADDRESS="secretariat@amvac2027.org"\n'
        'MAIL_FROM_NAME="AMVAC 2027 Secretariat"\n\n'
        '# Payment Gateway (Configure when merchant keys are issued)\n'
        'PAYSTACK_SECRET_KEY=\n'
        'PAYSTACK_PUBLIC_KEY=\n'
        'PAYSTACK_SANDBOX=true\n\n'
        '# Setup admin via: php database/create_admin.php <email> <password>\n'
        'ADMIN_DEFAULT_EMAIL="admin@amvac2027.org"\n'
    )

    # Collect and sort unique directory paths so parent dirs are created first
    all_dirs = set()
    for arc_path, disk_path, is_dir in entries:
        parts = arc_path.split("/")
        for i in range(1, len(parts)):
            parent = "/".join(parts[:i]) + "/"
            if parent != "/":
                all_dirs.add(parent)
        if is_dir and arc_path.endswith("/"):
            all_dirs.add(arc_path)

    # Sort dirs by depth
    sorted_dirs = sorted(list(all_dirs), key=lambda x: (x.count("/"), x))

    # Remove existing zip
    if os.path.exists(ZIP_OUTPUT):
        os.remove(ZIP_OUTPUT)

    now = time.localtime(time.time())[:6]

    with zipfile.ZipFile(ZIP_OUTPUT, "w", compression=zipfile.ZIP_DEFLATED, compresslevel=9) as zf:
        # Write directory entries first with 0755 pure Unix permissions (NO DOS attributes)
        for d in sorted_dirs:
            zinfo = zipfile.ZipInfo(d, date_time=now)
            zinfo.create_system = 3  # Unix
            # Pure Unix directory mode 0755
            zinfo.external_attr = (stat.S_IFDIR | 0o755) << 16
            zf.writestr(zinfo, "")

        # Write files with 0644 pure Unix permissions
        written_files = set()
        for arc_path, disk_path, is_dir in entries:
            if is_dir:
                continue
            if arc_path in written_files:
                continue
            written_files.add(arc_path)
            
            with open(disk_path, "rb") as fp:
                file_data = fp.read()

            zinfo = zipfile.ZipInfo(arc_path, date_time=now)
            zinfo.create_system = 3  # Unix
            # Pure Unix file mode 0644
            zinfo.external_attr = (stat.S_IFREG | 0o644) << 16
            zf.writestr(zinfo, file_data)

        # Write pre-configured .env directly into the root of archive
        zinfo_env = zipfile.ZipInfo(".env", date_time=now)
        zinfo_env.create_system = 3
        zinfo_env.external_attr = (stat.S_IFREG | 0o644) << 16
        zf.writestr(zinfo_env, cpanel_env_content.encode("utf-8"))

    size = os.path.getsize(ZIP_OUTPUT)
    print(f"\nSUCCESS: Production package created at: {ZIP_OUTPUT}")
    print(f"Total size: {round(size / (1024 * 1024), 2)} MB ({size} bytes)")

if __name__ == "__main__":
    create_unix_zip()
