#!/bin/bash
# Wrapper script to run formatters via DDEV
# Usage: format.sh <formatter> <absolute-file-path>
#
# Zed pipes file contents to stdin and expects formatted output on stdout.

FORMATTER="$1"
ABSOLUTE_PATH="$2"
FILENAME="$(basename "$ABSOLUTE_PATH")"

case "$FORMATTER" in
    pint)
        ddev exec bash -c "vendor/bin/pint --stdin-filename='$FILENAME'"
        ;;
    prettier)
        ddev exec bash -c "npx prettier --stdin-filepath '$FILENAME'"
        ;;
    *)
        echo "Unknown formatter: $FORMATTER" >&2
        exit 1
        ;;
esac
