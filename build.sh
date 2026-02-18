#!/bin/bash
#
# Build script for Dynamic Month & Year into Posts plugin.
# Creates a clean zip file ready for WordPress upload.
#
# Uses .distignore as the single source of truth for exclusions.
# Generates a production-only vendor/ in a temp directory to avoid
# mutating the working tree.
#
# Usage:
#   ./build.sh                  # Full build (npm + composer + zip)
#   ./build.sh --skip-npm       # Skip npm install/build (use existing build/)
#   ./build.sh --skip-checks    # Skip version consistency checks
#
# @package DMYIP

set -euo pipefail

PLUGIN_SLUG="dynamic-month-year-into-posts"
VERSION=$(grep "Version:" "${PLUGIN_SLUG}.php" | head -1 | sed 's/.*Version:[[:space:]]*//' | tr -d '[:space:]')
BUILD_DIR="build-release"
ZIP_FILE="${PLUGIN_SLUG}-${VERSION}.zip"
SKIP_NPM=false
SKIP_CHECKS=false

for arg in "$@"; do
	case "$arg" in
		--skip-npm) SKIP_NPM=true ;;
		--skip-checks) SKIP_CHECKS=true ;;
	esac
done

cleanup() {
	rm -rf "${BUILD_DIR}" "${VENDOR_TMP:-}"
}
trap cleanup EXIT

echo "Building ${PLUGIN_SLUG} v${VERSION}..."

# --- Version consistency check ---
if [ "$SKIP_CHECKS" = false ]; then
	echo "Checking version consistency..."
	ERRORS=0

	V_PLUGIN=$(sed -n "s/.*VERSION = '\([^']*\)'.*/\1/p" src/Plugin.php | head -1)
	V_PKG=$(sed -n 's/.*"version": "\([^"]*\)".*/\1/p' package.json | head -1)
	V_README=$(sed -n 's/Stable tag: *\([0-9.]*\).*/\1/p' readme.txt | head -1)

	for pair in "src/Plugin.php:${V_PLUGIN}" "package.json:${V_PKG}" "readme.txt:${V_README}"; do
		label="${pair%%:*}"
		found="${pair#*:}"
		if [ -n "$found" ] && [ "$found" != "$VERSION" ]; then
			echo "  ✗ ${label}: ${found} (expected ${VERSION})"
			ERRORS=$((ERRORS + 1))
		fi
	done

	if [ "$ERRORS" -gt 0 ]; then
		echo "Version mismatch detected. Fix before building."
		exit 1
	fi
	echo "  ✓ All versions match"
fi

# --- Build assets ---
if [ "$SKIP_NPM" = false ]; then
	echo "Installing npm dependencies..."
	npm ci --silent

	echo "Building assets..."
	npm run build
else
	echo "Skipping npm (--skip-npm)"
	if [ ! -d "build" ]; then
		echo "Error: build/ directory missing. Run without --skip-npm first."
		exit 1
	fi
fi

# --- Generate production vendor in temp directory ---
echo "Generating production vendor..."
VENDOR_TMP=$(mktemp -d)
cp composer.json composer.lock "${VENDOR_TMP}/"
composer install --no-dev --optimize-autoloader --quiet --working-dir="${VENDOR_TMP}"

# --- Clean previous build ---
rm -rf "${BUILD_DIR}"
rm -f "${ZIP_FILE}"

# --- Assemble distribution using .distignore ---
echo "Assembling distribution..."
mkdir -p "${BUILD_DIR}/${PLUGIN_SLUG}"

rsync -a \
	--exclude-from=".distignore" \
	--exclude="${BUILD_DIR}" \
	. "${BUILD_DIR}/${PLUGIN_SLUG}/"

# Inject production vendor + composer.json (excluded by .distignore to prevent dev deps leaking).
cp -r "${VENDOR_TMP}/vendor" "${BUILD_DIR}/${PLUGIN_SLUG}/"
cp composer.json "${BUILD_DIR}/${PLUGIN_SLUG}/"

# --- Clean up artifacts ---
echo "Cleaning artifacts..."
find "${BUILD_DIR}" -name ".DS_Store" -delete 2>/dev/null || true
find "${BUILD_DIR}" -name "Thumbs.db" -delete 2>/dev/null || true
find "${BUILD_DIR}" -name "*.map" -delete 2>/dev/null || true
find "${BUILD_DIR}" -type d -empty -delete 2>/dev/null || true

# --- Verify critical files ---
echo "Verifying distribution..."
MISSING=0
for f in \
	"${PLUGIN_SLUG}.php" \
	"index.php" \
	"uninstall.php" \
	"readme.txt" \
	"composer.json" \
	"vendor/autoload.php" \
	"build/index.js" \
	"includes/shortcodes.php" \
	"src/Plugin.php" \
	"src/blocks/dynamic-date/render.php"; do
	if [ ! -f "${BUILD_DIR}/${PLUGIN_SLUG}/${f}" ]; then
		echo "  ✗ Missing: ${f}"
		MISSING=$((MISSING + 1))
	fi
done

if [ "$MISSING" -gt 0 ]; then
	echo "Distribution is incomplete. Aborting."
	exit 1
fi
echo "  ✓ All critical files present"

# Check for dev dependency leaks.
if [ -d "${BUILD_DIR}/${PLUGIN_SLUG}/vendor/phpstan" ] || \
   [ -d "${BUILD_DIR}/${PLUGIN_SLUG}/vendor/phpunit" ] || \
   [ -d "${BUILD_DIR}/${PLUGIN_SLUG}/vendor/squizlabs" ]; then
	echo "  ✗ Dev dependencies leaked into vendor/. Aborting."
	exit 1
fi
echo "  ✓ No dev dependency leaks"

# --- Create zip ---
echo "Creating zip file..."
cd "${BUILD_DIR}"
zip -rq "../${ZIP_FILE}" "${PLUGIN_SLUG}"
cd ..

# Show result.
FILE_COUNT=$(zipinfo -t "${ZIP_FILE}" 2>/dev/null | sed 's/[^0-9]*\([0-9]*\) file.*/\1/' || echo "?")
ZIP_SIZE=$(du -h "${ZIP_FILE}" | cut -f1)

echo ""
echo "Build complete!"
echo "  File:  ${ZIP_FILE}"
echo "  Size:  ${ZIP_SIZE}"
echo "  Files: ${FILE_COUNT}"
echo ""
echo "Ready for WordPress upload."
