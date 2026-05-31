#!/usr/bin/env bash
# Ping once; on failure notify Telegram (useful for quick upstream checks).
# Usage: ./ping-host.sh <host_or_ip>

set -euo pipefail
# shellcheck source=tools/_lib.sh
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/_lib.sh"

TARGET="${1:-}"
if [[ -z "${TARGET}" ]]; then
  echo "Usage: $0 <hostname_or_ip>" >&2
  exit 2
fi

if ping -c 1 -W 5 "${TARGET}" >/dev/null 2>&1; then
  echo "OK ${TARGET}"
  exit 0
fi

notify_telegram "Ping failed: ${TARGET}"
exit 1
