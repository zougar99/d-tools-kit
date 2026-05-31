#!/usr/bin/env bash
# Warn via Telegram if disk use is at or above a threshold (default 90%).
# Usage: ./disk-alert.sh [percent] [mount]
# Example: ./disk-alert.sh 85 /home

set -euo pipefail
# shellcheck source=tools/_lib.sh
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/_lib.sh"

THRESH="${1:-90}"
MOUNT="${2:-/}"

if ! [[ "${THRESH}" =~ ^[0-9]+$ ]] || [[ "${THRESH}" -lt 1 || "${THRESH}" -gt 100 ]]; then
  echo "Usage: $0 [threshold_percent_1-100] [mountpoint]" >&2
  exit 2
fi

USE="$(df -P "${MOUNT}" 2>/dev/null | awk 'END {gsub(/%/,"",$5); print $5}')"
if [[ -z "${USE}" ]]; then
  echo "Could not read disk usage for ${MOUNT}" >&2
  exit 1
fi

if [[ "${USE}" -ge "${THRESH}" ]]; then
  HOST="$(hostname -s 2>/dev/null || hostname 2>/dev/null || echo unknown)"
  notify_telegram "Disk alert [${HOST}]: ${MOUNT} at ${USE}% (threshold ${THRESH}%)"
  exit 1
fi

echo "OK ${MOUNT} ${USE}%"
exit 0
