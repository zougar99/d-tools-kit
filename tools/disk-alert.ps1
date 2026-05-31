# Warn via Telegram if a drive is above threshold (default 90%).
# Usage:  .\disk-alert.ps1 -DriveLetter C -ThresholdPercent 85

param(
    [string] $DriveLetter = "C",
    [int] $ThresholdPercent = 90
)

$ErrorActionPreference = "Stop"
$toolsDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$notify = Join-Path $toolsDir "..\scripts\telegram-notify.ps1"

$vol = Get-Volume -DriveLetter $DriveLetter -ErrorAction SilentlyContinue
if (-not $vol) {
    Write-Error "Drive ${DriveLetter}: not found"
}

if ($vol.Size -le 0) { Write-Error "Drive size unknown" }
$usedPct = [math]::Round(100 * (1 - ($vol.SizeRemaining / $vol.Size)))

if ($usedPct -ge $ThresholdPercent) {
    $hostn = $env:COMPUTERNAME
    if (Test-Path $notify) {
        & $notify "Disk alert [${hostn}]: drive ${DriveLetter}: at ${usedPct}% (threshold ${ThresholdPercent}%)"
    }
    exit 1
}

Write-Host "OK drive ${DriveLetter}: ${usedPct}%"
exit 0
