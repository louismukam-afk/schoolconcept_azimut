#!/usr/bin/env bash
# For installations where public_html/index.php loads public_html/app/bootstrap.
set -euo pipefail

app_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd -P)"
if [ "$#" -ne 1 ]; then
    echo "Usage: bash scripts/sync-public-assets.sh /absolute/path/to/public_html" >&2
    exit 1
fi
web_root="$(cd "$1" && pwd -P)"
if [ ! -f "$web_root/index.php" ] || [ ! -f "$app_root/artisan" ]; then
    echo "Application or public index.php missing; no assets copied." >&2
    exit 1
fi
if [ "$web_root" = "$app_root/public" ]; then
    echo "The domain already serves app/public; assets are available directly."
    exit 0
fi
if [ "$web_root" = "$app_root" ]; then
    echo "The destination must be the public document root, not the application root." >&2
    exit 1
fi
command -v rsync >/dev/null || { echo "rsync is required." >&2; exit 1; }

# No deletion, no index.php, no configuration, no student photos or uploads.
for directory in css css1 dist fonts js; do
    if [ -d "$app_root/public/$directory" ]; then
        mkdir -p "$web_root/$directory"
        rsync -rlt --itemize-changes --chmod=D755,F644 \
            "$app_root/public/$directory/" "$web_root/$directory/"
    fi
done
echo "Public styles, scripts and fonts synchronized to $web_root."
