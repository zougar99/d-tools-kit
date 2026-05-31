#!/usr/bin/env bash
# Send a server alert message to Telegram (disk, backup, SSL, etc.).
# Messages are formatted with hostname + UTC time (HTML, Telegram-safe escaping).
#
# Usage:
#   ./telegram-notify.sh "Disk usage 90% on host X"
#
# Config: copy .env.example to .env, or export variables:
#   TELEGRAM_BOT_TOKEN   from @BotFather
#   TELEGRAM_CHAT_ID     channel or user id (e.g. -100…)
# Optional: TELEGRAM_MESSAGE_STYLE=html|plain  TELEGRAM_SHOW_HEADER=0|1
#           TELEGRAM_DISABLE_NOTIFICATION=0|1  TELEGRAM_HOST=override

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ENVFILE="${SCRIPT_DIR}/.env"

if [[ -f "${ENVFILE}" ]]; then
  set -a
  # shellcheck disable=SC1090
  source "${ENVFILE}"
  set +a
fi

TEXT="${*:-}"
if [[ -z "${TEXT}" ]]; then
  echo "Usage: $0 \"Your notification message\"" >&2
  exit 1
fi

if [[ -z "${TELEGRAM_BOT_TOKEN:-}" || -z "${TELEGRAM_CHAT_ID:-}" ]]; then
  echo "Missing TELEGRAM_BOT_TOKEN or TELEGRAM_CHAT_ID (scripts/.env or environment)." >&2
  exit 1
fi

if ! command -v python3 >/dev/null 2>&1; then
  echo "python3 is required to build JSON safely." >&2
  exit 1
fi

export TEXT
export CHAT="${TELEGRAM_CHAT_ID}"
export TELEGRAM_MESSAGE_STYLE="${TELEGRAM_MESSAGE_STYLE:-html}"
export TELEGRAM_SHOW_HEADER="${TELEGRAM_SHOW_HEADER:-1}"
export TELEGRAM_DISABLE_NOTIFICATION="${TELEGRAM_DISABLE_NOTIFICATION:-0}"
export TELEGRAM_HOST="${TELEGRAM_HOST:-$(hostname -f 2>/dev/null || hostname 2>/dev/null || echo unknown)}"

PAYLOAD="$(python3 <<'PY'
import html
import json
import os
import socket
from datetime import datetime, timezone

raw = os.environ.get("TEXT", "")
chat = os.environ["CHAT"]
style = os.environ.get("TELEGRAM_MESSAGE_STYLE", "html").lower()
show_header = os.environ.get("TELEGRAM_SHOW_HEADER", "1") != "0"
host = os.environ.get("TELEGRAM_HOST") or socket.gethostname()
now = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")

if not show_header:
    body = raw
elif style == "html":
    body = (
        "<b>🔔 Server alert</b>\n"
        f"<code>{html.escape(host)}</code> · <i>{html.escape(now)}</i>\n\n"
        f"{html.escape(raw)}"
    )
else:
    body = f"🔔 Server alert\n{host} · {now}\n\n{raw}"

payload = {
    "chat_id": chat,
    "text": body,
    "disable_web_page_preview": True,
}
if style == "html" and show_header:
    payload["parse_mode"] = "HTML"
silent = os.environ.get("TELEGRAM_DISABLE_NOTIFICATION", "0") == "1"
if silent:
    payload["disable_notification"] = True

print(json.dumps(payload))
PY
)"

URL="https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}/sendMessage"

if ! curl -sS -f -X POST "${URL}" \
  -H "Content-Type: application/json" \
  -d "${PAYLOAD}" >/dev/null; then
  echo "Telegram sendMessage failed (check token, chat_id, and bot permissions in the channel)." >&2
  exit 1
fi

echo "OK"
