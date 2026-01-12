#!/bin/bash
#
# Build script for Dynamic Month & Year into Posts plugin.
# Creates a clean zip file ready for WordPress upload.
#
# Usage: ./build.sh
#
# @package DMYIP

set -e

PLUGIN_SLUG="dynamic-month-year-into-posts"
VERSION=$(grep "Version:" "${PLUGIN_SLUG}.php" | head -1 | sed 's/.*Version:[[:space:]]*//' | tr -d '[:space:]')
BUILD_DIR="build-release"
ZIP_FILE="${PLUGIN_SLUG}-${VERSION}.zip"

echo "Building ${PLUGIN_SLUG} v${VERSION}..."

# Clean previous build.
rm -rf "${BUILD_DIR}"
rm -f "${ZIP_FILE}"

# Install dependencies.
echo "Installing npm dependencies..."
npm ci --silent

echo "Building assets..."
npm run build

echo "Installing composer dependencies (production only)..."
composer install --no-dev --optimize-autoloader --quiet

# Create build directory.
mkdir -p "${BUILD_DIR}/${PLUGIN_SLUG}"

# Copy plugin files.
echo "Copying plugin files..."

# Main plugin file.
cp "${PLUGIN_SLUG}.php" "${BUILD_DIR}/${PLUGIN_SLUG}/"

# Required directories.
cp -r build "${BUILD_DIR}/${PLUGIN_SLUG}/"
cp -r includes "${BUILD_DIR}/${PLUGIN_SLUG}/"
cp -r src "${BUILD_DIR}/${PLUGIN_SLUG}/"
cp -r vendor "${BUILD_DIR}/${PLUGIN_SLUG}/"

# Optional directories (if they exist).
[ -d "assets" ] && cp -r assets "${BUILD_DIR}/${PLUGIN_SLUG}/"
[ -d "languages" ] && cp -r languages "${BUILD_DIR}/${PLUGIN_SLUG}/"

# Required files.
cp index.php "${BUILD_DIR}/${PLUGIN_SLUG}/"
cp uninstall.php "${BUILD_DIR}/${PLUGIN_SLUG}/"
cp readme.txt "${BUILD_DIR}/${PLUGIN_SLUG}/"
cp composer.json "${BUILD_DIR}/${PLUGIN_SLUG}/"
[ -f "LICENSE" ] && cp LICENSE "${BUILD_DIR}/${PLUGIN_SLUG}/"
[ -f "LICENSE.txt" ] && cp LICENSE.txt "${BUILD_DIR}/${PLUGIN_SLUG}/"

# Remove development files from copied directories.
echo "Removing development files..."
find "${BUILD_DIR}" -name "*.map" -delete
find "${BUILD_DIR}" -name ".DS_Store" -delete
find "${BUILD_DIR}" -name "Thumbs.db" -delete
find "${BUILD_DIR}" -name ".gitkeep" -delete
find "${BUILD_DIR}" -type d -name ".git" -exec rm -rf {} + 2>/dev/null || true

# Remove markdown files (not needed in distribution).
find "${BUILD_DIR}" -name "*.md" -delete

# Remove src JS/CSS files (only need built versions).
find "${BUILD_DIR}/${PLUGIN_SLUG}/src" -name "*.js" -delete
find "${BUILD_DIR}/${PLUGIN_SLUG}/src" -name "*.css" -delete
find "${BUILD_DIR}/${PLUGIN_SLUG}/src" -name "*.json" ! -name "block.json" -delete

# Create zip file.
echo "Creating zip file..."
cd "${BUILD_DIR}"
zip -rq "../${ZIP_FILE}" "${PLUGIN_SLUG}"
cd ..

# Clean up build directory.
rm -rf "${BUILD_DIR}"

# Restore dev dependencies.
echo "Restoring dev dependencies..."
composer install --quiet

# Show result.
ZIP_SIZE=$(du -h "${ZIP_FILE}" | cut -f1)
echo ""
echo "Build complete!"
echo "  File: ${ZIP_FILE}"
echo "  Size: ${ZIP_SIZE}"
echo ""
echo "Ready for WordPress upload."
