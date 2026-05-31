#!/usr/bin/env bash
# Curl an URL; on failure send Telegram (needs scripts/.env).
# Usage: ./http-check.sh "https://example.com/health"
# Cron : */5 * * * * /path/tools/http-check.sh "https://yoursite.com"

set -euo pipefail
# shellcheck source=tools/_lib.sh
source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/_lib.sh"

URL="${1:-}"
if [[ -z "${URL}" ]]; then
  echo "Usage: $0 <url>" >&2
  exit 2
fi

if curl -sSf --max-time 20 --retry 1 "${URL}" >/dev/null; then
  echo "OK ${URL}"
  exit 0
fi

notify_telegram "HTTP check failed: ${URL}"
exit 1
