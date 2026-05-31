#!/usr/bin/env bash
# Shared paths for tools/*.sh (source this file, do not execute).
TOOLS_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
export NOTIFY_SCRIPT="${TOOLS_DIR}/../scripts/telegram-notify.sh"

notify_telegram() {
  if [[ ! -f "${NOTIFY_SCRIPT}" ]]; then
    echo "Missing ${NOTIFY_SCRIPT}" >&2
    return 1
  fi
  bash "${NOTIFY_SCRIPT}" "$@"
}
