# Same behaviour as telegram-notify.sh (formatted HTML + hostname + UTC).
# Usage:  .\telegram-notify.ps1 "Disk alert on SERVER1"

param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string] $Message
)

$ErrorActionPreference = "Stop"
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$EnvFile = Join-Path $ScriptDir ".env"

if (Test-Path $EnvFile) {
    Get-Content $EnvFile | ForEach-Object {
        if ($_ -match '^\s*#' -or $_ -notmatch '=') { return }
        $pair = $_.Split('=', 2)
        $name = $pair[0].Trim()
        $val = $pair[1].Trim().Trim('"')
        [Environment]::SetEnvironmentVariable($name, $val, "Process")
    }
}

$token = $env:TELEGRAM_BOT_TOKEN
$chat = $env:TELEGRAM_CHAT_ID
if (-not $token -or -not $chat) {
    Write-Error "Set TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID (environment or scripts\.env)."
}

$style = if ($env:TELEGRAM_MESSAGE_STYLE) { $env:TELEGRAM_MESSAGE_STYLE.ToLower() } else { "html" }
$showHeader = if ($env:TELEGRAM_SHOW_HEADER -eq "0") { $false } else { $true }
$silent = ($env:TELEGRAM_DISABLE_NOTIFICATION -eq "1")

$hostn = if ($env:TELEGRAM_HOST) { $env:TELEGRAM_HOST } else { $env:COMPUTERNAME }
$now = (Get-Date).ToUniversalTime().ToString("yyyy-MM-dd HH:mm") + " UTC"

if (-not $showHeader) {
    $bodyText = $Message
}
elseif ($style -eq "html") {
    $eh = [System.Net.WebUtility]::HtmlEncode($hostn)
    $et = [System.Net.WebUtility]::HtmlEncode($now)
    $em = [System.Net.WebUtility]::HtmlEncode($Message)
    $bodyText = "<b>🔔 Server alert</b>`n<code>${eh}</code> · <i>${et}</i>`n`n${em}"
}
else {
    $bodyText = "🔔 Server alert`n${hostn} · ${now}`n`n${Message}"
}

$bodyObj = [ordered]@{
    chat_id                  = $chat
    text                     = $bodyText
    disable_web_page_preview = $true
}
if ($style -eq "html" -and $showHeader) {
    $bodyObj["parse_mode"] = "HTML"
}
if ($silent) {
    $bodyObj["disable_notification"] = $true
}

$body = $bodyObj | ConvertTo-Json -Compress
$uri = "https://api.telegram.org/bot$token/sendMessage"
Invoke-RestMethod -Uri $uri -Method Post -ContentType "application/json; charset=utf-8" -Body $body | Out-Null
Write-Host "OK"
