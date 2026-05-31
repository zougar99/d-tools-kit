#!/usr/bin/env bash
# If TLS certificate expires in fewer than N days, notify Telegram.
# Usage: ./ssl-expiry.sh <host> [port] [warn_days]
# Example: ./ssl-expiry.sh example.com 443 21

set -euo pipefail
# shellcheck source=tools/_lib.sh
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/_lib.sh"

HOST="${1:-}"
PORT="${2:-443}"
WARN_DAYS="${3:-14}"

if [[ -z "${HOST}" ]]; then
  echo "Usage: $0 <hostname> [port] [warn_if_fewer_than_days]" >&2
  exit 2
fi

NOTAFTER="$(
  echo | timeout 15 openssl s_client -servername "${HOST}" -connect "${HOST}:${PORT}" 2>/dev/null \
    | openssl x509 -noout -enddate 2>/dev/null \
    | cut -d= -f2-
)"

if [[ -z "${NOTAFTER}" ]]; then
  notify_telegram "SSL check failed: could not read cert for ${HOST}:${PORT}"
  exit 1
fi

DAYS_LEFT="$(
  NOTAFTER="${NOTAFTER}" python3 <<'PY'
import os
import sys
from datetime import datetime, timezone

s = os.environ.get("NOTAFTER", "").strip()
dt = None
for fmt in ("%b %d %H:%M:%S %Y GMT", "%b %d %H:%M:%S %Y %Z"):
    try:
        dt = datetime.strptime(s, fmt).replace(tzinfo=timezone.utc)
        break
    except ValueError:
        continue
if dt is None:
    sys.exit(1)
now = datetime.now(timezone.utc)
print(max(0, (dt - now).days))
PY
)" || {
  notify_telegram "SSL check failed: could not parse expiry for ${HOST} (${NOTAFTER})"
  exit 1
}

if [[ "${DAYS_LEFT}" -lt "${WARN_DAYS}" ]]; then
  notify_telegram "SSL expiry [${HOST}:${PORT}]: certificate expires in ${DAYS_LEFT} day(s) (warn < ${WARN_DAYS}). notAfter=${NOTAFTER}"
  exit 1
fi

echo "OK ${HOST}:${PORT} expires in ${DAYS_LEFT} days"
exit 0
