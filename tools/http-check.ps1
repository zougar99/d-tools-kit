# HTTP GET health check; on failure calls scripts\telegram-notify.ps1
# Usage:  .\http-check.ps1 "https://example.com"

param(
    [Parameter(Mandatory = $true)]
    [string] $Url
)

$ErrorActionPreference = "Stop"
$toolsDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$notify = Join-Path $toolsDir "..\scripts\telegram-notify.ps1"

try {
    $r = Invoke-WebRequest -Uri $Url -UseBasicParsing -TimeoutSec 25 -MaximumRedirection 3
    if ($r.StatusCode -ge 200 -and $r.StatusCode -lt 400) {
        Write-Host "OK $Url"
        exit 0
    }
    throw "HTTP $($r.StatusCode)"
}
catch {
    if (Test-Path $notify) {
        & $notify "HTTP check failed: $Url ($($_.Exception.Message))"
    }
    else { Write-Error $_ }
    exit 1
}
