# AMVAC 2027 — Production Packaging Script
$ErrorActionPreference = "Stop"

$workspaceRoot = $PSScriptRoot
$releaseDir = Join-Path $workspaceRoot "dist\amvac2027_production_release"
$zipPath = Join-Path $workspaceRoot "amvac2027_production_release.zip"

Write-Host "Creating clean production build directory..."
if (Test-Path $releaseDir) {
    Remove-Item -Recurse -Force $releaseDir
}
if (Test-Path $zipPath) {
    Remove-Item -Force $zipPath
}

New-Item -ItemType Directory -Path $releaseDir -Force | Out-Null

# Copy directories
Write-Host "Copying core application directories..."
Copy-Item -Recurse (Join-Path $workspaceRoot "config") (Join-Path $releaseDir "config")
Copy-Item -Recurse (Join-Path $workspaceRoot "database") (Join-Path $releaseDir "database")
Copy-Item -Recurse (Join-Path $workspaceRoot "src") (Join-Path $releaseDir "src")
Copy-Item -Recurse (Join-Path $workspaceRoot "views") (Join-Path $releaseDir "views")

# Copy public directory, excluding screenshots
Write-Host "Copying public assets (excluding QA test screenshots)..."
$publicDist = Join-Path $releaseDir "public"
New-Item -ItemType Directory -Path $publicDist -Force | Out-Null
Copy-Item (Join-Path $workspaceRoot "public\index.php") $publicDist
Copy-Item (Join-Path $workspaceRoot "public\.htaccess") $publicDist

$assetsDist = Join-Path $publicDist "assets"
New-Item -ItemType Directory -Path $assetsDist -Force | Out-Null
Copy-Item -Recurse (Join-Path $workspaceRoot "public\assets\css") (Join-Path $assetsDist "css")
Copy-Item -Recurse (Join-Path $workspaceRoot "public\assets\js") (Join-Path $assetsDist "js")
Copy-Item -Recurse (Join-Path $workspaceRoot "public\assets\images") (Join-Path $assetsDist "images")

# Copy storage directory with structure and protection, without test email logs
Write-Host "Setting up clean storage directory..."
$storageDist = Join-Path $releaseDir "storage"
$storageMailDist = Join-Path $storageDist "mail"
New-Item -ItemType Directory -Path $storageMailDist -Force | Out-Null
Copy-Item (Join-Path $workspaceRoot "storage\.htaccess") $storageDist
New-Item -ItemType File -Path (Join-Path $storageMailDist ".gitkeep") -Force | Out-Null

# Copy root configuration and deployment documentation
Write-Host "Copying root config and deployment runbook..."
Copy-Item (Join-Path $workspaceRoot ".env.production.example") $releaseDir
Copy-Item (Join-Path $workspaceRoot ".htaccess") $releaseDir
Copy-Item (Join-Path $workspaceRoot "index.php") $releaseDir
Copy-Item (Join-Path $workspaceRoot "DEPLOYMENT_GUIDE.md") $releaseDir
Copy-Item (Join-Path $workspaceRoot "README.md") $releaseDir

# Compress to ZIP
Write-Host "Creating production zip archive: $zipPath..."
Compress-Archive -Path "$releaseDir\*" -DestinationPath $zipPath -CompressionLevel Optimal

# Clean up temporary staging directory
Remove-Item -Recurse -Force (Join-Path $workspaceRoot "dist")

$zipSize = (Get-Item $zipPath).Length
$zipSizeMB = [math]::Round($zipSize / 1MB, 2)
Write-Host "Production package created successfully!"
Write-Host "File: $zipPath ($zipSizeMB MB / $zipSize bytes)"
