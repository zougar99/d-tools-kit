# Local PHP dev server for the CC checker (requires PHP on PATH).
$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot
$port = 8080
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "PHP is not installed or not on PATH. Install PHP for Windows, then run this script again." -ForegroundColor Yellow
    Write-Host "Download: https://windows.php.net/download/" -ForegroundColor Cyan
    exit 1
}
Write-Host "Serving http://localhost:$port/ (folder: $PSScriptRoot)" -ForegroundColor Green
php -S "localhost:$port"
