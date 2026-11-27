#!/usr/bin/env bash

# Tailscale share provider for DDEV: `ddev share`, the default for this project (config.share.yaml).
#
# Serves the site, and the Vite dev server with hot reloading, at https://<machine>.<tailnet>.ts.net
# to devices on your tailnet only. Nothing is public. Press Ctrl+C to stop sharing.
#
# Needs Tailscale installed and signed in, with HTTPS certificates enabled for the tailnet
# (admin console > DNS > HTTPS Certificates).

set -euo pipefail

if [[ "${DDEV_DEBUG:-}" == "true" ]] || [[ "${DDEV_VERBOSE:-}" == "true" ]]; then
  set -x
fi

# The macOS app doesn't put the CLI on PATH unless you install it from the app's settings.
TAILSCALE="$(command -v tailscale || true)"
if [[ -z "$TAILSCALE" ]] && [[ -x /Applications/Tailscale.app/Contents/MacOS/Tailscale ]]; then
  TAILSCALE=/Applications/Tailscale.app/Contents/MacOS/Tailscale
fi
if [[ -z "$TAILSCALE" ]]; then
  echo "Error: Tailscale not found. Install it from https://tailscale.com/download" >&2
  exit 1
fi

if [[ -z "${DDEV_LOCAL_URL:-}" ]]; then
  echo "Error: DDEV_LOCAL_URL not set" >&2
  exit 1
fi

# --peers=false leaves only this machine's DNSName in the output.
STATUS="$("$TAILSCALE" status --json --peers=false 2>/dev/null || true)"
if [[ "$STATUS" != *'"BackendState": "Running"'* ]]; then
  echo "Error: Tailscale isn't connected. Open it and sign in, then try again." >&2
  exit 1
fi
HOST="$(printf '%s\n' "$STATUS" | sed -n 's/.*"DNSName": "\([^"]*\)\.".*/\1/p' | head -1)"
if [[ -z "$HOST" ]]; then
  echo "Error: couldn't find this machine's Tailscale hostname. Is MagicDNS enabled for the tailnet?" >&2
  exit 1
fi

# Find the host port Docker maps to Vite's port (5173), through the web container that publishes
# DDEV_LOCAL_URL's port. DDEV only passes DDEV_LOCAL_URL to share providers, not the project's name.
WEB_PORT="${DDEV_LOCAL_URL##*:}"
WEB_CONTAINER="$(docker ps --filter "publish=$WEB_PORT" --format '{{.Names}}' 2>/dev/null | head -1 || true)"
VITE_PORT=""
if [[ -n "$WEB_CONTAINER" ]]; then
  VITE_PORT="$(docker port "$WEB_CONTAINER" 5173/tcp 2>/dev/null | head -1 | sed 's/.*://' || true)"
fi

# SEOmatic caches its meta tags (og:url, canonical) by page, not by host, so whichever host
# requests a page first sets them for both. Clear them when sharing starts and stops.
clear_seomatic_cache() {
  if [[ -n "$WEB_CONTAINER" ]]; then
    docker exec "$WEB_CONTAINER" php craft clear-caches/seomatic-metabundle-caches > /dev/null 2>&1 || true
  fi
}

PIDS=()
cleanup() {
  trap - EXIT INT TERM
  for pid in "${PIDS[@]}"; do
    kill "$pid" 2>/dev/null || true
  done
  clear_seomatic_cache
}
trap cleanup EXIT INT TERM

clear_seomatic_cache

# Run in the foreground (no --bg), so each serve is removed when this script stops.
"$TAILSCALE" serve --https=443 "$DDEV_LOCAL_URL" > /dev/null &
PIDS+=($!)

if [[ -n "$VITE_PORT" ]]; then
  "$TAILSCALE" serve --https=5173 "http://127.0.0.1:$VITE_PORT" > /dev/null &
  PIDS+=($!)
  echo "Shared with devices on your tailnet. Run \`ddev npm run dev\` for styles and hot reloading." >&2
else
  echo "Warning: Vite's port isn't exposed, so pages will render without styles. Run \`ddev restart\`." >&2
fi

# DDEV reads the first line of stdout as the share URL.
echo "https://$HOST"

wait
